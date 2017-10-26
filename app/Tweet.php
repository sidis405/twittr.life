<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $guarded = [];

    protected $dates = ['tweet_created_at'];

    public function getTweetMediaAttribute($value)
    {
        return json_decode($value);
    }

    public function quote()
    {
        return $this->belongsTo(Tweet::class, 'quoted');
    }
}
