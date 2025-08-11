<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Posts\Post;
use Illuminate\Http\Request;
use App\Models\Tanents\Tanent;
use App\Models\Categories\Category;
use Illuminate\Support\Facades\Auth;

class TanentController extends Controller
{
    protected $user;
    protected $tenantId;
    
    public function __construct()
    {
        $this->user = Auth::user();
        $this->tenantId = currentTenantId();
        
        
    }

    public function index(Request $request)
    {
        // Allowed sort fields
        $allowedSorts = ['created_at', 'created_by', 'updated_at', 'updated_by'];
        $sortBy = $request->get('sort_by', 'created_at');
        $direction = $request->get('direction', 'desc');

        // Make sure we don't allow bad column names
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'updated_by';
        }

        // Fetch categories with posts count
        $categories = Category::where('tenant_id', $this->tenantId)->withCount('posts')->with('posts')->get();



        // Fetch posts sorted by the chosen column
        $posts = Post::where('tenant_id', $this->tenantId)->orderBy($sortBy, $direction)
            ->paginate(6)
            ->appends(['sort_by' => $sortBy, 'direction' => $direction]);



        return Inertia::render('Home', [
            'categories' => $categories,
            'posts' => $posts,
            'sortBy' => $sortBy,
            'direction' => $direction,
        ]);
    }


}
