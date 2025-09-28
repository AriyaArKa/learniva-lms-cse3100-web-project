<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;


class InstructorController extends Controller
{
    public function InstructorDashboard(){
        return view('instructor.index');
    }

    public function InstructorLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/instructor/login');
    } //end method

    public function InstructorLogin()
    {
        return view('instructor.instructor_login');
    }

    public function InstructorProfile()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('instructor.instructor_profile_view', compact('id', 'profileData'));
    }

    public function InstructorChangePassword()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('instructor.instructor_change_password', compact('id', 'profileData'));
    }

    public function InstructorPasswordUpdate(Request $request)
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


    public function InstructorProfileStore(Request $request)
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
            if ($data->photo && file_exists(public_path('upload/instructor_images/' . $data->photo))) {
                unlink(public_path('upload/instructor_images/' . $data->photo));
            }
            $file = $request->file('photo');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/instructor_images'), $filename);
            $data->photo = $filename;
        }
        $data->save();
        $notification = array(
            'message' => 'Instructor Profile Updated Successfully!',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

}
