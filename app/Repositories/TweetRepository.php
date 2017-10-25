<?php

namespace App\Repositories;

use Carbon\Carbon;

class TweetRepository
{
    public function makeFromRaw($raw)
    {
        $raw = json_decode($raw);
        return $this->formatFromRaw($raw);
    }

    protected function formatFromRaw($raw)
    {
        $tweet = new \stdClass();

        // dd(json_decode($raw));

        $tweet->tweet_id = $raw->id;
        $tweet->text = $raw->text;
        $tweet->twitter_user = $raw->user->screen_name;
        $tweet->twitter_user_id = $raw->user->id;
        $tweet->twitter_user_image = $raw->user->profile_background_image_url;
        $tweet->tweet_created_at = Carbon::parse($raw->created_at)->format('Y-m-d H:i:s');

        $tweet = $this->linkMentions($raw, $tweet);

        return $tweet;
    }

    public function linkMentions($raw, $tweet)
    {
        foreach ($raw->entities->user_mentions as $mention) {
            $tweet->text = str_replace(
                '@' . $mention->screen_name,
                "<a href='http://twitter.com/{$mention->screen_name}' target='_blank'>@{$mention->screen_name}</a>",
                $tweet->text
            );
        }

        return $tweet;
    }
}
