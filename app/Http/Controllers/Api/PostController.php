<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // public function index()
    // {
    //     $posts = Post::all();
    //     return response()->json([
    //         'data' => PostResource::collection($posts)
    //     ]);
    // }
    public function index()
    {
        try {
            $posts = Post::all();
            return response()->json([
                'success' => true,
                'data' => PostResource::collection($posts)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        return response()->json([
            'data' => new PostResource($post)
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => 'required|string',
            'content' => 'required|string',
            'author'  => 'required|string',
            'tags'    => 'nullable|string', // Accepts comma-separated tags
        ]);

        // Convert tags to array if given as comma-separated string
        if (isset($data['tags']) && is_string($data['tags'])) {
            $data['tags'] = array_filter(array_map('trim', explode(',', $data['tags'])));
        }

        $data['published_at'] = now();

        $post = Post::create($data);

        return new PostResource($post);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $data = $request->validate([
            'title'   => 'sometimes|required|string',
            'content' => 'sometimes|required|string',
            'author'  => 'sometimes|required|string',
            'tags'    => 'nullable|string',
        ]);

        if (isset($data['tags']) && is_string($data['tags'])) {
            $data['tags'] = array_filter(array_map('trim', explode(',', $data['tags'])));
        }

        $post->update($data);

        return new PostResource($post);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted'], 200);
    }
}