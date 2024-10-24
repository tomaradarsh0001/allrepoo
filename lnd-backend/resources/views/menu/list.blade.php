@extends('layout.main')

@section('content')
<style>
    .table-responsive {
        overflow-x: auto; 
    }
    .table th, .table td {
        white-space: normal; 
        word-wrap: break-word; 
        padding: 15px; 
        font-size: 14px; 
    }
    .btn-icon {
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }
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
                        <li class="breadcrumb-item active" aria-current="page">Menu List</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-menu"></i>
                </span>
                Manage Menu
            </h3>
            <a href="{{ route('createMenu') }}" class="btn btn-gradient-primary">Add Menu</a>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Menu List</h4>
                    </div>
                    <div class="card-body">

                        @include('include.statusAlert')

                        @if(isset($parentMenus) && $parentMenus->count() > 0)
                        <table class="table" id="myDataTable">
                            <thead>
                                <tr>
                                    <th style="width: 2%;">S. No.</th>
                                    <th style="width: 15%;">Label</th>
                                    <!-- <th style="width: 15%;">Label Hindi</th> -->
                                    <th style="width: 10%;">Location</th>
                                    <!-- <th style="width: 5%;">Sort Order</th> -->
                                    <th style="width: 15%;">Link</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="menu-list">
                                @foreach($parentMenus as $pm)
                                <tr class="parent">
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$pm->label_eng}}</td>
                                    <!-- <td>{{$pm->label_hin}}</td> -->
                                    <td>{{$pm->location}}</td>
                                    <td>{{$pm->link}}</td>
                                    <td>
                                        <label class="switch" style="position: relative; display: inline-block; width: 40px; height: 23px;">
                                            <input type="checkbox" class="status-toggle" {{ $pm->is_active == 1 ? 'checked' : '' }} data-id="{{$pm->id}}" style="opacity: 0; width: 0; height: 0;">
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <!-- <td>{{$pm->sort_order}}</td> -->
                                    <td>
                                        <a href="{{ route('viewMenu', ['id' => $pm->id]) }}" class="btn btn-icon btn-gradient-info btn-rounded mr-2" title="View"><i class="mdi mdi-eye"></i></a>
                                        <a href="{{route('editMenu', ['id' => $pm->id])}}" class="btn btn-icon btn-gradient-primary btn-rounded mr-2" title="edit"><i class="mdi mdi-grease-pencil"></i></a>
                                        <a href="{{route('deleteMenu', ['id' => $pm->id])}}" class="btn btn-icon btn-gradient-danger btn-rounded" title="edit" onclick="return confirm('This action will permanently delete this menu. Are you sure to continue?')"><i class="mdi mdi-delete"></i></a>
                                    </td>
                                </tr>
                                @if($pm->submenus && $pm->submenus->count() > 0)
                                @foreach($pm->submenus as $sm)
                                <tr class="child">
                                    <!-- <td>{{$loop->iteration}}</td> -->
                                    <td>{{$loop->parent->iteration}}.{{$loop->iteration}}</td>
                                    <td>{{$sm->label_eng}}</td>
                                    <!-- <td>{{$sm->label_hin}}</td> -->
                                    <td>{{$sm->location}}</td>
                                    <td>{{$sm->link}}</td>
                                    <td>
                                        <label class="switch" style="position: relative; display: inline-block; width: 40px; height: 23px;">
                                            <input type="checkbox" class="status-toggle" {{ $sm->is_active == 1 ? 'checked' : '' }} data-id="{{$sm->id}}" style="opacity: 0; width: 0; height: 0;">
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <!-- <td>{{$sm->sort_order}}</td> -->
                                    <td>
                                        <a href="{{ route('viewMenu', ['id' => $sm->id]) }}" class="btn btn-icon btn-gradient-info btn-rounded mr-2" title="View"><i class="mdi mdi-eye"></i></a>
                                        <a href="{{ route('editMenu', ['id' => $sm->id])}}" class="btn btn-icon btn-gradient-primary btn-rounded mr-2" title="edit"><i class="mdi mdi-grease-pencil"></i></a>
                                        <a href="{{ route('deleteMenu', ['id' => $sm->id])}}" class="btn btn-icon btn-gradient-danger btn-rounded" title="delete" onclick="return confirm('This action will permanently delete this menu. Are you sure to continue?')"><i class="mdi mdi-delete"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <h3>Here we display the menus. Currently there is no data to display!!!</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
        $('.status-toggle').change(function() {
            const menuId = $(this).data('id');
            const isActive = $(this).is(':checked') ? 1 : 0; 
            $.ajax({
                type: 'POST',
                url: '/updateMenuStatus/' + menuId,
                data: {
                _token: '{{ csrf_token() }}',
                is_active: isActive
                },
                success: (response) => {
                console.log('Status updated', response);
                },
                error: (error) => {
                console.error('Error updating status', error);
                $(this).prop('checked', !isActive);
                }
            });
        });
</script>
@endsection
