<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Repositories\TweetRepository;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessRawTweet implements ShouldQueue
{
    protected $rawTweet;
    protected $user;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($rawTweet, $user)
    {
        $this->rawTweet = $rawTweet;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $repo = new TweetRepository;

        $formattedTweet = $repo->makeFromRaw($this->rawTweet->dump);

        $tweet = json_decode(json_encode($formattedTweet), true);


        if ($tweet['quoted']) {
            $tweet['quoted'] = $this->user->tweets()->create($tweet['quoted'])->id;
        }

        $this->user->tweets()->create($tweet);

        $this->rawTweet->delete();
    }
}
