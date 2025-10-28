<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Order;
use App\Models\Course;



class UserController extends Controller
{
    //
    public function Index()
    {
        return view('frontend.index');
    }

    public function UserProfile()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('frontend.dashboard.edit_profile', compact('id', 'profileData'));
    }

    public function UserLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Logout Successfully',
            'alert-type' => 'info'
        );

        return redirect('/login')->with($notification);
    } //end method

    public function UserProfileUpdate(Request $request)
    {
        // Debug: Log the incoming request data
        Log::info('Profile update request received:', $request->all());

        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'username' => 'required|string|max:255|unique:users,username,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $id = Auth::user()->id;
        $data = User::find($id);

        // Check if user exists
        if (!$data) {
            $notification = array(
                'message' => 'User not found.',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        // Update user data
        $data->name = $request->name;
        $data->email = $request->email;
        $data->username = $request->username;
        $data->phone = $request->phone;
        $data->address = $request->address;

        // Handle photo upload
        if ($request->hasFile('photo')) {
            Log::info('Photo file detected for upload');
            Log::info('File details:', [
                'original_name' => $request->file('photo')->getClientOriginalName(),
                'size' => $request->file('photo')->getSize(),
                'mime_type' => $request->file('photo')->getMimeType()
            ]);

            // Delete old photo if it exists
            if ($data->photo && file_exists(public_path('upload/user_images/' . $data->photo))) {
                unlink(public_path('upload/user_images/' . $data->photo));
                Log::info('Old photo deleted: ' . $data->photo);
            }

            $file = $request->file('photo');
            $filename = date('YmdHi') . $file->getClientOriginalName();

            try {
                $file->move(public_path('upload/user_images'), $filename);
                $data->photo = $filename;
                Log::info('Photo uploaded successfully: ' . $filename);
            } catch (\Exception $e) {
                Log::error('Photo upload failed: ' . $e->getMessage());
                $notification = array(
                    'message' => 'Photo upload failed: ' . $e->getMessage(),
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
        } else {
            Log::info('No photo file detected in request');
            if ($request->has('photo')) {
                Log::info('Photo field exists but no file uploaded');
            } else {
                Log::info('Photo field does not exist in request');
            }
        }

        $data->save();

        $notification = array(
            'message' => 'User Profile Updated Successfully!',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function UserChangePassword()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('frontend.dashboard.change_password', compact('id', 'profileData'));
    }

    public function UserPasswordUpdate(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        // Check if the old password matches
        if (!Hash::check($request->old_password, auth::user()->password)) {
            $notification = array(
                'message' => 'Old Password Does not Match!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        // Update the new password
        User::whereId(auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $notification = array(
            'message' => 'Password Changed Successfully!',
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }

    public function LiveChat()
    {
        return view('frontend.dashboard.live_chat');
    } // End Method 

    public function TermsConditions()
    {
        return view('frontend.pages.terms_conditions');
    } // End Method

    public function PrivacyPolicy()
    {
        return view('frontend.pages.privacy_policy');
    } // End Method

    public function UserDashboard()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);

        // Get all enrolled courses (total orders for this user)
        $enrolledCourses = Order::where('user_id', $id)->count();

        // Get unique courses (in case user ordered same course multiple times)
        $totalCourses = Order::where('user_id', $id)
            ->distinct('course_id')
            ->count('course_id');

        // Get active courses (courses with status = 1)
        $activeCourses = Order::where('user_id', $id)
            ->whereHas('course', function ($query) {
                $query->where('status', 1);
            })
            ->distinct('course_id')
            ->count('course_id');

        // Get completed courses (for now, let's assume courses with status = 0 are completed)
        // You can modify this logic based on your actual completion tracking
        $completedCourses = Order::where('user_id', $id)
            ->whereHas('course', function ($query) {
                $query->where('status', 0);
            })
            ->distinct('course_id')
            ->count('course_id');

        return view('frontend.dashboard.index', compact(
            'profileData',
            'enrolledCourses',
            'activeCourses',
            'completedCourses',
            'totalCourses'
        ));
    } // End Method


}
