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

    <!-- Breadcrumb start-->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div style="position: absolute; right: 0;">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="margin-bottom: 20px;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Who's Who List</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-account-card-details"></i>
                </span>
                Manage Directory
            </h3>
            <a href="{{route('createDirectory')}}" class="btn btn-gradient-primary">Add Who's Who</a>
        </div>
    <!-- Breadcrumb end-->

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Who's Who List</h4>
                    </div>
                    <div class="card-body">

                        @include('include.statusAlert')

                        <div class="table-responsive">
                            <table class="table" id="myDataTable">
                                <thead>
                                    <tr>
                                        <th style="width: 2%;">S. No.</th>
                                        <th style="width: 15%;">Name</th>
                                        <th style="width: 5%;">Designation</th>
                                        <th style="width: 5%;">Room No.</th>
                                        <th style="width: 10%;">Telephone</th>
                                        <th style="width: 5%;">Email</th>
                                        <th style="width: 5%;">Active</th>
                                        <!-- <th style="width: 5%;">Sort Order</th> -->
                                        <th style="width: 15%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="directory-list">
                                    @forelse ($directories as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{ $item->name_eng }}</td>
                                            <td>{{ $item->designation_eng }}</td>
                                            <td>{{ $item->room_no }}</td>
                                            <td>{{ $item->telephone }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>
                                                <label class="switch" style="position: relative; display: inline-block; width: 40px; height: 23px;">
                                                    <input type="checkbox" class="status-toggle" {{ $item->is_active==1 ? 'checked' : '' }} data-id="{{$item->id}}" style="opacity: 0; width: 0; height: 0;">
                                                    <span class="slider"></span>
                                                </label>
                                            </td>
                                            <!-- <td>{{ $item->sort_order }}</td> -->
                                            <td>
                                                <a href="{{ route('viewDirectory', $item->id) }}" class="btn btn-icon btn-gradient-info btn-rounded mr-2" title="view">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                <a href="{{ route('editDirectory', $item->id) }}" class="btn btn-icon btn-gradient-primary btn-rounded mr-2" title="edit">
                                                    <i class="mdi mdi-grease-pencil"></i>
                                                </a>
                                                <a href="{{ route('deleteDirectory', $item->id) }}" class="btn btn-icon btn-gradient-danger btn-rounded" title="delete" onclick="return confirm('This action will permanently delete this news. Are you sure to continue?')">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            </td>
                                            
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No Contacts found</td>
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
</div>


<script>
$(document).ready(function() {
        $('.status-toggle').change(function() {
            const directoryId = $(this).data('id');
            const isActive = $(this).is(':checked') ? 1 : 0; // 1 for active, 0 for inactive

            $.ajax({
                type: 'POST',
                url: '/updateDirStatus/' + directoryId,
                data: {
                    _token: '{{ csrf_token() }}',
                    is_active: isActive
                },
                success: (response) => {
                    console.log('Status updated', response);
                    // Optionally alert the user to a successful update
                },
                error: (error) => {
                    console.error('Error updating status', error);
                    // Optionally revert the switch if the update fails
                }
            });
        });
    });
</script>
@endsection
