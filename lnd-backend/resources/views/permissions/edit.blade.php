@extends('layout.main')

@section('content')
<style>
    .checkbox-indent {
    padding-left: 35px; 
}
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <!-- Breadcrumb and Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div style="position: absolute; right: 0;">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="margin-bottom: 20px;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('userList') }}">Manage Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Update User Roles</li>
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
            <a href="{{route('userList')}}" class="btn btn-gradient-primary">View all users</a>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Update User Roles</h4>
                    </div>
                    <div class="card-body">
                        @include('include.statusAlert')

                        <!-- User Selection -->
                        <div class="form-group">
                            <label for="userSelect"><b>Select User:</b></label>
                            <select id="userSelect" class="form-control" name="userId" onchange="fetchUserRolesAndPermissions(this.value);">
                                <option value="">Select a User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label><b>Roles:</b></label>
                            <div class="row" id="rolesCheckboxList">
                                @foreach($roles as $role)
                                    <div class="col-sm-3 form-check checkbox-indent"> <!-- Adjust the column size as needed -->
                                        <input class="form-check-input role-checkbox" type="checkbox" name="roles[]" value="{{ $role->id }}"
                                            id="role{{ $role->id }}" onchange="updateUserRoleOrPermission({{ $role->id }}, this.checked, 'role')">
                                        <label class="form-check-label" for="role{{ $role->id }}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- <div style="margin-top: 30px; margin-bottom: 30px;">&nbsp;</div> -->
                        <div class="form-group">
                            <label><b>Permissions:</b></label>
                            <div class="row" id="permissionsCheckboxList">
                                @foreach($permissions as $permission)
                                    <div class="col-sm-3 form-check checkbox-indent"> <!-- Adjust the column size as needed -->
                                        <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                            id="permission{{ $permission->id }}" onchange="updateUserRoleOrPermission({{ $permission->id }}, this.checked, 'permission')">
                                        <label class="form-check-label" for="permission{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function fetchUserRolesAndPermissions(userId) {
        var url = '{{ route("getUserRolesAndPermissions", ["userId" => ":userId"]) }}'.replace(':userId', userId);
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                // Update role checkboxes
                $('.role-checkbox').each(function() {
                    var roleId = parseInt($(this).val());
                    $(this).prop('checked', data.roles.includes(roleId));
                });
                // Update permission checkboxes
                $('.permission-checkbox').each(function() {
                    var permissionId = parseInt($(this).val());
                    $(this).prop('checked', data.permissions.includes(permissionId));
                });
            },
            error: function(err) {
                console.error('Error fetching roles and permissions:', err);
            }
        });
    }

    function updateUserRoleOrPermission(entityId, isChecked, type) {
        var userId = $('#userSelect').val();
        if (!userId) {
            alert('Please select a user.');
            return;
        }
        $.ajax({
            url: '{{ route("assignRoleOrPermissionToUser") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                userId: userId,
                entityId: entityId,
                isChecked: isChecked ? 'true' : 'false',
                type: type
            },
            success: function(response) {
                console.log(ucfirst(type) + ' update successful:', response);
                if (response.status === 'success') {
                    alert(ucfirst(type) + ' updated successfully');
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    }

</script>

@endsection


