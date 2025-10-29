@extends('admin.admin_dashboard')
@section('admin')
    <style>
        .large-checkbox {
            transform: scale(1.5);
        }
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>


    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">All Instructor</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Instructor Name</th>
                                <th>UserName</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allinstructor as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->username }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->phone }}</td>

                                    <td>
                                        @if ($item->status == 1)
                                            <span class="btn btn-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>

                                    <td>
                                        <!-- Toggle Switch -->
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status-toggle large-checkbox" type="checkbox"
                                                id="switch{{ $item->id }}" data-user-id="{{ $item->id }}"
                                                {{ $item->status ? 'checked' : '' }}>
                                            <label class="form-check-label" for="switch{{ $item->id }}">
                                                {{ $item->status ? 'Active' : 'Inactive' }}
                                            </label>
                                        </div>

                                        <!-- Alternative: Click Button -->
                                        <button
                                            class="btn btn-sm status-btn {{ $item->status ? 'btn-success' : 'btn-danger' }} mt-2 status-button"
                                            data-user-id="{{ $item->id }}" data-status="{{ $item->status }}">
                                            <i class='bx {{ $item->status ? 'bx-check-circle' : 'bx-x-circle' }}'></i>
                                            {{ $item->status ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <script>
        $(document).ready(function() {
            // Toggle Switch Handler
            $('.status-toggle').on('change', function() {
                var userId = $(this).data('user-id');
                var isChecked = $(this).is(':checked');

                updateUserStatus(userId, isChecked ? 1 : 0);
            });

            // Button Click Handler
            $('.status-button').on('click', function() {
                var userId = $(this).data('user-id');
                var currentStatus = $(this).data('status');
                var newStatus = currentStatus == 1 ? 0 : 1;

                updateUserStatus(userId, newStatus);
            });

            // Common function to update status
            function updateUserStatus(userId, status) {
                $.ajax({
                    url: "{{ route('update.user.status') }}",
                    method: "POST",
                    data: {
                        user_id: userId,
                        is_checked: status,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        // Reload page to update UI
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        toastr.error('Failed to update status');
                        console.log(xhr);
                    }
                });
            }
        });
    </script>
@endsection
