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

    protected function formatFromRaw($raw, $isQuote = false)
    {
        // if ($isQuote) {
        //     dd($raw);
        // }

        $tweet = new \stdClass();

        // dd(json_decode($raw));

        $tweet->tweet_id = $raw->id;
        $tweet->tweet_url = 'https://twitter.com/' . $raw->user->screen_name . '/status/' . $raw->id;
        $tweet->text = $raw->text;
        $tweet->twitter_user = $raw->user->name;
        $tweet->twitter_user_name = $raw->user->screen_name;
        $tweet->twitter_user_id = $raw->user->id;
        $tweet->twitter_user_image = str_replace('normal', 'bigger', ($raw->user->profile_image_url) ?? $raw->user->profile_image_url);
        $tweet->tweet_created_at = Carbon::parse($raw->created_at)->format('Y-m-d H:i:s');
        $tweet->tweet_media = collect();
        $tweet->quoted = null;

        $tweet = $this->linkMentions($raw, $tweet);
        $tweet = $this->linkMedia($raw, $tweet);
        $tweet = $this->linkUrls($raw, $tweet);
        $tweet = $this->linkQuotes($raw, $tweet);

        return $tweet;
    }

    protected function linkQuotes($raw, $tweet)
    {
        if (isset($raw->quoted_status)) {
            $tweet->quoted = $this->formatFromRaw($raw->quoted_status, true);
        } else {
            $tweet->quoted = null;
        }

        return $tweet;
    }

    protected function linkMentions($raw, $tweet)
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

    protected function linkUrls($raw, $tweet)
    {
        foreach ($raw->entities->urls as $url) {
            $tweet->text = str_replace(
                $url->url,
                "<a href='{$url->url}' target='_blank'>{$url->display_url}</a>",
                $tweet->text
            );
        }

        return $tweet;
    }

    protected function linkMedia($raw, $tweet)
    {
        if (isset($raw->entities) && isset($raw->entities->media)) {
            foreach ($raw->entities->media as $media) {
                $tweet->text = str_replace(
                    $media->url,
                    "<a href='{$media->url}' target='_blank'>{$media->display_url}</a>",
                    $tweet->text
                );

                $tweet->tweet_media->push($media->media_url);
            }
        }

        $tweet->tweet_media = json_encode($tweet->tweet_media);
        return $tweet;
    }
}
