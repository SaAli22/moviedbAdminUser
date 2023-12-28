<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Watchlist;

class WatchlistController extends Controller
{
    function addToWatchlist(string $movie_id, string $user_id )
    {

        $entity = new Watchlist();

        $entity->user_id = $user_id;

        $entity->movie_id = $movie_id;



        $entity->save();
    }

    function removeFromWatchlist()
    {
        $user = request()->input('user_id');
        $movie = request()->input('movie_id');

        $watchlist = Watchlist::where('user_id', $user)->where('movie_id', $movie);

        $watchlist->delete();
    }

    function getWatchlist()
    {
        $watchlist = Watchlist::where('user_id', '=', request()->user_id)->where('movie_id', '=', request()->movie_id)->get();

        return $watchlist;
    }

    function getUserWatchlist()
    {
        $watchlist = Watchlist::where('user_id', request()->user_id)->get();

        return $watchlist;
    }
}
