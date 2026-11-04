<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('category')->latest()->get();

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.form', [
            'post' => new Post(['type' => 'portfolio', 'is_featured' => true, 'url' => '#']),
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Post::create($this->validated($request));

        return redirect()->route('admin.posts.index')->with('status', 'Post created.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', [
            'post' => $post,
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $data = $this->validated($request);

        if (isset($data['image_url'])) {
            $this->deleteImage($post);
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('status', 'Post updated.');
    }

    public function destroy(Post $post)
    {
        $this->deleteImage($post);
        $post->delete();

        return redirect()->route('admin.posts.index')->with('status', 'Post deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'type' => ['required', Rule::in(Post::TYPES)],
            'title' => ['required', 'string', 'max:120'],
            'excerpt' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'url' => ['required', 'string', 'max:255'],
        ]);

        // Save the uploaded file to storage/app/public/posts and keep its full URL
        if ($request->hasFile('image')) {
            $data['image_url'] = asset('storage/'.$request->file('image')->store('posts', 'public'));
        }
        unset($data['image']);

        return $data + ['is_featured' => $request->boolean('is_featured')];
    }

    // Remove the old file, but only if it was uploaded through this panel
    private function deleteImage(Post $post): void
    {
        if ($post->image_url && str_contains($post->image_url, '/storage/posts/')) {
            Storage::disk('public')->delete('posts/'.basename($post->image_url));
        }
    }
}
