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
                                <i class="la la-ban text-warning" style="font-size: 80px;"></i>
                            </div>
                            <h2 class="card-title text-warning mb-4">Payment Cancelled!</h2>
                            <p class="mb-4">Your payment has been cancelled. You can try again when you're ready.</p>
                            <div class="btn-box">
                                <a href="{{ route('checkout') }}" class="btn theme-btn">
                                    <i class="la la-refresh mr-2"></i>Try Again
                                </a>
                                <a href="{{ route('index') }}" class="btn theme-btn-white ml-3">
                                    <i class="la la-home mr-2"></i>Back to Home
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