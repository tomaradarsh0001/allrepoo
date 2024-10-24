@extends('layouts.app')

@section('title', 'Permissions List')

@section('content') 
<!--Breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Permissions</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item">Settings</li>
                <li class="breadcrumb-item active" aria-current="page">Permissions</li>
            </ol>
        </nav>
    </div>
</div>
<!-- End -->
    <div>
            <div class="col pt-3">
                <div class="card">
                    <div class="card-body">
                    <div class="d-flex justify-content-end">
                    @haspermission('create permission')
                        <a href="{{ url('permissions/create') }}"><button class="btn btn-primary">+ Add Permission</button></a>
                    @endhaspermission
                    </div>
                        <table class="table mb-0">
                                    <thead>
										<tr>
											<th scope="col">#</th>
											<th scope="col">Name</th>
											<th scope="col">Action</th>
										</tr>
									</thead>
									<tbody>
                                        @foreach($permissions as $key => $permission)
										<tr class="">
											<th scope="row">{{$key+1}}</th>
											<td>{{$permission->name}}</td>
											<td>
                                                <div class="d-flex gap-3">
                                                @haspermission('update permission')
                                                    <a href="{{ url('permissions/'.$permission->id.'/edit') }}"><button type="button" class="btn btn-primary px-5">Edit</button></a>
                                                @endhaspermission
                                                
                                                @haspermission('delete permission')
                                                    <a href="{{ url('permissions/'.$permission->id.'/delete') }}"> <button type="button" class="btn btn-danger px-5">Delete</button></a>
                                                @endhaspermission
                                                </div>
                                            </td>
										</tr>
										@endforeach
									</tbody>
								</table>
                    </div>
                </div>
            </div>
        
           
</div>
@endsection