<?php

namespace App\Http\Controllers\admin;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {

        return view('comments');
    }

    public function fetchComments()
    {
        $comments = Comment::all();

        return response()->json($comments);
    }

    public function store(Request $request)
    {
        $comment = Comment::create($request->all());

        //event(new CommentEvent($comment));
        return response()->json('ok');

    }
}
