<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class AdminController extends Controller
{
    public function AdminDashboard()
    {
        return view('admin.index');
    } //end method

    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Logout Successfully',
            'alert-type' => 'info'
        );

        return redirect('/admin/login')->with($notification);
    } //end method


    public function AdminLogin()
    {
        return view('admin.admin_login');
    }

    public function AdminProfile()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_profile_view', compact('id', 'profileData'));
    }

    public function AdminChangePassword()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_change_password', compact('id', 'profileData'));
    }

    public function AdminPasswordUpdate(Request $request)
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


    public function AdminProfileStore(Request $request)
    {
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

        // Only update if the values are not null or empty
        $data->name = $request->filled('name') ? $request->name : $data->name;
        $data->email = $request->filled('email') ? $request->email : $data->email;
        $data->username = $request->filled('username') ? $request->username : $data->username;
        $data->phone = $request->filled('phone') ? $request->phone : $data->phone;
        $data->address = $request->filled('address') ? $request->address : $data->address;

        if ($request->file('photo')) {
            // Delete old photo if it exists
            if ($data->photo && file_exists(public_path('upload/admin_images/' . $data->photo))) {
                unlink(public_path('upload/admin_images/' . $data->photo));
            }
            $file = $request->file('photo');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $filename);
            $data->photo = $filename;
        }
        $data->save();
        $notification = array(
            'message' => 'Admin Profile Updated Successfully!',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }



    //Instructor register realted functions
    public function BecomeInstructor()
    {
        return view('frontend.instructor.reg_instructor');
    } //end method

    public function InstructorRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
        ]);

        User::insert([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'instructor',
            'status' => '0',
        ]);

        $notification = array(
            'message' => 'Instructor Registered Successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('instructor.login')->with($notification);
    } //end method



    //all instructor method
    public function AllInstructor()
    {
        $allinstructor = User::where('role', 'instructor')->latest()->get();
        return view('admin.backend.instructor.all_instructor', compact('allinstructor'));
    } //end method


    //update user status method
    public function UpdateUserStatus(Request $request)
    {
        $userId = $request->input('user_id');
        $isChecked = $request->input('is_checked', 0);
        $user = User::find($userId);

        if ($user) {
            $user->status = $isChecked;
            $user->save();
        }

        return response()->json([
            'message' => 'User Status updated successfully'
        ]);
    } //end method



    public function AdminAllCourse()
    {

        $course = Course::latest()->get();
        return view('admin.backend.courses.all_course', compact('course'));

    }// End Method

    public function UpdateCourseStatus(Request $request)
    {

        $courseId = $request->input('course_id');
        $isChecked = $request->input('is_checked', 0);

        $course = Course::find($courseId);
        if ($course) {
            $course->status = $isChecked;
            $course->save();
        }

        return response()->json(['message' => 'Course Status Updated Successfully']);

    }// End Method


    public function AdminCourseDetails($id)
    {

        $course = Course::find($id);
        return view('admin.backend.courses.course_details', compact('course'));

    }// End Method

    /// Admin User All Method ////////////

    public function AllAdmin()
    {

        $alladmin = User::where('role', 'admin')->get();
        return view('admin.backend.pages.admin.all_admin', compact('alladmin'));

    }// End Method
    public function AddAdmin()
    {

        $roles = Role::all();
        return view('admin.backend.pages.admin.add_admin', compact('roles'));

    }// End Method

    public function StoreAdmin(Request $request)
    {

        // Validate the request data
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'password' => 'required|string|min:3',
            'roles' => 'required|exists:roles,id'
        ]);

        $user = new User();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->role = 'admin';
        $user->status = '1';
        $user->save();

        if ($request->roles) {
            // Find the role by ID and get its name
            $role = \Spatie\Permission\Models\Role::find($request->roles);
            if ($role) {
                $user->assignRole($role->name);
            }
        }

        $notification = array(
            'message' => 'New Admin Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.admin')->with($notification);

    }// End Method
    public function EditAdmin($id)
    {

        $user = User::find($id);
        $roles = Role::all();
        return view('admin.backend.pages.admin.edit_admin', compact('user', 'roles'));

    }// End Method

    public function UpdateAdmin(Request $request, $id)
    {

        // Validate the request data
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'roles' => 'required|exists:roles,id'
        ]);

        $user = User::find($id);
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->role = 'admin';
        $user->status = '1';
        $user->save();

        $user->roles()->detach();
        if ($request->roles) {
            // Find the role by ID and get its name
            $role = \Spatie\Permission\Models\Role::find($request->roles);
            if ($role) {
                $user->assignRole($role->name);
            }
        }

        $notification = array(
            'message' => 'Admin Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.admin')->with($notification);

    }// End Method
    public function DeleteAdmin($id)
    {

        $user = User::find($id);
        if (!is_null($user)) {
            $user->delete();
        }

        $notification = array(
            'message' => 'Admin Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }// End Method


}
