@extends('layout.main')

@section('content')
<style>
    .table-responsive {
        overflow-x: auto; 
    }
    .table th, .table td {
        white-space: normal; 
        word-wrap: break-word; 
        padding: 8px; 
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
        <!-- Breadcrumb and Page Title -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div style="position: absolute; right: 0;">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="margin-bottom: 20px;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">ESO Court List</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-gavel"></i>
                </span>
                Manage ESO Court
            </h3>
            <a href="{{route('createCase')}}" class="btn btn-gradient-primary">Add Case Detail</a>
        </div>

        <!-- Office Document List -->
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">ESO Court List</h4>
                    </div>
                    <div class="card-body">
                        @include('include.statusAlert')
                        <table class="table" id="myDataTable">
                            <thead>
                                <tr>
                                    <th style="width: 2%;">S. No.</th>
                                    <th style="width: 10%;">Section</th>
                                    <th style="width: 15%;">File No.</th>
                                    <th style="width: 15%;">Case No.</th>
                                    <!-- <th style="width: 20%;">Subject</th> -->
                                    <th style="width: 5%;">LDOH</th>
                                    <th style="width: 5%;">NDOH</th>
                                    <th style="width: 10%;">Active</th>
                                    <!-- <th style="width: 5%;">Sort Order</th> -->
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cases as $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $item->section ? $item->section->short_name : 'No Section' }}</td>
                                        <td>{{ $item->file_no }}</td>
                                        <td>{{ $item->case_no }}</td>
                                        <!-- <td>{{ $item->subject }}</td> -->
                                        <td>{{ $item->ldoh }}</td>
                                        <td>{{ $item->ndoh }}</td>
                                        <td>
                                            <label class="switch" style="position: relative; display: inline-block; width: 40px; height: 23px;">
                                                <input type="checkbox" class="status-toggle" {{ $item->is_active==1 ? 'checked' : '' }} data-id="{{$item->id}}" style="opacity: 0; width: 0; height: 0;">
                                                <span class="slider"></span>
                                            </label>
                                        </td>
                                        <!-- <td>{{ $item->sort_order }}</td> -->
                                        <td>
                                            <a href="{{ route('viewCase', $item->id) }}" class="btn btn-icon btn-gradient-info btn-rounded mr-2" title="view"><i class="mdi mdi-eye"></i></a>
                                            <a href="{{ route('editCase', $item->id) }}" class="btn btn-icon btn-gradient-primary btn-rounded mr-2" title="edit"><i class="mdi mdi-grease-pencil"></i></a>
                                            <a href="{{ route('deleteCase', $item->id) }}" class="btn btn-icon btn-gradient-danger btn-rounded" title="delete" onclick="return confirm('This action will permanently delete this case. Are you sure to continue?')"><i class="mdi mdi-delete"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No case found</td>
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

<script>
        $('.status-toggle').change(function() {
            const caseId = $(this).data('id');
            const isActive = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                type: 'POST',
                url: '{{ url("/updateCaseStatus") }}/' + caseId,
                data: {
                    _token: '{{ csrf_token() }}',
                    is_active: isActive
                },
                success: (response) => {
                    if (response.success) {
                        console.log('Status updated', response);
                    } else {
                        console.error('Error updating status', response);
                        $(this).prop('checked', !isActive);
                    }
                },
                error: (error) => {
                    console.error('Error updating status', error);
                    $(this).prop('checked', !isActive);
                }
            });
        });
</script>
@endsection
