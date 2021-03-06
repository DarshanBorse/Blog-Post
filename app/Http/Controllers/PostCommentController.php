<?php

namespace App\Http\Controllers;

use App\Events\PostCommented;
use App\Http\Requests\Comment;
use App\Http\Resources\Comment as ResourcesComment;
use App\Mail\CommentPostedMarkDown;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PostCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Post $post)
    {
        // dump($post->comment);
        // dump(get_class($post->comment()->with('user')->get()));
        // die;

        return  ResourcesComment::collection(($post->comment()->with('user')->get()));
        // return $post->comment()->with('user')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Post $post, Comment $request)
    {
        $comment = $post->comment()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id
        ]);

        // Mail::to($post->user)->send(
        //     new CommentPostedMarkDown($comment)
        // );

        // Mail::to($post->user)->queue(
        //     new CommentPostedMarkDown($comment)
        // );

        event(new PostCommented($comment));

        $time = now()->addMinutes(1);

        // Mail::to($post->user)->later(
        //     $time,
        //     new CommentPostedMarkDown($comment)
        // );

        $request->session()->flash('status', 'Comment was created!');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
