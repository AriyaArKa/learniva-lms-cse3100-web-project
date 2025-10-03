<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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
        //Return the view to add a new category
        return view('admin.backend.category.add_category');
    }

    public function StoreCategory(Request $request)
    {
        // Validate the request
        $request->validate([
            'category_name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $image = $request->file('image');

            if (!$image || !$image->isValid()) {
                throw new \Exception('No valid image file received');
            }

            // Generate unique filename
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Create upload directory if it doesn't exist
            $upload_path = public_path('upload/category/');
            if (!File::exists($upload_path)) {
                File::makeDirectory($upload_path, 0755, true);
            }

            // Image handling - simple approach without processing for now
            $save_url = 'upload/category/' . $name_gen;
            $full_path = $upload_path . $name_gen;

            // Just move the file without processing (until GD is enabled)
            if (!$image->move($upload_path, $name_gen)) {
                throw new \Exception('Failed to save image file');
            }

            Log::info('Image saved successfully without processing');

            // Insert category into database
            $result = Category::create([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
                'image' => $save_url,
            ]);

            Log::info('Category created successfully', ['category_id' => $result->id]);

            $notification = array(
                'message' => 'Category inserted successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.category')->with($notification);
        } catch (\Exception $e) {
            Log::error('Category creation failed: ' . $e->getMessage());

            $notification = array(
                'message' => 'Error inserting category: ' . $e->getMessage(),
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification)->withInput();
        }
    }

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
