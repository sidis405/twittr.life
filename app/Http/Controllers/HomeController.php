<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tweets = auth()->user()->load('tweets.quote')->tweets;

        // $tweets = auth()->user()->load('rawTweets')->rawTweets->pluck('dump')->map(function ($raw) {
        //     return json_decode($raw);
        // });

        // return $tweets;

        return view('home', compact('tweets'));
    }
}
