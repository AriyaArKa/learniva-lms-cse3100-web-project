<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;



class UserController extends Controller
{
    //
    public function Index(){
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

        return redirect('/login');
    } //end method

    public function UserProfileUpdate(Request $request)
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
            if ($data->photo && file_exists(public_path('upload/user_images/' . $data->photo))) {
                unlink(public_path('upload/user_images/' . $data->photo));
            }
            $file = $request->file('photo');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/user_images'), $filename);
            $data->photo = $filename;
        }
        $data->save();
        $notification = array(
            'message' => 'User Profile Updated Successfully!',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    
}
