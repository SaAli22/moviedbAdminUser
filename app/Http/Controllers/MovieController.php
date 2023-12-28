<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Input;

class MovieController extends Controller
{

    public function loadPopular()
    {
        $accessToken =
            "Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI3MzU2ZjZjNzgxZjg0MjAyNjM2N2I4YmFhMjI1YWJkYiIsInN1YiI6IjY1MDFjOTdkNTU0NWNhMDBhYjVkYmRkOSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.zvglGM1QgLDK33Dt6PpMK9jeAOrLNnxClZ6mkLeMgBE";
        $url = "https://api.themoviedb.org/3/discover/movie?include_adult=false&include_video=false&language=en-US&page=1&sort_by=popularity.desc";
        $res = Http::withHeaders(["Authorization" => $accessToken])->get($url);
        $decode = json_decode($res->body(), false);
        session(['data' => $decode->results]);
        return view('test');
    }

    public function setupSearch($query)
    {
        $APIKEY = "7356f6c781f842026367b8baa225abdb";
        $url = 'https://api.themoviedb.org/3/search/movie?query=' . $query . '&api_key=' . $APIKEY;
        $res = Http::get($url);
        $decode2 = json_decode($res->body(), false);
        if (isset($decode2->results[0]->poster_path)) {
            session(['poster' => $decode2->results[0]->poster_path]);
        }
        return $decode2->results[0]->poster_path;
        //return view('test')->with('poster', $decode2->results[0]->poster_path);
    }

    public function getDetails($id)
    {
        $accessToken =
            "Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI3MzU2ZjZjNzgxZjg0MjAyNjM2N2I4YmFhMjI1YWJkYiIsInN1YiI6IjY1MDFjOTdkNTU0NWNhMDBhYjVkYmRkOSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.zvglGM1QgLDK33Dt6PpMK9jeAOrLNnxClZ6mkLeMgBE";
        $url = 'https://api.themoviedb.org/3/movie/' . $id . '?language=en-US';
        $res = Http::withHeaders(["Authorization" => $accessToken])->get($url);
        $data = json_decode($res->body(), false);
        $url2 = 'https://api.themoviedb.org/3/movie/' . $id . '/videos';
        $res2 = Http::withHeaders(["Authorization" => $accessToken])->get($url2);
        $decode = json_decode($res2->body(), false);
        return view('movie', ['data' => $data, 'key' => $decode]);
    }
    public function updateDetails($id)
    {
        // Assuming you receive updated movie details via a form or request

        // Example updated movie details (replace this with the actual updated data)
        $updatedDetails = [
            'title' => 'Updated Title',
            'overview' => 'Updated overview description',
            // Add other fields you want to update
        ];

        $accessToken = "Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI3MzU2ZjZjNzgxZjg0MjAyNjM2N2I4YmFhMjI1YWJkYiIsInN1YiI6IjY1MDFjOTdkNTU0NWNhMDBhYjVkYmRkOSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.zvglGM1QgLDK33Dt6PpMK9jeAOrLNnxClZ6mkLeMgBE"; // Replace with your TMDb API key
        $url = 'https://api.themoviedb.org/3/movie/' . $id; // API endpoint for updating movie details

        // Send a PATCH or PUT request to update movie details
        $response = Http::withHeaders(["Authorization" => $accessToken])
            ->put($url, $updatedDetails);

        // Check if the request was successful
        if ($response->successful()) {
            // Movie details updated successfully
            return redirect()->back()->with('success', 'Movie details updated!');
        } else {
            // Failed to update movie details
            return redirect()->back()->with('error', 'Failed to update movie details. Please try again.');
        }
    }

    public function getPosterpath()
    {
        $id = request()->id;
        $accessToken =
            "Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI3MzU2ZjZjNzgxZjg0MjAyNjM2N2I4YmFhMjI1YWJkYiIsInN1YiI6IjY1MDFjOTdkNTU0NWNhMDBhYjVkYmRkOSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.zvglGM1QgLDK33Dt6PpMK9jeAOrLNnxClZ6mkLeMgBE";
        $url = 'https://api.themoviedb.org/3/movie/' . $id . '?language=en-US';
        $res = Http::withHeaders(["Authorization" => $accessToken])->get($url);
        $data = json_decode($res->body(), false);

        return $data;
    }

    public function updateDescription(Request $request, $movieid)
    {
        // Check if the user is an admin before allowing the update
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $movie = Movie::find($movieid);
        if (!$movie) {
            return response()->json(['error' => 'Movie not found'], 404);
        }

        // Validate the incoming request data
        $validatedData = $request->validate([
            'description' => 'required|string|max:255',
        ]);

        // Update the movie description
        $movie->description = $validatedData['description'];
        $movie->save();

        return response()->json(['message' => 'Description updated successfully']);
    }

    public function updateMovieOverview(Request $request, $movie_id)
    {
        $movie = Movie::find($movie_id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        $newOverview = $request->input('newOverview');

        // Validate and update the overview
        if ($newOverview) {
            $movie->overview = $newOverview;
            $movie->save();

            return response()->json(['message' => 'Movie overview updated successfully', 'movie' => $movie]);
        } else {
            return response()->json(['message' => 'Please provide a new overview'], 400);
        }
    }

    public function saveMovieDescription(Request $request, $movieId)
    {
        $movieDescription = MovieDescription::updateOrCreate(
            ['movie_id' => $movieId],
            ['description' => $request->input('description')]
        );

        return response()->json(['message' => 'Description saved successfully']);
    }

// Retrieve movie description
   
}
