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
    z-index: 1000; /* Ensure it covers other content */
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
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
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
                    <input type="hidden" value="0" name="updateId">
                    <div id="newstep-vl-1" role="tabpane3" class="bs-stepper-pane content fade"
                        aria-labelledby="stepper3trigger1">
                        <h5 class="mb-1">FILL APPLICATION DETAILS</h5>
                        <p class="mb-4">Enter Your Application Information</p>
                        <!-- begin -->
                        <div class="radio-buttons-0">
                            <div class="row">
                                <div class="col-lg-4 col-12">
                                    <div class="form-group">
                                        <label for="gender" class="form-label">Property ID</label>
                                        <select class="form-select" name="propertyid" id="propertyid">
                                            <option value="">Select</option>
                                            @foreach($userProperties as $userProperty)
                                            <option value="{{$userProperty}}">{{$userProperty}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger" id="propertyIdError"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-6">
                                    <label for="statusapplicant" class="form-label">Property Status</label>
                                    <label for="freehold" class="custom-radio"
                                        style="margin-bottom: 12px; margin-top: 0px; padding: 8px 10px; border-radius: 0px;">
                                        <div class="radio-btn">
                                            <div class="newcontent">
                                                <div class="profile-card">
                                                    <h4>Free Hold</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="radio" name="applicationStatus" value=952 id="freehold"
                                            class="radio_input_0">
                                    </label>
                                    <div class="text-danger" id="applicationStatusError" style="margin-top: -.5rem;">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-6">
                                    <label for="statusapplicant" class="form-label" style="visibility: hidden;">Status
                                        of applicant</label>
                                    <label for="leasehold" class="custom-radio"
                                        style="margin-bottom: 12px; margin-top: 0px; padding: 8px 10px; border-radius: 0px;">
                                        <div class="radio-btn">
                                            <div class="newcontent">
                                                <div class="profile-card">
                                                    <h4>Lease Hold</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="radio" name="applicationStatus" value=951 id="leasehold"
                                            class="radio_input_0">
                                    </label>

                                </div>

                            </div>

                        </div>

                        <div id="freeHoldDiv" style="display: none;">
                            <!-- <h5 class="form_section_title mt-2">Free Hold</h5> -->
                            <div class="container-fluid">
                                <div class="row less-padding-input pt-4">
                                    <div class="col-lg-3 col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" name="freehold_options" type="checkbox"
                                                value="NOC" id="NOC">
                                            <label class="form-check-label" for="NOC">
                                                <h6 class="mb-0">NOC</h6>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" name="freehold_options" type="checkbox"
                                                value="Substitution/Mutation" id="FHSubstitutionMutation">
                                            <label class="form-check-label" for="FHSubstitutionMutation">
                                                <h6 class="mb-0">Substitution/Mutation</h6>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" name="freehold_options" type="checkbox"
                                                value="Property Certificate" id="PropertyCertificate">
                                            <label class="form-check-label" for="PropertyCertificate">
                                                <h6 class="mb-0">Property Certificate</h6>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="text-danger" id="freeHoldOptionsStatusError"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="leaseHoldDiv" style="display: none;">
                            <!-- <h5 class="form_section_title mt-2">Lease Hold</h5> -->
                            <div class="container-fluid">
                                <div class="row row-cols-auto less-padding-input justify-space-between pt-4">
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" name="leasehold_options" type="checkbox"
                                                value="Sale permission" id="Salepermission">
                                            <label class="form-check-label" for="Salepermission">
                                                <h6 class="mb-0">Sale permission</h6>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" name="leasehold_options" type="checkbox"
                                                value="Substitution/Mutation" id="LHSubstitutionMutation">
                                            <label class="form-check-label" for="LHSubstitutionMutation">
                                                <h6 class="mb-0">Substitution/Mutation</h6>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" name="leasehold_options" type="checkbox"
                                                value="Property Certificate" id="LHPropertyCertificate">
                                            <label class="form-check-label" for="LHPropertyCertificate">
                                                <h6 class="mb-0">Property Certificate</h6>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" name="leasehold_options" type="checkbox"
                                                value="Conversion" id="Conversion">
                                            <label class="form-check-label" for="Conversion">
                                                <h6 class="mb-0">Conversion</h6>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" name="leasehold_options" type="checkbox"
                                                value="Deed of Apartment" id="deedofapartment">
                                            <label class="form-check-label" for="deedofapartment">
                                                <h6 class="mb-0">Deed of Apartment</h6>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" name="leasehold_options" type="checkbox"
                                                value="Land Use Change" id="landusechange">
                                            <label class="form-check-label" for="landusechange">
                                                <h6 class="mb-0">LUC</h6>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="text-danger" id="leaseHoldOptionsStatusError"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End -->
                        <div class="landusechangeDiv mt-3" style="display: none;">
                            <div class="container-fluid">
                                <div class="row g-3 mb-2 mt-3">
                                    <div class="col-lg-12">
                                        <div class="part-title mb-2">
                                            <h5>Property Details</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <label for="luclocality" class="form-label">Select Locality</label>
                                            <select name="luclocality" id="luclocality" class="form-select" disabled>
                                                <option value="">Select Locality</option>
                                                <option value="TEHAR 1" selected>TEHAR - I</option>
                                                <option value="YORK ROAD">YORK ROAD</option>
                                            </select>
                                            <div id="luclocalityError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <label for="lucblockno" class="form-label">Block No.</label>
                                            <input type="text" name="lucblockno" id="lucblockno"
                                                class="form-control alphaNum-hiphenForwardSlash" placeholder="Block No."
                                                readonly>
                                            <div id="lucblocknoError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <label for="lucplotno" class="form-label">Plot No.</label>
                                            <input type="text" name="lucplotno" id="lucplotno"
                                                class="form-control plotNoAlpaMix" placeholder="Property/Plot No."
                                                readonly>
                                            <div id="lucplotnoError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <label for="lucknownas" class="form-label">Known As (Optional)</label>
                                            <input type="text" name="lucknownas" id="lucknownas"
                                                class="form-control" placeholder="Knowns As (Optional)" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <label for="lucarea" class="form-label">Area</label>
                                            <input type="text" name="lucarea" id="lucarea"
                                                class="form-control alpha-only" placeholder="Area" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <label for="leasetype" class="form-label">Lease Type</label>
                                            <input type="text" name="leasetype" id="leasetype"
                                                class="form-control alpha-only" placeholder="Lease Type" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 mb-2 mt-3">
                                    <div class="col-lg-12">
                                        <div class="part-title mb-2">
                                            <h5>Land Use Change Details</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                            <label for="lucpropertytype" class="form-label">Property Type</label>
                                            <select name="lucpropertytype" id="lucpropertytype" class="form-select"
                                                disabled>
                                                <option value="">Select</option>
                                                <option value="47" selected>Residential</option>
                                                <option value="48">Commercial</option>
                                                <option value="49">Institutional</option>
                                                <option value="72">Mixed</option>
                                                <option value="1353">Others</option>
                                            </select>
                                            <div id="lucpropertytypeError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                            <label for="lucpropertychangetouse" class="form-label">Property Change to
                                                Use<span class="text-danger">*</span> </label>
                                            <select name="lucpropertychangetouse" id="lucpropertychangetouse"
                                                class="form-select">
                                                <option value="">Select</option>
                                                <option value="47">Residential</option>
                                                <option value="48">Commercial</option>
                                                <option value="49">Institutional</option>
                                                <option value="72">Mixed</option>
                                                <option value="1353">Others</option>
                                            </select>
                                            <div id="lucpropertychangetouseError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- substitution/mutation application form SOURAV CHAUHAN (13sep/2024)-->
                        @include('application.mutation.include.mutation')

                        <!-- <div id="LHSubstitutionMutationdiv" class="mt-3" style="display: none;">
                                Lease Hold Form will soon..
                             </div> -->

                        <div class="LHConversiondiv" style="display: none;" class="mt-3">
                            <div class="container-fluid">
                                <div class="row g-3 mb-2 mt-3">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="statusofapplicant">Status of Applicant</label>
                                            <select class="form-select" name="statusofapplicant" id="statusofapplicant">
                                                <option value="">Select</option>
                                                <option value="Sale-Deed Holder">Sale-Deed Holder</option>
                                                <option value="Decree Holder">Decree Holder</option>
                                                <option value="Court Order">Court Order</option>
                                                <option value="WILL Beneficiary/RD Beneficiary">WILL Deneficiary/RD
                                                    Beneficiary</option>
                                                <option value="Legal Heir">Legal heir</option>
                                                <option value="Gift Beneficiary">Gift Beneficiary</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
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
                                            <label for="aadhar" class="form-label">Aadhar</label>
                                            <input type="text" name="aadhar" class="form-control numericOnly"
                                                id="aadhar" maxlength="12" placeholder="Aadhar Number" readonly>
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
                                                                        class="form-label">Aadhar</label>
                                                                    <input type="text" name="aadhar"
                                                                        class="form-control numericOnly" id="aadhar"
                                                                        maxlength="12" placeholder="Aadhar Number"
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
                                            <input type="text" name="bookno" class="form-control numericOnly"
                                                id="bookno" placeholder="Book No.">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="volumeno" class="form-label">Volume No.</label>
                                            <input type="text" name="volumeno" class="form-control numericOnly"
                                                id="volumeno" placeholder="Volume No.">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="pageno" class="form-label">Page No.</label>
                                            <input type="text" name="pageno" class="form-control numericOnly"
                                                id="pageno" placeholder="Page No.">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="regdate" class="form-label">Regn. Date.</label>
                                            <input type="date" name="regdate" class="form-control numericOnly"
                                                id="regdate">
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
                                                    <label for="casenosubmut" class="form-label">Case No.<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="casenosubmut"
                                                        class="form-control alphaNum-hiphenForwardSlash"
                                                        id="casenosubmut" placeholder="Case No." readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- row end -->

                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-12 col-lg-4">
                                <button type="button" class="btn btn-primary px-4" id="submitbtn1">Next<i
                                        class='bx bx-right-arrow-alt ms-2'></i></button>
                            </div>
                        </div>
                        <!---end row-->
                    </div>

                     <!-- mutation step two SOURAV CHAUHAN (20/sep/2024)**********************-->
                     @include('application.mutation.include.mutation-step-second')
                   

                    <!-- mutation step three SOURAV CHAUHAN (20/sep/2024)**********************-->
                    @include('application.mutation.include.mutation-step-three')
                    
                </form>
            </div>
        </div>
    </div>

    <!-- loader for image upload -->
    <div id="spinnerOverlay" style="display:none;">
        <div class="spinner"></div>
    </div>
</div>

{{-- Dynamic Element --}}
@endsection
@section('footerScript')
<script src="{{asset('assets/plugins/bs-stepper/js/bs-stepper.min.js')}}"></script>
<script src="{{asset('assets/plugins/bs-stepper/js/main.js')}}"></script>
<script src="{{asset('assets/plugins/form-repeater/repeater.js')}}"></script>
<script src="{{ asset('assets/js/newApplicant.js') }}"></script>
<script>
    //fetch proerty details - Sourav Chauhan 13/sep/2024
    $('#propertyid').on('change', function () {
        var propertyId = $(this).val();
        $.ajax({
            url: "{{route('getPropertyDetails')}}",
            type: "POST",
            dataType: "JSON",
            data: {
                propertyId: propertyId,
                _token: '{{csrf_token()}}'
            },
            success: function (response) {
                console.log(response);
                if (response.status) {
                    if (response.data.status == '952') {
                        $('#freehold').prop('checked', true);
                        $('#freehold').prop('checked', true).change();
                    } else {
                        $('#leasehold').prop('checked', true);
                        $('#leasehold').prop('checked', true).change();
                    }
                }
            },
            error: function (response) {
                console.log(response);
            }

        })
    })


    //fetch user details - Sourav Chauhan 13/sep/2024
    $('#FHSubstitutionMutation').on('change', function () {
        if ($(this).is(':checked')) {
            $.ajax({
                url: "{{route('fetchUserDetails')}}",
                type: "GET",
                dataType: "JSON",
                data: {
                    _token: '{{csrf_token()}}'
                },
                success: function (response) {
                    if (response.status) {
                        console.log(response);
                        $('#mutNameApp').val(response.data.user.name)
                        $('#mutGenderApp').val(response.data.details.gender)
                        $('#mutAgeApp').val(response.data.user.name)
                        $('#mutprefixApp').html(response.data.details.so_do_spouse)
                        $('#mutFathernameApp').val(response.data.details.second_name)
                        $('#mutAadharApp').val(response.data.details.aadhar_card)
                        $('#mutPanApp').val(response.data.details.pan_card)
                        $('#mutMobilenumberApp').val(response.data.user.mobile_no)

                    }

                },
                error: function (response) {
                    console.log(response);
                }

            })
        }
    })

    //handle upload file
    function handleFileUpload(file, name, type) {
        const spinnerOverlay = document.getElementById('spinnerOverlay');
        spinnerOverlay.style.display = 'flex';

        const formData = new FormData();
        formData.append('file', file); // Append the file to the FormData object
        formData.append('name', name); // Append the field name
        formData.append('type', type); // Append the field type
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
            success: function (response) {
                if (response.status) {
                    spinnerOverlay.style.display = 'none';
                    console.log(response);
                }
            },
            error: function (response) {
                spinnerOverlay.style.display = 'none';
                console.log(response);
            }
        });
    }
</script>
@endsection