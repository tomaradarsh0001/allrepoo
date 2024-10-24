@extends('layout.main')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div style="position: absolute; right: 0;">
                <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="margin-bottom: 20px;">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('userList') }}">Manage Users</a></li>
                    @if(isset($user) && $user->id)
                        @if(isset($mode) && $mode == 'view')
                            <li class="breadcrumb-item active" aria-current="page">View User</li>
                        @else
                            <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                        @endif
                    @else
                        <li class="breadcrumb-item active" aria-current="page">Add User</li>
                    @endif
                </ol>
                </nav>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-menu"></i>
                </span>
                @if(isset($mode) && $mode == 'view')
                    View User
                @else
                    Manage Users
                @endif
            </h3>
            <a href="{{ route('userList') }}" class="btn btn-gradient-primary">User List</a>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    @if(isset($user) && $user->id)
                        @if(isset($mode) && $mode == 'view')
                            <form class="forms-sample">
                        @else
                            <form class="forms-sample" method="POST" action="{{ route('updateUser', $user->id) }}">
                            @method('PUT')
                        @endif
                    @else
                        <form class="forms-sample" method="POST" action="{{ route('storeUser') }}">
                    @endif
                        @csrf
                        <div class="card-header">
                            <h4 class="card-title">User Form</h4>
                            <p class="card-description">
                                @if(isset($mode) && $mode == 'view')
                                    View User
                                @else
                                    Add or edit user
                                @endif
                            </p>
                        </div>
                        <div class="card-body">
                            @include('include.statusAlert')
                            <div class="mb-3">
                                <label for="name"><b>Name</b></label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter User Full Name" value="{{ old('name', $user->name ?? '') }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                            </div>
                            <div class="mb-3">
                                <label for="email"><b>Email</b></label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ old('email', $user->email ?? '') }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                            </div>
                            <div class="mb-3">
                                <label for="password"><b>Password</b></label>
                                <input type="text" class="form-control" id="password" name="password" placeholder="Enter password" value="{{ old('password', $user->password ?? '') }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                            </div>
                        </div>
                        <div class="card-footer">
                        @if(Route::currentRouteName() != 'viewUser')
                        <div class="card-footer">
                            <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                            <button type="button" class="btn btn-danger" onclick="window.location.href='{{ route('userList') }}';">Cancel</button>
                        </div>
                        @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
