@php
    $id = Auth::user()->id;
    $profileData = App\Models\User::find($id);

    // Get user's wishlist items
$wishlistItems = App\Models\Wishlist::where('user_id', $id)->with('course')->latest()->take(2)->get();
$wishlistCount = App\Models\Wishlist::where('user_id', $id)->count();

    // Get notifications count (you can customize this based on your notification system)
    $notificationsCount = 0; // Update this when you implement notifications

@endphp


<header class="header-menu-area">
    <div class="header-menu-content dashboard-menu-content pr-30px pl-30px bg-white shadow-sm">
        <div class="container-fluid">
            <div class="main-menu-content">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <div class="logo-box logo--box">
                            <a href="{{ url('/') }}" class="logo"><img src="{{ asset('frontend/images/logo.png') }}"
                                    alt="logo"></a>
                            <div class="user-btn-action">
                                <div class="off-canvas-menu-toggle cat-menu-toggle icon-element icon-element-sm shadow-sm mr-2"
                                    data-toggle="tooltip" data-placement="top" title="Category menu">
                                    <i class="la la-th-large"></i>
                                </div>
                                <div class="off-canvas-menu-toggle main-menu-toggle icon-element icon-element-sm shadow-sm"
                                    data-toggle="tooltip" data-placement="top" title="Main menu">
                                    <i class="la la-bars"></i>
                                </div>
                            </div>
                        </div><!-- end logo-box -->
                        <div class="menu-wrapper justify-content-end">
                            <div class="nav-right-button d-flex align-items-center">
                                <div class="user-action-wrap d-flex align-items-center">
                                    <div class="shop-cart wishlist-cart pr-3 mr-3 border-right border-right-gray">
                                        <ul>
                                            <li>
                                                <p class="shop-cart-btn">
                                                    <i class="la la-heart-o"></i>
                                                    @if ($wishlistCount > 0)
                                                        <span class="dot-status bg-1"></span>
                                                    @endif
                                                </p>
                                                <ul class="cart-dropdown-menu after-none">
                                                    @forelse($wishlistItems as $item)
                                                        <li>
                                                            <div class="media media-card">
                                                                <a href="{{ url('course/details/' . $item->course->id . '/' . $item->course->course_name_slug) }}"
                                                                    class="media-img">
                                                                    <img class="mr-3"
                                                                        src="{{ asset($item->course->course_image) }}"
                                                                        alt="{{ $item->course->course_name }}">
                                                                </a>
                                                                <div class="media-body">
                                                                    <h5><a
                                                                            href="{{ url('course/details/' . $item->course->id . '/' . $item->course->course_name_slug) }}">{{ Str::limit($item->course->course_name, 50) }}</a>
                                                                    </h5>
                                                                    <span
                                                                        class="d-block lh-18 py-1">{{ $item->course->user->name ?? 'Instructor' }}</span>
                                                                    @if ($item->course->discount_price)
                                                                        <p
                                                                            class="text-black font-weight-semi-bold lh-18">
                                                                            ${{ $item->course->discount_price }} <span
                                                                                class="before-price fs-14">${{ $item->course->selling_price }}</span>
                                                                        </p>
                                                                    @else
                                                                        <p
                                                                            class="text-black font-weight-semi-bold lh-18">
                                                                            ${{ $item->course->selling_price }}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <button type="button"
                                                                onclick="addToCart({{ $item->course_id }}, '{{ $item->course->course_name }}', {{ $item->course->instructor_id }}, '{{ $item->course->course_name_slug }}')"
                                                                class="btn theme-btn theme-btn-sm theme-btn-transparent lh-28 w-100 mt-3">Add
                                                                to cart <i
                                                                    class="la la-arrow-right icon ml-1"></i></button>
                                                        </li>
                                                    @empty
                                                        <li>
                                                            <div class="media media-card">
                                                                <div class="media-body text-center py-4">
                                                                    <p class="text-muted">Your wishlist is empty</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforelse
                                                    @if ($wishlistCount > 0)
                                                        <li>
                                                            <a href="{{ route('user.wishlist') }}"
                                                                class="btn theme-btn w-100">Go to
                                                                wishlist <i class="la la-arrow-right icon ml-1"></i></a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </li>
                                        </ul>
                                    </div><!-- end shop-cart -->





                                    {{-- User data --}}
                                    <div class="shop-cart user-profile-cart">
                                        <ul>
                                            <li>
                                                <div class="shop-cart-btn">
                                                    <div class="avatar-xs">
                                                        <img class="rounded-full img-fluid"
                                                            src="{{ !empty($profileData->photo) ? url('upload/user_images/' . $profileData->photo) : url('upload/no_image.jpg') }}"
                                                            alt="Avatar image">
                                                    </div>
                                                    <span class="dot-status bg-1"></span>
                                                </div>
                                                <ul
                                                    class="cart-dropdown-menu after-none p-0 notification-dropdown-menu">
                                                    <li class="menu-heading-block d-flex align-items-center">
                                                        <a href="teacher-detail.html"
                                                            class="avatar-sm flex-shrink-0 d-block">
                                                            <img class="rounded-full img-fluid"
                                                                src="{{ !empty($profileData->photo) ? url('upload/user_images/' . $profileData->photo) : url('upload/no_image.jpg') }}"
                                                                alt="Avatar image">
                                                        </a>
                                                        <div class="ml-2">
                                                            <h4><a href="teacher-detail.html"
                                                                    class="text-black">{{ $profileData->name }}</a>
                                                            </h4>
                                                            <span
                                                                class="d-block fs-14 lh-20">{{ $profileData->email }}</span>
                                                        </div>
                                                    </li>



                                                    <li>
                                                        <div
                                                            class="theme-picker d-flex align-items-center justify-content-center lh-40">
                                                            <button
                                                                class="theme-picker-btn dark-mode-btn w-100 font-weight-semi-bold justify-content-center"
                                                                title="Dark mode">
                                                                <svg class="mr-1" viewBox="0 0 24 24"
                                                                    stroke-width="1.5" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <path
                                                                        d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z">
                                                                    </path>
                                                                </svg>
                                                                Dark Mode
                                                            </button>
                                                            <button
                                                                class="theme-picker-btn light-mode-btn w-100 font-weight-semi-bold justify-content-center"
                                                                title="Light mode">
                                                                <svg class="mr-1" viewBox="0 0 24 24"
                                                                    stroke-width="1.5" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <circle cx="12" cy="12" r="5">
                                                                    </circle>
                                                                    <line x1="12" y1="1" x2="12"
                                                                        y2="3"></line>
                                                                    <line x1="12" y1="21" x2="12"
                                                                        y2="23"></line>
                                                                    <line x1="4.22" y1="4.22" x2="5.64"
                                                                        y2="5.64">
                                                                    </line>
                                                                    <line x1="18.36" y1="18.36" x2="19.78"
                                                                        y2="19.78"></line>
                                                                    <line x1="1" y1="12"
                                                                        x2="3" y2="12"></line>
                                                                    <line x1="21" y1="12"
                                                                        x2="23" y2="12"></line>
                                                                    <line x1="4.22" y1="19.78"
                                                                        x2="5.64" y2="18.36">
                                                                    </line>
                                                                    <line x1="18.36" y1="5.64"
                                                                        x2="19.78" y2="4.22">
                                                                    </line>
                                                                </svg>
                                                                Light Mode
                                                            </button>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <ul class="generic-list-item">

                                                            <li>
                                                                <div class="section-block"></div>
                                                            </li>

                                                            <li>
                                                                <a href="{{ route('my.course') }}">
                                                                    <i class="la la-history mr-1"></i> My Courses
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <div class="section-block"></div>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('user.profile') }}">
                                                                    <i class="la la-user mr-1"></i> Public profile
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('user.profile') }}">
                                                                    <i class="la la-edit mr-1"></i> Edit profile
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <div class="section-block"></div>
                                                            </li>

                                                            <li>
                                                                <a href="{{ route('user.logout') }}">
                                                                    <i class="la la-power-off mr-1"></i> Logout
                                                                </a>
                                                            </li>


                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div><!-- end shop-cart -->
                                </div>
                            </div><!-- end nav-right-button -->
                        </div><!-- end menu-wrapper -->
                    </div><!-- end col-lg-10 -->
                </div><!-- end row -->
            </div>
        </div><!-- end container-fluid -->
    </div><!-- end header-menu-content -->
    <div class="off-canvas-menu custom-scrollbar-styled main-off-canvas-menu">
        <div class="off-canvas-menu-close main-menu-close icon-element icon-element-sm shadow-sm"
            data-toggle="tooltip" data-placement="left" title="Close menu">
            <i class="la la-times"></i>
        </div><!-- end off-canvas-menu-close -->
        <h4 class="off-canvas-menu-heading pt-20px">Alerts</h4>
        <ul class="generic-list-item off-canvas-menu-list pt-1 pb-2 border-bottom border-bottom-gray">
            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('user.wishlist') }}">Wishlist</a></li>
            <li><a href="{{ route('mycart') }}">My cart</a></li>
        </ul>
        <h4 class="off-canvas-menu-heading pt-20px">Account</h4>
        <ul class="generic-list-item off-canvas-menu-list pt-1 pb-2 border-bottom border-bottom-gray">
            <li><a href="{{ route('user.profile') }}">Account settings</a></li>
            <li><a href="{{ route('my.course') }}">My Courses</a></li>
        </ul>
        <h4 class="off-canvas-menu-heading pt-20px">Profile</h4>
        <ul class="generic-list-item off-canvas-menu-list pt-1 pb-2 border-bottom border-bottom-gray">
            <li><a href="{{ route('user.profile') }}">Public profile</a></li>
            <li><a href="{{ route('user.profile') }}">Edit profile</a></li>
            <li><a href="{{ route('user.logout') }}">Log out</a></li>
        </ul>
        <h4 class="off-canvas-menu-heading pt-90px">Learn</h4>
        <ul class="generic-list-item off-canvas-menu-list pt-1 pb-2 border-bottom border-bottom-gray">
            <li><a href="{{ route('my.course') }}">My learning</a></li>
        </ul>
        <h4 class="off-canvas-menu-heading pt-20px">Categories</h4>
        <ul class="generic-list-item off-canvas-menu-list pt-1">
            <li>
                <a href="course-grid.html">Development</a>
                <ul class="sub-menu">
                    <li><a href="#">All Development</a></li>
                    <li><a href="#">Web Development</a></li>
                    <li><a href="#">Mobile Apps</a></li>
                    <li><a href="#">Game Development</a></li>
                    <li><a href="#">Databases</a></li>
                    <li><a href="#">Programming Languages</a></li>
                    <li><a href="#">Software Testing</a></li>
                    <li><a href="#">Software Engineering</a></li>
                    <li><a href="#">E-Commerce</a></li>
                </ul>
            </li>
            <li>
                <a href="course-grid.html">business</a>
                <ul class="sub-menu">
                    <li><a href="#">All Business</a></li>
                    <li><a href="#">Finance</a></li>
                    <li><a href="#">Entrepreneurship</a></li>
                    <li><a href="#">Strategy</a></li>
                    <li><a href="#">Real Estate</a></li>
                    <li><a href="#">Home Business</a></li>
                    <li><a href="#">Communications</a></li>
                    <li><a href="#">Industry</a></li>
                    <li><a href="#">Other</a></li>
                </ul>
            </li>
            <li>
                <a href="course-grid.html">IT & Software</a>
                <ul class="sub-menu">
                    <li><a href="#">All IT & Software</a></li>
                    <li><a href="#">IT Certification</a></li>
                    <li><a href="#">Hardware</a></li>
                    <li><a href="#">Network & Security</a></li>
                    <li><a href="#">Operating Systems</a></li>
                    <li><a href="#">Other</a></li>
                </ul>
            </li>
            <li>
                <a href="course-grid.html">Finance & Accounting</a>
                <ul class="sub-menu">
                    <li><a href="#"> All Finance & Accounting</a></li>
                    <li><a href="#">Accounting & Bookkeeping</a></li>
                    <li><a href="#">Cryptocurrency & Blockchain</a></li>
                    <li><a href="#">Economics</a></li>
                    <li><a href="#">Investing & Trading</a></li>
                    <li><a href="#">Other Finance & Economics</a></li>
                </ul>
            </li>
            <li>
                <a href="course-grid.html">design</a>
                <ul class="sub-menu">
                    <li><a href="#">All Design</a></li>
                    <li><a href="#">Graphic Design</a></li>
                    <li><a href="#">Web Design</a></li>
                    <li><a href="#">Design Tools</a></li>
                    <li><a href="#">3D & Animation</a></li>
                    <li><a href="#">User Experience</a></li>
                    <li><a href="#">Other</a></li>
                </ul>
            </li>
            <li>
                <a href="course-grid.html">Personal Development</a>
                <ul class="sub-menu">
                    <li><a href="#">All Personal Development</a></li>
                    <li><a href="#">Personal Transformation</a></li>
                    <li><a href="#">Productivity</a></li>
                    <li><a href="#">Leadership</a></li>
                    <li><a href="#">Personal Finance</a></li>
                    <li><a href="#">Career Development</a></li>
                    <li><a href="#">Parenting & Relationships</a></li>
                    <li><a href="#">Happiness</a></li>
                </ul>
            </li>
            <li>
                <a href="course-grid.html">Marketing</a>
                <ul class="sub-menu">
                    <li><a href="#">All Marketing</a></li>
                    <li><a href="#">Digital Marketing</a></li>
                    <li><a href="#">Search Engine Optimization</a></li>
                    <li><a href="#">Social Media Marketing</a></li>
                    <li><a href="#">Branding</a></li>
                    <li><a href="#">Video & Mobile Marketing</a></li>
                    <li><a href="#">Affiliate Marketing</a></li>
                    <li><a href="#">Growth Hacking</a></li>
                    <li><a href="#">Other</a></li>
                </ul>
            </li>
            <li>
                <a href="course-grid.html">Health & Fitness</a>
                <ul class="sub-menu">
                    <li><a href="#">All Health & Fitness</a></li>
                    <li><a href="#">Fitness</a></li>
                    <li><a href="#">Sports</a></li>
                    <li><a href="#">Dieting</a></li>
                    <li><a href="#">Self Defense</a></li>
                    <li><a href="#">Meditation</a></li>
                    <li><a href="#">Mental Health</a></li>
                    <li><a href="#">Yoga</a></li>
                    <li><a href="#">Dance</a></li>
                    <li><a href="#">Other</a></li>
                </ul>
            </li>
            <li>
                <a href="course-grid.html">Photography</a>
                <ul class="sub-menu">
                    <li><a href="#">All Photography</a></li>
                    <li><a href="#">Digital Photography</a></li>
                    <li><a href="#">Photography Fundamentals</a></li>
                    <li><a href="#">Commercial Photography</a></li>
                    <li><a href="#">Video Design</a></li>
                    <li><a href="#">Photography Tools</a></li>
                    <li><a href="#">Other</a></li>
                </ul>
            </li>
        </ul>
    </div><!-- end off-canvas-menu -->
    <div class="body-overlay"></div>
</header><!-- end header-menu-area -->
