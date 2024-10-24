@extends('layouts.app')

@section('title', 'Edit Permission')

@section('content') 
        <!--Breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Edit Permission</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item">Settings</li>
                <li class="breadcrumb-item">Permissions</li>
                <li class="breadcrumb-item active" aria-current="page">Edit Permissions</li>
            </ol>
        </nav>
    </div>
</div>
<!-- End -->
    <div id="sectionToPrint">
            <div class="col pt-3">
                <div class="card">
                    <div class="card-body">
                            <form action="{{ url('permissions/'.$permission->id) }}" method="POST" >
                            @csrf
                            @method('PUT')
                                <div class="row align-items-end">
                                        <div class="col-12 col-lg-4">
                                            <label for="permission_name" class="form-label">Permission Name</label>
                                            <input type="text" name="name" class="form-control" id="PropertyID" value="{{ $permission->name }}" placeholder="Permission Name" required>
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                            </form>

                        
                                    
                    </div>
                </div>
            </div>
        
            
    </div>

@endsection