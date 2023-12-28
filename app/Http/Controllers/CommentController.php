<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    private $predefinedComments = [
        [
            'id' => 1,
            'body' => 'This movie was fantastic!',
        ],
        [
            'id' => 2,
            'body' => 'I loved the storyline.',
        ],
        // Add more predefined comments as needed
    ];

    public function index()
    {
        // Return predefined comments to the view
        return view('comments.index', ['comments' => $this->predefinedComments]);
    }

    public function delete(Request $request, $id)
    {
        // Admin can delete a predefined comment by its ID
        foreach ($this->predefinedComments as $key => $comment) {
            if ($comment['id'] == $id) {
                unset($this->predefinedComments[$key]);
                break;
            }
        }
        // Redirect back to the comments page
        return redirect()->route('comments.index');
    }
    public function store(Request $request)
    {
        $comment = new Comment();

        $comment->commentsId = Str::uuid();
        $comment->userId = $request->userId;
        $comment->body = $request->body;
        $comment->movieId = $request->movieId;

        $comment->save();
    }

    public function getAllForMovie(Request $req, String $movieId)
    {
        $comments = Comment::where('movieId', $movieId)->get();

        return $comments;
    }

    public function getAllForUser(Request $req, String $userId)
    {
        $comments = Comment::where('userId', $userId)->get();

        return $comments;
    }

    public function deleteComment(Request $req, String $commentsId)
    {
        $comment = Comment::find($commentsId);

        $comment->delete();
    }

    public function updateComment(Request $req, String $commentId, String $body)
    {
        $comment = Comment::find($commentId);

        $comment->body = $body;

        $comment->save();
    }
}
