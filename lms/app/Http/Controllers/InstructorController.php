<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Order;
use App\Models\Payment;


class InstructorController extends Controller
{
    public function InstructorDashboard()
    {
        $id = Auth::user()->id;

        // Get instructor's courses
        $instructorCourses = Course::where('instructor_id', $id)->pluck('id');

        // Get statistics
        $totalCourses = Course::where('instructor_id', $id)->count();

        // Get payment IDs with 'complete' status
        $completedPaymentIds = Payment::where('status', 'complete')->pluck('id');

        // Get total students enrolled in instructor's courses (using completed payments)
        $totalStudents = Order::whereIn('course_id', $instructorCourses)
            ->whereIn('payment_id', $completedPaymentIds)
            ->distinct('user_id')
            ->count('user_id');

        // Get total revenue from instructor's courses
        $totalRevenue = Order::whereIn('course_id', $instructorCourses)
            ->whereIn('payment_id', $completedPaymentIds)
            ->sum('price');

        // Get total orders (with completed payments)
        $totalOrders = Order::whereIn('course_id', $instructorCourses)
            ->whereIn('payment_id', $completedPaymentIds)
            ->count();

        // Get pending payments
        $pendingPaymentIds = Payment::where('status', 'pending')->pluck('id');
        $pendingOrders = Order::whereIn('course_id', $instructorCourses)
            ->whereIn('payment_id', $pendingPaymentIds)
            ->count();

        // Calculate bounce rate (pending orders percentage)
        $bounceRate = ($totalOrders + $pendingOrders) > 0
            ? round(($pendingOrders / ($totalOrders + $pendingOrders)) * 100, 1)
            : 0;

        // Get recent orders with course and payment info
        $recentOrders = Order::whereIn('course_id', $instructorCourses)
            ->with(['course', 'payment', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get monthly revenue for current year
        $currentYear = date('Y');
        $monthlyRevenue = Payment::whereIn('id', function ($query) use ($instructorCourses) {
            $query->select('payment_id')
                ->from('orders')
                ->whereIn('course_id', $instructorCourses);
        })
            ->where('status', 'complete')
            ->where('order_year', $currentYear)
            ->selectRaw('order_month, SUM(total_amount) as total')
            ->groupBy('order_month')
            ->pluck('total', 'order_month')
            ->toArray();

        // Create array for all 12 months
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $monthlyRevenueData = [];
        $monthlyOrdersData = [];

        foreach ($months as $month) {
            $monthlyRevenueData[] = $monthlyRevenue[$month] ?? 0;

            // Get orders count per month
            $ordersCount = Order::whereIn('course_id', $instructorCourses)
                ->whereIn('payment_id', function ($query) use ($month, $currentYear) {
                    $query->select('id')
                        ->from('payments')
                        ->where('status', 'complete')
                        ->where('order_month', $month)
                        ->where('order_year', $currentYear);
                })
                ->count();
            $monthlyOrdersData[] = $ordersCount;
        }

        // Calculate this month's revenue
        $currentMonth = date('F');
        $thisMonthRevenue = $monthlyRevenue[$currentMonth] ?? 0;

        // Calculate last month's revenue for comparison
        $lastMonth = date('F', strtotime('-1 month'));
        $lastMonthRevenue = $monthlyRevenue[$lastMonth] ?? 0;

        // Calculate percentage change
        $revenueChange = $lastMonthRevenue > 0
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        return view('instructor.index', compact(
            'totalCourses',
            'totalStudents',
            'totalRevenue',
            'totalOrders',
            'bounceRate',
            'recentOrders',
            'monthlyRevenueData',
            'monthlyOrdersData',
            'revenueChange'
        ));
    }

    public function InstructorLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Logout Successfully',
            'alert-type' => 'info'
        );

        return redirect('/instructor/login')->with($notification);
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
