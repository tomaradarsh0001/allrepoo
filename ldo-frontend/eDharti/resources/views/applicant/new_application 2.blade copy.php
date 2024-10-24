@extends('layouts.app')

@section('title', 'Applicant Property History')

@section('content')
<link href="{{asset('assets/plugins/bs-stepper/css/bs-stepper.css')}}" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<style>
    #spinnerOverlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        /* Ensure it covers other content */
    }

    .spinner {
        border: 8px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top: 8px solid #ffffff;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">New Applications</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item">Application</li>
                <li class="breadcrumb-item active" aria-current="page">New Application</li>

            </ol>
        </nav>
    </div>
</div>
<div class="card newApplications">
    <div class="card-body">
        <div id="stepper3" class="bs-stepper gap-4 vertical">
            <div class="bs-stepper-header" role="tablist">
                <div class="step" data-target="#newstep-vl-1">
                    <div class="step-trigger" role="tab" id="stepper3trigger1" aria-controls="newstep-vl-1">
                        <div class="bs-stepper-circle">1</div>
                        <div class="bs-stepper-circle-content">
                        </div>
                    </div>
                </div>

                <div class="step" data-target="#newstep-vl-2">
                    <div class="step-trigger" role="tab" id="stepper3trigger2" aria-controls="newstep-vl-2">
                        <div class="bs-stepper-circle">2</div>
                        <div class="bs-stepper-circle-content">
                        </div>
                    </div>
                </div>

                <div class="step" data-target="#newstep-vl-3">
                    <div class="step-trigger" role="tab" id="stepper3trigger3" aria-controls="newstep-vl-3">
                        <div class="bs-stepper-circle">3</div>
                        <div class="bs-stepper-circle-content">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bs-stepper-content">
                <form method="POST" action="#" enctype="multipart/form-data">
                    @csrf

                    @if(isset($application))
                        <input type="hidden" id="updateId" name="updateId" value="{{ $application->id }}">
                        <input type="hidden" id="draftApplicationPropertyId" value="{{ $application->old_property_id }}">
                    @else
                        <input type="hidden" value="0" name="updateId" >
                        <input type="hidden" value="0" name="lastPropertyId" >
                    @endif
                    <div id="newstep-vl-1" role="tabpane3" class="bs-stepper-pane content fade"
                        aria-labelledby="stepper3trigger1">
                        <h5 class="mb-1">FILL APPLICATION DETAILS</h5>
                        <p class="mb-4">Enter Your Application Information</p>
                        <!-- begin -->
                        <div class="radio-buttons-0">
                            <div class="row">
                                <div class="col-lg-3 col-12">
                                    <div class="form-group">
                                        <label for="gender" class="form-label">Property ID</label>
                                        @if(isset($application))
                                            <input type="text" class="form-control alpha-only" name="propertyid" id="propertyid" value="{{$application->old_property_id}}" readonly>
                                        @else
                                            <select class="form-select" name="propertyid" id="propertyid">
                                                <option value="">Select</option>
                                                @foreach($userProperties as $userProperty)
                                                    <option value="{{$userProperty}}">{{$userProperty}}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                        <div class="text-danger" id="propertyIdError"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="form-group">
                                        <label for="statusapplicant" class="form-label">Property Status</label>
                                        <input type="text" name="applicationStatus" class="form-control alpha-only" id="propertyStatus" placeholder="Property Status" readonly>
                                        <div id="propertyStatusError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group form-box">
                                        <label for="applicationType" class="form-label">Application Type</label>
                                        <select name="applicationType" id="applicationType" class="form-select" id="applicationType">

                                        </select>
                                        <div id="applicationTypeError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="statusofapplicant" class="form-label">Status of Applicant</label>
                                        <select class="form-select" name="mutStatusOfApplicant" id="statusofapplicant">
                                            <option value="">Select</option>
                                            <option value="3" {{ isset($application) && $application->status_of_applicant == 3 ? 'selected' : '' }}>Sale-Deed Holder</option>
                                            <option value="5" {{ isset($application) && $application->status_of_applicant == 5 ? 'selected' : '' }}>Decree Holder</option>
                                            <option value="7" {{ isset($application) && $application->status_of_applicant == 7 ? 'selected' : '' }}>Court Order</option>
                                            <option value="8" {{ isset($application) && $application->status_of_applicant == 8 ? 'selected' : '' }}>WILL Deneficiary/RD
                                                Beneficiary</option>
                                            <option value="11" {{ isset($application) && $application->status_of_applicant == 11 ? 'selected' : '' }}>Legal heir</option>
                                            <option value="34" {{ isset($application) && $application->status_of_applicant == 34 ? 'selected' : '' }}>Gift Beneficiary</option>
                                            <option value="45" {{ isset($application) && $application->status_of_applicant == 45 ? 'selected' : '' }}>Others</option>
                                        </select>
                                        <div id="statusofapplicantError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- End -->
                        <!-- For NOC Step-1 -->
                        <div class="nocDiv mt-3" style="display: none;"></div>
                        <!-- End -->

                        <!-- substitution/mutation application form - SOURAV CHAUHAN (13sep/2024)-->
                        @include('application.mutation.include.mutation')

                        <!-- For Property Certificate Step-1 -->
                        <div class="propertycertificateDiv mt-3" style="display: none;"></div>
                        <!-- End -->

                        <!-- For Sale Permission Step-1 -->
                        <div class="salepermissionDiv mt-3" style="display: none;"></div>
                        <!-- End -->

                        <!-- For Conversion Step-1 -->
                        <!-- substitution/mutation application form SOURAV CHAUHAN (13sep/2024)-->


                        <!-- For Property Certificate Step-1 -->
                        <div class="propertycertificateDiv mt-3" style="display: none;"></div>
                        <!-- End -->

                        <!-- For Sale Permission Step-1 -->
                        <div class="salepermissionDiv mt-3" style="display: none;"></div>
                        <!-- End -->

                        <!-- For Conversion Step-1 -->
                        <div class="LHConversiondiv" style="display: none;" class="mt-3">
                            <div class="container-fluid">

                                <!-- row end -->
                                <div class="row g-2">
                                    <div class="col-lg-12">
                                        <div class="part-title mb-2">
                                            <h5>Details of Registered Applicant</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="namergapp" class="form-label">Name</label>
                                            <input type="text" name="namergapp" class="form-control alpha-only"
                                                id="namergapp" placeholder="Name" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="gender" class="form-label">Gender</label>
                                            <select class="form-select" name="gender" id="gender" disabled>
                                                <option value="">Gender</option>
                                                <option value="Male" selected>Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="age" class="form-label">Age</label>
                                            <input type="text" name="age" class="form-control numericOnly" id="age"
                                                maxlength="2" placeholder="Age" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="fathername" class="form-label">Father's name</label>
                                            <input type="text" name="fathername" class="form-control alpha-only"
                                                id="fathername" placeholder="Father's Name" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="aadhar" class="form-label">Aadhaar</label>
                                            <input type="text" name="aadhar" class="form-control numericOnly"
                                                id="aadhar" maxlength="12" placeholder="Aadhaar Number" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="pan" class="form-label">PAN</label>
                                            <input type="text" name="pan"
                                                class="form-control pan_number_format text-uppercase" id="pan"
                                                maxlength="10" placeholder="PAN Number" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="mobilenumber" class="form-label">Mobile Number</label>
                                            <input type="text" name="mobilenumber" class="form-control numericOnly"
                                                id="mobilenumber" maxlength="10" placeholder="Mobile Number" readonly>
                                        </div>
                                    </div>
                                </div>
                                <!-- row end -->
                                <div class="row g-2">
                                    <div class="col-lg-12">
                                        <div id="repeater" class="position-relative">

                                            <div class="part-title mb-2">
                                                <h5>Details of Other Co-Applicants</h5>
                                            </div>
                                            <div class="position-sticky text-end mt-2"
                                                style="top: 70px; margin-right: 10px; margin-bottom: 10px; z-index: 9;">
                                                <!-- <label>Add Co-Applicant</label> -->
                                                <button type="button"
                                                    class="btn btn-primary repeater-add-btn fullwidthbtn"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Click on to add more Co-Applicant below"><i
                                                        class="bx bx-plus me-0"></i> Add More</button>
                                            </div>
                                            <!-- Repeater Items -->
                                            <div class="duplicate-field-tab">
                                                <div class="items" data-group="coapplicant">
                                                    <!-- Repeater Content -->
                                                    <div class="item-content mb-2">
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="namergapp"
                                                                        class="form-label">Name</label>
                                                                    <input type="text" name="namergapp"
                                                                        class="form-control alpha-only"
                                                                        placeholder="Name" id="namergapp"
                                                                        data-name="name">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="gender"
                                                                        class="form-label">Gender</label>
                                                                    <select class="form-select" name="gender"
                                                                        id="gender" data-name="gender">
                                                                        <option value="">Select</option>
                                                                        <option value="Male">Male</option>
                                                                        <option value="Female">Female</option>
                                                                        <option value="Other">Other</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="age" class="form-label">Age</label>
                                                                    <input type="text" name="age"
                                                                        class="form-control numericOnly" id="age"
                                                                        maxlength="2" placeholder="Age" data-name="age">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="fathername" class="form-label">Father's
                                                                        name</label>
                                                                    <input type="text" name="fathername"
                                                                        class="form-control alpha-only" id="fathername"
                                                                        placeholder="Father's Name"
                                                                        data-name="fathername">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="aadhar"
                                                                        class="form-label">Aadhaar</label>
                                                                    <input type="text" name="aadhar"
                                                                        class="form-control numericOnly" id="aadhar"
                                                                        maxlength="12" placeholder="Aadhaar Number"
                                                                        data-name="aadharnumber">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="pan" class="form-label">PAN</label>
                                                                    <input type="text" name="pan"
                                                                        class="form-control pan_number_format text-uppercase"
                                                                        id="pan" maxlength="10" placeholder="PAN Number"
                                                                        data-name="pannumber">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="mobilenumber" class="form-label">Mobile
                                                                        Number</label>
                                                                    <input type="text" name="mobilenumber"
                                                                        class="form-control numericOnly" maxlength="10"
                                                                        id="mobilenumber" placeholder="Mobile Number"
                                                                        data-name="mobilenumber">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Repeater Remove Btn -->
                                                    <div class="repeater-remove-btn">
                                                        <button type="button" class="btn btn-danger remove-btn px-4"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Click on to delete this form">
                                                            <i class="fadeIn animated bx bx-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <!-- row end -->
                                <div class="row g-2 mt-2">
                                    <div class="col-lg-12">
                                        <div class="part-title mb-2">
                                            <h5 id="freeleasetitle">Details of Lease Deed</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="namergapp" class="form-label">Name</label>
                                            <input type="text" name="namergapp" class="form-control alpha-only"
                                                id="namergapp" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="fathername" class="form-label">Father's name</label>
                                            <input type="text" name="fathername" class="form-control alpha-only"
                                                id="fathername" placeholder="Father's Name">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="regno" class="form-label">Regn. No.</label>
                                            <input type="text" name="regno" class="form-control numericOnly" id="regno"
                                                placeholder="Registration No.">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="bookno" class="form-label">Book No.</label>
                                            <input type="text" name="bookno" class="form-control numericOnly" id="bookno"
                                                placeholder="Book No.">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="volumeno" class="form-label">Volume No.</label>
                                            <input type="text" name="volumeno" class="form-control numericOnly" id="volumeno"
                                                placeholder="Volume No.">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="pageno" class="form-label">Page No.</label>
                                            <input type="text" name="pageno" class="form-control numericOnly" id="pageno"
                                                placeholder="Page No.">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="regdate" class="form-label">Regn. Date.</label>
                                            <input type="date" name="regdate" class="form-control numericOnly" id="regdate">
                                        </div>
                                    </div>
                                </div>
                                <!-- row end -->
                                <div class="row g-3 align-items-end mt-3">
                                    <div class="col-lg-6">
                                        <div class="d-flex align-items-center">
                                            <h6 class="mr-5 mb-0">Whether the application is on basis of court order?
                                            </h6>
                                            <div class="form-check mr-5">
                                                <input class="form-check-input" name="courtorderConversion" type="radio"
                                                    value="Yes" id="YesCourtOrderConversion">
                                                <label class="form-check-label" for="YesCourtOrderConversion">
                                                    <h6 class="mb-0">Yes</h6>
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" name="courtorderConversion" type="radio"
                                                    value="No" id="NoCourtOrderConversion" checked>
                                                <label class="form-check-label" for="NoCourtOrderConversion">
                                                    <h6 class="mb-0">No</h6>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12" id="yescourtorderConversionDiv" style="display: none;">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="casenosubmut" class="form-label">Case No.<span class="text-danger">*</span></label>
                                                    <input type="text" name="casenosubmut" class="form-control alphaNum-hiphenForwardSlash" id="casenosubmut" placeholder="Case No." readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- row end -->

                            </div>
                        </div>
                        <!-- End -->

                        <!-- For Deed of Apartment Step-1 - SOURAV CHAUHAN (27/sep/2024) -->
                        @include('application.deed_of_appartment.include.step-1')
                        <!-- End -->

                        <!-- Land Use Change Step-1 -->
                        @include('application.luc.include.step-1')
                        <!-- End -->


                        <div class="row g-3 mt-2">
                            <div class="col-12 col-lg-4">
                                <button type="button" class="btn btn-primary px-4" id="submitbtn1">Next<i
                                        class='bx bx-right-arrow-alt ms-2'></i></button>
                            </div>
                        </div>
                        <!---end row-->
                    </div>

                    <div id="newstep-vl-2" role="tabpane3" class="bs-stepper-pane content fade"
                        aria-labelledby="stepper3trigger2">

                        <h5 class="mb-1">Compulsory/Mandatory Documents</h5>
                        <p class="mb-4">Please Enter Compulsory Details</p>

                        <!-- mutation step two SOURAV CHAUHAN (20/sep/2024)**********************-->
                        @include('application.mutation.include.mutation-step-second')



                        <!-- Conversion 2 -->
                        <div class="LHConversiondiv" style="display: none;" class="mt-3">
                            <div class="container-fluid">
                                <div class="row g-2">
                                    <div class="col-lg-12">
                                        <div class="row row-mb-2">
                                            <div class="col-lg-1 icons-flex"></div>
                                            <div class="col-lg-11 selected-docs-field">
                                                <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="form-group form-box">
                                                            <label for="indemnityBond" class="quesLabel">Indemnity
                                                                Bond (Annexure-F)<span class="text-danger">*</span></label>
                                                            <input type="file" name="indemnityBond" class="form-control"
                                                                accept="application/pdf" id="indemnityBond">
                                                            <div id="indemnityBondError" class="text-danger text-left">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="indemnityBonddateattestation">Date of
                                                                attestation<span class="text-danger">*</span></label>
                                                            <input type="date" name="indemnityBonddateattestation"
                                                                class="form-control" id="indemnityBonddateattestation">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="indemnityBondattestedby">Attested by<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="indemnityBondattestedby"
                                                                class="form-control alpha-only"
                                                                id="indemnityBondattestedby" placeholder="Attested By">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row row-mb-2">
                                            <div class="col-lg-1 icons-flex"></div>
                                            <div class="col-lg-11 selected-docs-field">
                                                <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="form-group form-box">
                                                            <label for="Undertaking" class="quesLabel">Undertaking (Annexure-G)<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="file" name="Undertaking" class="form-control"
                                                                accept="application/pdf" id="Undertaking">
                                                            <div id="UndertakingError" class="text-danger text-left">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="dateofundertaking">Date of Undertaking<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="date" name="dateofundertaking"
                                                                class="form-control" id="dateofundertaking">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row row-mb-2">
                                            <div class="col-lg-1 icons-flex"></div>
                                            <div class="col-lg-11 selected-docs-field">
                                                <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="form-group form-box">
                                                            <label for="lastsubsmutletter" class="quesLabel" data-toggle="tooltip" data-placement="top" title="Self-attested copy of last Substitution/Mutation Letter">Last Substitution/Mutation Letter<span
                                                                    class="text-danger">*</span> <span><i class='bx bx-info-circle'></i></span></label>
                                                            <input type="file" name="lastsubsmutletter" class="form-control"
                                                                accept="application/pdf" id="lastsubsmutletter">
                                                            <div id="lastsubsmutletterError" class="text-danger text-left">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="dateofdocument">Date of Document<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="date" name="dateofdocument"
                                                                class="form-control" id="dateofdocument">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row row-mb-2">
                                            <div class="col-lg-1 icons-flex"></div>
                                            <div class="col-lg-11 selected-docs-field">
                                                <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="selectdoc" data-toggle="tooltip" data-placement="top" title="Self-attested copy of C/D Form, Sanctioned Building Plan / Completion Certificate / Occupancy Certificate / Payment Receipt of Property Tax of immediately Preceding 2 years">File Name<span
                                                                    class="text-danger">*</span> <span><i class='bx bx-info-circle'></i></span></label>
                                                            <select class="form-select" name="selectdoc" id="selectdocselfattesteddocname">
                                                                <option value="">Select</option>
                                                                <option value="C/D Form">C/D Form</option>
                                                                <option value="Sanctioned Building Plan">Sanctioned Building Plan</option>
                                                                <option value="Completion Certificate">Completion Certificate</option>
                                                                <option value="Occupancy Certificate">Occupancy Certificate</option>
                                                                <option value="Payment Receipt of Property Tax Preceding 2 Years">Payment Receipt of Property Tax Preceding 2 Years</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group form-box">
                                                            <label for="selftattesteddoc" class="quesLabel">File Upload<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="file" name="selftattesteddoc"
                                                                class="form-control" accept="application/pdf"
                                                                id="selftattesteddoc">
                                                            <div id="selftattesteddocError" class="text-danger text-left">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3" id="docName" style="display: none;">
                                                        <div class="form-group">
                                                            <label for="conversiondocname">Name of Document<span class="text-danger">*</span></label>
                                                            <input type="date" name="conversiondocname" class="form-control alpha-only" id="conversiondocname">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="datedocument">Date of Document<span class="text-danger">*</span></label>
                                                            <input type="date" name="datedocument" class="form-control alpha-only" id="datedocument">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="issueauthority">Issuing Authority<span class="text-danger">*</span></label>
                                                            <input type="text" name="issueauthority" class="form-control alpha-only" placeholder="Issuing Authority" id="issueauthority">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row row-mb-2">
                                            <div class="col-lg-1 icons-flex"></div>
                                            <div class="col-lg-11 selected-docs-field">
                                                <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="form-group form-box">
                                                            <label for="proofpossession" class="quesLabel" data-toggle="tooltip" data-placement="top" title="Latest Electricity Bill/IGL Bill/Telephone Bill">Proof of possession of the premises<span
                                                                    class="text-danger">*</span> <span><i class='bx bx-info-circle'></i></span></label>
                                                            <input type="file" name="proofpossession"
                                                                class="form-control" accept="application/pdf"
                                                                id="proofpossession">
                                                            <div id="proofpossessionError" class="text-danger text-left">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="conversionproofposdocname">Name of Document<span class="text-danger">*</span></label>
                                                            <input type="date" name="conversionproofposdocname" class="form-control alpha-only" id="conversionproofposdocname">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="datedocumentproofpos">Date of Document<span class="text-danger">*</span></label>
                                                            <input type="date" name="datedocumentproofpos" class="form-control alpha-only" id="datedocumentproofpos">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="issueauthorityproofpos">Issuing Authority<span class="text-danger">*</span></label>
                                                            <input type="text" name="issueauthorityproofpos" class="form-control alpha-only" id="issueauthorityproofpos">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row row-mb-2">
                                            <div class="col-lg-1 icons-flex"></div>
                                            <div class="col-lg-11 selected-docs-field">
                                                <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="form-group form-box">
                                                            <label for="regLeaseDeed" class="quesLabel" data-toggle="tooltip" data-placement="top" title="Self-attesred of copy of registered Lease Deed with registration particulars">Registered Lease Deed<span
                                                                    class="text-danger">*</span> <span><i class='bx bx-info-circle'></i></span></label>
                                                            <input type="file" name="regLeaseDeed"
                                                                class="form-control" accept="application/pdf"
                                                                id="regLeaseDeed">
                                                            <div id="regLeaseDeedError" class="text-danger text-left">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="datedocumentproofpos">Date of Document<span class="text-danger">*</span></label>
                                                            <input type="date" name="datedocumentproofpos" class="form-control alpha-only" id="datedocumentproofpos">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row row-mb-2">
                                            <div class="col-lg-1 icons-flex"></div>
                                            <div class="col-lg-11 selected-docs-field">
                                                <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                                <div class="form-group form-box">
                                                                    <label for="aadharuploadconversion" class="quesLabel" data-toggle="tooltip" data-placement="top" title="Self-attested copy of Aadhaar of the Applicants">Upload Applicants Aadhar<span
                                                                            class="text-danger">*</span> <span><i class='bx bx-info-circle'></i></span></label>
                                                                    <input type="file" name="aadharuploadconversion"
                                                                        class="form-control" accept="application/pdf"
                                                                        id="aadharuploadconversion">
                                                                    <div id="aadharuploadconversionError" class="text-danger text-left">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-group">
                                                                    <label for="aadharconversion"
                                                                        class="form-label">Applicants Aadhar Number</label>
                                                                    <input type="text" name="aadharconversion"
                                                                        class="form-control numericOnly" id="aadharconversion"
                                                                        maxlength="12" placeholder="Aadhar Number">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-group">
                                                                    <label for="aadharcertificatenoconversion">Certificate No.<span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" name="aadharcertificatenoconversion"
                                                                        class="form-control" id="aadharcertificatenoconversion"
                                                                        placeholder="Certificate No.">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-group">
                                                                    <label for="aadhardateissue">Date of Issue<span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="date" name="aadhardateissue" class="form-control"
                                                                        id="aadhardateissue">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                                <div class="form-group form-box">
                                                                    <label for="PANuploadconversion" class="quesLabel" data-toggle="tooltip" data-placement="top" title="Self-attested copy of PAN of the Applicants">Upload Applicants PAN<span
                                                                            class="text-danger">*</span> <span><i class='bx bx-info-circle'></i></span></label>
                                                                    <input type="file" name="PANuploadconversion"
                                                                        class="form-control" accept="application/pdf"
                                                                        id="PANuploadconversion">
                                                                    <div id="PANuploadconversionError" class="text-danger text-left">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <div class="form-group">
                                                                    <label for="pannumberconversion">Applicants PAN Number<span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" name="pannumberconversion"
                                                                        class="form-control pan_number_format text-uppercase"
                                                                        id="pannumberconversion" maxlength="10" placeholder="PAN Number">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-group">
                                                                    <label for="pancertificatenoconversion">Certificate No.<span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" name="pancertificatenoconversion"
                                                                        class="form-control" id="pancertificatenoconversion"
                                                                        placeholder="Certificate No.">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-group">
                                                                    <label for="pandateissue">Date of Issue<span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="date" name="pandateissue" class="form-control"
                                                                        id="pandateissue">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div id="repeaterLessee" class="position-relative mb-2">

                                                            <div class="position-sticky text-end mt-2"
                                                                style="top: 70px; margin-right: 10px; margin-bottom: 10px; z-index: 9;">
                                                                <!-- <label>Add Co-Applicant</label> -->
                                                                <button type="button"
                                                                    class="btn btn-primary repeater-add-btn fullwidthbtn"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="Add PAN & Aadhar Details of Lessee"><i
                                                                        class="bx bx-plus me-0"></i> Add More</button>
                                                            </div>
                                                            <!-- Repeater Items -->
                                                            <div class="duplicate-field-tab">
                                                                <div class="items" data-group="coapplicant">
                                                                    <!-- Repeater Content -->
                                                                    <div class="item-content mb-2">
                                                                        <div class="row">
                                                                            <div class="col-lg-3">
                                                                                <div class="form-group form-box">
                                                                                    <label for="aadharuploadconversion" class="quesLabel">Upload Lessee Aadhar<span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="file" name="aadharuploadconversion"
                                                                                        class="form-control" accept="application/pdf"
                                                                                        id="aadharuploadconversion">
                                                                                    <div id="aadharuploadconversionError" class="text-danger text-left">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-3">
                                                                                <div class="form-group">
                                                                                    <label for="aadharconversion"
                                                                                        class="form-label">Lessee Aadhar Number</label>
                                                                                    <input type="text" name="aadharconversion"
                                                                                        class="form-control numericOnly" id="aadharconversion"
                                                                                        maxlength="12" placeholder="Aadhar Number">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-3">
                                                                                <div class="form-group">
                                                                                    <label for="aadharcertificatenoconversion">Certificate No.<span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="text" name="aadharcertificatenoconversion"
                                                                                        class="form-control" id="aadharcertificatenoconversion"
                                                                                        placeholder="Certificate No.">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-3">
                                                                                <div class="form-group">
                                                                                    <label for="aadhardateissue">Date of Issue<span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="date" name="aadhardateissue" class="form-control"
                                                                                        id="aadhardateissue">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-lg-3">
                                                                                <div class="form-group form-box">
                                                                                    <label for="PANuploadconversion" class="quesLabel">Upload Lessee PAN<span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="file" name="PANuploadconversion"
                                                                                        class="form-control" accept="application/pdf"
                                                                                        id="PANuploadconversion">
                                                                                    <div id="PANuploadconversionError" class="text-danger text-left">
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-lg-3">
                                                                                <div class="form-group">
                                                                                    <label for="pannumberconversion">Lessee PAN Number<span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="text" name="pannumberconversion"
                                                                                        class="form-control pan_number_format text-uppercase"
                                                                                        id="pannumberconversion" maxlength="10" placeholder="PAN Number">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-3">
                                                                                <div class="form-group">
                                                                                    <label for="pancertificatenoconversion">Certificate No.<span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="text" name="pancertificatenoconversion"
                                                                                        class="form-control" id="pancertificatenoconversion"
                                                                                        placeholder="Certificate No.">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-3">
                                                                                <div class="form-group">
                                                                                    <label for="pandateissue">Date of Issue<span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="date" name="pandateissue" class="form-control"
                                                                                        id="pandateissue">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Repeater Remove Btn -->
                                                                    <div class="repeater-remove-btn">
                                                                        <button type="button" class="btn btn-danger remove-btn px-4"
                                                                            data-toggle="tooltip" data-placement="bottom"
                                                                            title="Click on to delete this form">
                                                                            <i class="fadeIn animated bx bx-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row row-mb-2">
                                            <div class="col-lg-1 icons-flex"></div>
                                            <div class="col-lg-11 selected-docs-field">
                                                <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="form-group form-box">
                                                            <label for="conversionphoto1" class="quesLabel" data-toggle="tooltip" data-placement="top" title="Latest photographs of the property showing the bona fide use">Upload Property Photo<span
                                                                    class="text-danger">*</span> <span><i class='bx bx-info-circle'></i></span></label>
                                                            <input type="file" name="conversionphoto1" class="form-control"
                                                                accept="application/pdf" id="conversionphoto1">
                                                            <div id="conversionphoto1Error" class="text-danger text-left">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group form-box">
                                                            <label for="conversionphotooptional" class="quesLabel" data-toggle="tooltip" data-placement="top" title="Latest photographs of the property showing the bona fide use (Optional)">Upload Property Photo (Optional) <span><i class='bx bx-info-circle'></i></span></label>
                                                            <input type="file" name="conversionphotooptional" class="form-control"
                                                                accept="application/pdf" id="conversionphotooptional">
                                                            <div id="conversionphotooptionalError" class="text-danger text-left">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row row-mb-2">
                                            <div class="col-lg-1 icons-flex"></div>
                                            <div class="col-lg-11 selected-docs-field">
                                                <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="form-group form-box">
                                                            <label for="otherDocConversion" class="quesLabel" data-toggle="tooltip" data-placement="top" title="Any other document that is particularly required in processing of the application">Other Document<span
                                                                    class="text-danger">*</span> <span><i class='bx bx-info-circle'></i></span></label>
                                                            <input type="file" name="otherDocConversion"
                                                                class="form-control" accept="application/pdf"
                                                                id="otherDocConversion">
                                                            <div id="otherDocConversionError" class="text-danger text-left">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="namedocumentconversion">Name of Document<span class="text-danger">*</span></label>
                                                            <input type="text" name="namedocumentconversion" class="form-control alpha-only" id="namedocumentconversion">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="datedocumentconversion">Date of Document<span class="text-danger">*</span></label>
                                                            <input type="date" name="datedocumentconversion" class="form-control alpha-only" id="datedocumentconversion">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="issuingauthConversion">Issuing Authority<span class="text-danger">*</span></label>
                                                            <input type="text" name="issuingauthConversion" class="form-control alpha-only" id="issuingauthConversion">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row row-mb-2">
                                            <div class="col-lg-1 icons-flex"></div>
                                            <div class="col-lg-11 selected-docs-field">
                                                <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="form-group form-box">
                                                            <label for="Affidavits" class="quesLabel" data-toggle="tooltip" data-placement="top" title="Affidavit to the effect that there shall be no court case pending in any court with law">Affidavits<span
                                                                    class="text-danger">*</span> <span><i class='bx bx-info-circle'></i></span></label>
                                                            <input type="file" name="Affidavits" class="form-control"
                                                                accept="application/pdf" id="Affidavits">
                                                            <div id="AffidavitsError" class="text-danger text-left">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="dateattestation">Date of Document<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="date" name="dateattestation"
                                                                class="form-control" id="dateattestation">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="attestedby">Issuing Authority<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="attestedby"
                                                                class="form-control alpha-only" id="attestedby"
                                                                placeholder="Issuing Authority">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <!-- End -->

                        <!-- For Deed of Apartment Step-2 - SOURAV CHAUHAN (27/sep/2024) -->
                        @include('application.deed_of_appartment.include.step-2')
                        <!-- End -->

                        <!-- =========== Begin Land Use Change Div ========== -->
                        @include('application.luc.include.step-2')
                        <!-- ========== End Land Use Change Div =============== -->
                        <div class="row g-3 mt-2">

                            <div class="col-lg-6 col-12">
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" class="btn btn-outline-secondary px-4"
                                        onclick="stepper3.previous()"><i
                                            class='bx bx-left-arrow-alt me-2'></i>Previous</button>

                                    <button type="button" class="btn btn-primary px-4" id="submitbtn2">Next<i
                                            class='bx bx-right-arrow-alt ms-2'></i></button>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12 text-end">
                                <label class="note text-danger text-sm"><strong>Note<span class="text-danger">*</span>:</strong> Upload documents (pdf file, up to 5 MB)</label>
                            </div>
                        </div><!---end row-->

                    </div>

                    <div id="newstep-vl-3" role="tabpane3" class="bs-stepper-pane content fade"
                        aria-labelledby="stepper3trigger3">
                        <h5 class="mb-1" id="finalStateTitle">Additional/Optional Documents</h5>
                        <p class="mb-4" id="finalStateSubtitle">Please Enter Additional Details</p>

                        <!-- mutation step three SOURAV CHAUHAN (20/sep/2024)**********************-->
                        @include('application.mutation.include.mutation-step-three')

                        <!-- Conversion 3 -->
                        <div class="LHConversiondiv" style="display: none;" class="mt-3">
                            <div class="container-fluid">
                                <div class="row g-2">
                                    <div class="col-lg-12">
                                        <div class="row row-mb-2">
                                            <div class="col-lg-1 icons-flex"></div>
                                            <div class="col-lg-11 selected-docs-field">
                                                <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="form-group form-box">
                                                            <label for="AffidavitsConversion" class="quesLabel" data-toggle="tooltip" data-placement="top" title="Affidavit to the effect that the Lessee is alive">Affidavits <span><i class='bx bx-info-circle'></i></span></label>
                                                            <input type="file" name="AffidavitsConversion" class="form-control"
                                                                accept="application/pdf" id="AffidavitsConversion">
                                                            <div id="AffidavitsConversionError" class="text-danger text-left">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="dateattestationConversion">Date of Document</label>
                                                            <input type="date" name="dateattestationConversion"
                                                                class="form-control" id="dateattestationConversion">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="attestedbyConversion">Issuing Authority<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="attestedbyConversion"
                                                                class="form-control alpha-only" id="attestedbyConversion"
                                                                placeholder="Issuing Authority">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row row-mb-2">
                                            <div class="col-lg-1 icons-flex"></div>
                                            <div class="col-lg-11 selected-docs-field">
                                                <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                                                <div class="row pb-2">
                                                    <div class="col-lg-6">
                                                        <div class="d-flex align-items-center">
                                                            <h6 class="mr-5 mb-0">Where the Lease deed is lost?</h6>
                                                            <div class="form-check mr-5">
                                                                <input class="form-check-input" name="DeedLostConversion" type="radio"
                                                                    value="Yes" id="YesDeedLostConversion">
                                                                <label class="form-check-label" for="YesDeedLostConversion">
                                                                    <h6 class="mb-0">Yes</h6>
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" name="DeedLostConversion" type="radio"
                                                                    value="No" id="NoDeedLostConversion" checked>
                                                                <label class="form-check-label" for="NoDeedLostConversion">
                                                                    <h6 class="mb-0">No</h6>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12" id="yesDeedLostDivConversion" style="display: none;">
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group form-box">
                                                                    <label for="AffidavitsConversiondeedlost" class="quesLabel" data-toggle="tooltip" data-placement="top" title="Affidavit for Lease Deed is Lost">Affidavits <span><i class='bx bx-info-circle'></i></span></label>
                                                                    <input type="file" name="AffidavitsConversiondeedlost" class="form-control"
                                                                        accept="application/pdf" id="AffidavitsConversiondeedlost">
                                                                    <div id="AffidavitsConversiondeedlostError" class="text-danger text-left">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="dateattestationConversiondeedlost">Date of Document</label>
                                                                    <input type="date" name="dateattestationConversiondeedlost"
                                                                        class="form-control" id="dateattestationConversiondeedlost">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="attestedbyConversiondeedlost">Issuing Authority</label>
                                                                    <input type="text" name="attestedbyConversiondeedlost"
                                                                        class="form-control alpha-only" id="attestedbyConversiondeedlost"
                                                                        placeholder="Issuing Authority">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group form-box">
                                                                    <label for="publicnoticeenhinLeaseDeed" class="quesLabel">Public
                                                                        Notice in National Daily (English &amp; Hindi)</label>
                                                                    <input type="file" name="publicnoticeenhinLeaseDeed" class="form-control" accept="application/pdf" id="publicnoticeenhinLeaseDeed">
                                                                    <div id="publicnoticeenhinLeaseDeedError" class="text-danger text-left"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="newspapernameengligh">Name of Newspaper
                                                                        (English or Hindi)</label>
                                                                    <input type="text" name="newspapernameengligh" class="form-control alpha-only" id="newspapernameengligh" placeholder="Name of Newspaper (English)">
                                                                </div>
                                                            </div>
                                                            <!-- <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="publicnoticedate">Date of Public Notice<span class="text-danger">*</span></label>
                                                                    <input type="date" name="publicnoticedate" class="form-control" id="publicnoticedate">
                                                                </div>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row row-mb-2">
                                            <div class="col-lg-1 icons-flex"></div>
                                            <div class="col-lg-11 selected-docs-field">
                                                <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                                                <div class="row pb-2">
                                                    <div class="col-lg-6">
                                                        <div class="d-flex align-items-center">
                                                            <h6 class="mr-5 mb-0">Where the Property is mortgaged?</h6>
                                                            <div class="form-check mr-5">
                                                                <input class="form-check-input" name="propertymortgagedConversion" type="radio"
                                                                    value="Yes" id="YesMortgagedConversion">
                                                                <label class="form-check-label" for="YesMortgagedConversion">
                                                                    <h6 class="mb-0">Yes</h6>
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" name="propertymortgagedConversion" type="radio"
                                                                    value="No" id="NoMortgagedConversion" checked>
                                                                <label class="form-check-label" for="NoMortgagedConversion">
                                                                    <h6 class="mb-0">No</h6>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12" id="yesRemarksDivConversion" style="display: none;">
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group form-box">
                                                                    <label for="NOCMortgageeBankDoc" class="quesLabel" data-toggle="tooltip" data-placement="top" title="NOC from Mortgagee Bank/Authority">NOC from Mortgagee Bank/Authority <span><i class='bx bx-info-circle'></i></span></label>
                                                                    <input type="file" name="NOCMortgageeBankDoc" class="form-control"
                                                                        accept="application/pdf" id="NOCMortgageeBankDoc">
                                                                    <div id="NOCMortgageeBankDocError" class="text-danger text-left">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="NOCConversiondateattestationConversion">Date of Document</label>
                                                                    <input type="date" name="NOCConversiondateattestationConversion"
                                                                        class="form-control" id="NOCConversiondateattestationConversion">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="NOCConversionattestedbyConversion">Issuing Authority</label>
                                                                    <input type="text" name="NOCConversionattestedbyConversion"
                                                                        class="form-control alpha-only" id="NOCConversionattestedbyConversion"
                                                                        placeholder="Issuing Authority">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row row-mb-2">
                                            <div class="col-lg-1 icons-flex"></div>
                                            <div class="col-lg-11 selected-docs-field">
                                                <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="form-group form-box">
                                                            <label for="CertifiedCourtOrderDecree" class="quesLabel" data-toggle="tooltip" data-placement="top" title="Certified copies of any court order / decree">Court Order / Decree <span><i class='bx bx-info-circle'></i></span></label>
                                                            <input type="file" name="CertifiedCourtOrderDecree" class="form-control"
                                                                accept="application/pdf" id="CertifiedCourtOrderDecree">
                                                            <div id="CertifiedCourtOrderDecreeError" class="text-danger text-left">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="courtorderdodConversion">Date of Document</label>
                                                            <input type="date" name="courtorderdodConversion"
                                                                class="form-control" id="courtorderdodConversion">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="courtorderattestedbyConversion">Issuing Authority</label>
                                                            <input type="text" name="courtorderattestedbyConversion"
                                                                class="form-control alpha-only" id="courtorderattestedbyConversion"
                                                                placeholder="Issuing Authority">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <div class="row mt-2">
                                    <div class="col-lg-12">
                                        <h6 class="mt-3 mb-0">Terms & Conditions</h6>
                                        <ul class="consent-agree">
                                            <li>Declaration is given by applicant(s) that all facts details given by
                                                him/her are correct and true to his knowledge otherwise his application
                                                will be liable to be rejected. and,</li>
                                            <li>Undertaking that applicant is agreeing with the terms and conditions as
                                                mentioned in <span id="terms-content">substitution/Mutation</span> brochure/manual.</li>
                                            <li>Payment of Non-Refundable Processing Fee</li>
                                        </ul>
                                        <div class="form-check form-group">
                                            <input class="form-check-input" type="checkbox" value="agreeconsent"
                                                id="agreeconsent">
                                            <label class="form-check-label" for="agreeconsent">I agree, all the
                                                information provided by me is accurate to the best of my knowledge. I
                                                take full responsibility for any issues or failures that may arise from
                                                its use.</label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End -->

                        <!-- For Deed of Apartment Step-3 - SOURAV CHAUHAN (27/sep/2024) -->
                        @include('application.deed_of_appartment.include.step-3')
                        <!-- End -->

                        <!-- =========== Begin Land Use Change Div ========== -->
                        @include('application.luc.include.step-3')
                        <!-- ========== End Land Use Change Div =============== -->
                        <div class="row mt-3">
                            <div class="col-lg-6 col-12">
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" class="btn btn-outline-secondary px-4"
                                        onclick="stepper3.previous()"><i
                                            class='bx bx-left-arrow-alt me-2'></i>Previous</button>

                                    <button type="button" class="btn btn-primary px-4" id="btnfinalsubmit">Proceed to
                                        Pay</button>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12 text-end">
                                <label class="note text-danger text-sm"><strong>Note<span class="text-danger">*</span>:</strong> Upload documents (pdf file, up to 5 MB)</label>
                            </div>
                        </div><!---end row-->

                    </div>
                </form>
            </div>
        </div>

    </div>
    <div id="spinnerOverlay" style="display:none;">
        <div class="spinner"></div>
    </div>
</div>

@include('include.alerts.application.change-property')

{{-- Dynamic Element --}}
@endsection
@section('footerScript')
<script src="{{asset('assets/plugins/bs-stepper/js/bs-stepper.min.js')}}"></script>
<script src="{{asset('assets/plugins/bs-stepper/js/main.js')}}"></script>
<script src="{{asset('assets/plugins/form-repeater/repeater.js')}}"></script>
<script src="{{ asset('assets/js/newApplicant.js') }}"></script>
<script>
    $('#appChangeProperty').on('hidden.bs.modal', function() {
        var lastPropertyId = $("input[name='lastPropertyId']").val();
        $('#propertyid').val(lastPropertyId);
    });

    //fetch proerty details when property Id change at creation time - Sourav Chauhan 13/sep/2024
    $('#propertyid').on('change', function() {
        var propertyId = $(this).val();
        fetchPropertyDetails(propertyId,false);
    });

    //fetch property details when editing the application from draft - Sourav Chauhan 26/sep/2024
    $(document).ready(function() {
        var draftApplicationPropertyId = $('#draftApplicationPropertyId').val();
        if (draftApplicationPropertyId) {
            fetchPropertyDetails(draftApplicationPropertyId,true); // Call the function with the property ID
        }
    })


    function fetchPropertyDetails(propertyId,draftApplicationPropertyId) {
    var updateId = $("input[name='updateId']").val();
    $.ajax({
        url: "{{route('appGetPropertyDetails')}}",
        type: "POST",
        dataType: "JSON",
        data: {
            draftApplicationPropertyId: draftApplicationPropertyId,
            propertyId: propertyId,
            updateId: updateId,
            _token: '{{csrf_token()}}'
        },
        success: function (response) {
            if (response.status) {
                if (response.data.status == '952') {
                    $('#propertyStatus').val('Free Hold');
                } else {
                    $('#propertyStatus').val('Lease Hold');
                }
                const items = response.data.items;

                // Clear the existing options first (if necessary)
                $("#applicationType").empty();

                // Get the number of items
                const itemCount = Object.keys(items).length;
                $.each(items, function (key, value) {
                    $("#applicationType").append('<option value="' + key + '">' + value + '</option>');
                });

                // Check the number of items
                if (itemCount == 1) {
                    // If there's only one item, select it
                    $("#applicationType").val(Object.keys(items)[0]).change();// Select the first (and only) item
                } else {
                    // If there are multiple items, add a default "Select" option
                    $("#applicationType").prepend('<option value="" selected>Select</option>');
                }
            } else {
                if (response.data == 'deleteYes') {
                    $('#appModalId').val(updateId);
                    $('#appChangeProperty').modal('show');
                }
            }
        },
        error: function (response) {
            // Handle error
        }
    });
}


   //handle upload file
   function handleFileUpload(file, name, type,processType) {
        const spinnerOverlay = document.getElementById('spinnerOverlay');
        spinnerOverlay.style.display = 'flex';

        const formData = new FormData();
        formData.append('file', file); // Append the file to the FormData object
        formData.append('name', name); // Append the field name
        formData.append('type', type); // Append the field type
        formData.append('processType', processType); // Append the process type
        formData.append('_token', '{{ csrf_token() }}'); // Append the CSRF token
        var propertyId = $('#propertyid').val();
        formData.append('propertyId', propertyId); // Append the Property Id
        var updateId = $("input[name='updateId']").val();
        formData.append('updateId', updateId); // Append the modal Id

        $.ajax({
            url: "{{ route('uploadFile') }}",
            type: "POST",
            data: formData,
            contentType: false, // Prevent jQuery from overriding content type
            processData: false, // Prevent jQuery from processing the data
            success: function(response) {
                if (response.status) {
                    spinnerOverlay.style.display = 'none';
                }
            },
            error: function(response) {
                spinnerOverlay.style.display = 'none'
            }
        });
    }

    $('#confirmApplicationDelete').on('click', function() {
        var confirmationButton = $(this);
        confirmationButton.html('Submitting...').prop('disabled', true);
        var modalId = $("input[name='modalId']").val();
        $.ajax({
            url: "{{route('deleteApplication')}}",
            type: "POST",
            dataType: "JSON",
            data: {
                modalId: modalId,
                _token: '{{csrf_token()}}'
            },
            success: function(response) {
                if (response.status) {
                    window.location.reload();
                } else {}
            },
            error: function(response) {}
        })
    })
</script>
@endsection