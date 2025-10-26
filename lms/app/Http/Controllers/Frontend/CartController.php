<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Course;
use App\Models\Course_goal;
use App\Models\CourseSection;
use App\Models\CourseLecture;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Coupon;
use Illuminate\Support\Facades\Session;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\Orderconfirm;
use App\Services\SSLCommerzService;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderComplete;



class CartController extends Controller
{
    public function AddToCart(Request $request, $id)
    {

        $course = Course::find($id);

        if (Session::has('coupon')) {
            Session::forget('coupon');
        }

        // Check if the course is already in the cart
        $cartItem = Cart::search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id === $id;
        });

        if ($cartItem->isNotEmpty()) {
            return response()->json(['error' => 'Course is already in your cart']);
        }
        if ($course->discount_price == NULL) {

            Cart::add([
                'id' => $id,
                'name' => $request->course_name,
                'qty' => 1,
                'price' => $course->selling_price,
                'weight' => 1,
                'options' => [
                    'image' => $course->course_image,
                    'slug' => $request->course_name_slug,
                    'instructor' => $request->instructor,
                ],
            ]);

        } else {

            Cart::add([
                'id' => $id,
                'name' => $request->course_name,
                'qty' => 1,
                'price' => $course->discount_price,
                'weight' => 1,
                'options' => [
                    'image' => $course->course_image,
                    'slug' => $request->course_name_slug,
                    'instructor' => $request->instructor,
                ],
            ]);
        }

        return response()->json(['success' => 'Successfully Added on Your Cart']);



    }// End Method


    public function CartData()
    {

        $carts = Cart::content();
        $cartTotal = Cart::total();
        $cartQty = Cart::count();

        return response()->json(array(
            'carts' => $carts,
            'cartTotal' => $cartTotal,
            'cartQty' => $cartQty,
        ));

    }// End Method 


    public function AddMiniCart()
    {

        $carts = Cart::content();
        $cartTotal = Cart::total();
        $cartQty = Cart::count();

        return response()->json(array(
            'carts' => $carts,
            'cartTotal' => $cartTotal,
            'cartQty' => $cartQty,
        ));

    }// End Method 


    public function RemoveMiniCart($rowId)
    {

        Cart::remove($rowId);
        return response()->json(['success' => 'Course Remove From Cart']);

    }// End Method 



    public function MyCart()
    {

        return view('frontend.mycart.view_mycart');

    } // End Method 


    public function GetCartCourse()
    {

        $carts = Cart::content();
        $cartTotal = Cart::total();
        $cartQty = Cart::count();

        return response()->json(array(
            'carts' => $carts,
            'cartTotal' => $cartTotal,
            'cartQty' => $cartQty,
        ));

    }// End Method 


    public function CartRemove($rowId)
    {

        Cart::remove($rowId);
        if (Session::has('coupon')) {
            $coupon_name = Session::get('coupon')['coupon_name'];
            $coupon = Coupon::where('coupon_name', $coupon_name)->first();

            Session::put('coupon', [
                'coupon_name' => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => round(Cart::total() * $coupon->coupon_discount / 100),
                'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount / 100)
            ]);

        }
        return response()->json(['success' => 'Course Remove From Cart']);

    }// End Method 


    public function CouponApply(Request $request)
    {
        $coupon = Coupon::where('coupon_name', $request->coupon_name)->where('coupon_validity', '>=', Carbon::now()->format('Y-m-d'))->first();

        if ($coupon) {
            Session::put('coupon', [
                'coupon_name' => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => round(Cart::total() * $coupon->coupon_discount / 100),
                'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount / 100)
            ]);

            return response()->json(array(
                'validity' => true,
                'success' => 'Coupon Applied Successfully'
            ));

        } else {
            return response()->json(['error' => 'Invaild Coupon']);
        }


    }// End Method 

    public function InsCouponApply(Request $request)
    {

        $coupon = Coupon::where('coupon_name', $request->coupon_name)->where('coupon_validity', '>=', Carbon::now()->format('Y-m-d'))->first();

        if ($coupon) {
            if ($coupon->course_id == $request->course_id && $coupon->instructor_id == $request->instructor_id) {

                Session::put('coupon', [
                    'coupon_name' => $coupon->coupon_name,
                    'coupon_discount' => $coupon->coupon_discount,
                    'discount_amount' => round(Cart::total() * $coupon->coupon_discount / 100),
                    'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount / 100)
                ]);

                return response()->json(array(
                    'validity' => true,
                    'success' => 'Coupon Applied Successfully'
                ));

            } else {
                return response()->json(['error' => 'Coupon Criteria Not Met for this course and instructor']);
            }
        } else {
            return response()->json(['error' => 'Invalid Coupon']);
        }
    }// End Method 

    public function CouponCalculation()
    {

        if (Session::has('coupon')) {
            return response()->json(array(
                'subtotal' => Cart::total(),
                'coupon_name' => session()->get('coupon')['coupon_name'],
                'coupon_discount' => session()->get('coupon')['coupon_discount'],
                'discount_amount' => session()->get('coupon')['discount_amount'],
                'total_amount' => session()->get('coupon')['total_amount'],
            ));
        } else {
            return response()->json(array(
                'total' => Cart::total(),
            ));
        }

    }// End Method 

    public function CouponRemove()
    {

        Session::forget('coupon');
        return response()->json(['success' => 'Coupon Remove Successfully']);

    }// End Method 

    public function CheckoutCreate()
    {

        if (Auth::check()) {

            if (Cart::total() > 0) {
                $carts = Cart::content();
                $cartTotal = Cart::total();
                $cartQty = Cart::count();

                return view('frontend.checkout.checkout_view', compact('carts', 'cartTotal', 'cartQty'));
            } else {

                $notification = array(
                    'message' => 'Add At list One Course',
                    'alert-type' => 'error'
                );
                return redirect()->to('/')->with($notification);

            }

        } else {

            $notification = array(
                'message' => 'You Need to Login First',
                'alert-type' => 'error'
            );
            return redirect()->route('login')->with($notification);

        }

    }// End Method 
    public function Payment(Request $request)
    {
        $user = User::where('role', 'instructor')->get();


        if (Session::has('coupon')) {
            $total_amount = Session::get('coupon')['total_amount'];
        } else {
            $total_amount = round(Cart::total());
        }

        // Cerate a new Payment Record 

        $data = new Payment();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->cash_delivery = $request->cash_delivery;
        $data->total_amount = $total_amount;
        $data->payment_type = $request->cash_delivery == 'sslcommerz' ? 'SSLCommerz' : 'Direct Payment';

        $data->invoice_no = 'EOS' . mt_rand(10000000, 99999999);
        $data->order_date = Carbon::now()->format('d F Y');
        $data->order_month = Carbon::now()->format('F');
        $data->order_year = Carbon::now()->format('Y');
        $data->status = 'pending';
        $data->created_at = Carbon::now();
        $data->save();

        foreach ($request->course_title as $key => $course_title) {

            $existingOrder = Order::where('user_id', Auth::user()->id)->where('course_id', $request->course_id[$key])->first();

            if ($existingOrder) {

                $notification = array(
                    'message' => 'You Have already enrolled in this course',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            } // end if 

            $order = new Order();
            $order->payment_id = $data->id;
            $order->user_id = Auth::user()->id;
            $order->course_id = $request->course_id[$key];
            $order->instructor_id = $request->instructor_id[$key];
            $order->course_title = $course_title;
            $order->price = $request->price[$key];
            $order->save();

        } // end foreach 

        $paymentId = $data->id;

        if ($request->cash_delivery == 'sslcommerz') {
            // SSLCommerz Payment Integration
            $sslcommerz = new SSLCommerzService();

            $postData = [
                'total_amount' => $total_amount,
                'currency' => 'BDT',
                'tran_id' => $data->invoice_no,
                'success_url' => url('/payment/success'),
                'fail_url' => url('/payment/fail'),
                'cancel_url' => url('/payment/cancel'),
                'ipn_url' => url('/payment/ipn'),

                'cus_name' => $request->name,
                'cus_email' => $request->email,
                'cus_add1' => $request->address,
                'cus_phone' => $request->phone,

                'product_name' => 'Course Purchase',
                'product_category' => 'Education',
                'product_profile' => 'general',
                'num_of_item' => count($request->course_title),
            ];

            $response = $sslcommerz->makePayment($postData);

            if (isset($response['status']) && $response['status'] == 'SUCCESS') {
                // Store payment session data (don't clear cart yet)
                Session::put('payment_id', $paymentId);
                Session::put('pending_payment', true);
                Session::put('payment_user_id', Auth::id()); // Store user ID for later

                // Redirect to SSLCommerz payment gateway
                return redirect($response['GatewayPageURL']);
            } else {
                // Payment initialization failed
                $notification = array(
                    'message' => 'Payment initialization failed. Please try again.',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
        } else {
            // Direct Payment (Cash on Delivery)
            $request->session()->forget('cart');

            /// Start Send email to student ///
            $sendmail = Payment::find($paymentId);
            $emailData = [
                'invoice_no' => $sendmail->invoice_no,
                'amount' => $total_amount,
                'name' => $sendmail->name,
                'email' => $sendmail->email,
            ];

            Mail::to($request->email)->send(new Orderconfirm($emailData));

            /// End Send email to student ///

            //send notification
            Notification::send($user, new OrderComplete($request->name));



            $notification = array(
                'message' => 'Cash Payment Submit Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('index')->with($notification);
        }

    }// End Method 


    // SSLCommerz Success Callback
    public function paymentSuccess(Request $request)
    {
        try {
            // Log the incoming data for debugging
            Log::info('Payment Success Callback Data:', $request->all());

            $tran_id = $request->input('tran_id');
            $val_id = $request->input('val_id');
            $amount = $request->input('amount');
            $card_type = $request->input('card_type');
            $store_amount = $request->input('store_amount');
            $bank_tran_id = $request->input('bank_tran_id');
            $status = $request->input('status');

            // For sandbox mode, we'll check if the status is VALID
            if ($status == 'VALID' || $status == 'VALIDATED') {
                $payment = Payment::where('invoice_no', $tran_id)->first();

                if ($payment) {
                    // Check if payment is already confirmed to avoid duplicate processing
                    if ($payment->status == 'confirmed') {
                        return view('frontend.payment.success', compact('payment'));
                    }

                    // Update payment status
                    $payment->status = 'confirmed';
                    $payment->transaction_id = $bank_tran_id ?? $val_id;
                    $payment->save();

                    // Don't clear any sessions or cart data here
                    // This preserves user authentication state

                    // Send confirmation email
                    try {
                        $emailData = [
                            'invoice_no' => $payment->invoice_no,
                            'amount' => $payment->total_amount,
                            'name' => $payment->name,
                            'email' => $payment->email,
                        ];

                        Mail::to($payment->email)->send(new Orderconfirm($emailData));
                    } catch (\Exception $emailError) {
                        Log::error('Email sending failed: ' . $emailError->getMessage());
                    }

                    // Return success view with payment details
                    return view('frontend.payment.success', compact('payment'));
                } else {
                    Log::error('Payment not found for transaction ID: ' . $tran_id);
                }
            } else {
                Log::error('Invalid payment status: ' . $status);
            }

            // If we reach here, something went wrong
            return view('frontend.payment.fail');

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Payment Success Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return view('frontend.payment.fail');
        }
    }

    // SSLCommerz Fail Callback
    public function paymentFail(Request $request)
    {
        try {
            Log::info('Payment Fail Callback Data:', $request->all());

            $tran_id = $request->input('tran_id');
            if ($tran_id) {
                $payment = Payment::where('invoice_no', $tran_id)->first();

                if ($payment) {
                    $payment->status = 'failed';
                    $payment->save();
                }
            }

            return view('frontend.payment.fail');
        } catch (\Exception $e) {
            Log::error('Payment Fail Error: ' . $e->getMessage());
            return view('frontend.payment.fail');
        }
    }

    // SSLCommerz Cancel Callback
    public function paymentCancel(Request $request)
    {
        try {
            Log::info('Payment Cancel Callback Data:', $request->all());

            $tran_id = $request->input('tran_id');
            if ($tran_id) {
                $payment = Payment::where('invoice_no', $tran_id)->first();

                if ($payment) {
                    $payment->status = 'cancelled';
                    $payment->save();
                }
            }

            return view('frontend.payment.cancel');
        } catch (\Exception $e) {
            Log::error('Payment Cancel Error: ' . $e->getMessage());
            return view('frontend.payment.cancel');
        }
    }

    // SSLCommerz IPN (Instant Payment Notification)
    public function paymentIPN(Request $request)
    {
        $sslcommerz = new SSLCommerzService();
        $validation = $sslcommerz->validateTransaction($request->all());

        if ($validation['status'] == 'valid') {
            $tran_id = $request->tran_id;
            $payment = Payment::where('invoice_no', $tran_id)->first();

            if ($payment && $payment->status == 'pending') {
                $payment->status = 'confirmed';
                $payment->transaction_id = $request->bank_tran_id;
                $payment->save();
            }
        }

        return response('OK');
    }


    public function BuyToCart(Request $request, $id)
    {

        $course = Course::find($id);

        // Check if the course is already in the cart
        $cartItem = Cart::search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id === $id;
        });

        if ($cartItem->isNotEmpty()) {
            return response()->json(['error' => 'Course is already in your cart']);
        }

        if ($course->discount_price == NULL) {

            Cart::add([
                'id' => $id,
                'name' => $request->course_name,
                'qty' => 1,
                'price' => $course->selling_price,
                'weight' => 1,
                'options' => [
                    'image' => $course->course_image,
                    'slug' => $request->course_name_slug,
                    'instructor' => $request->instructor,
                ],
            ]);

        } else {

            Cart::add([
                'id' => $id,
                'name' => $request->course_name,
                'qty' => 1,
                'price' => $course->discount_price,
                'weight' => 1,
                'options' => [
                    'image' => $course->course_image,
                    'slug' => $request->course_name_slug,
                    'instructor' => $request->instructor,
                ],
            ]);
        }

        return response()->json(['success' => 'Successfully Added on Your Cart']);

    }// End Method 



    public function MarkAsRead(Request $request, $notificationId)
    {

        $user = Auth::user();
        // If the User model doesn't use the Notifiable trait, query the notifications table directly
        $notification = \Illuminate\Notifications\DatabaseNotification::where('id', $notificationId)
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->first();

        if ($notification) {
            // mark as read by setting read_at
            $notification->update(['read_at' => Carbon::now()]);
        }

        // count unread notifications directly from the notifications table
        $unreadCount = \Illuminate\Notifications\DatabaseNotification::where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $unreadCount]);

    }// End Method 





}
