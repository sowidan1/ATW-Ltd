<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Auth::user()->posts()->withTrashed()->orderBy('pinned', 'desc')->orderBy('deleted_at', 'asc')->get();
        return response()->json($posts);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required|string',
            'cover_image' => 'required|image',
            'pinned' => 'required|boolean',
            'tags' => 'nullable|array|exists:tags,id',
            'tags_string' => 'nullable|string', // Add this new validation rule
        ]);

        // Convert the comma-separated string to an array if provided
        $tags = $validatedData['tags'] ?? [];
        if (isset($validatedData['tags_string'])) {
            $tags = array_merge($tags, explode(',', $validatedData['tags_string']));
        }

        $coverImage = $request->file('cover_image');
        $coverImagePath = $coverImage->storeAs('public/cover_images');

        $post = Auth::user()->posts()->create([
            'title' => $validatedData['title'],
            'body' => $validatedData['body'],
            'cover_image' => $coverImagePath,
            'pinned' => $validatedData['pinned'],
        ]);

        $post->tags()->attach($validatedData['tags']);

        return response()->json($post, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        if ($post->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($post);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        if ($post->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required|string',
            'cover_image' => 'nullable|image',
            'pinned' => 'required|boolean',
            'tags' => 'array|exists:tags,id',
        ]);

        if ($request->hasFile('cover_image')) {
            Storage::delete($post->cover_image);
            $coverImage = $request->file('cover_image');
            $coverImagePath = $coverImage->store('public/cover_images');
            $post->cover_image = $coverImagePath;
        }

        $post->title = $validatedData['title'];
        $post->body = $validatedData['body'];
        $post->pinned = $validatedData['pinned'];
        $post->save();

        $post->tags()->sync($validatedData['tags']);

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }

    public function deletedPosts()
    {
        $deletedPosts = Auth::user()->posts()->onlyTrashed()->get();

        if ($deletedPosts->isEmpty()) {
            return response()->json(['message' => 'No deleted posts found.'], 200);
        }

        return response()->json($deletedPosts);
    }

    public function restore(Post $post)
    {
        if ($post->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $post->restore();

        return response()->json(['message' => 'Post restored']);
    }

    public function stats()
    {
        $totalUsers = User::count();
        $totalPosts = Post::count();
        $usersWithNoPosts = User::has('posts', '=', 0)->count();

        return response()->json([
            'total_users' => $totalUsers,
            'total_posts' => $totalPosts,
            'users_with_no_posts' => $usersWithNoPosts,
        ]);
    }
}
