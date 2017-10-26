<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'nickname',
        'email',
        'description',
        'avatar',
        'password',
        'twitter_id',
        'twitter_token',
        'twitter_tokenSecret',
        'twitter_dump',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function tweets()
    {
        return $this->hasMany(Tweet::class)->orderBy('id', 'DESC');
    }

    public function rawTweets()
    {
        return $this->hasMany(Raw::class);
    }
}
