@extends('layouts.app')

@section('title', 'Application History List')

@section('content')

    <style>
        div.dt-buttons {
            float: none !important;
            width: 19%;
        }

        div.dt-buttons.btn-group {
            margin-bottom: 20px;
        }

        div.dt-buttons.btn-group .btn {
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 4px;
        }

        /* Ensure responsiveness on smaller screens */
        @media (max-width: 768px) {
            div.dt-buttons.btn-group {
                flex-direction: column;
                align-items: flex-start;
            }

            div.dt-buttons.btn-group .btn {
                width: 100%;
                text-align: left;
            }
        }
    </style>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Applications</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">History</li>
                    <li class="breadcrumb-item active" aria-current="page">Submitted Applications</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="example" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Application No</th>
                        <th>Property Id</th>
                        <th>Locality</th>
                        <th>Block</th>
                        <th>Plot No.</th>
                        <th>Known As</th>
                        <th>Applied For</th>
                        <th>Status</th>
                        <th>Applied At</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    @include('include.alerts.application.withdraw-application')
    @include('include.alerts.ajax-alert')
   
@endsection


@section('footerScript')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#example').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('getHistoryApplications') }}",
                    data: function(d) {
                        d.status = $('#statusSelect').val(); // Add selected status to the request
                    }
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'application_no',
                        name: 'application_no'
                    },
                    {
                        data: 'old_property_id',
                        name: 'old_property_id'
                    },
                    {
                        data: 'new_colony_name',
                        name: 'new_colony_name'
                    },
                    {
                        data: 'block_no',
                        name: 'block_no'
                    },
                    {
                        data: 'plot_or_property_no',
                        name: 'plot_or_property_no'
                    },
                    {
                        data: 'presently_known_as',
                        name: 'presently_known_as'
                    },
                    {
                        data: 'applied_for',
                        name: 'applied_for',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                dom: '<"top"Blf>rt<"bottom"ip><"clear">', // Custom DOM for buttons and pagination
                buttons: ['csv', 'excel', 'pdf'], // Export buttons
                createdRow: function(row, data, dataIndex) {
                    // Adding dynamic IDs to the 'status' and 'action' columns
                    $('td', row).eq(6).attr('id', 'status-' + data.id); // Status column
                    $('td', row).eq(7).attr('id', 'action-' + data.id); // Action column
                }
            });
            
        });
        function withdrawApplication(applicationNo){
            $('#withdrawApplicantNo').val(applicationNo)
            $('#withdawApplication').modal('show');
        }

        $('#confirmWithdawApplication').on('click',function(){
            var applicationNo = $('#withdrawApplicantNo').val()
            $.ajax({
            url: "{{route('withdrawApplication')}}",
            type: "POST",
            dataType: "JSON",
            data: {
                applicationNo: applicationNo,
                _token: '{{csrf_token()}}'
            },
            success: function(response) {
                if (response.status) {
                    $('#withdawApplication').modal('hide');
                    showSuccess(response.message,window.location.href)
                } else {
                    showError(response.message)
                }
            },
            error: function(response) {
                showError(response.message)
            }
        })
            
        })
    </script>
@endsection
