@php
    $setting = App\Models\SiteSetting::find(1);
    $categories = App\Models\Category::orderBy('category_name', 'ASC')->limit(6)->get();
    $blogPosts = App\Models\BlogPost::latest()->limit(3)->get();
@endphp
<section class="footer-area pt-100px">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 responsive-column-half">
                <div class="footer-item">
                    <a href="index.html">
                        <img src="{{ asset($setting->logo) }}" alt="footer logo" class="footer__logo">
                    </a>
                    <ul class="generic-list-item pt-4">
                        <li><a href="tel:+1631237884">{{ $setting->phone }}</a></li>
                        <li><a href="mailto:support@wbsite.com">{{ $setting->email }}</a></li>
                        <li>{{ $setting->address }}</li>
                    </ul>
                    <h3 class="fs-20 font-weight-semi-bold pt-4 pb-2">We are on</h3>
                    <ul class="social-icons social-icons-styled">
                        @if ($setting->facebook)
                            <li class="mr-1"><a href="{{ $setting->facebook }}" class="facebook-bg" target="_blank"><i
                                        class="la la-facebook"></i></a></li>
                        @endif
                        @if ($setting->twitter)
                            <li class="mr-1"><a href="{{ $setting->twitter }}" class="twitter-bg" target="_blank"><i
                                        class="la la-twitter"></i></a></li>
                        @endif
                    </ul>
                </div><!-- end footer-item -->
            </div><!-- end col-lg-3 -->
            <div class="col-lg-3 responsive-column-half">
                <div class="footer-item">
                    <h3 class="fs-20 font-weight-semi-bold">Company</h3>
                    <span class="section-divider section--divider"></span>
                    <ul class="generic-list-item">
                        <li><a href="{{ url('/') }}">About us</a></li>
                        <li><a href="{{ url('/contact') }}">Contact us</a></li>
                        <li><a href="{{ route('become.instructor') }}">Become a Teacher</a></li>
                        <li><a href="{{ url('/support') }}">Support</a></li>
                        <li><a href="{{ url('/faq') }}">FAQs</a></li>
                        @if ($blogPosts->count() > 0)
                            <li><a href="{{ url('/blog') }}">Blog</a></li>
                        @endif
                    </ul>
                </div><!-- end footer-item -->
            </div><!-- end col-lg-3 -->
            <div class="col-lg-3 responsive-column-half">
                <div class="footer-item">
                    <h3 class="fs-20 font-weight-semi-bold">Courses</h3>
                    <span class="section-divider section--divider"></span>
                    <ul class="generic-list-item">
                        @forelse($categories as $category)
                            <li><a
                                    href="{{ url('category/' . $category->id . '/' . $category->category_slug) }}">{{ $category->category_name }}</a>
                            </li>
                        @empty
                            <li><a href="#">Web Development</a></li>
                            <li><a href="#">Programming</a></li>
                            <li><a href="#">Business</a></li>
                            <li><a href="#">Design</a></li>
                        @endforelse
                    </ul>
                </div><!-- end footer-item -->
            </div><!-- end col-lg-3 -->
            <div class="col-lg-3 responsive-column-half">
                <div class="footer-item">
                    <h3 class="fs-20 font-weight-semi-bold">Download App</h3>
                    <span class="section-divider section--divider"></span>
                    <div class="mobile-app">
                        <p class="pb-3 lh-24">Download our mobile app and learn on the go.</p>
                        <a href="#" class="d-block mb-2 hover-s"><img
                                src="{{ asset('frontend/images/appstore.png') }}" alt="App store"
                                class="img-fluid"></a>
                        <a href="#" class="d-block hover-s"><img
                                src="{{ asset('frontend/images/googleplay.png') }}" alt="Google play store"
                                class="img-fluid"></a>
                    </div>
                </div><!-- end footer-item -->
            </div><!-- end col-lg-3 -->
        </div><!-- end row -->
    </div><!-- end container -->
    <div class="section-block"></div>
    <div class="copyright-content py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <p class="copy-desc"> {{ $setting->copyright }}</p>
                </div><!-- end col-lg-6 -->
                <div class="col-lg-6">
                    <div class="d-flex flex-wrap align-items-center justify-content-end">
                        <ul class="generic-list-item d-flex flex-wrap align-items-center fs-14">
                            <li class="mr-3"><a href="{{ route('terms.conditions') }}">Terms & Conditions</a></li>
                            <li class="mr-3"><a href="{{ route('privacy.policy') }}">Privacy Policy</a></li>
                        </ul>
                        <div class="select-container select-container-sm">
                            <select class="select-container-select">
                                <option value="1">English</option>
                                <option value="17">Hindi</option>
                            </select>
                        </div>
                    </div>
                </div><!-- end col-lg-6 -->
            </div><!-- end row -->
        </div><!-- end container -->
    </div><!-- end copyright-content -->
</section><!-- end footer-area -->
