<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Str;

class CommentController extends Controller
{

    public function store(Request $req, String $movieId, String $body, String $userId)
    {
        $comment = new Comment();

        $comment->commentsId = Str::uuid();

        $comment->userId = $userId;

        $comment->body = $body;

        $comment->movieId = $movieId;

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
