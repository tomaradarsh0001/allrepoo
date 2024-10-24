@extends('layout.main')

@section('content')

<?php
if ($type == 'role') {
    $addNewRoute = route('createRole');
}
if ($type == 'permission') {
    $addNewRoute = route('createPermission');
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
                        <li class="breadcrumb-item active" aria-current="page">Manage {{$type == 'role'? 'Roles':'Permissions'}}</li>
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
            <a href="{{$addNewRoute}}" class="btn btn-gradient-primary">Add {{$type == 'role'? 'role':'permission'}}</a>
        </div>
        <!-- SwatiMishra 30-04-2024, 2:40 PM BreadCrumb End -->
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{($type == 'role'? 'Role':'Permission')}} Table</h4>
                    </div>
                    <div class="card-body">
                        @include('include.statusAlert')
                        <table class="table table-striped">
                            @if($rows->count() > 0)
                            <thead>
                                <tr>
                                    <th>S. No.</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $row)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$row}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            @else

                            <thead>
                                <tr>
                                    <th>No Data To display</th>
                                </tr>
                            </thead>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection