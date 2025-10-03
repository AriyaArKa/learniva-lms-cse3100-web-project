<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function AllCategory()
    {
        $category = Category::latest()->get();
        return view('admin.backend.category.all_category', compact('category'));
    }
    // Return all categories
    public function AddCategory()
    {
        // Return the view to add a new category
        return view('admin.backend.category.add_category');
    }

    // public function StoreCategory(Request $request)
    // {
    //     // Validate and store the category
    // }

    // public function EditCategory($id)
    // {
    //     // Find the category by ID and return the edit view
    // }

    // public function UpdateCategory(Request $request)
    // {
    //     // Validate and update the category
    // }

    // public function DeleteCategory($id)
    // {
    //     // Find the category by ID and delete it
    // }
}
