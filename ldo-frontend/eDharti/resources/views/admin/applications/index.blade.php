@extends('layouts.app')

@section('title', 'Application Listing')

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
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Applications List</li>
                </ol>
            </nav>
        </div>
        <!-- <div class="ms-auto"><a href="#" class="btn btn-primary">Button</a></div> -->
    </div>

    <hr>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <ul class="d-flex gap-3">
                    <li class="list-group-item d-flex gap-2 align-items-center flex-wrap">
                        <i class="lni lni-spellcheck fs-5" style="color:#6610f2"></i>
                        <span class="text-secondary">Mis Is Checked</span>
                    </li>
                    <li class="list-group-item d-flex gap-2 align-items-center flex-wrap">|</li>
                    <li class="list-group-item d-flex gap-2 align-items-center flex-wrap">
                        <i class="fadeIn animated bx bx-file-find fs-5" style="color:#20c997"></i>
                        <span class="text-secondary">Scanned Files Checked</span>
                    </li>
                    <li class="list-group-item d-flex gap-2 align-items-center flex-wrap">|</li>
                    <li class="list-group-item d-flex gap-2 align-items-center flex-wrap">
                        <i class="lni lni-cloud-upload fs-5" style="color:#fd7e14"></i>
                        <span class="text-secondary">Uploaded Documents Checked</span>
                    </li>
                </ul>
            </div>
            <table id="example" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Applicant No.</th>
                        <th>Property ID</th>
                        <th>Locality</th>
                        <th>Block</th>
                        <th>Plot No.</th>
                        <th>Known As</th>
                        @if ($user->roles[0]['name'] == 'deputy-lndo')
                            <th>Section</th>
                        @endif
                        <th>Applied For</th>
                        <th>Activity</th>
                        <th>
                            <select class="form-control form-select form-select-sm" name="status" id="status"
                                style="font-weight: bold;">
                                <option value="">Status</option>
                                @foreach ($items as $item)
                                    <option class="text-capitalize" value="{{ $item->id }}" @if ($getStatusId == $item->id)
                                        @selected(true)
                                    @endif>{{ $item->item_name }}
                                    </option>
                                @endforeach
                            </select>
                        </th>
                        <th>Applied At</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>

        </div>
    </div>
    <div id="tooltip"></div>
@endsection


@section('footerScript')

    <script type="text/javascript">
       $(document).ready(function() {
            var table = $('#example').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('admin.getApplications') }}",
                    data: function(d) {
                        d.status = $('#status').val(); // Add selected status to the request
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
                        data: 'activity',
                        name: 'activity',
                        render: function(data, type, row) {
                            console.log(data);
                            
                            let mis = $('<div>').text(data.mis).html();
                            let scannedFiles = $('<div>').text(data.scanned_files).html();
                            let uploadedDoc = $('<div>').text(data.uploaded_doc).html();
                            console.log(mis,scannedFiles,uploadedDoc);
                            let misCheckedBy = data.mis_checked_by || '';
                            let scanFileCheckedBy = data.scan_file_checked_by || '';
                            let uploadedDocCheckedBy = data.uploaded_doc_checked_by || '';
                            let misColor = data.mis_color_code || '';
                            let scannedFilesColor = data.scan_file_color_code || '';
                            let uploadedDocColor = data.uploaded_doc_color_code || '';

                            let misHtml = mis == 1 ? `<div class="list-inline-item d-flex align-items-center">
            <i class="lni lni-spellcheck fs-5" style="color:${misColor}"></i> <span class="px-2 fst-italic">${misCheckedBy}</span></div>` : '';

                            let scannedFilesHtml = scannedFiles == 1 ?
                                `<div class="list-inline-item d-flex align-items-center pt-1">
            <i class="fadeIn animated bx bx-file-find fs-5" style="color:${scannedFilesColor}"></i><span class="px-2 fst-italic">${scanFileCheckedBy}</span></div>` : '';

                            let uploadedDocHtml = uploadedDoc == 1 ?
                                `<div class="list-inline-item d-flex align-items-center pt-1">
            <i class="lni lni-cloud-upload fs-5" style="color:${uploadedDocColor}"></i> <span class="px-2 fst-italic">${uploadedDocCheckedBy}</span></div>` : '';

                            return `<div>
                            ${misHtml}
                            ${scannedFilesHtml}
                            ${uploadedDocHtml}
                        </div>`;
                        }
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
            $('#status').change(function() {
                table.ajax.reload();
            });
            
        });
    </script>
@endsection
