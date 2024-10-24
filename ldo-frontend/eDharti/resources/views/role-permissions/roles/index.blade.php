@extends('layouts.app')

@section('title', 'Roles List')

@section('content') 
    
    <div>
            <div class="col pt-3">
                <div class="card">
                    <div class="card-body">
                    <div class="d-flex justify-content-end">
                        @haspermission('create role')
                            <a href="{{ url('roles/create') }}"><button class="btn btn-primary">+ Add Role</button></a>
                        @endhaspermission
                    </div>
                        <h6 class="mb-0 text-uppercase tabular-record_font pb-4">Roles</h6>
                        <table class="table mb-0">
                                    <thead>
										<tr>
											<th scope="col">#</th>
											<th scope="col">Name</th>
											<th scope="col">Action</th>
										</tr>
									</thead>
									<tbody>
                                        @foreach($roles as $key => $role)
										<tr class="">
											<th scope="row">{{$key+1}}</th>
											<td>{{$role->name}}</td>
											<td>
                                                <div class="d-flex gap-3">
                                                    @haspermission('update role')
                                                        <a href="{{ url('roles/'.$role->id.'/edit') }}"><button type="button" class="btn btn-primary px-5">Edit</button></a>
                                                    @endhaspermission
                                                    @haspermission('delete role')
                                                        <a href="{{ url('roles/'.$role->id.'/delete') }}"> <button type="button" class="btn btn-danger px-5">Delete</button></a>
                                                    @endhaspermission
                                                    @haspermission('create role')
                                                        <a href="{{ url('roles/'.$role->id.'/give-permissions') }}"> <button type="button" class="btn btn-warning px-5">Add / Edit Role Permission</button></a>
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