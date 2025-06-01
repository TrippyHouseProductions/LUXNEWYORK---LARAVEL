<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Resources\PostResource;
use MongoDB\Laravel\Eloquent\Model;

class PostController extends Controller
{
    // List all posts
    // public function index()
    // {
    //     return PostResource::collection(Post::all());
    // }

    public function index()
    {
        return response()->json(Post::all());
    }


    // Store new post
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string',
            'content' => 'required|string',
            'tags'    => 'nullable|array',
        ]);

        $post = Post::create([
            'title'        => $request->title,
            'content'      => $request->content,
            'tags'         => $request->tags ?? [],
            'published_at' => now(),
        ]);

        return new PostResource($post);
    }

    // Show a single post
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return new PostResource($post);
    }

    // Update a post
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $post->update($request->only(['title', 'content', 'tags']));

        return new PostResource($post);
    }

    // Delete a post
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }

    public function adminIndex(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $posts = Post::orderBy('published_at', 'desc')
                    ->paginate($perPage);

        return PostResource::collection($posts);
    }


}