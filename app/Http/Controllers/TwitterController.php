<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class TwitterController extends Controller
{
    public function __construct()
    {
        $this->provider = 'twitter';
    }

    public function redirectToProvider()
    {
        return Socialite::driver($this->provider)->redirect();
    }


    public function handleProviderCallback()
    {
        try {
            $twitter_user = Socialite::driver($this->provider)->user();
        } catch (InvalidStateException $e) {
            dd($e);
        }

        // dd($twitter_user);

        $user = User::where('twitter_id', $twitter_user->id)->first();

        if ($user) {
            return $this->handleExistingUser($user, $twitter_user);
        } else {
            return $this->handleNewUser($twitter_user);
        }
    }

    public function handleNewUser($twitter_user)
    {
        $user =  User::create([
            'name' => $twitter_user->name,
            'nickname' => $twitter_user->nickname,
            'email' => $twitter_user->email,
            'description' => $twitter_user->user['description'],
            'location' => $twitter_user->user['location'],
            'avatar' => $twitter_user->avatar,
            'password' => bcrypt($twitter_user->name . $twitter_user->id),
            'twitter_id' => $twitter_user->id,
            'twitter_token' => $twitter_user->token,
            'twitter_tokenSecret' => $twitter_user->tokenSecret,
            'twitter_dump' => json_encode($twitter_user),
        ]);

        return $this->login($user);
    }

    public function handleExistingUser($user, $twitter_user)
    {
        $user->email = $twitter_user->email;
        $user->description = $twitter_user->user['description'];
        $user->location = $twitter_user->user['location'];
        $user->twitter_token = $twitter_user->token;
        $user->twitter_tokenSecret = $twitter_user->tokenSecret;
        $user->twitter_dump = json_encode($twitter_user);
        $user->save();

        return $this->login($user);
    }

    public function login($user)
    {
        Auth::login($user, true);

        return redirect('/home');
    }
}
