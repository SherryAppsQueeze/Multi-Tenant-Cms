<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Posts\Post;
use Illuminate\Http\Request;
use App\Models\Categories\Category;

class CategoryController extends Controller
{

    protected $tenantId;

    public function __construct()
    {
        $this->tenantId = currentTenantId();
    }

    public function createIndex()
    {
        return Inertia::render('CategoryCreate');
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'The category name is required.',
            'name.string' => 'The category name must be a string.',
            'name.max' => 'The category name may not be greater than 255 characters.',
        ]);

        // Assuming you have a Tanent model to handle the creation
        Category::create([
            'tenant_id' => $this->tenantId ?? 1,
            'name' => $data['name'],
        ]);

        return redirect()->route('home')->with('success', 'Category created successfully.');
    }

    public function categoryPosts()
    {
        $id = request()->query('id');
        $categoryName = request()->query('categoryName');

        if (!$id || !$categoryName) {
            return redirect()->back()->with('error', 'Category ID and name are required');
        }

     $post = Post::where('category_id', $id)
    ->where('tenant_id', $this->tenantId)
    ->with('category')
    ->get();


        return Inertia::render('CategoryPosts', [
            'posts' => $post,
            'categoryId' => $id,
            'categoryName' => $categoryName,
        ]);
    }


    public function categoryedit()
    {
        $id = request()->query('id');
        $categoryName = request()->query('categoryName');

        if (!$id || !$categoryName) {
            return redirect()->back()->with('error', 'Category ID and name are required');
        }

        $Category = Category::where('id', $id)->firstOrFail();

        return Inertia::render('CategoryEdit', [
            'Category' => $Category
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'The category name is required.',
            'name.string' => 'The category name must be a string.',
            'name.max' => 'The category name may not be greater than 255 characters.',
        ]);

        $category = Category::findOrFail($id);
        $category->update($data);

        return redirect()->route('home')->with('success', 'Category updated successfully.');
    }
}
