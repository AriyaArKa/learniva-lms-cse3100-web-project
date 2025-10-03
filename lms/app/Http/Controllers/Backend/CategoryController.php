<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
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

    public function EditCategory($id)
    {
        $category = Category::find($id);
        return view('admin.backend.category.edit_category', compact('category'));
    }

    public function UpdateCategory(Request $request)
    {
        // Validate the request
        $request->validate([
            'category_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id' => 'required|exists:categories,id'
        ]);

        try {
            $cat_id = $request->id;
            $category = Category::findOrFail($cat_id);

            if ($request->file('image')) {
                $image = $request->file('image');

                if (!$image->isValid()) {
                    throw new \Exception('Invalid image file');
                }

                // Generate unique filename
                $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

                // Create upload directory if it doesn't exist
                $upload_path = public_path('upload/category/');
                if (!File::exists($upload_path)) {
                    File::makeDirectory($upload_path, 0755, true);
                }

                $save_url = 'upload/category/' . $name_gen;
                $full_path = $upload_path . $name_gen;

                // Try to use Intervention Image for processing, fallback to simple move
                try {
                    Image::make($image)->resize(300, 300)->save($full_path);
                    Log::info('Image processed and saved with Intervention Image');
                } catch (\Exception $imageException) {
                    Log::warning('Intervention Image failed, using simple file move: ' . $imageException->getMessage());
                    if (!$image->move($upload_path, $name_gen)) {
                        throw new \Exception('Failed to save image file');
                    }
                }

                // Delete old image if it exists and is not the default image
                if ($category->image && $category->image !== 'upload/no_image.jpg') {
                    $old_image_path = public_path($category->image);
                    if (File::exists($old_image_path)) {
                        File::delete($old_image_path);
                        Log::info('Old image deleted: ' . $old_image_path);
                    }
                }

                $category->update([
                    'category_name' => $request->category_name,
                    'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
                    'image' => $save_url,
                ]);

                $notification = array(
                    'message' => 'Category updated with image successfully',
                    'alert-type' => 'success'
                );
            } else {
                $category->update([
                    'category_name' => $request->category_name,
                    'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
                ]);

                $notification = array(
                    'message' => 'Category updated successfully',
                    'alert-type' => 'success'
                );
            }

            Log::info('Category updated successfully', ['category_id' => $cat_id]);
            return redirect()->route('all.category')->with($notification);
        } catch (\Exception $e) {
            Log::error('Category update failed: ' . $e->getMessage());

            $notification = array(
                'message' => 'Error updating category: ' . $e->getMessage(),
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification)->withInput();
        }
    }

    public function DeleteCategory($id)
    {
        try {
            $category = Category::findOrFail($id);

            // Delete associated image if it exists and is not the default image
            if ($category->image && $category->image !== 'upload/no_image.jpg') {
                $image_path = public_path($category->image);
                if (File::exists($image_path)) {
                    File::delete($image_path);
                    Log::info('Category image deleted: ' . $image_path);
                }
            }

            $category->delete();
            Log::info('Category deleted successfully', ['category_id' => $id]);

            $notification = array(
                'message' => 'Category deleted successfully',
                'alert-type' => 'success'
            );
        } catch (\Exception $e) {
            Log::error('Category deletion failed: ' . $e->getMessage());

            $notification = array(
                'message' => 'Error deleting category: ' . $e->getMessage(),
                'alert-type' => 'error'
            );
        }

        return redirect()->back()->with($notification);
    }









    // Subcategory Methods
    public function AllSubCategory()
    {
        $subcategory = SubCategory::with('category')->latest()->get();
        return view('admin.backend.subcategory.all_subcategory', compact('subcategory'));
    }

    public function AddSubCategory()
    {
        $category = Category::latest()->get();
        return view('admin.backend.subcategory.add_subcategory', compact('category'));
    }

    public function StoreSubCategory(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_name' => 'required|string|max:255|unique:sub_categories,subcategory_name',
        ]);

        SubCategory::create([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => strtolower(str_replace(' ', '-', $request->subcategory_name)),
        ]);

        $notification = array(
            'message' => 'SubCategory Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.subcategory')->with($notification);
    }

    public function EditSubCategory($id)
    {
        $subcategory = SubCategory::find($id);
        $category = Category::latest()->get();
        return view('admin.backend.subcategory.edit_subcategory', compact('subcategory', 'category'));
    }

    public function UpdateSubCategory(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sub_categories,id',
            'category_id' => 'required|exists:categories,id',
            'subcategory_name' => 'required|string|max:255|unique:sub_categories,subcategory_name,' . $request->id,
        ]);

        $subcategory = SubCategory::findOrFail($request->id);

        $subcategory->update([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => strtolower(str_replace(' ', '-', $request->subcategory_name)),
        ]);

        $notification = array(
            'message' => 'SubCategory Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.subcategory')->with($notification);
    }

    public function DeleteSubCategory($id)
    {
        try {
            $subcategory = SubCategory::findOrFail($id);
            $subcategory->delete();

            $notification = array(
                'message' => 'SubCategory Deleted Successfully',
                'alert-type' => 'success'
            );
        } catch (\Exception $e) {
            $notification = array(
                'message' => 'Error deleting subcategory: ' . $e->getMessage(),
                'alert-type' => 'error'
            );
        }

        return redirect()->back()->with($notification);
    }
}
