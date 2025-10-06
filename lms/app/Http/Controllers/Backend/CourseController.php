<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\Course;
use App\Models\Course_goal;
use Illuminate\Support\Facades\Auth;



class CourseController extends Controller
{
    public function AllCourse() {
        $id = Auth::user()->id;
        $courses = Course::where('instructor_id', $id)->latest()->get();$courses = Course::where('instructor_id',$id)->orderBy('id','desc')->get();
        return view('instructor.courses.all_course',compact('courses'));
    } //end method

    public function AddCourse(){

        $categories = Category::latest()->get();
        return view('instructor.courses.add_course',compact('categories'));
    }// End Method 

}
