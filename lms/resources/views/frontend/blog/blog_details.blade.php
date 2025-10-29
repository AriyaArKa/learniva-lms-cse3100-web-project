@extends('frontend.master')
@section('home')
@section('title')
    {{ $blog->post_title }} | Algo Oasis Learn
@endsection

<style>
    /* Fix for bullet points display in breadcrumb */
    .generic-list-item--bullet li {
        padding-left: 0 !important;
    }

    .generic-list-item--bullet li:not(:last-child)::after {
        content: "•";
        margin: 0 8px;
        color: #666;
    }

    /* Fix for bullet points and formatting in blog content */
    .blog-content ul {
        list-style-type: disc !important;
        margin-left: 20px !important;
        padding-left: 20px !important;
        margin-bottom: 15px !important;
    }

    .blog-content ol {
        list-style-type: decimal !important;
        margin-left: 20px !important;
        padding-left: 20px !important;
        margin-bottom: 15px !important;
    }

    .blog-content ul li,
    .blog-content ol li {
        display: list-item !important;
        margin-bottom: 10px !important;
        line-height: 1.6 !important;
    }

    .blog-content ul ul {
        list-style-type: circle !important;
        margin-top: 10px !important;
    }

    .blog-content ul ul ul {
        list-style-type: square !important;
    }

    .blog-content p {
        margin-bottom: 15px !important;
        line-height: 1.7 !important;
    }

    .blog-content h1 {
        font-size: 2rem !important;
        margin-top: 25px !important;
        margin-bottom: 15px !important;
        font-weight: 600 !important;
    }

    .blog-content h2 {
        font-size: 1.75rem !important;
        margin-top: 25px !important;
        margin-bottom: 15px !important;
        font-weight: 600 !important;
    }

    .blog-content h3 {
        font-size: 1.5rem !important;
        margin-top: 20px !important;
        margin-bottom: 12px !important;
        font-weight: 600 !important;
    }

    .blog-content h4 {
        font-size: 1.25rem !important;
        margin-top: 20px !important;
        margin-bottom: 12px !important;
        font-weight: 600 !important;
    }

    .blog-content h5,
    .blog-content h6 {
        font-size: 1.1rem !important;
        margin-top: 15px !important;
        margin-bottom: 10px !important;
        font-weight: 600 !important;
    }

    .blog-content blockquote {
        border-left: 4px solid #ddd;
        padding-left: 15px;
        margin: 20px 0;
        font-style: italic;
        color: #666;
    }

    .blog-content code {
        background-color: #f4f4f4;
        padding: 2px 6px;
        border-radius: 3px;
        font-family: monospace;
        font-size: 0.9em;
    }

    .blog-content pre {
        background-color: #f4f4f4;
        padding: 15px;
        border-radius: 5px;
        overflow-x: auto;
        margin: 15px 0;
    }

    .blog-content pre code {
        background-color: transparent;
        padding: 0;
    }

    .blog-content img {
        max-width: 100%;
        height: auto;
        margin: 15px 0;
        border-radius: 5px;
    }

    .blog-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
    }

    .blog-content table th,
    .blog-content table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    .blog-content table th {
        background-color: #f4f4f4;
        font-weight: 600;
    }

    .blog-content a {
        color: #007bff;
        text-decoration: underline;
    }

    .blog-content a:hover {
        color: #0056b3;
    }

    .blog-content strong,
    .blog-content b {
        font-weight: 700;
    }

    .blog-content em,
    .blog-content i {
        font-style: italic;
    }
</style>

<!-- ================================
    START BREADCRUMB AREA
================================= -->
<section class="breadcrumb-area pt-80px pb-80px pattern-bg">
    <div class="container">
        <div class="breadcrumb-content">
            <div class="section-heading pb-3">
                <h2 class="section__title"> {{ $blog->post_title }}</h2>
            </div>
            <ul class="generic-list-item generic-list-item-arrow d-flex flex-wrap align-items-center">
                <li><a href="{{ route('index') }}">Home</a></li>
                <li><a href="{{ route('blog.post') }}">Blog</a></li>
                <li>{{ $blog->post_title }}</li>
            </ul>
            <ul
                class="generic-list-item generic-list-item-bullet generic-list-item--bullet d-flex align-items-center flex-wrap fs-14 pt-2">
                <li class="d-flex align-items-center">By&nbsp;<a href="#">Admin</a></li>
                <li class="d-flex align-items-center">{{ $blog->created_at->format('M d Y') }}</li>
                <li class="d-flex align-items-center"><a href="#comments"
                        class="page-scroll">{{ isset($blog->comments) ? $blog->comments->count() : 0 }} Comments</a>
                </li>
                <li class="d-flex align-items-center">{{ $blog->views ?? 0 }} Views</li>
            </ul>
        </div><!-- end breadcrumb-content -->
    </div><!-- end container -->
