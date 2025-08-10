<?php

namespace App\Http\Controllers\Api;

use App\Models\Posts\Post;
use Illuminate\Http\Request;
use App\Models\Categories\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiResourceController extends Controller
{

    protected $user;
    protected $tenantId;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->tenantId = currentTenantId();
    }


    public function categoryindex()
    {
        $data = Category::all();
        return response()->json($data, 200);
    }

    public function createCategory(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'The category name is required.',
            'name.string' => 'The category name must be a string.',
            'name.max' => 'The category name may not be greater than 255 characters.',
        ]);

        Category::create([
            'tenant_id' => $this->tenantId ?? 1,
            'name' => $data['name'],
        ]);

        return response()->json(['message' => 'Category created successfully.'], 201);
    }

    public function updateCategory(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'id' => 'required|integer|exists:categories,id',
        ], [
            'name.required' => 'The category name is required.',
            'name.string' => 'The category name must be a string.',
            'name.max' => 'The category name may not be greater than 255 characters.',
            'id.required' => 'The category ID is required.',
        ]);

        $category = Category::findOrFail($request->id);

        $category->update($data);

        return response()->json(['message' => 'Category updated successfully.'], 200);
    }

    public function categoryPosts(Request $request)
    {
        $id = $request->query('id');


        if (!$id) {
            return response()->json(['message' => 'Category ID are required'], 400);
        }

        $posts = Post::where('category_id', $id)->with('category')->get();

        $posts->each(function ($post) {
            if ($post->featured_image) {
                $post->featured_image = asset('storage/featured_images/' . $post->featured_image);
            }
        });

        return response()->json([
            'posts' => $posts,
            'categoryId' => $id,
            'categoryName' => Category::find($id)->name,
        ], 200);
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
            'tanant_id' => $this->tenantId ?? 1,
            'created_by' => $this->user ? $this->user->id : null,
        ]);

        return response()->json(['message' => 'Post created successfully.'], 201);
    }

    public function postDetail(Request $request)
    {
        $id = $request->query('id');

        if (!$id) {
            return response()->json(['message' => 'Post ID is required'], 400);
        }

        $post = Post::with('category')->findOrFail($id);

        if ($post->featured_image) {
            $post->featured_image = asset('storage/featured_images/' . $post->featured_image);
        }

        return response()->json($post, 200);
    }


    public function Postupdate(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:posts,id',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable',
            'category_id' => 'nullable|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        $post = Post::find($data['id']);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if ($request->hasFile('featured_image')) {
            $imageName = time() . '.' . $request->file('featured_image')->getClientOriginalExtension();
            $request->file('featured_image')->storeAs('featured_images', $imageName, 'public');
            $data['featured_image'] = $imageName;

            if ($post->featured_image) {
                $oldImagePath = public_path('storage/featured_images/' . $post->featured_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        } else {
            $data['featured_image'] = $post->featured_image;
        }

        $data['tenant_id'] = 1;
        $data['updated_by'] = $this->user ? $this->user->id : null;

        unset($data['id']);

        $post->update($data);
        return response()->json(['message' => 'Post updated successfully.'], 200);
    }


    public function destroy($id)
    {
        if (!$id) {
            return response()->json(['message' => 'Post ID is required'], 400);
        }

        $post = Post::findOrFail($id);

        if ($post->featured_image) {
            $oldImagePath = public_path('storage/featured_images/' . $post->featured_image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully.'], 200);
    }
}
