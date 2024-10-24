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
                        <li class="breadcrumb-item active" aria-current="page">Components List</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-checkerboard"></i>
                </span>
                Manage Components
            </h3>
            <a href="{{ route('createComponent') }}" class="btn btn-gradient-primary">Add Component</a>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Component List</h4>
                    </div>
                    <div class="card-body">
                        @include('include.statusAlert')


                        @if(isset($components) && $components->count() > 0)
                        <table class="table" id="myDataTable">
                            <thead>
                                <tr>
                                    <th>S. No.</th>
                                    <th>Name</th>
                                    <th>Heading</th>
                                    <!-- <th>Hindi Heading</th> -->
                                    <th>Page</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="component-list">
                                @foreach($components as $component)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$component->name}}</td>
                                    <td>{{$component->heading_eng}}</td>
                                    <!-- <td>{{$component->heading_hin}}</td> -->
                                    <td>{{$component->page->name}}</td>
                                    <td>
                                        <label class="switch" style="position: relative; display: inline-block; width: 40px; height: 23px;">
                                            <input type="checkbox" class="status-toggle" {{ $component->is_active==1 ? 'checked' : '' }} data-id="{{$component->id}}" style="opacity: 0; width: 0; height: 0;">
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <a href="{{route('viewComponent', [$component->id])}}" class="btn btn-icon btn-gradient-info btn-rounded" title="View"><i class="mdi mdi-eye"></i></a>    
                                        <a href="{{route('editComponent', [$component->id])}}" class="btn btn-icon btn-gradient-primary btn-rounded" title="edit"><i class="mdi mdi-grease-pencil"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        @else
                        <h3>Here we display the the list of components. Currently there is no data to display!!!</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    
        $(document).on('change', '.status-toggle', function() {
            const componentId = $(this).data('id');
            const isActive = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                type: 'POST',
                url: '/updateComponentStatus/' + componentId,
                data: {
                    _token: '{{ csrf_token() }}',
                    is_active: isActive
                },
                success: function(response) {
                    if(response.success) {
                        console.log('Status updated successfully');
                    } else {
                        console.log('Failed to update status');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error updating status', xhr, status, error); 
                }
            });
        });
</script>

@endsection
