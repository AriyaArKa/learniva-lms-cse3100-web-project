@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Admin</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Edit Admin</h5>
                <form id="myForm" action="{{ route('update.admin', $user->id) }}" method="post" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">Admin User Name</label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                            id="input1" value="{{ old('username', $user->username) }}">
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input2" class="form-label">Admin Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            id="input2" value="{{ old('name', $user->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input3" class="form-label">Admin Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            id="input3" value="{{ old('email', $user->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input4" class="form-label">Admin Phone</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            id="input4" value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input5" class="form-label">Admin Address</label>
                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                            id="input5" value="{{ old('address', $user->address) }}">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input6" class="form-label">Role Name</label>
                        <select name="roles" class="form-select mb-3 @error('roles') is-invalid @enderror"
                            aria-label="Default select example" id="input6">
                            <option selected="" disabled>Open this select menu</option>
                            @foreach ($roles as $role)
                                @php
                                    $isSelected = old('roles')
                                        ? old('roles') == $role->id
                                        : $user->hasRole($role->name);
                                @endphp
                                <option value="{{ $role->id }}" {{ $isSelected ? 'selected' : '' }}>
                                    {{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('roles')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>




    </div>


@endsection
