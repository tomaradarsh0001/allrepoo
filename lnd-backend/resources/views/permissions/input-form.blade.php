@extends('layout.main')

@section('content')

<?php
if ($type ==  'role'){
    $action = route('storeRole');
    $listUrl = route('listRole');
}
if ($type ==  'permission'){
    $action = route('storePermission');
    $listUrl = route('listPermission');
}
    
?>
<div class="main-panel">
    <div class="content-wrapper">
        <!-- SwatiMishra 30-04-2024, 2:40 PM BreadCrumb Start, replace <div class="page header">....</div> -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div style="position: absolute; right: 0;">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="margin-bottom: 20px;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route($type == 'role'? 'listRole':'listPermission') }}">Manage {{$type == 'role'? 'Roles':'Permissions'}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add {{$type == 'role'? 'Roles':'Permissions'}}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-block-helper"></i>
                </span>
                Manage {{$type == 'role'? 'Roles':'Permissions'}}
            </h3>
            <nav aria-label="breadcrumb">
                <a href="{{$listUrl}}" class="btn btn-gradient-primary">View All {{$type == 'role'? 'Roles':'Permissions'}}</a>
            </nav>
        </div>
        <!-- SwatiMishra 30-04-2024, 2:40 PM BreadCrumb End -->

        <div class="row justify-content-md-center">
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                    <h4 class="card-title">{{$type == 'role' ? 'Role' : 'Permission'}} Input Form</h4>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample" method="post" action="{{$action}}">
                            @csrf
                                @include('include.statusAlert')
                            <div class="form-group">
                                <label for="name">{{$type == 'role' ? 'Role' : 'Permission'}} Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required>
                                @error('name') <span class="error">{{$message}}</span>@enderror
                            </div>


                            <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection