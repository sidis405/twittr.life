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

        $this->user->tweets()->create(json_decode(json_encode($formattedTweet), true));

        $this->rawTweet->delete();
    }
}
