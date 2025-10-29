@extends('frontend.master')
@section('home')
@section('title')
    {{ $category->category_name }} | Algo Oasis
@endsection
<!-- ================================
    START BREADCRUMB AREA
================================= -->
<section class="breadcrumb-area section-padding img-bg-2">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between">
            <div class="section-heading">
                <h2 class="section__title text-white">{{ $category->category_name }}</h2>
            </div>
            <ul
                class="generic-list-item generic-list-item-white generic-list-item-arrow d-flex flex-wrap align-items-center">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>{{ $category->category_name }}</li>
            </ul>
        </div><!-- end breadcrumb-content -->
    </div><!-- end container -->
</section><!-- end breadcrumb-area -->
<!-- ================================
    END BREADCRUMB AREA
================================= -->

<!--======================================
        START COURSE AREA
======================================-->
<section class="course-area section--padding">
    <div class="container">
        <div class="filter-bar mb-4">
            <div class="filter-bar-inner d-flex flex-wrap align-items-center justify-content-between">
                <p class="fs-14">We found <span class="text-black" id="course-count">{{ count($courses) }}</span>
                    courses available for
                    you</p>
                <button class="btn btn-sm btn-outline-secondary" id="clear-filters" style="display: none;">
                    <i class="la la-times"></i> Clear Filters
                </button>
            </div><!-- end filter-bar-inner -->
        </div><!-- end filter-bar -->
        <div class="row">
            <div class="col-lg-4">
                <div class="sidebar mb-5">
                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-18 pb-2">Search Field</h3>
                            <div class="divider"><span></span></div>
                            <form method="post">
                                <div class="form-group mb-0">
                                    <input class="form-control form--control pl-3" type="text" name="search"
                                        placeholder="Search courses">
                                    <span class="la la-search search-icon"></span>
                                </div>
                            </form>
                        </div>
                    </div><!-- end card -->

                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-18 pb-2">Course Categories</h3>
                            <div class="divider"><span></span></div>
                            <ul class="generic-list-item">
                                @foreach ($categories as $cat)
                                    <li>
                                        <a href="{{ url('category/' . $cat->id . '/' . $cat->category_slug) }}">
                                            {{ $cat->category_name }}
                                            <span class="text-gray">({{ $cat->courses_count ?? 0 }})</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div><!-- end card -->

                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-18 pb-2">Ratings</h3>
                            <div class="divider"><span></span></div>
                            <div class="custom-control custom-radio mb-1 fs-15">
                                <input type="radio" class="custom-control-input rating-filter" id="fiveStarRating"
                                    name="radio-stacked" value="5">
                                <label class="custom-control-label custom--control-label" for="fiveStarRating">
                                    <span class="rating-wrap d-flex align-items-center">
                                        <span class="review-stars">
                                            <span class="la la-star"></span>
                                            <span class="la la-star"></span>
                                            <span class="la la-star"></span>
                                            <span class="la la-star"></span>
                                            <span class="la la-star"></span>
                                        </span>
                                        <span class="rating-total pl-1"><span
                                                class="mr-1 text-black">5.0</span>({{ $ratingStats['5'] ?? 0 }})</span>
                                    </span>
                                </label>
                            </div>
                            <div class="custom-control custom-radio mb-1 fs-15">
                                <input type="radio" class="custom-control-input rating-filter" id="fourStarRating"
                                    name="radio-stacked" value="4">
                                <label class="custom-control-label custom--control-label" for="fourStarRating">
                                    <span class="rating-wrap d-flex align-items-center">
                                        <span class="review-stars">
                                            <span class="la la-star"></span>
                                            <span class="la la-star"></span>
                                            <span class="la la-star"></span>
                                            <span class="la la-star"></span>
                                            <span class="la la-star-o"></span>
                                        </span>
                                        <span class="rating-total pl-1"><span class="mr-1 text-black">4.0 &
                                                up</span>({{ $ratingStats['4'] ?? 0 }})</span>
                                    </span>
                                </label>
                            </div>
                            <div class="custom-control custom-radio mb-1 fs-15">
                                <input type="radio" class="custom-control-input rating-filter" id="threeStarRating"
                                    name="radio-stacked" value="3">
                                <label class="custom-control-label custom--control-label" for="threeStarRating">
                                    <span class="rating-wrap d-flex align-items-center">
                                        <span class="review-stars">
                                            <span class="la la-star"></span>
                                            <span class="la la-star"></span>
                                            <span class="la la-star"></span>
                                            <span class="la la-star-o"></span>
                                            <span class="la la-star-o"></span>
                                        </span>
                                        <span class="rating-total pl-1"><span class="mr-1 text-black">3.0 &
                                                up</span>({{ $ratingStats['3'] ?? 0 }})</span>
                                    </span>
                                </label>
                            </div>
                            <div class="custom-control custom-radio mb-1 fs-15">
                                <input type="radio" class="custom-control-input rating-filter" id="twoStarRating"
                                    name="radio-stacked" value="2">
                                <label class="custom-control-label custom--control-label" for="twoStarRating">
                                    <span class="rating-wrap d-flex align-items-center">
                                        <span class="review-stars">
                                            <span class="la la-star"></span>
                                            <span class="la la-star"></span>
                                            <span class="la la-star-o"></span>
                                            <span class="la la-star-o"></span>
                                            <span class="la la-star-o"></span>
                                        </span>
                                        <span class="rating-total pl-1"><span class="mr-1 text-black">2.0 &
                                                up</span>({{ $ratingStats['2'] ?? 0 }})</span>
                                    </span>
                                </label>
                            </div>
                            <div class="custom-control custom-radio mb-1 fs-15">
                                <input type="radio" class="custom-control-input rating-filter" id="oneStarRating"
                                    name="radio-stacked" value="1">
                                <label class="custom-control-label custom--control-label" for="oneStarRating">
                                    <span class="rating-wrap d-flex align-items-center">
                                        <span class="review-stars">
                                            <span class="la la-star"></span>
                                            <span class="la la-star-o"></span>
                                            <span class="la la-star-o"></span>
                                            <span class="la la-star-o"></span>
                                            <span class="la la-star-o"></span>
                                        </span>
                                        <span class="rating-total pl-1"><span class="mr-1 text-black">1.0 &
                                                up</span>({{ $ratingStats['1'] ?? 0 }})</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div><!-- end card -->


                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-18 pb-2">Level</h3>
                            <div class="divider"><span></span></div>
                            <div class="custom-control custom-checkbox mb-1 fs-15">
                                <input type="checkbox" class="custom-control-input level-filter" id="levelCheckbox"
                                    value="all">
                                <label class="custom-control-label custom--control-label text-black"
                                    for="levelCheckbox">
                                    All Levels<span class="ml-1 text-gray">({{ $levelStats['all'] ?? 0 }})</span>
                                </label>
                            </div><!-- end custom-control -->
                            <div class="custom-control custom-checkbox mb-1 fs-15">
                                <input type="checkbox" class="custom-control-input level-filter" id="levelCheckbox2"
                                    value="Beginner">
                                <label class="custom-control-label custom--control-label text-black"
                                    for="levelCheckbox2">
                                    Beginner<span class="ml-1 text-gray">({{ $levelStats['beginner'] ?? 0 }})</span>
                                </label>
                            </div><!-- end custom-control -->
                            <div class="custom-control custom-checkbox mb-1 fs-15">
                                <input type="checkbox" class="custom-control-input level-filter" id="levelCheckbox3"
                                    value="Intermediate">
                                <label class="custom-control-label custom--control-label text-black"
                                    for="levelCheckbox3">
                                    Intermediate<span
                                        class="ml-1 text-gray">({{ $levelStats['intermediate'] ?? 0 }})</span>
                                </label>
                            </div><!-- end custom-control -->
                            <div class="custom-control custom-checkbox mb-1 fs-15">
                                <input type="checkbox" class="custom-control-input level-filter" id="levelCheckbox4"
                                    value="Advanced">
                                <label class="custom-control-label custom--control-label text-black"
                                    for="levelCheckbox4">
                                    Expert<span class="ml-1 text-gray">({{ $levelStats['expert'] ?? 0 }})</span>
                                </label>
                            </div><!-- end custom-control -->
                        </div>
                    </div><!-- end card -->



                </div><!-- end sidebar -->
            </div><!-- end col-lg-4 -->
            <div class="col-lg-8">
                <div class="row" id="course-list">
                    @forelse ($courses as $course)
                        <div class="col-lg-4 responsive-column-half course-item"
                            data-rating="{{ $course->average_rating }}" data-level="{{ $course->label }}">
                            <div class="card card-item card-preview">
                                <div class="card-image">
                                    <a href="{{ url('course/details/' . $course->id . '/' . $course->course_name_slug) }}"
                                        class="d-block">
                                        <img class="card-img-top" src="{{ asset($course->course_image) }}"
                                            alt="Card image cap">
                                    </a>
                                    @php
                                        $amount = $course->selling_price - $course->discount_price;
                                        $discount = ($amount / $course->selling_price) * 100;
                                    @endphp

                                    <div class="course-badge-labels">
                                        @if ($course->bestseller == 1)
                                            <div class="course-badge">Bestseller</div>
                                        @endif

                                        @if ($course->highestrated == 1)
                                            <div class="course-badge sky-blue">Highest Rated</div>
                                        @endif
                                        @if ($course->discount_price == null)
                                            <div class="course-badge blue">New</div>
                                        @else
                                            <div class="course-badge blue">-{{ round($discount) }}%</div>
                                        @endif
                                    </div>
                                </div><!-- end card-image -->
                                <div class="card-body">
                                    <h6 class="ribbon ribbon-blue-bg fs-14 mb-3">{{ $course->label }}</h6>
                                    <h5 class="card-title"><a
                                            href="{{ url('course/details/' . $course->id . '/' . $course->course_name_slug) }}">{{ $course->course_name }}</a>
                                    </h5>
                                    <p class="card-text"><a
                                            href="#">{{ $course['user']['name'] ?? 'Instructor' }}</a></p>
                                    <div class="rating-wrap d-flex align-items-center py-2">
                                        <div class="review-stars">
                                            <span class="rating-number">{{ $course->average_rating }}</span>
                                            @php
                                                $fullStars = floor($course->average_rating);
                                                $halfStar = $course->average_rating - $fullStars >= 0.5;
                                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                            @endphp

                                            @for ($i = 0; $i < $fullStars; $i++)
                                                <span class="la la-star"></span>
                                            @endfor

                                            @if ($halfStar)
                                                <span class="la la-star-half-o"></span>
                                            @endif

                                            @for ($i = 0; $i < $emptyStars; $i++)
                                                <span class="la la-star-o"></span>
                                            @endfor
                                        </div>
                                        <span class="rating-total pl-1">({{ $course->review_count }})</span>
                                    </div><!-- end rating-wrap -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        @if ($course->discount_price == null)
                                            <p class="card-price text-black font-weight-bold">
                                                ${{ $course->selling_price }}</p>
                                        @else
                                            <p class="card-price text-black font-weight-bold">
                                                ${{ $course->discount_price }}
                                                <span
                                                    class="before-price font-weight-medium">${{ $course->selling_price }}</span>
                                            </p>
                                        @endif
                                        <div class="icon-element icon-element-sm shadow-sm cursor-pointer"
                                            title="Add to Wishlist">
                                            <i class="la la-heart-o"></i>
                                        </div>
                                    </div>
                                </div><!-- end card-body -->
                            </div><!-- end card -->
                        </div><!-- end col-lg-4 -->
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <h4 class="text-muted">No courses found in this category</h4>
                                <p class="text-muted">Please check back later or browse other categories.</p>
                            </div>
                        </div>
                    @endforelse
                </div><!-- end row -->
                @if (count($courses) > 0)
                    <div class="text-center pt-3">
                        <p class="fs-14 pt-2">Showing <span id="results-count">{{ count($courses) }}</span> of
                            {{ count($courses) }} results</p>
                    </div>
                @endif
            </div><!-- end col-lg-8 -->
        </div><!-- end row -->
    </div><!-- end container -->
