<?php

namespace App\Http\Controllers\Website;

use App\Category;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostDetailController extends Controller
{
    public function index(Request $request,$slug)
    {
        $post = new Post();
        $detail = $post->GetPostDetail($slug);
        if ($detail)
        {

            $post->UpdateViews($slug);

            $categories = Category::all();
            $mostViewedPosts= $post->MostViewedPosts(2);
            $mostLikedPosts= $post->MostLikedPosts(2);
            return view('website.detail',[
                'post'=>$detail,
                'categories'=>$categories,
                'mostViewedPosts'=>$mostViewedPosts,
                'mostLikedPosts'=>$mostLikedPosts
            ]);
        }
        return redirect()->back();

    }
}
