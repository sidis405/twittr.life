<?php

namespace App\Listeners;

use App\Jobs\ProcessRawTweet;
use App\Events\RawTweetWasSaved;

class RawTweetSavingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RawTweetWasSaved  $event
     * @return void
     */
    public function handle(RawTweetWasSaved $event)
    {
        dispatch(new ProcessRawTweet($event->rawTweet, $event->user));
    }
}
