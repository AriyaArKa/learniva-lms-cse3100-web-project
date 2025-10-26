@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<style>
    .form-check-label{
        text-transform: capitalize;
    }
</style>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add Role In Permission</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">

               <form id="myForm" action="{{ route('role.permission.store') }}" method="post" class="row g-3" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label"> Roles Name</label>
                                    <select name="role_id" class="form-select mb-3" aria-label="Default select example">


                            <option selected="" disabled>Open Roles</option>
                            @foreach ($roles as $role)
                                              <option value="{{ $role->id }}">{{ $role->name }}</option>


                            @endforeach

                        </select>
                    </div>

                    @php
                        $groups = App\Models\User::getpermissionGroups();
                    @endphp

                    @foreach ($groups as $group)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault{{ $loop->index }}">
                            <label class="form-check-label" for="flexCheckDefault{{ $loop->index }}"> {{ $group->group_name }}</label>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-3">

                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" value="" id="flexCheckMain{{ $loop->index }}">
                        <label class="form-check-label" for="flexCheckMain{{ $loop->index }}">Permission All </label>
                                </div>

                            </div>

                            <div class="col-9">
                                @php
                                    $permissions = App\Models\User::getpermissionByGroupName($group->group_name);
                                @endphp

                                @foreach ($permissions as $permission)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permission[]"
                                            value="{{ $permission->id }}" id="checkDefault{{ $permission->id }}">
                                        <label class="form-check-label"
                                            for="checkDefault{{ $permission->id }}">{{ $permission->name }}</label>
                                    </div>
                                @endforeach
                                <br>
                            </div>

                        </div>

                        {{-- // end row --}}

                    @endforeach

                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


<script>
    // Handle "Permission All" checkboxes for each group
    $('[id^="flexCheckMain"]').click(function(){
        var groupIndex = $(this).attr('id').replace('flexCheckMain', '');
        var groupContainer = $(this).closest('.row');
        
        if ($(this).is(':checked')) {
            groupContainer.find('input[type=checkbox]').prop('checked', true);
        } else {
            groupContainer.find('input[type=checkbox]').prop('checked', false);
        }
    });
</script>
@endsection
