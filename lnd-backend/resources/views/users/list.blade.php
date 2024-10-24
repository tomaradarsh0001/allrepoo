@extends('layout.main')

@section('content')

<style>
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: .4s;
  border-radius: 20px;
}

.btn-icon {
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: .4s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #28a745;
}

input:checked + .slider:before {
  transform: translateX(16px);
}
</style>

<div class="main-panel">
    <div class="content-wrapper">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div style="position: absolute; right: 0;">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="margin-bottom: 20px;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Manage Users</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-menu"></i>
                </span>
                Manage Users
            </h3>
            <a href="{{route('createUsers')}}" class="btn btn-gradient-primary">Add new user</a>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">User Management System</h4>
                    </div>
                    <div class="card-body">
                        @include('include.statusAlert')                     

                        <table class="table table-striped" id="myDataTable">
                            <thead>
                                <tr>
                                    <th>S. No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Assigned Role</th>
                                    <!-- <th>Actions</th> -->
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="user-list">
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>
                                            @forelse($user->roles as $role)
                                            <span style="background-color: #ad6fd3; color: white; padding: 5px 5px; border-radius: 16px; font-size: 14px; white-space: nowrap; width: 90px;">{{ $role->name }}</span>
                                            @empty
                                                <span style="background-color: #b2bec3; color: white; padding: 5px 10px; border-radius: 16px; font-size: 14px;">No role assigned</span>
                                            @endforelse
                                        </td>
                                        <!-- <td>
                                            <a href="{{ route('viewUser', $user->id) }}" class="btn btn-icon btn-gradient-info btn-rounded mr-2" title="view"><i class="mdi mdi-eye"></i></a>
                                            <a href="{{ route('updateUserRoleOrPermission') }}" class="btn btn-icon btn-gradient-primary btn-rounded mr-2" title="edit role or permission"><i class="mdi mdi-grease-pencil"></i></a>
                                        </td> -->
                                        <td>
                                            <label class="switch" style="position: relative; display: inline-block; width: 40px; height: 23px;">
                                                <input type="checkbox" class="status-toggle" {{ $user->status==1 ? 'checked' : '' }} data-id="{{$user->id}}" style="opacity: 0; width: 0; height: 0;">
                                                <span class="slider"></span>
                                            </label>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No users found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('.status-toggle').change(function() {
        const userId = $(this).data('id');
        const status = $(this).is(':checked') ? 1 : 0; // 1 for active, 0 for inactive

        $.ajax({
            type: 'post',
            url: '{{ route("updateUserStatus") }}',
            data: {
                _token: '{{ csrf_token() }}',
                userId: userId,
                status: status
            },
            success: (response) => {
                console.log('Status updated', response);
            },
            error: (error) => {
                console.error('Error updating status', error);
                $(this).prop('checked', !status);
            }
        });
    });

</script>
@endsection