</section><!-- end courses-area -->
<!--======================================
        END COURSE AREA
======================================-->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ratingFilters = document.querySelectorAll('.rating-filter');
        const levelFilters = document.querySelectorAll('.level-filter');
        const courseItems = document.querySelectorAll('.course-item');
        const courseCount = document.getElementById('course-count');
        const resultsCount = document.getElementById('results-count');
        const clearFiltersBtn = document.getElementById('clear-filters');

        let selectedRating = null;
        let selectedLevels = [];

        // Rating filter change
        ratingFilters.forEach(filter => {
            filter.addEventListener('change', function() {
                if (this.checked) {
                    selectedRating = parseFloat(this.value);
                } else {
                    selectedRating = null;
                }
                applyFilters();
            });
        });

        // Level filter change
        levelFilters.forEach(filter => {
            filter.addEventListener('change', function() {
                const value = this.value;

                if (value === 'all') {
                    if (this.checked) {
                        // Check all levels
                        levelFilters.forEach(f => {
                            if (f.value !== 'all') {
                                f.checked = true;
                                if (!selectedLevels.includes(f.value)) {
                                    selectedLevels.push(f.value);
                                }
                            }
                        });
                    } else {
                        // Uncheck all levels
                        levelFilters.forEach(f => {
                            f.checked = false;
                        });
                        selectedLevels = [];
                    }
                } else {
                    if (this.checked) {
                        if (!selectedLevels.includes(value)) {
                            selectedLevels.push(value);
                        }
                        // Check if all specific levels are checked
                        const specificLevels = ['Beginner', 'Intermediate', 'Advanced'];
                        const allChecked = specificLevels.every(level => selectedLevels
                            .includes(level));
                        if (allChecked) {
                            document.getElementById('levelCheckbox').checked = true;
                        }
                    } else {
                        selectedLevels = selectedLevels.filter(l => l !== value);
                        document.getElementById('levelCheckbox').checked = false;
                    }
                }
                applyFilters();
            });
        });

        // Clear filters button
        clearFiltersBtn.addEventListener('click', function() {
            // Clear rating filters
            ratingFilters.forEach(filter => {
                filter.checked = false;
            });
            selectedRating = null;

            // Clear level filters
            levelFilters.forEach(filter => {
                filter.checked = false;
            });
            selectedLevels = [];

            applyFilters();
        });

        function applyFilters() {
            let visibleCount = 0;
            let hasActiveFilters = selectedRating !== null || selectedLevels.length > 0;

            courseItems.forEach(item => {
                const courseRating = parseFloat(item.dataset.rating);
                const courseLevel = item.dataset.level;

                let showCourse = true;

                // Apply rating filter (rating and above)
                if (selectedRating !== null) {
                    if (courseRating < selectedRating) {
                        showCourse = false;
                    }
                }

                // Apply level filter
                if (selectedLevels.length > 0) {
                    if (!selectedLevels.includes(courseLevel)) {
                        showCourse = false;
                    }
                }

                // Show/hide course
                if (showCourse) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Update count
            courseCount.textContent = visibleCount;
            if (resultsCount) {
                resultsCount.textContent = visibleCount;
            }

            // Show/hide clear filters button
            if (hasActiveFilters) {
                clearFiltersBtn.style.display = 'inline-block';
            } else {
                clearFiltersBtn.style.display = 'none';
            }

            // Show "no results" message if needed
            updateNoResultsMessage(visibleCount);
        }

        function updateNoResultsMessage(visibleCount) {
            const courseList = document.getElementById('course-list');
            let noResultsDiv = courseList.querySelector('.no-results-message');

            if (visibleCount === 0) {
                if (!noResultsDiv) {
                    noResultsDiv = document.createElement('div');
                    noResultsDiv.className = 'col-12 no-results-message';
                    noResultsDiv.innerHTML = `
                        <div class="text-center py-5">
                            <h4 class="text-muted">No courses found matching your filters</h4>
                            <p class="text-muted">Try adjusting your filter criteria.</p>
                        </div>
                    `;
                    courseList.appendChild(noResultsDiv);
                }
            } else {
                if (noResultsDiv) {
                    noResultsDiv.remove();
                }
            }
        }
    });
</script>

@endsection
