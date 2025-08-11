<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Posts\Post;
use Illuminate\Http\Request;
use App\Models\Categories\Category;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    protected $user;
    protected $tenantId;
    public function __construct()
    {
        $this->user = Auth::user();
        $this->tenantId = currentTenantId();
    }



    public function postCreateIndex(Request $request)
    {
        $categoryId = $request->input('categoryId');

        if (!$categoryId) {
            return redirect()->back()->with('error', 'Category ID is required');
        }

        return Inertia::render('PostCreate', [
            'categoryId' => $categoryId,
            'categoryName' => $request->input('categoryName', 'Default Category Name'),
        ]);
    }



    public function postCreate(Request $request)
    {

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ], [
            'title.required' => 'The post title is required.',
            'title.string' => 'The post title must be a string.',
            'title.max' => 'The post title may not be greater than 255 characters.',
            'category_id.required' => 'The category ID is required.',
            'category_id.exists' => 'The selected category does not exist.',
            'featured_image.image' => 'The featured image must be an image file.',
            'featured_image.mimes' => 'The featured image must be a file of type: jpeg, png, jpg, svg, or webp.',
            'featured_image.max' => 'The featured image may not be greater than 2MB.',
        ]);

        if ($request->hasFile('featured_image')) {
            $imageName = time() . '.' . $request->file('featured_image')->getClientOriginalExtension();
            $request->file('featured_image')->storeAs('featured_images', $imageName, 'public');
            $data['featured_image'] = $imageName;
        } else {
            $data['featured_image'] = null;
        }

        Post::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'featured_image' => $data['featured_image'] ?? null,
            'category_id' => $data['category_id'],
            'tenant_id' => $this->tenantId ?? 1,
            'created_by' => $this->user ? $this->user->id : null,
        ]);

        return redirect()->back()->with('success', 'Post created successfully.');
    }


    public function postDetail(Request $request)
    {
        $postId = $request->query('id');
        $postTitle = $request->query('postTitle');

        if (!$postId || !$postTitle) {
            return redirect()->back()->with('error', 'Post ID and title are required');
        }

        $post = Post::where('id', $postId)->with('category')->first();
        $categories = Category::where('tenant_id', $this->tenantId)->get();

        if (!$post) {
            return redirect()->back()->with('error', 'Post not found');
        }

        return Inertia::render('PostDetail', [
            'post' => $post,
            'postTitle' => $postTitle,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'Post ID is required');
        }

        $post = Post::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);
        if (!$data) {
            return redirect()->back()->with('error', 'Validation failed');
        }

        if ($request->hasFile('featured_image')) {
            $imageName = time() . '.' . $request->file('featured_image')->getClientOriginalExtension();
            $request->file('featured_image')->storeAs('featured_images', $imageName, 'public');
            $data['featured_image'] = $imageName;

            // Delete the old image if it exists
            if ($post->featured_image) {
                $oldImagePath = public_path('storage/featured_images/' . $post->featured_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        } else {
            $data['featured_image'] = $post->featured_image;
        }
        $data['tanant_id'] = $this->tenantId ?? 1;
        $data['updated_by'] = $this->user ? $this->user->id : null;

        $post->update($data);

        return redirect()->back()->with('success', 'Post updated successfully.');
    }

    public function destroy($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'Post ID is required');
        }

        $post = Post::findOrFail($id);

        if ($post->featured_image) {
            $oldImagePath = public_path('storage/featured_images/' . $post->featured_image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $post->delete();

        return redirect()->route('home')->with('success', 'Post deleted successfully.');
    }
}