</section><!-- end breadcrumb-area -->
<!-- ================================
    END BREADCRUMB AREA
================================= -->

<!-- ================================
       START BLOG AREA
================================= -->
<section class="blog-area pt-100px pb-100px">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mb-5">
                <div class="card card-item">
                    <div class="card-body">
                        <div class="card-text pb-3 blog-content">
                            {!! $blog->long_descp !!}
                        </div>



                        <div class="section-block"></div>
                        <h3 class="fs-18 font-weight-semi-bold pt-3">Tags</h3>
                        <div class="d-flex flex-wrap justify-content-between align-items-center pt-3">
                            <ul class="generic-list-item generic-list-item-boxed d-flex flex-wrap fs-15">
                                @if (count($tags_all) > 0)
                                    @foreach ($tags_all as $tag)
                                        @if (trim($tag))
                                            <li class="mr-2"><a href="#">{{ ucwords(trim($tag)) }}</a></li>
                                        @endif
                                    @endforeach
                                @else
                                    <li class="mr-2">No tags</li>
                                @endif
                            </ul>
                            <div class="share-wrap">
                                <ul class="social-icons social-icons-styled">
                                    <li class="mr-0"><a href="#" class="facebook-bg"><i
                                                class="la la-facebook"></i></a></li>
                                    <li class="mr-0"><a href="#" class="twitter-bg"><i
                                                class="la la-twitter"></i></a></li>
                                    <li class="mr-0"><a href="#" class="instagram-bg"><i
                                                class="la la-instagram"></i></a></li>
                                </ul>
                                <div class="icon-element icon-element-sm shadow-sm cursor-pointer share-toggle"
                                    title="Toggle to expand social icons"><i class="la la-share-alt"></i></div>
                            </div>
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
                <div class="instructor-wrap py-5">
                    <h3 class="fs-22 font-weight-semi-bold pb-4">About the author</h3>
                    <div class="media media-card">
                        <div class="media-img rounded-full avatar-lg mr-4">
                            @if($admin && !empty($admin->photo))
                                <img src="{{ asset('upload/admin_images/'.$admin->photo) }}" alt="Admin Avatar" class="rounded-full">
                            @else
                                <img src="{{ asset('upload/no_image.jpg') }}" alt="Avatar image" class="rounded-full">
                            @endif
                        </div>
                        <div class="media-body">
                            <h5>{{ $admin->name ?? 'Admin' }}</h5>
                            <span class="d-block lh-18 pt-2 pb-2">{{ $admin->email ?? 'admin@algooasis.com' }}</span>
                            <p class="pb-3">Content creator and educator passionate about sharing knowledge and
                                helping students learn effectively through engaging blog posts and tutorials.</p>
                            <ul class="social-icons social-icons-styled social--icons-styled">
                                <li><a href="#"><i class="la la-facebook"></i></a></li>
                                <li><a href="#"><i class="la la-twitter"></i></a></li>
                                <li><a href="#"><i class="la la-instagram"></i></a></li>
                                <li><a href="#"><i class="la la-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div><!-- end instructor-wrap -->
                <div class="section-block"></div>
                <div class="comments-wrap pt-5" id="comments">
                    <div class="d-flex align-items-center justify-content-between pb-4">
                        <h3 class="fs-22 font-weight-semi-bold">Comments</h3>
                        <span
                            class="ribbon ribbon-lg">{{ isset($blog->comments) ? $blog->comments->count() : 0 }}</span>
                    </div>
                    <div class="comment-list">
                        @if (isset($blog->comments) && $blog->comments->count() > 0)
                            @foreach ($blog->comments as $comment)
                                <div class="media media-card border-bottom border-bottom-gray pb-4 mb-4">
                                    <div class="media-img mr-4 rounded-full">
                                        @if ($comment->user)
                                            <img class="rounded-full"
                                                src="{{ !empty($comment->user->photo) ? url('upload/user_images/' . $comment->user->photo) : asset('upload/no_image.jpg') }}"
                                                alt="User image">
                                        @else
                                            <img class="rounded-full" src="{{ asset('upload/no_image.jpg') }}"
                                                alt="User image">
                                        @endif
                                    </div>
                                    <div class="media-body">
                                        <h5 class="pb-2">{{ $comment->name }}</h5>
                                        <span
                                            class="d-block lh-18 pb-2">{{ $comment->created_at->diffForHumans() }}</span>
                                        <p class="pb-3">{{ $comment->message }}</p>
                                    </div>
                                </div><!-- end media -->
                            @endforeach
                        @else
                            <p class="text-muted">No comments yet. Be the first to comment!</p>
                        @endif
                    </div>
                    <div class="load-more-btn-box text-center pt-3 pb-5">
                        <button class="btn theme-btn theme-btn-sm theme-btn-transparent lh-30"><i
                                class="la la-refresh mr-1"></i> Load More Comment</button>
                    </div>
                </div>
                <div class="section-block"></div>
                <div class="add-comment-wrap pt-5">
                    <h3 class="fs-22 font-weight-semi-bold pb-4">Add a Comment</h3>
                    <form method="POST" action="{{ route('blog.comment.store') }}">
                        @csrf
                        <input type="hidden" name="blog_post_id" value="{{ $blog->id }}">

                        <div class="row">
                            <div class="input-box col-lg-6">
                                <label class="label-text">Name<span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input class="form-control form--control" type="text" name="name"
                                        value="{{ Auth::check() ? Auth::user()->name : old('name') }}"
                                        placeholder="Your Name" required>
                                    <span class="la la-user input-icon"></span>
                                </div>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div><!-- end input-box -->

                            <div class="input-box col-lg-6">
                                <label class="label-text">Email<span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input class="form-control form--control" type="email" name="email"
                                        value="{{ Auth::check() ? Auth::user()->email : old('email') }}"
                                        placeholder="Email Address" required>
                                    <span class="la la-envelope input-icon"></span>
                                </div>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div><!-- end input-box -->

                            <div class="input-box col-lg-12">
                                <label class="label-text">Message<span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <textarea class="form-control form--control pl-3" name="message" placeholder="Write your comment here..."
                                        rows="5" required>{{ old('message') }}</textarea>
                                </div>
                                @error('message')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div><!-- end input-box -->

                            <div class="btn-box col-lg-12">
                                <div class="alert alert-info mb-3">
                                    <i class="la la-info-circle"></i> Your comment will be visible after admin
                                    approval.
                                </div>
                                <button class="btn theme-btn" type="submit">
                                    <i class="la la-paper-plane mr-1"></i> Submit Comment
                                </button>
                            </div><!-- end btn-box -->
                        </div><!-- end row -->
                    </form>
                </div><!-- end add-comment-wrap -->
            </div><!-- end col-lg-8 -->
            <div class="col-lg-4">
                <div class="sidebar">


                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-18 pb-2">Blog Category</h3>
                            <div class="divider"><span></span></div>
                            <ul class="generic-list-item">
                                @if ($bcategory && $bcategory->count() > 0)
                                    @foreach ($bcategory as $cat)
                                        <li><a href="{{ url('blog/cat/list/' . $cat->id) }}">{{ $cat->category_name }}
                                                <span
                                                    class="badge badge-primary ml-2">{{ \App\Models\BlogPost::where('blogcat_id', $cat->id)->count() }}</span></a>
                                        </li>
                                    @endforeach
                                @else
                                    <li>No categories available</li>
                                @endif
                            </ul>
                        </div>
                    </div><!-- end card -->
                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-18 pb-2">Recent Posts</h3>
                            <div class="divider"><span></span></div>

                            @if ($post && $post->count() > 0)
                                @foreach ($post as $dpost)
                                    <div class="media media-card border-bottom border-bottom-gray pb-4 mb-4">
                                        <a href="{{ url('blog/details/' . $dpost->post_slug) }}" class="media-img">
                                            <img class="mr-3" src="{{ asset($dpost->post_image) }}"
                                                alt="Related course image">
                                        </a>
                                        <div class="media-body">
                                            <h5 class="fs-15"><a
                                                    href="{{ url('blog/details/' . $dpost->post_slug) }}">{{ $dpost->post_title }}</a>
                                            </h5>
                                            <span
                                                class="d-block lh-18 py-1 fs-14">{{ $dpost->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div><!-- end media -->
                                @endforeach
                            @else
                                <p class="text-muted">No recent posts available.</p>
                            @endif

                            <div class="view-all-course-btn-box">
                                <a href="{{ route('blog') }}" class="btn theme-btn w-100">View All Posts <i
                                        class="la la-arrow-right icon ml-1"></i></a>
                            </div>
                        </div>
                    </div><!-- end card -->



                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-18 pb-2">Connect & Follow</h3>
                            <div class="divider"><span></span></div>
                            <ul class="social-icons social-icons-styled social--icons-styled">
                                <li><a href="#"><i class="la la-facebook"></i></a></li>
                                <li><a href="#"><i class="la la-twitter"></i></a></li>
                                <li><a href="#"><i class="la la-instagram"></i></a></li>
                                <li><a href="#"><i class="la la-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div><!-- end card -->
                </div><!-- end sidebar -->
            </div><!-- end col-lg-4 -->
        </div><!-- end row -->
    </div><!-- end container -->
</section><!-- end blog-area -->
<!-- ================================
       START BLOG AREA
================================= -->








@endsection
