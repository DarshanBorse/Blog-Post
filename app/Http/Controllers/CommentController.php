<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment;
use App\Models\Comment as ModelsComment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
     public function comments()
    {
        return view('post');
    }

    public function index($comment){
        $post = Post::FindOrFail( $comment);
        return view('addcomment', compact('post'));
    }

    public function store(Comment $request)
    {
        
        $validation = $request->validated();
        $validation['user_id'] = $request->user()->id;
        ModelsComment::create($validation);

        $request->session()->flash('status', 'Comment posted!');
        return redirect()->route('post.show',$validation['post_id']);
    }
}