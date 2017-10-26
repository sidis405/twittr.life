<?php

namespace App\Console\Commands;

use App\Raw;
use App\User;
use App\Tweet;
use Illuminate\Console\Command;
use App\Events\RawTweetWasSaved;
use Thujohn\Twitter\Facades\Twitter;

class GetFavoritesForUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'life:for-user {nickname : The twitter user to import favorites for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get last 20 favorites for user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('Fetching favorites for @' . $this->argument('nickname'));

        $user = User::whereNickname($this->argument('nickname'))->first();

        $index = Tweet::get()->pluck('tweet_id');

        $favorites = Twitter::getFavorites(
            ['screen_name' => $this->argument('nickname'),
            'count' => 201, 'format' => 'json',
            'include_entities' => true]
        );

        $favorites = json_decode($favorites);

        $saved = 0;

        $queue = collect();

        foreach ($favorites as $favorite) {
            if (!$index->contains($favorite->id)) {
                $raw = new Raw;
                $raw->user_id = $user->id;
                $raw->tweet_id = $favorite->id;
                $raw->dump = json_encode($favorite);
                $raw->save();

                $this->line('Saved raw tweet ' . $raw->tweet_id);

                $saved++;
                $queue->push($raw);

                // event(new RawTweetWasSaved($raw, $user));

                // $this->line('Processed raw tweet ' . $raw->tweet_id);
            }
        }

        foreach ($queue->reverse() as $raw) {
            event(new RawTweetWasSaved($raw, $user));

            $this->line('Processed raw tweet ' . $raw->tweet_id);
        }


        $this->line('Got ' . $saved . ' new tweets');
    }
}
