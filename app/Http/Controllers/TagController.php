<?php

namespace App\Http\Controllers;

use App\Models\Tag;

class TagController extends Controller
{
    public function index($tag){
        $tags = Tag::FindOrFail($tag);
        $post = $tags->post;
         return view('posts',compact('post'));
    }
}
