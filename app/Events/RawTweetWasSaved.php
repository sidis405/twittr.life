<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class RawTweetWasSaved
{
    public $rawTweet;
    public $user;

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($rawTweet, $user)
    {
        $this->rawTweet = $rawTweet;
        $this->user = $user;
    }
}
