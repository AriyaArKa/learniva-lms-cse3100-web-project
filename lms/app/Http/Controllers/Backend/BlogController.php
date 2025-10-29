<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogComment;
use App\Models\SiteSetting;
use Intervention\Image\Laravel\Facades\Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;




class BlogController extends Controller
{
    public function AllBlogCategory()
    {

        $category = BlogCategory::latest()->get();
        return view('admin.backend.blogcategory.blog_category', compact('category'));

    }// End Method 

    public function StoreBlogCategory(Request $request)
    {

        BlogCategory::insert([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
        ]);

        $notification = array(
            'message' => 'BlogCategory Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);


    }// End Method 


    public function EditBlogCategory($id)
    {

        $categories = BlogCategory::find($id);
        return response()->json($categories);

    }// End Method 


    public function UpdateBlogCategory(Request $request)
    {
        $cat_id = $request->cat_id;

        BlogCategory::find($cat_id)->update([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
        ]);

        $notification = array(
            'message' => 'BlogCategory Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);


    }// End Method 

    public function DeleteBlogCategory($id)
    {

        BlogCategory::find($id)->delete();

        $notification = array(
            'message' => 'BlogCategory Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);


    }// End Method 

    //////////// All Blog Post Method .//

    public function BlogPost()
    {
        $post = BlogPost::latest()->get();
        return view('admin.backend.post.all_post', compact('post'));
    }// End Method 


    public function AddBlogPost()
    {

        $blogcat = BlogCategory::latest()->get();
        return view('admin.backend.post.add_post', compact('blogcat'));

    }// End Method 



    public function StoreBlogPost(Request $request)
    {

        $image = $request->file('post_image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

        // Create the upload directory if it doesn't exist
        if (!file_exists(public_path('upload/post'))) {
            mkdir(public_path('upload/post'), 0755, true);
        }

        // Process image with Intervention Image v3 syntax
        $processedImage = Image::read($image)->resize(370, 247);
        $processedImage->save(public_path('upload/post/' . $name_gen));
        $save_url = 'upload/post/' . $name_gen;

        BlogPost::insert([
            'blogcat_id' => $request->blogcat_id,
            'post_title' => $request->post_title,
            'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
            'long_descp' => $request->long_descp,
            'post_tags' => $request->post_tags,
            'post_image' => $save_url,
            'created_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Blog Post Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('blog.post')->with($notification);

    }// End Method 




    public function EditBlogPost($id)
    {

        $blogcat = BlogCategory::latest()->get();
        $post = BlogPost::find($id);
        return view('admin.backend.post.edit_post', compact('post', 'blogcat'));

    }// End Method 


    public function UpdateBlogPost(Request $request)
    {

        $post_id = $request->id;

        if ($request->file('post_image')) {

            $image = $request->file('post_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Create the upload directory if it doesn't exist
            if (!file_exists(public_path('upload/post'))) {
                mkdir(public_path('upload/post'), 0755, true);
            }

            // Process image with Intervention Image v3 syntax
            $processedImage = Image::read($image)->resize(370, 247);
            $processedImage->save(public_path('upload/post/' . $name_gen));
            $save_url = 'upload/post/' . $name_gen;

            BlogPost::find($post_id)->update([
                'blogcat_id' => $request->blogcat_id,
                'post_title' => $request->post_title,
                'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
                'long_descp' => $request->long_descp,
                'post_tags' => $request->post_tags,
                'post_image' => $save_url,
                'created_at' => Carbon::now(),

            ]);

            $notification = array(
                'message' => 'Blog Post Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('blog.post')->with($notification);

        } else {

            BlogPost::find($post_id)->update([
                'blogcat_id' => $request->blogcat_id,
                'post_title' => $request->post_title,
                'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
                'long_descp' => $request->long_descp,
                'post_tags' => $request->post_tags,
                'created_at' => Carbon::now(),

            ]);

            $notification = array(
                'message' => 'Blog Post Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('blog.post')->with($notification);

        } // end else 

    }// End Method 


    public function DeleteBlogPost($id)
    {

        $item = BlogPost::find($id);
        $img = $item->post_image;
        unlink($img);

        BlogPost::find($id)->delete();

        $notification = array(
            'message' => 'Blog Post Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }// End Method 



    public function BlogDetails($slug)
    {

        $blog = BlogPost::where('post_slug', $slug)->with('comments.user')->first();

        if (!$blog) {
            abort(404, 'Blog post not found');
        }

        $tags = $blog->post_tags;
        $tags_all = $tags ? explode(',', $tags) : [];
        $bcategory = BlogCategory::latest()->get();
        $post = BlogPost::latest()->limit(3)->get();

        // Get admin user (role = 'admin')
        $admin = \App\Models\User::where('role', 'admin')->first();

        return view('frontend.blog.blog_details', compact('blog', 'tags_all', 'bcategory', 'post', 'admin'));

    }// End Method 


    public function BlogCatList($id)
    {

        $blog = BlogPost::where('blogcat_id', $id)->get();
        $breadcat = BlogCategory::where('id', $id)->first();
        $bcategory = BlogCategory::latest()->get();
        $post = BlogPost::latest()->limit(3)->get();
        return view('frontend.blog.blog_cat_list', compact('blog', 'breadcat', 'bcategory', 'post'));

    }// End Method


    public function BlogList()
    {

        $blog = BlogPost::latest()->paginate(2);
        $bcategory = BlogCategory::latest()->get();
        $post = BlogPost::latest()->limit(3)->get();
        return view('frontend.blog.blog_list', compact('blog', 'bcategory', 'post'));


    }// End Method 


    public function StoreComment(Request $request)
    {
        $request->validate([
            'blog_post_id' => 'required|exists:blog_posts,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        BlogComment::create([
            'blog_post_id' => $request->blog_post_id,
            'user_id' => Auth::check() ? Auth::id() : null,
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
            'status' => 0, // Pending approval
        ]);

        $notification = array(
            'message' => 'Comment submitted successfully! It will appear after admin approval.',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Method


    public function AdminPendingComment()
    {
        $comments = BlogComment::with(['blogPost', 'user'])
            ->where('status', 0)
            ->latest()
            ->get();

        return view('admin.backend.comment.pending_comment', compact('comments'));

    }// End Method


    public function AdminApprovedComment()
    {
        $comments = BlogComment::with(['blogPost', 'user'])
            ->where('status', 1)
            ->latest()
            ->get();

        return view('admin.backend.comment.approved_comment', compact('comments'));

    }// End Method


    public function ApproveComment($id)
    {
        BlogComment::findOrFail($id)->update(['status' => 1]);

        $notification = array(
            'message' => 'Comment Approved Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Method


    public function DeleteComment($id)
    {
        BlogComment::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Comment Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Method




}
