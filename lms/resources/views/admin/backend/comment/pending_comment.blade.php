@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Pending Blog Comments</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Pending Comments</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('approved.comment') }}" class="btn btn-success">Approved Comments</a>
                </div>
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
                                <th>Blog Post</th>
                                <th>Commenter Name</th>
                                <th>Email</th>
                                <th>Comment</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comments as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <a href="{{ url('blog/details/' . $item->blogPost->post_slug) }}" target="_blank"
                                            class="text-primary">
                                            {{ Str::limit($item->blogPost->post_title, 40) }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($item->user)
                                            <div class="d-flex align-items-center">
                                                <img src="{{ !empty($item->user->photo) ? url('upload/user_images/' . $item->user->photo) : url('upload/no_image.jpg') }}"
                                                    class="rounded-circle" width="40" height="40" alt="">
                                                <div class="ms-2">
                                                    <strong>{{ $item->name }}</strong>
                                                    <br><small class="text-muted">Registered User</small>
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center">
                                                <img src="{{ url('upload/no_image.jpg') }}" class="rounded-circle"
                                                    width="40" height="40" alt="">
                                                <div class="ms-2">
                                                    <strong>{{ $item->name }}</strong>
                                                    <br><small class="text-muted">Guest</small>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        <div style="max-width: 300px;">
                                            {{ Str::limit($item->message, 100) }}
                                        </div>
                                    </td>
                                    <td>{{ $item->created_at->format('M d, Y') }}<br><small>{{ $item->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('approve.comment', $item->id) }}" class="btn btn-success btn-sm"
                                            title="Approve Comment">
                                            <i class="bx bx-check"></i> Approve
                                        </a>
                                        <a href="{{ route('delete.comment', $item->id) }}" class="btn btn-danger btn-sm"
                                            id="delete" title="Delete Comment">
                                            <i class="bx bx-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sl</th>
                                <th>Blog Post</th>
                                <th>Commenter Name</th>
                                <th>Email</th>
                                <th>Comment</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
