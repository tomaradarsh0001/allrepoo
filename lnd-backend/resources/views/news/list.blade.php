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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div style="position: absolute; right: 0;">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="margin-bottom: 20px;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">News List</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-newspaper"></i>
                </span>
                Manage News
            </h3>
            <a href="{{route('createNews')}}" class="btn btn-gradient-primary">Add News</a>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">News List</h4>
                    </div>
                    <div class="card-body">

                        @include('include.statusAlert')

                        <table class="table" id="myDataTable">
                            <thead>
                                <tr>
                                    <th style="width: 2%;">S. No.</th>
                                    <th style="width: 25%;">Title English</th>
                                    <th style="width: 25%;">Title Hindi</th>
                                    <th style="width: 10%;">Status</th>
                                    <!-- <th style="width: 5%;">Sort Order</th> -->
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($news as $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $item->title_eng }}</td>
                                        <td>{{ $item->title_hin }}</td>
                                        <td>
                                            <label class="switch" style="position: relative; display: inline-block; width: 40px; height: 23px;">
                                                <input type="checkbox" class="status-toggle" {{ $item->is_active==1 ? 'checked' : '' }} data-id="{{$item->id}}" style="opacity: 0; width: 0; height: 0;">
                                                <span class="slider"></span>
                                            </label>
                                        </td>
                                        <!-- <td>{{ $item->sort_order }}</td> -->
                                        <td>
                                            <a href="{{ route('viewNews', $item->id) }}" class="btn btn-icon btn-gradient-info btn-rounded mr-2" title="view"><i class="mdi mdi-eye"></i></a>
                                            <a href="{{ route('editNews', $item->id) }}" class="btn btn-icon btn-gradient-primary btn-rounded mr-2" title="edit"><i class="mdi mdi-grease-pencil"></i></a>
                                            <a href="{{ route('deleteNews', $item->id) }}" class="btn btn-icon btn-gradient-danger btn-rounded" title="delete" onclick="return confirm('This action will permanently delete this news. Are you sure to continue?')"><i class="mdi mdi-delete"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No news found</td>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

         $('.status-toggle').change(function() {
            const newsId = $(this).data('id');
            const isActive = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                type: 'POST',
                url: '/updateNewsStatus/' + newsId,
                data: {
                    _token: '{{ csrf_token() }}',
                    is_active: isActive
                },
                success: (response) => {
                    console.log('Status updated', response);
                },
                error: (error) => {
                    console.error('Error updating status', error);
                }
            });
        });
</script>
@endsection
