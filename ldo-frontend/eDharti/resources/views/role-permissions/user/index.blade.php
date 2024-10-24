@extends('layouts.app')

@section('title', 'Users List')

@section('content')
<!--Breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Create User</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item">Settings</li>
                <li class="breadcrumb-item">User</li>
                <li class="breadcrumb-item active" aria-current="page">Create User</li>
            </ol>
        </nav>
    </div>
</div>
<!-- End -->
    <div>
            <div class="col pt-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex pb-5 justify-content-between">
                            <a href="{{ url('users/create') }}"><button class="btn btn-primary">+ Add User</button></a>
                            <div class="d-flex gap-3">
                                @haspermission('view role')
                                    <a href="{{ url('roles') }}"><button class="btn btn-info">Roles</button></a>
                                @endhaspermission
                                @haspermission('view permission')
                                    <a href="{{ url('permissions') }}"><button class="btn btn-warning">Permissions</button></a>
                                @endhaspermission
                            </div>
                        </div>
                        <h6 class="mb-0 text-uppercase tabular-record_font pb-4">Users</h6>
                        <table class="table mb-0">
                                    <thead>
										<tr>
											<th scope="col">#</th>
											<th scope="col">Name</th>
											<th scope="col">Email</th>
                                            {{-- <th scope="col">Permissions</th> --}}
                                            <th scope="col">Status</th>
											<th scope="col">Roles</th>
											<th scope="col">Action</th>
										</tr>
									</thead>
									<tbody>
                                        @foreach($users as $key => $user)
										<tr class="">
											<th scope="row">{{$key+1}}</th>
											<td>{{$user->name}}</td>
											<td>{{$user->email}}</td>
                                            {{-- <td>
                                                @if (!empty($user->getDirectPermissions()))
                                                    @foreach ($user->getDirectPermissions() as $permission)
                                                        <span class="badge text-sm bg-info text-dark">{{ $permission->name }}</span>
                                                    @endforeach
                                                    
                                                @endif
                                            </td> --}}
                                            <td>
                                                @if(auth()->user()->can('status.user'))
                                                    @if($user->status == 1)
                                                        <a href="{{route('user.status',$user->id)}}"><div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>Active</div></a>
                                                        @else
                                                        <a href="{{route('user.status',$user->id)}}"><div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>In-Active</div></a>
                                                    @endif
                                                @else
                                                    @if($user->status == 1)
                                                        <div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>Active</div>
                                                    @else
                                                        <div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>In-Active</div>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                            @if (!empty($user->getRoleNames()))
                                            @foreach ($user->getRoleNames() as $rolename)
                                                <label class="badge bg-primary mx-1">{{ $rolename }}</label>
                                            @endforeach
                                        @endif
                                            </td>
											<td>
                                                <div class="d-flex gap-3">
                                                @haspermission('update user')
                                                    <a href="{{ url('users/'.$user->id.'/edit') }}"><button type="button" class="btn btn-primary px-5">Edit</button></a>
                                                @endhaspermission
                                                @haspermission('delete user')
                                                    <a href="{{ url('users/'.$user->id.'/delete') }}"> <button type="button" class="btn btn-danger px-5">Delete</button></a>
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