<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $guarded = [];

    protected $dates = ['tweet_created_at'];
}
