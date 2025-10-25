<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Payment;

class PreserveAuthForPayment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // If user is not authenticated and this is a payment callback
        if (!Auth::check() && $request->has('tran_id')) {
            $tran_id = $request->input('tran_id');
            $payment = Payment::where('invoice_no', $tran_id)->first();

            if ($payment) {
                // Try to get stored user ID from session first
                $storedUserId = Session::get('payment_user_id');

                if (!$storedUserId) {
                    // If no stored user ID, get from the first order
                    $order = \App\Models\Order::where('payment_id', $payment->id)->first();
                    if ($order) {
                        $storedUserId = $order->user_id;
                    }
                }

                // Re-authenticate the user
                if ($storedUserId) {
                    Auth::loginUsingId($storedUserId);
                }
            }
        }

        return $next($request);
    }
}
