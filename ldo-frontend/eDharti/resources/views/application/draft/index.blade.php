@extends('layouts.app')

@section('title', 'Appointments List')

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
                <li class="breadcrumb-item active" aria-current="page">Draft Applications</li>
            </ol>
        </nav>
    </div>
</div>
@include('include.alerts.ajax-alert')
@include('include.alerts.delete-confirmation')
<div class="card">
    <div class="card-body">
        <table id="example" class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Property Id</th>
                    <th>Locality</th>
                    <th>Block</th>
                    <th>Plot No.</th>
                    <th>Known As</th>
                    <th>Applied For</th>
                    <th>Applied At</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection


@section('footerScript')
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#example').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('getDraftApplications') }}",
                data: function(d) {
                    d.status = $('#statusSelect').val(); // Add selected status to the request
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id'
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
                    data: 'created_at',
                    name: 'created_at',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }

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


    let confirmationCallback = null;

    function deleteConfirmModal(customMessage, modelName, modelId) {
        document.getElementById('customConfirmationMessage').textContent = customMessage;
        confirmationCallback = function() {
            // Call deleteApplication with base64 encoded parameters
            deleteApplication(modelName, modelId);
        };
        $('#ModalDelete').modal('show');
    }

    $('#confirmDelete').click(() => {
        // If the callback is defined, call it
        if (confirmationCallback) {
            confirmationCallback();
            $('#ModalDelete').modal('hide'); // Close the modal after confirming
        }
    });

    function deleteApplication(modelName, modelId) {
        var modalId = atob(modelId);
        var modalName = atob(modelName);
        $.ajax({
            url: "{{route('deleteApplication')}}",
            type: "POST",
            dataType: "JSON",
            data: {
                modalId: modalId,
                modalName: modalName,
                _token: '{{csrf_token()}}'
            },
            success: function(response) {
                if (response.status) {
                    showSuccess(response.message, window.location.href);
                } else {
                    showError(response.message);
                }
            },
            error: function(response) {}
        })
    }
</script>
@endsection