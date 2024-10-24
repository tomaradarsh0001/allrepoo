@extends('layouts.app')
@section('title', 'Application Details')
@section('content')
<style>
    .pagination .active a {
        color: #ffffff !important;
    }

    .required-error-message {
        display: none;
    }

    .required-error-message {
        margin-left: -1.5em;
        margin-top: 3px;
    }

    .form-check-inputs[type=checkbox] {
        border-radius: .25em;
    }

    .form-check .form-check-inputs {
        float: left;
        margin-left: -1.5em;
    }

    .form-check-inputs {
        width: 1.5em;
        height: 1.5em;
        margin-top: 0;
    }
</style>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">APPLICATION DETAILS</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item">{{$applicationType}}</li>
                <li class="breadcrumb-item active" aria-current="page">Application Details</li>
            </ol>
        </nav>
    </div>
</div>
<!-- <div class="ms-auto"><a href="#" class="btn btn-primary">Button</a></div> -->
<hr>
<div class="card">
    <div class="card-body">
        <div>
            <div class="parent_table_container pb-3">
                <table class="table report-item">
                    <tbody>

                        <tr>
                            <td>Application No: <span class="highlight_value">{{ $details->application_no ?? ''
                                    }}</span></td>
                            <td>Application Type: <span class="highlight_value">
                                    <div
                                        class="ml-2 badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">
                                        {{$applicationType}}
                                    </div>
                                </span></td>
                            <td>Application Current Satus: <span class="highlight_value lessee_address">
                                    @switch(getStatusDetailsById( $details->status ?? '' )->item_code)
                                    @case('RS_REJ')
                                    <div
                                        class="ml-2 badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">
                                        {{ getStatusDetailsById($details->status ?? '')->item_name }}
                                    </div>
                                    @break

                                    @case('APP_NEW')
                                    <div
                                        class="ml-2 badge rounded-pill text-primary bg-light-primary p-2 text-uppercase px-3">
                                        {{ getStatusDetailsById($details->status ?? '')->item_name }}
                                    </div>
                                    @break

                                    @case('RS_UREW')
                                    <div
                                        class="ml-2 badge rounded-pill text-white bg-secondary p-2 text-uppercase px-3">
                                        {{ getStatusDetailsById($details->status ?? '')->item_name }}
                                    </div>
                                    @break

                                    @case('RS_REW')
                                    <div
                                        class="ml-2 badge rounded-pill text-warning bg-light-warning p-2 text-uppercase px-3">
                                        {{ getStatusDetailsById($details->status ?? '')->item_name }}
                                    </div>
                                    @break

                                    @case('RS_PEN')
                                    <div
                                        class="ml-2 badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">
                                        {{ getStatusDetailsById($details->status ?? '')->item_name }}
                                    </div>
                                    @break

                                    @case('RS_APP')
                                    <div
                                        class="ml-2 badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">
                                        {{ getStatusDetailsById($details->status ?? '')->item_name }}
                                    </div>
                                    @break

                                    @default
                                    <div
                                        class="ml-2 badge rounded-pill text-secondary bg-light p-2 text-uppercase px-3">
                                        {{ getStatusDetailsById($details->status ?? '')->item_name }}
                                    </div>
                                    @endswitch
                                </span></td>
                            <td>Status of Applicant: <span class="highlight_value">{{
                                    getServiceNameById($details->status_of_applicant ?? '') }}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="part-title">
            <h5>Property Details</h5>
        </div>
        <div class="part-details">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <table class="table table-bordered property-table-info">
                            <tbody>
                                <tr>
                                    <th>Old Property ID:</th>
                                    <td>{{ $details->old_property_id ?? '' }}</td>
                                    <th>New Property ID:</th>
                                    <td>{{ $details->new_property_id ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Lease Type:</th>
                                    <td>{{ $propertyCommonDetails['leaseType'] ?? '' }}</td>
                                    <th>Lease Exection Date:</th>
                                    <td>{{ $propertyCommonDetails['leaseExectionDate'] ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Property Type:</th>
                                    <td>{{ $propertyCommonDetails['propertyType'] ?? '' }}</td>
                                    <th>Property Sub Type:</th>
                                    <td>{{ $propertyCommonDetails['propertySubType'] ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Presently Known As:</th>
                                    <td>{{ $propertyCommonDetails['presentlyKnownAs'] ?? '' }}</td>
                                    <th>Original Lessee:</th>
                                    <td>{{ $propertyCommonDetails['inFavourOf'] ?? '' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



        <div class="part-title">
            <h5>Name & details of Registered Applicant</h5>
        </div>
        <div class="part-details">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <table class="table table-bordered property-table-info">
                            <tbody>
                                <tr>
                                    <th>Applicant Number:</th>
                                    <td>{{ $user->applicantUserDetails->applicant_number ?? '' }}</td>
                                    <th>User Type:</th>
                                    <td class="text-uppercase">{{ $user->applicantUserDetails->user_sub_type ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ $user->name ?? '' }} </td>
                                    <th>Email:</th>
                                    <td>{{ $user->email ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Gender:</th>
                                    <td>{{ $user->applicantUserDetails->gender ?? '' }}</td>
                                    <th>{{$user->applicantUserDetails->so_do_spouse}}:</th>
                                    <td> {{$user->applicantUserDetails->second_name}}</td>
                                </tr>
                                <tr>
                                    <th>PAN:</th>
                                    <td>{{ $user->applicantUserDetails->pan_card ?? '' }}</td>
                                    <th>Aadhaar:</th>
                                    <td> {{$user->applicantUserDetails->aadhar_card}}</td>
                                </tr>
                                <tr>
                                    <th>Mobile:</th>
                                    <td>{{ $user->mobile_no ?? '' }}</td>
                                    <th>Address:</th>
                                    <td class="w-50">{{$user->applicantUserDetails->address ?? '' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($coapplicants) && count($coapplicants) > 0)
        <div class="part-title">
            <h5>Name & Details of Other Co-Applicants</h5>
        </div>
        <div class="part-details">
            <div class="container-fluid">
                <div class="row">
                    @foreach($coapplicants as $key => $coapplicant)
                    <div class="col-lg-12 col-12 items" style="
                            position: relative;
                        ">
                        <div style="
                                position: absolute;
                                right: 10px;
                                padding: 2px 8px;
                                background: #126a6ba8;
                                border-radius: 50%;
                                color: #ffffff;
                            ">{{$key+1}}</div>
                        <div class="parent_table_container">
                            <table class="table table-bordered property-table-info" style="
                                margin: 5px 0px;
                            ">
                                <tbody>
                                    <tr>
                                        <td>Name: <span
                                                class="highlight_value">{{$coapplicant->co_applicant_name}}</span></td>
                                        <td>Gender/Age: <span
                                                class="highlight_value">{{$coapplicant->co_applicant_gender}}/
                                                {{$coapplicant->co_applicant_age}}</span></td>
                                        <td>Father Name <span
                                                class="highlight_value lessee_address">{{$coapplicant->co_applicant_father_name}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Aadhaar: <span
                                                class="highlight_value">{{$coapplicant->co_applicant_aadhar}}</span>
                                        </td>
                                        <td>PAN: <span class="highlight_value">{{$coapplicant->co_applicant_pan}}</span>
                                        </td>
                                        <td>Mobile Number: <span
                                                class="highlight_value">{{$coapplicant->co_applicant_mobile}}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @if(isset($details->name_as_per_lease_conv_deed))
        <div class="part-title">
            @if($details->property_status == 952)
            <h5>Details of Conveyance Deed</h5>
            @else
            <h5>Details of Lease Deed</h5>
            @endif
        </div>
        <div class="part-details">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <table class="table table-bordered property-table-info">
                            <tbody>
                                <tr>
                                    <th>Executed In Favour of:</th>
                                    <td>{{ $details->name_as_per_lease_conv_deed ?? '' }}</td>
                                    <th>Executed On:</th>
                                    <td>{{ $details->executed_on ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Regn. No.:</th>
                                    <td>{{ $details->reg_no_as_per_lease_conv_deed ?? '' }}</td>
                                    <th>Book No.:</th>
                                    <td>{{ $details->book_no_as_per_lease_conv_deed ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Columne No.:</th>
                                    <td>{{ $details->volume_no_as_per_lease_conv_deed ?? '' }}</td>
                                    <th>Page No.:</th>
                                    <td>{{ $details->page_no_as_per_lease_conv_deed ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Regn. Date:</th>
                                    <td>{{ $details->reg_date_as_per_lease_conv_deed ?? '' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(isset($details->property_type_change_to))
        <div class="part-title">
            <h5>Application Details</h5>
        </div>
        <div class="part-details">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <table class="table table-bordered property-table-info">
                            <tbody>
                                <tr>
                                    <th>Property Type Applicant Want To Change:</th>
                                    <td>{{ getServiceNameById($details->property_type_change_to) ?? '' }}</td>
                                    <th>Property Sub Type Applicant Want To Change:</th>
                                    <td>{{ getServiceNameById($details->property_subtype_change_to) ?? '' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(isset($details->applicant_name))
        <div class="part-title">
            <h5>Apartment Details</h5>
        </div>
        <div class="part-details">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <table class="table table-bordered property-table-info">
                            <tbody>
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ $details->applicant_name ?? '' }}</td>
                                    <th>Communication Address:</th>
                                    <td>{{ $details->applicant_address ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Building Name:</th>
                                    <td>{{ $details->building_name ?? '' }}</td>
                                    <th>Locality:</th>
                                    <td>{{ $details->locality ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Block:</th>
                                    <td>{{ $details->block ?? '' }}</td>
                                    <th>Plot No.:</th>
                                    <td>{{ $details->plot ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Presently Known As:</th>
                                    <td>{{ $details->known_as ?? '' }}</td>
                                    <th>Flat:</th>
                                    <td>{{ $details->flat_id ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>is Flat not listed:</th>
                                    <td>{{ $details->flat_number ?? '' }}</td>
                                    <th>Flat No.:</th>
                                    <td>{{ $details->flat_number ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Name of Builder / Developer:</th>
                                    <td>{{ $details->builder_developer_name ?? '' }}</td>
                                    <th>Name Of Original Buyer:</th>
                                    <td>{{ $details->original_buyer_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Name Of Present Occupant:</th>
                                    <td>{{ $details->present_occupant_name ?? '' }}</td>
                                    <th>Purchased From:</th>
                                    <td>{{ $details->purchased_from ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Date of Purchase:</th>
                                    <td>{{ $details->purchased_date ?? '' }}</td>
                                    <th>Flat Area:</th>
                                    <td>{{ $details->flat_area ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Plot Area:</th>
                                    <td>{{ $details->plot_area ?? '' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        

        @switch($applicationType)
        @case('Mutation')
            <div class="part-title mt-2">
                <h5>Property Document Details</h5>
            </div>
            <div class="part-details">
                <div class="container-fluid">
                    <div class="row g-2">
                        <div class="col-lg-12">
                            <table class="table table-bordered property-table-info">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Document Name</th>
                                        <th>Document Values</th>
                                        <th style="text-align:center;">View Docs</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="address_data">Required Documents</td>
                                    </tr>
                                    @php
                                    $count = 0;
                                    @endphp
    
                                    @if (!empty($requiredDoc))
                                    @foreach($requiredDoc as $key => $document)
                                    <tr>
                                        <td>{{ ++$count }}</td>
                                        <td>{{ $key == 'Lease_Conveyance Deed'?'Lease Deed/Conveyance Deed' :$key }}</td>
                                        <td>
                                            <table class="table table-bordered property-table-info" style="
                                                    margin: 5px 0px;
                                                ">
                                                <tbody>
                                                    @if (isset($document['value']) && is_array($document['value']))
                                                    @foreach($document['value'] as $value)
                                                    <tr>
                                                        <td><b>{{$value['label'] ?? 'N/A'}}: </b></td>
                                                        <td>{{ $value['value'] ?? 'N/A' }}</td>
                                                    </tr>
                                                    @endforeach
                                                    @else
                                                    N/A<br>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </td>
                                        <td style="text-align:center;">
                                            <a href="{{ asset('storage/' . ($document['file_path'] ?? '')) }}"
                                                target="_blank" class="text-danger view_docs" data-toggle="tooltip"
                                                title="View Uploaded Files">
                                                <i class="bx bxs-file-pdf"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="form-check form-check-success">
                                                <input class="form-check-input property-document-approval-chk"
                                                    type="checkbox" role="switch" id="saleDeedDoc{{ $count }}" @if ($checkList && $checkList->is_uploaded_doc_checked == 1) checked disabled @endif
                                                @if ($roles === 'deputy-lndo') disabled @endif>
                                                <label class="form-check-label"
                                                    for="saleDeedDoc{{ $count }}">Checked</label>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
    
    
                                    <tr>
                                        <td colspan="5" class="address_data">Optional Documents</td>
                                    </tr>
                                    @php
                                    $count = 0;
                                    @endphp
    
                                    @if (!empty($optionalDoc))
                                    @foreach($optionalDoc as $key => $document)
                                    <tr>
                                        <td>{{ ++$count }}</td>
                                        <td>{{ $key == 'Unregd WILL_CODICIL'?'Unregd. WILL/CODICIL' :$key }}</td>
                                        <td>
                                            <table class="table table-bordered property-table-info" style="
                                            margin: 5px 0px;
                                        ">
                                                <tbody>
                                                    @if (isset($document['value']) && is_array($document['value']))
                                                    @foreach($document['value'] as $value)
                                                    <tr>
                                                        <td><b>{{$value['label'] ?? 'N/A'}}: </b></td>
                                                        <td>{{ $value['value'] ?? 'N/A' }}</td>
                                                    </tr>
                                                    @endforeach
                                                    @else
                                                    N/A<br>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </td>
                                        <td style="text-align:center;">
                                            @if (isset($document['file_path']))
                                            <a href="{{ asset('storage/' . ($document['file_path'] ?? '')) }}"
                                                target="_blank" class="text-danger view_docs" data-toggle="tooltip"
                                                title="View Uploaded Files">
                                                <i class="bx bxs-file-pdf"></i>
                                            </a>
                                            @else
                                            Not Uploaded
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($document['file_path']))
                                            <div class="form-check form-check-success">
                                                <input class="form-check-input property-document-approval-chk"
                                                    type="checkbox" role="switch" id="saleDeedDoc{{ $count }}" @if ($checkList && $checkList->is_uploaded_doc_checked == 1) checked disabled @endif
                                                @if ($roles === 'deputy-lndo') disabled @endif>
                                                <label class="form-check-label"
                                                    for="saleDeedDoc{{ $count }}">Checked</label>
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @break
        @case('Land Use Change')
        @case('Deed Of Apartment')
            <div class="part-title mt-2">
                <h5>Property Document Details</h5>
            </div>
            <div class="part-details">
                <div class="container-fluid">
                    <div class="row g-2">
                        <div class="col-lg-12">
                            <table class="table table-bordered property-table-info">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Document Name</th>
                                        <th style="text-align:center;">View Docs</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="address_data">Required Documents</td>
                                    </tr>
                                    @if (!empty($documents['required']))
                                        @foreach($documents['required'] as $key => $document)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $document['title']}}</td>
                                                <td style="text-align:center;">
                                                    <a href="{{ asset('storage/' . ($document['file_path'] ?? '')) }}"
                                                        target="_blank" class="text-danger view_docs" data-toggle="tooltip"
                                                        title="View Uploaded Files">
                                                        <i class="bx bxs-file-pdf"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-success">
                                                        <input class="form-check-input property-document-approval-chk"
                                                            type="checkbox" role="switch" @if ($checkList && $checkList->is_uploaded_doc_checked == 1) checked disabled @endif
                                                        @if ($roles === 'deputy-lndo') disabled @endif>
                                                        <label class="form-check-label">Checked</label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    @if (!empty($documents['optional']))
                                    <tr>
                                        <td colspan="5" class="address_data">Optional Documents</td>
                                    </tr>
                                        @foreach($documents['optional'] as $key => $document)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $document['title']}}</td>
                                                <td>
                                                @if (isset($document['file_path']))
                                                    <a href="{{ asset('storage/' . ($document['file_path'] ?? '')) }}"
                                                        target="_blank" class="text-danger view_docs" data-toggle="tooltip"
                                                        title="View Uploaded Files">
                                                        <i class="bx bxs-file-pdf"></i>
                                                    </a>
                                                @else
                                                    Not Uploaded
                                                @endif
                                                </td>
                                                <td>
                                                    @if (isset($document['file_path']))
                                                        <div class="form-check form-check-success">
                                                            <input class="form-check-input property-document-approval-chk"
                                                                type="checkbox" role="switch" @if ($checkList && $checkList->is_uploaded_doc_checked == 1) checked disabled @endif
                                                            @if ($roles === 'deputy-lndo') disabled @endif>
                                                            <label class="form-check-label">Checked</label>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @break
        @default
            <div class="part-title mt-2">
                <h5>Property Document Details</h5>
            </div>
            <div class="part-details">
                <div class="container-fluid">
                    <div class="row g-2">
                        <div class="col-lg-12">
                            <p>Property Documents Not Available</p>
                        </div>
                    </div>
                </div>
            </div>
    @endswitch


       



    @php
        $serviceType = $details->serviceType;
    @endphp


        
        @if ($roles === 'section-officer')
            <div class="part-title mt-2">
                <h5>OFFICE ACTIVITY</h5>
            </div>
        <div class="part-details">
            <form id="approvalForm" method="POST" action="{{ route('approve.user.registration') }}">
                @csrf
                <div class="container-fluid pb-3">
                    <div class="row">
                        <input type="hidden" name="emailId" id="emailId" value="{{ $details->email ?? '' }}">
                        <input type="hidden" name="registrationId" id="registrationId" value="{{ $details->id ?? '' }}">
                        <input type="hidden" name="oldPropertyId" id="oldPropertyId" value="{{ $data['oldPropertyId'] ?? '' }}">
                        <div class="d-flex gap-1 flex-row align-items-end ">
                            @php
                                $serviceCode = getServiceCodeById($serviceType) ?? '';
                                $modalId = $details->id ?? '';
                                $applicant_no = $details->application_no ?? '';
                                $masterId = $details->property_master_id ?? '';
                                $uniquePropertyId = $details->new_property_id ?? '';
                                $oldPropertyId = $details->old_property_id ?? '';
                                $sectionCode = $details->sectionCode ?? '';
                                $additionalData = [
                                    $serviceCode,
                                    $modalId,
                                    $applicant_no,
                                    $masterId,
                                    $uniquePropertyId,
                                    $oldPropertyId,
                                    $sectionCode,
                                ];
                                $additionalDataJson = json_encode($additionalData);
                            @endphp
                            <div class="btn-group">
                                    <a href="{{ route('viewDetails', ['property' => $propertyMasterId]) }}?params={{ urlencode($additionalDataJson) }}">
                                    <button type="button" id="PropertyIDSearchBtn" class="btn btn-primary ml-2">Go to Details</button>
                                </a>
                            </div>
                            
                            <div class="btn-group">
                                <a href="{{ route('mis.index') }}">
                                    <button type="button" id="PropertyIDSearchBtn" class="btn btn-warning ml-2">Go to MIS</button>
                                </a>
                            </div>
                        </div>
                        
                        <div class="row py-3">
                            <div class="col-lg-4 mt-4">
                                <div class="checkbox-options">
                                    <div class="form-check form-check-success">
                                        <label class="form-check-label" for="isMISCorrect">
                                            is MIS Checked
                                        </label>
                                        <input class="form-check-input required-for-approve" @if ($checkList &&
                                            $checkList->is_mis_checked == 1) checked disabled @endif
                                        
                                        name="is_mis_checked" type="checkbox" value="1"
                                        id="isMISCorrect">
                                        <div class="text-danger required-error-message" id="misCheckedError">This
                                            field is required.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 mt-4">
                                <div class="checkbox-options">
                                    <div class="form-check form-check-success">
                                        <label class="form-check-label" for="isScanningCorrect">
                                            is Property Scanned File Checked
                                        </label>
                                        <input class="form-check-input required-for-approve" @if ($checkList &&
                                            $checkList->is_scan_file_checked == 1) checked disabled @endif
                                        name="is_scan_file_checked" type="checkbox" value="1"
                                        id="isScanningCorrect">
                                        <div class="text-danger required-error-message">This field is required.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 mt-4">
                                <div class="checkbox-options">
                                    <div class="form-check form-check-success">
                                        <label class="form-check-label" for="isDocumentCorrect">
                                            is Uploaded Documents Checked
                                        </label>
                                        <input class="form-check-input required-for-approve" @if ($checkList &&
                                            $checkList->is_uploaded_doc_checked == 1) checked disabled @endif
                                        name="is_uploaded_doc_checked" type="checkbox" value="1"
                                        id="isDocumentCorrect">
                                        <div class="text-danger required-error-message" id="isDocumentCorrectError">
                                            This field is required.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               
                    <div class="row">
                        <div class="d-flex justify-content-end gap-4 col-lg-12">
                            <button type="button" class="btn btn-primary" id="approveBtn">Approve</button>
                            @if (Auth::user()->hasRole('section-officer') && Auth::user()->can('reject.register.user'))
                            <button type="button" id="rejectButton" class="btn btn-danger">Reject</button>
                            @endif
                        </div>
                    </div>
                
            </form>
        </div>
        

    </div>
</div>
</div>
@endif
<!-- View Scanned Files Modal -->
<div class="modal fade" id="viewScannedFiles" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewScannedFilesLabel">View Scanned Files</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($scannedFiles)
                <ul class="files-link">
                    @foreach ($scannedFiles['files'] as $scannedFile)
                    <li><a href="{{ $scannedFiles['baseUrl'] }}{{ $scannedFile }}" target="_blank">{{ $scannedFile
                            }}</a></li>
                    @endforeach
                </ul>
                @else
                <p class="text-danger fs-4">No scanned files available.</p>
                @endif
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="serviceType" name="serviceType" value="{{getServiceCodeById($details->serviceType)}}">
<input type="hidden" id="modalId" name="modalId" value="{{ $details->id }}">
<input type="hidden" id="applicantNo" name="applicantNo" value="{{ $details->application_no }}">
<!-- End Modal -->
@include('include.loader')
@include('include.alerts.ajax-alert')
@include('include.alerts.section.scanned-files-checked')


@endsection
@section('footerScript')
<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<script>
    $(document).ready(function () {
        //Reject the application
        $('#rejectButton').click(function () {
            var isDocumentCorrect = $('#isDocumentCorrect');
            if (isDocumentCorrect.is(':checked')) {
                $('#isDocumentCorrectError').hide();
                var value1 = 0;
                var input1 = $('#isMISCorrect');
                if (input1.is(':checked')) {
                    value1 = 1;
                }
                var value2 = 0;
                var input2 = $('#isScanningCorrect');
                if (input2.is(':checked')) {
                    value2 = 1;
                }
                var value3 = 0;
                var input3 = $('#isDocumentCorrect');
                if (input3.is(':checked')) {
                    value3 = 1;
                }
                // Dynamically create input elements and append to the modal
                $('#modalInputs').html(`
                    <input type="hidden" id="input1" name="is_mis_checked" value="${value1}">
                    <input type="hidden" id="input2" name="is_scan_file_checked" value="${value2}">
                    <input type="hidden" id="input3" name="is_uploaded_doc_checked" value="${value3}">
                    <br>
                    `);
                $('#rejectUserStatus').modal('show');
            } else {
                $('#isDocumentCorrectError').show();
            }
        })
        $('#approveBtn').click(function () {
            console.log('clicked');
            let allChecked = true;
            $('.required-for-approve').each(function () {
                const $checkbox = $(this);
                // console.log($checkbox);
                const $errorMsg = $checkbox.siblings('.required-error-message');
                // console.log($errorMsg);
                if (!$checkbox.is(':checked')) {
                    console.log('inside if');
                    $errorMsg.show();
                    allChecked = false;
                } else {
                    console.log('inside else');
                    $errorMsg.hide();
                }
            });
            if (allChecked) {
                console.log("inside if");
                //Check Property link with other applicant.
                var button = $('#approveBtn');
                var error = $('#isPropertyFree');
                button.prop('disabled', true);
                button.html('Submitting...');
                var propertyId = $('#SuggestedPropertyID').val();
                $.ajax({
                    url: "{{ route('register.user.checkProperty', ['id' => '__propertyId__']) }}"
                        .replace('__propertyId__', propertyId),
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (response) {
                        //console.log(response);
                        if (response.success === true) {
                            $('#approvePropertyModal').modal('show');
                        } else {
                            $('#checkProperty').modal('show');
                            error.html(response.details)
                            button.prop('disabled', false);
                            button.html('Approve');
                        }
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });
            }
        });
        $('#confirmApproveSubmit').on('click', function (e) {
            e.preventDefault();
            $('#approveBtn').prop('disabled', true);
            $('#approveBtn').html('Submitting...');
            $('#confirmApproveSubmit').prop('disabled', true);
            $('#confirmApproveSubmit').html('Submitting...');
            $('#approvalForm').submit();
        });
        $('#closeApproveModelButton').on('click', function (e) {
            e.preventDefault();
            e.preventDefault();
            $('#approveBtn').prop('disabled', false);
            $('#approveBtn').html('Approve');
        });
        $('#rejectBtn').on('click', function (e) {
            e.preventDefault();
            $('#rejectModal').modal('show');
        });
    });
    $(document).ready(function () {
        $('#isUnderReview').on('change', function () {
            if ($(this).is(':checked')) {
                $('#modelReview').modal('show');
            }
        });
        // Optionally, you can handle the closing of the modal
        $('#modelReview').on('hidden.bs.modal', function () {
            // Do something after the modal is hidden, like unchecking the checkbox if needed
            $('#isUnderReview').prop('checked', false);
        });
    });
    //confirmation for scanned files checked - Sourav Chauhan - 9/sep/2024
    $(document).ready(function () {
        $('#isScanningCorrect').on('change', function () {
            if ($(this).is(':checked')) {
                $('#ModelScannFile').modal('show');
            }
        });
        $('#ModelScannFile').on('hidden.bs.modal', function () {
            $('#isScanningCorrect').prop('checked', false);
        });
        // confirm and aproove Scanned files checked by section - Sourav Chauhan (09/sep/2024)
        $('#confirmScannFileChecked').on('click', function (e) {
            e.preventDefault();
            $('#confirmScannFileChecked').prop('disabled', true).html('Submitting...');
            // Serialize form data
            let formData = $('#scannFileCheckedForm').serialize();
            // Send AJAX request
            $.ajax({
                url: "{{ route('scannedFilesChecked') }}", // Your form action URL
                type: 'POST',
                data: formData,
                success: function (response) {
                    if (response.status == 'success') {
                        // Handle success response
                        $('#ModelScannFile').modal('hide');
                        $('.loader_container').addClass('d-none');
                        if ($('.results').hasClass('d-none'))
                            $('.results').removeClass('d-none');
                        showSuccess(response.message);
                        // Ensure checkbox is checked and disabled after success
                        setTimeout(function () {
                            $('#isScanningCorrect').prop('checked', true).prop(
                                'disabled', true);
                        }, 500); // Slight delay to ensure modal is fully hidden
                    } else {
                        // Handle success response
                        $('#ModelScannFile').modal('hide');
                        $('.loader_container').addClass('d-none');
                        if ($('.results').hasClass('d-none'))
                            $('.results').removeClass('d-none');
                        showError(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    // Handle error response
                    $('.loader_container').addClass('d-none');
                    if ($('.results').hasClass('d-none'))
                        $('.results').removeClass('d-none');
                    if (response.responseJSON && response.responseJSON.message) {
                        showError(response.responseJSON.message)
                    }
                }
            });
        });
    });
    // Event delegation for dynamically added elements by lalit on 01/08-2024 for remarks validation
    $(document).on('click', '.confirm-reject-btn', function (event) {
        event.preventDefault();

        var form = $('#rejectUserStatusForm');
        var remarksInput = form.find('textarea[name="remarks"]');
        var remarksValue = remarksInput.val().trim();
        var errorLabel = form.find('.error-label');
        var url = "{{ route('update.registration.status', ['id' => $details->id]) }}";

        if (remarksValue === '') {
            // Show the error label if remarks are empty
            errorLabel.show();
        } else {
            // Hide the error label and submit the form via AJAX
            errorLabel.hide();

            $.ajax({
                url: url,
                type: 'POST',
                data: form.serialize(), // Serialize the form data
                success: function (response) {
                    if (response.status == 'success') {
                        $('#rejectUserStatus').modal('hide');
                        $('.loader_container').addClass('d-none');
                        if ($('.results').hasClass('d-none'))
                            $('.results').removeClass('d-none');
                        showSuccess(response.message);
                    } else {
                        $('#rejectUserStatus').modal('hide');
                        $('.loader_container').addClass('d-none');
                        if ($('.results').hasClass('d-none'))
                            $('.results').removeClass('d-none');
                        showError(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    // Handle error (show error message or take appropriate action)
                    alert('Error: ' + xhr.responseText);
                }
            });
        }
    });

    $(document).on('click', '.confirm-user-review-btn', function (event) {
        event.preventDefault();

        var form = $('#reviewUserRegistrationForm');
        var remarksInput = form.find('textarea[name="remarks"]');
        var remarksValue = remarksInput.val().trim();
        var errorLabel = form.find('.error-label');
        var url = "{{ route('review.user.registration', ['id' => $details->id]) }}";

        if (remarksValue === '') {
            // Show the error label if remarks are empty
            errorLabel.show();
        } else {
            // Hide the error label and submit the form via AJAX
            errorLabel.hide();

            $.ajax({
                url: url,
                type: 'POST',
                data: form.serialize(), // Serialize the form data
                success: function (response) {
                    if (response.status == 'success') {
                        // Handle success response
                        $('#modelReview').modal('hide');
                        $('.loader_container').addClass('d-none');
                        if ($('.results').hasClass('d-none'))
                            $('.results').removeClass('d-none');
                        showSuccess(response.message);
                        // Ensure checkbox is checked and disabled after success
                        setTimeout(function () {
                            $('#isUnderReview').prop('checked', true).prop(
                                'disabled', true);
                        }, 500); // Slight delay to ensure modal is fully hidden
                    } else {
                        // Handle success response
                        $('#modelReview').modal('hide');
                        $('.loader_container').addClass('d-none');
                        if ($('.results').hasClass('d-none'))
                            $('.results').removeClass('d-none');
                        showError(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    // Handle error (show error message or take appropriate action)
                    alert('Error: ' + xhr.responseText);
                }
            });
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var remarksTextarea = document.getElementById('remarks');
        if (remarksTextarea) {
            var $reviewBtnNew = $('#reviewBtnNew');
            // Event listener for keypress event
            remarksTextarea.addEventListener('keypress', function (event) {
                // You can perform actions here based on the key pressed
                $reviewBtnNew.prop('disabled', false); // Enable button
            });
        }
    });
    $(document).ready(function () {
        var $errorMsg = $('#errorMsgNew');
        $('#reviewBtnNew').click(function () {
            var allChecked = true;
            var remark = $('#remarks').val();
            if (remark.trim() === '') {
                $errorMsg.show();
                allChecked = false;
            } else {
                $errorMsg.hide();
                allChecked = true;
            }
            if (allChecked) {
                $('#reviewBtnNew').prop('disabled', true);
                $('#reviewBtnNew').html('Submitting...');
                $('#approvalForm').submit();
            }
        });
    });
    $(document).ready(function () {
        // When the checkbox with name "is_uploaded_doc_checked" is checked or unchecked
        $('input[name="is_uploaded_doc_checked"]').change(function () {
            // Check if any checkbox with class "property-document-approval-chk" is unchecked
            if ($(this).prop('checked') && $('.property-document-approval-chk:not(:checked)').length >
                0) {
                // Show error message
                $('#isDocumentCorrectError').text('Please check the uploaded documents.').show();
                $(this).prop('checked', false); // Uncheck the checkbox
            } else {
                // Hide error message
                $('#isDocumentCorrectError').hide();
            }
        });
        // When any checkbox with the class "property-document-approval-chk" is checked or unchecked
        $('.property-document-approval-chk').change(function () {
            // Check if all checkboxes with class "property-document-approval-chk" are checked
            var allChecked = $('.property-document-approval-chk').length === $(
                '.property-document-approval-chk:checked').length;
            // Check or uncheck the checkbox with name "is_uploaded_doc_checked"
            
            // Hide error message if all checkboxes are checked
            if (allChecked) {
                var serviceType = $('#serviceType').val();
                var modalId = $('#modalId').val();
                var applicantNo = $('#applicantNo').val();
                $.ajax({
                    url: "{{ route('uploadedDocsChecked') }}",
                    type: "POST",
                    data: {
                        serviceType: serviceType,
                        modalId: modalId,
                        applicantNo: applicantNo,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        if(result.success){
                            $('input[name="is_uploaded_doc_checked"]').prop('checked', allChecked);
                            $('#isDocumentCorrect').prop('disabled', true)
                            $('.property-document-approval-chk').prop('disabled', true)
                            $('#isDocumentCorrectError').hide();
                        } else {
                            $('.property-document-approval-chk').prop('checked', false);
                            $('#isDocumentCorrectError').show();
                            $('#isDocumentCorrectError').html('Some issue in saving');
                            
                        }
                    }
                });
            }
        });
    });
    $(document).ready(function () {
        $('#isMISCorrect').on('change', function () {
            if ($(this).is(':checked')) {
                $(this).prop('checked', false);
                $('#misCheckedError').text('Please check MIS, Click on Go to Details button.').show();
            } else {
                $('#misCheckedError').hide();
                $(this).prop('checked', false);
            }
        });
    });
</script>
@endsection