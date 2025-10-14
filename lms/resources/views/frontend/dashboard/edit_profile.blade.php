@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')


    <div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between mb-5">
        <div class="media media-card align-items-center">
            <div class="media-img media--img media-img-md rounded-full">
                <img class="rounded-full"
                    src="{{ !empty($profileData->photo) ? url('upload/user_images/' . $profileData->photo) : url('upload/no_image.jpg') }}"
                    alt="Student thumbnail image">
            </div>
            <div class="media-body">
                <h2 class="section__title fs-30">Hello, {{ $profileData->name }}</h2>

            </div><!-- end media-body -->
        </div><!-- end media -->
    </div>

    <div class="tab-pane fade show active" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
        <div class="setting-body">
            <h3 class="fs-17 font-weight-semi-bold pb-4">Edit Profile</h3>
        </div>

        <form method="post" action="{{ route('user.profile.update') }}" enctype="multipart/form-data" class="row pt-40px">
            @csrf

            @if ($errors->any())
                <div class="col-12">
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="media media-card align-items-center">
                <div class="media-img media-img-lg mr-4 bg-gray"
                    style="position: relative; overflow: hidden; border-radius: 8px;">
                    <img id="imagePreview" class="mr-3"
                        style="width: 100%; height: 100%; object-fit: cover; transition: opacity 0.3s ease;"
                        src="{{ !empty($profileData->photo) ? url('upload/user_images/' . $profileData->photo) : url('upload/no_image.jpg') }}"
                        alt="avatar image">
                    <div id="imageOverlay"
                        style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); color: white; display: none; align-items: center; justify-content: center; font-size: 14px;">
                        <i class="la la-camera"></i> Preview
                    </div>
                </div>
                <div class="media-body">
                    <div class="file-upload-wrap file-upload-wrap-2">
                        <input type="file" name="photo" class="file-upload-input" accept="image/*" id="photoUpload">
                        <span class="file-upload-text"><i class="la la-photo mr-2"></i>Upload a Photo</span>
                    </div><!-- file-upload-wrap -->
                    <p class="fs-14">Max file size is 5MB, Minimum dimension: 200x200 And Suitable files are .jpg & .png
                    </p>
                </div>
            </div><!-- end media -->

            {{-- name --}}
            <div class="input-box col-lg-6">
                <label class="label-text">Name</label>
                <div class="form-group">
                    <input class="form-control form--control" type="text" name="name"
                        value="{{ $profileData->name }}">
                    <span class="la la-user input-icon"></span>
                </div>
            </div><!-- end input-box -->

            {{-- username --}}
            <div class="input-box col-lg-6">
                <label class="label-text">User Name</label>
                <div class="form-group">
                    <input class="form-control form--control" type="text" name="username"
                        value="{{ $profileData->username }}">
                    <span class="la la-user input-icon"></span>
                </div>
            </div><!-- end input-box -->

            {{-- email --}}
            <div class="input-box col-lg-6">
                <label class="label-text">Email Address</label>
                <div class="form-group">
                    <input class="form-control form--control" type="email" name="email"
                        value="{{ $profileData->email }}">
                    <span class="la la-envelope input-icon"></span>
                </div>
            </div><!-- end input-box -->

            {{-- phone --}}
            <div class="input-box col-lg-6">
                <label class="label-text">Phone Number</label>
                <div class="form-group">
                    <input class="form-control form--control" type="text" name="phone"
                        value="{{ $profileData->phone }}">
                    <span class="la la-phone input-icon"></span>
                </div>
            </div><!-- end input-box -->

            {{-- address --}}
            <div class="input-box col-lg-12">
                <label class="label-text">Address</label>
                <div class="form-group">
                    <input class="form-control form--control" type="text" name="address"
                        value="{{ $profileData->address }}">
                    <span class="la la-map-marker input-icon"></span>
                </div>
            </div><!-- end input-box -->




            <div class="input-box col-lg-12 py-2">
                <button class="btn theme-btn">Save Changes</button>
            </div><!-- end input-box -->
        </form>
    </div><!-- end tab-pane -->

    <script>
        document.getElementById('photoUpload').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                var file = e.target.files[0];

                // Validate file type
                if (!file.type.match('image.*')) {
                    alert('Please select a valid image file.');
                    e.target.value = '';
                    return;
                }

                // Validate file size (5MB = 5 * 1024 * 1024 bytes)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB.');
                    e.target.value = '';
                    return;
                }

                var reader = new FileReader();
                reader.onload = function(e) {
                    // Update the preview image with animation
                    var img = document.getElementById('imagePreview');
                    if (img) {
                        img.style.opacity = '0.5';
                        setTimeout(function() {
                            img.src = e.target.result;
                            img.style.opacity = '1';
                        }, 100);
                    }
                }
                reader.readAsDataURL(file);

                // Update the upload text with file name and size
                var uploadText = document.querySelector('.file-upload-text');
                if (uploadText) {
                    var fileSize = (file.size / 1024).toFixed(1) + ' KB';
                    if (file.size > 1024 * 1024) {
                        fileSize = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
                    }
                    uploadText.innerHTML = '<i class="la la-check mr-2 text-success"></i>' + file.name + ' (' +
                        fileSize + ')';
                    uploadText.style.color = '#28a745';
                }
            } else {
                // Reset to original state if no file selected
                var uploadText = document.querySelector('.file-upload-text');
                if (uploadText) {
                    uploadText.innerHTML = '<i class="la la-photo mr-2"></i>Upload a Photo';
                    uploadText.style.color = '';
                }
            }
        });

        // Add drag and drop functionality
        var fileUploadWrap = document.querySelector('.file-upload-wrap');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUploadWrap.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileUploadWrap.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileUploadWrap.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            fileUploadWrap.style.backgroundColor = '#f8f9fa';
            fileUploadWrap.style.border = '2px dashed #007bff';
        }

        function unhighlight(e) {
            fileUploadWrap.style.backgroundColor = '';
            fileUploadWrap.style.border = '';
        }

        fileUploadWrap.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            var dt = e.dataTransfer;
            var files = dt.files;

            if (files.length > 0) {
                document.getElementById('photoUpload').files = files;
                // Trigger the change event
                var event = new Event('change', {
                    bubbles: true
                });
                document.getElementById('photoUpload').dispatchEvent(event);
            }
        }
    </script>
















@endsection
