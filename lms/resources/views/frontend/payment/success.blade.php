@extends('frontend.master')
@section('home')
    <!-- ================================
            START BREADCRUMB AREA
        ================================= -->
    <section class="breadcrumb-area section-padding img-bg-2">
        <div class="overlay"></div>
        <div class="container">
            <div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between">
                <div class="section-heading">
                    <h2 class="section__title text-white">Payment Status</h2>
                </div>
                <ul
                    class="generic-list-item generic-list-item-white generic-list-item-arrow d-flex flex-wrap align-items-center">
                    <li><a href="{{ route('index') }}">Home</a></li>
                    <li>Payment</li>
                    <li>Status</li>
                </ul>
            </div><!-- end breadcrumb-content -->
        </div><!-- end container -->
    </section><!-- end breadcrumb-area -->
    <!-- ================================
            END BREADCRUMB AREA
        ================================= -->

    <!-- ================================
               START PAYMENT STATUS AREA
        ================================= -->
    <section class="cart-area section--padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card card-item text-center">
                        <div class="card-body">
                            <div class="payment-status-icon mb-4">
                                <i class="la la-check-circle text-success" style="font-size: 80px;"></i>
                            </div>
                            <h2 class="card-title text-success mb-4">Payment Successful!</h2>
                            <p class="mb-4">Thank you for your purchase. Your payment has been processed successfully.</p>
                            
                            @if(isset($payment))
                            <div class="payment-details bg-light p-4 rounded mb-4">
                                <h5 class="mb-3">Payment Details</h5>
                                <div class="row">
                                    <div class="col-md-6 text-left">
                                        <p><strong>Invoice No:</strong> {{ $payment->invoice_no }}</p>
                                        <p><strong>Amount:</strong> ${{ $payment->total_amount }}</p>
                                        <p><strong>Payment Method:</strong> {{ $payment->payment_type }}</p>
                                    </div>
                                    <div class="col-md-6 text-left">
                                        <p><strong>Order Date:</strong> {{ $payment->order_date }}</p>
                                        <p><strong>Status:</strong> <span class="badge badge-success">Confirmed</span></p>
                                        @if($payment->transaction_id)
                                            <p><strong>Transaction ID:</strong> {{ $payment->transaction_id }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="la la-info-circle mr-2"></i>
                                You can now access your purchased courses from your dashboard. A confirmation email has been sent to your registered email address.
                            </div>
                            @endif
                            <div class="btn-box">
                                <a href="{{ route('index') }}" class="btn theme-btn">
                                    <i class="la la-home mr-2"></i>Back to Home
                                </a>
                                <a href="{{ route('my.course') }}" class="btn theme-btn-white ml-3">
                                    <i class="la la-book mr-2"></i>My Courses
                                </a>
                            </div>
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div><!-- end col-lg-8 -->
            </div><!-- end row -->
        </div><!-- end container -->
    </section>
    <!-- ================================
               END PAYMENT STATUS AREA
        ================================= -->
@endsection