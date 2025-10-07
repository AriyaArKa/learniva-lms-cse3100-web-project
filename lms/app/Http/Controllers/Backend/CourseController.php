<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Course;
use App\Models\Course_goal;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CourseController extends Controller
{
    public function AllCourse()
    {
        $id = Auth::user()->id;
        $courses = Course::where('instructor_id', $id)->orderBy('id', 'desc')->get();
        return view('instructor.courses.all_course', compact('courses'));
    } //end method

    public function AddCourse()
    {

        $categories = Category::latest()->get();
        return view('instructor.courses.add_course', compact('categories'));
    } // End Method 

    public function GetSubCategory($category_id)
    {
        $subcat = SubCategory::where('category_id', $category_id)->orderBy('subcategory_name', 'ASC')->get();
        return json_encode($subcat);
    } // End Method 

    public function CheckData()
    {
        $categories = Category::all();
        $subcategories = SubCategory::all();

        return response()->json([
            'categories' => $categories,
            'subcategories' => $subcategories
        ]);
    } // End Method

    public function StoreCourse(Request $request)
    {

        $request->validate([
            'video' => 'required|mimes:mp4|max:10000',
        ]);

        $image = $request->file('course_image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

        // Ensure upload directory exists
        $uploadPath = public_path('upload/course/thambnail/');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        try {
            // Use native PHP GD functions for image processing
            if (extension_loaded('gd') && function_exists('imagecreatefromstring')) {
                $sourceImage = imagecreatefromstring(file_get_contents($image->getPathname()));
                if ($sourceImage !== false) {
                    $width = imagesx($sourceImage);
                    $height = imagesy($sourceImage);

                    // Calculate new dimensions maintaining aspect ratio
                    $newWidth = 370;
                    $newHeight = 246;
                    $aspectRatio = $width / $height;

                    if ($aspectRatio > ($newWidth / $newHeight)) {
                        $newHeight = $newWidth / $aspectRatio;
                    } else {
                        $newWidth = $newHeight * $aspectRatio;
                    }

                    $newImage = imagecreatetruecolor($newWidth, $newHeight);
                    imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                    // Save based on file extension
                    $extension = strtolower($image->getClientOriginalExtension());
                    switch ($extension) {
                        case 'jpg':
                        case 'jpeg':
                            imagejpeg($newImage, $uploadPath . $name_gen, 90);
                            break;
                        case 'png':
                            imagepng($newImage, $uploadPath . $name_gen);
                            break;
                        case 'gif':
                            imagegif($newImage, $uploadPath . $name_gen);
                            break;
                        default:
                            imagejpeg($newImage, $uploadPath . $name_gen, 90);
                    }

                    imagedestroy($sourceImage);
                    imagedestroy($newImage);
                } else {
                    throw new \Exception('Could not create image from source');
                }
            } else {
                throw new \Exception('GD extension not available');
            }
        } catch (\Exception $e) {
            // Fallback: Use native PHP GD functions if available
            if (extension_loaded('gd') && function_exists('imagecreatefromstring')) {
                try {
                    $sourceImage = imagecreatefromstring(file_get_contents($image->getPathname()));
                    if ($sourceImage !== false) {
                        $width = imagesx($sourceImage);
                        $height = imagesy($sourceImage);

                        // Calculate new dimensions maintaining aspect ratio
                        $newWidth = 370;
                        $newHeight = 246;
                        $aspectRatio = $width / $height;

                        if ($aspectRatio > ($newWidth / $newHeight)) {
                            $newHeight = $newWidth / $aspectRatio;
                        } else {
                            $newWidth = $newHeight * $aspectRatio;
                        }

                        $newImage = imagecreatetruecolor($newWidth, $newHeight);
                        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                        // Save based on file extension
                        $extension = strtolower($image->getClientOriginalExtension());
                        switch ($extension) {
                            case 'jpg':
                            case 'jpeg':
                                imagejpeg($newImage, $uploadPath . $name_gen, 90);
                                break;
                            case 'png':
                                imagepng($newImage, $uploadPath . $name_gen);
                                break;
                            case 'gif':
                                imagegif($newImage, $uploadPath . $name_gen);
                                break;
                            default:
                                imagejpeg($newImage, $uploadPath . $name_gen, 90);
                        }

                        imagedestroy($sourceImage);
                        imagedestroy($newImage);
                    } else {
                        // Final fallback: just move the original image
                        $image->move($uploadPath, $name_gen);
                    }
                } catch (\Exception $gdException) {
                    // Final fallback: just move the original image
                    $image->move($uploadPath, $name_gen);
                }
            } else {
                // Final fallback: just move the original image without resizing
                $image->move($uploadPath, $name_gen);
            }
        }

        $save_url = 'upload/course/thambnail/' . $name_gen;

        $video = $request->file('video');
        $videoName = time() . '.' . $video->getClientOriginalExtension();

        // Ensure video upload directory exists
        $videoUploadPath = public_path('upload/course/video/');
        if (!file_exists($videoUploadPath)) {
            mkdir($videoUploadPath, 0755, true);
        }

        $video->move($videoUploadPath, $videoName);
        $save_video = 'upload/course/video/' . $videoName;


        $course_id = Course::insertGetId([

            'category_id' => $request->category_id,
            'subcategory_id' => !empty($request->subcategory_id) ? $request->subcategory_id : null,
            'instructor_id' => Auth::user()->id,
            'course_title' => $request->course_title,
            'course_name' => $request->course_name,
            'course_name_slug' => strtolower(str_replace(' ', '-', $request->course_name)),
            'description' => $request->description,
            'video' => $save_video,

            'label' => $request->label,
            'duration' => $request->duration,
            'resources' => $request->resources,
            'certificate' => $request->certificate,
            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,
            'prerequisites' => $request->prerequisites,

            'bestseller' => $request->bestseller,
            'featured' => $request->featured,
            'highestrated' => $request->highestrated,
            'status' => 1,
            'course_image' => $save_url,
            'created_at' => Carbon::now(),

        ]);

        /// Course Goals Add Form 

        $goles = Count($request->course_goals);
        if ($goles != NULL) {
            for ($i = 0; $i < $goles; $i++) {
                $gcount = new Course_goal();
                $gcount->course_id = $course_id;
                $gcount->goal_name = $request->course_goals[$i];
                $gcount->save();
            }
        }
        /// End Course Goals Add Form 

        $notification = array(
            'message' => 'Course Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.course')->with($notification);
    } // End Method 

    public function EditCourse($id){

        $course = Course::find($id);
        $categories = Category::latest()->get();
        $subcategories = SubCategory::latest()->get();
        return view('instructor.courses.edit_course',compact('course','categories','subcategories'));

    }// End Method 









}
