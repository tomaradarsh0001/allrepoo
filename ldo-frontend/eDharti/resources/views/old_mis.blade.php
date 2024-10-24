@extends('layouts.app')

@section('title', 'MIS Form')

@section('content')
<link href="{{asset('assets/plugins/bs-stepper/css/bs-stepper.css')}}" rel="stylesheet" />

    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">MIS</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">MIS</li>
                        </ol>
                    </nav>
                </div>
                <!-- <div class="ms-auto"><a href="#" class="btn btn-primary">Button</a></div> -->
            </div>
            <!--end breadcrumb-->

            <!--start stepper one-->

            <div id="stepper1" class="bs-stepper">

            </div>
            <!--end stepper one-->


            <div id="stepper2" class="bs-stepper">
            </div>
            <!--end stepper two-->


            <!--start stepper three-->
            <!-- <h6 class="text-uppercase">MIS</h6> -->
            <hr>
            <div class="card">
                <div class="card-body">
                    <div id="stepper3" class="bs-stepper gap-4 vertical">
                        <div class="bs-stepper-header" role="tablist">
                            <div class="step" data-target="#test-vl-1">
                                <div class="step-trigger" role="tab" id="stepper3trigger1" aria-controls="test-vl-1">
                                    <div class="bs-stepper-circle"><i class='bx bx-user fs-4'></i></div>
                                    <div class="">
                                        <h5 class="mb-0 steper-title">BASIC DETAILS</h5>
                                        <p class="mb-0 steper-sub-title">Enter Your Details</p>
                                    </div>
                                </div>
                            </div>

                            <div class="step" data-target="#test-vl-2">
                                <div class="step-trigger" role="tab" id="stepper3trigger2" aria-controls="test-vl-2">
                                    <div class="bs-stepper-circle"><i class='bx bx-file fs-4'></i></div>
                                    <div class="">
                                        <h5 class="mb-0 steper-title">LEASE DETAILS</h5>
                                        <p class="mb-0 steper-sub-title">Enter Lease Details</p>
                                    </div>
                                </div>
                            </div>

                            <div class="step" data-target="#test-vl-3">
                                <div class="step-trigger" role="tab" id="stepper3trigger3" aria-controls="test-vl-3">
                                    <div class="bs-stepper-circle"><i class='bx bx-file fs-4'></i></div>
                                    <div class="">
                                        <h5 class="mb-0 steper-title">LAND TRANSFER DETAILS</h5>
                                        <p class="mb-0 steper-sub-title">Enter Land Transfer Details</p>
                                    </div>
                                </div>
                            </div>


                            <div class="step" data-target="#test-vl-4">
                                <div class="step-trigger" role="tab" id="stepper3trigger4" aria-controls="test-vl-4">
                                    <div class="bs-stepper-circle"><i class='bx bx-file fs-4'></i></div>
                                    <div class="">
                                        <h5 class="mb-0 steper-title">PROPERTY STATUS DETAILS</h5>
                                        <p class="mb-0 steper-sub-title">Enter Property Status Details</p>
                                    </div>
                                </div>
                            </div>

                            <div class="step" data-target="#test-vl-5">
                                <div class="step-trigger" role="tab" id="stepper3trigger5" aria-controls="test-vl-5">
                                    <div class="bs-stepper-circle"><i class='bx bx-file fs-4'></i></div>
                                    <div class="">
                                        <h5 class="mb-0 steper-title">INSPECTION & DEMAND DETAILS</h5>
                                        <p class="mb-0 steper-sub-title">Enter Inspection & Demand Details</p>
                                    </div>
                                </div>
                            </div>

                            <div class="step" data-target="#test-vl-6">
                                <div class="step-trigger" role="tab" id="stepper3trigger6" aria-controls="test-vl-6">
                                    <div class="bs-stepper-circle"><i class='bx bx-file fs-4'></i></div>
                                    <div class="">
                                        <h5 class="mb-0 steper-title">MISCELLANEOUS DETAILS</h5>
                                        <p class="mb-0 steper-sub-title">Enter Miscellaneous Details</p>
                                    </div>
                                </div>
                            </div>

                            <div class="step" data-target="#test-vl-7">
                                <div class="step-trigger" role="tab" id="stepper3trigger7" aria-controls="test-vl-7">
                                    <div class="bs-stepper-circle"><i class='bx bx-file fs-4'></i></div>
                                    <div class="">
                                        <h5 class="mb-0 steper-title">Latest Contact Details</h5>
                                        <p class="mb-0 steper-sub-title">Enter Latest Contact Details</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bs-stepper-content">
                            <form method="POST" action="{{route('mis.store')}}">
                            @csrf
                                <div id="test-vl-1" role="tabpane3" class="bs-stepper-pane content fade"
                                    aria-labelledby="stepper3trigger1">
                                    <h5 class="mb-1">BASIC DETAILS</h5>
                                    <p class="mb-4">Enter your basic information</p>
                                    <div class="row g-3">
                                        <div class="row align-items-end">
                                            <div class="col-12 col-lg-4">
                                                <label for="PropertyID" class="form-label">Property ID</label>
                                                <input type="text" name="property_id" class="form-control"
                                                    id="PropertyID" placeholder="Property ID">
                                            </div>
                                            <div class="col-12 col-lg-2">
                                                <button class="btn btn-primary">Search</button>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-12">
                                            <div class="d-flex align-items-center">
                                                <h6 class="mr-2 mb-0">Are there more than 1 Property IDs apparently
                                                    visible:</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="is_multiple_prop_id"
                                                        type="checkbox" value="" id="flexCheckChecked" checked>
                                                    <label class="form-check-label" for="flexCheckChecked">
                                                        <h6 class="mb-0">Yes</h6>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="FileNumber" class="form-label">File Number</label>
                                            <input type="text" name="file_number" class="form-control"
                                                id="FileNumber" placeholder="File Number">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="fileNumberGenerated" class="form-label">Computer generated
                                                file no</label>
                                            <input type="text" name="file_number_generated" class="form-control"
                                                id="fileNumberGenerated" placeholder="Generated File No." readonly
                                                disabled>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="colonyName" class="form-label">Colony Name (Present)</label>
                                            <select class="form-select" name="present_colony_name" id="colonyName"
                                                aria-label="Colony Name (Present)">
                                                <option selected>Select Colony Name</option>
                                                @foreach ($colonyList as $colony)
                                                    <option value="{{ $colony->id }}">{{ $colony->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="ColonyNameOld" class="form-label">Colony Name (Old)</label>
                                            <select class="form-select" name="old_colony_name" id="ColonyNameOld"
                                                aria-label="Default select example">
                                                <option selected>Select Colony Name</option>
                                                @foreach ($colonyList as $colony)
                                                    <option value="{{ $colony->id }}">{{ $colony->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="PropertyStatus" class="form-label">Property Status</label>
                                            <select class="form-select" name="property_status" id="PropertyStatus"
                                                aria-label="Default select example">

                                                <option value="" selected>Select Property Status</option>
                                                @foreach ($propertyStatus[0]->items as $status)
                                                    <option value="{{ $status->id }}">{{ $status->item_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="LandType" class="form-label">Land Type</label>
                                            <select name="land_type" class="form-select" id="LandType"
                                                aria-label="Default select example">
                                                <option selected>Select Land Type</option>
                                                @foreach ($landTypes[0]->items as $landType)
                                                    <option value="{{ $landType->id }}">{{ $landType->item_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <!-- <button class="btn btn-primary px-4" onclick="stepper3.next()">Next<i
                                                        class='bx bx-right-arrow-alt ms-2'></i></button> -->
                                            <a href="javascript:void(0)" class="btn btn-primary px-4"
                                                onclick="stepper3.next()">Next<i
                                                    class='bx bx-right-arrow-alt ms-2'></i></a>
                                        </div>
                                    </div><!---end row-->

                                </div>

                                <div id="test-vl-2" role="tabpane3" class="bs-stepper-pane content fade"
                                    aria-labelledby="stepper3trigger2">

                                    <h5 class="mb-1">LEASE DETAILS</h5>
                                    <p class="mb-4">Enter Your Lease Details</p>

                                    <div class="row g-3">
                                        <div class="col-12 col-lg-4">
                                            <label for="TypeLease" class="form-label">Type of Lease</label>
                                            <select class="form-select" id="TypeLease" aria-label="Type of Lease">
                                                <option selected>Select Type of Lease</option>
                                                @foreach ($leaseTypes[0]->items as $leaseType)
                                                    <option value="{{ $leaseType->id }}">{{ $leaseType->item_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="InputUsername" class="form-label">Date of Expiration</label>
                                            <input type="date" class="form-control" id="InputUsername">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="LeaseAllotmentNo" class="form-label">Lease/Allotment
                                                No.</label>
                                            <input type="text" class="form-control" id="LeaseAllotmentNo"
                                                placeholder="Lease/Allotment No.">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="dateexecution" class="form-label">Date of Execution</label>
                                            <input type="date" class="form-control" id="dateexecution">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="dateallotment" class="form-label">Date of Allotment</label>
                                            <input type="date" class="form-control" id="dateallotment">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="blockno" class="form-label">Block No.</label>
                                            <input type="text" class="form-control" id="blockno" maxlength="4"
                                                placeholder="Block No.">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="plotno" class="form-label">Plot No.</label>
                                            <input type="text" class="form-control" id="plotno" maxlength="4"
                                                placeholder="Plot No.">
                                        </div>

                                        <div class="col-12 col-lg-12">
                                            <!-- Repeater Content -->
                                            <div id="repeater">
                                                <div class="col-12 col-lg-12">
                                                    <label for="plotno" class="form-label">In favour of</label>
                                                    <button type="button"
                                                        class="btn btn-outline-primary repeater-add-btn"><i
                                                            class="bx bx-plus me-0"></i></button>
                                                    <!-- <button class="btn btn-primary repeater-add-btn px-4"><i class="fadeIn animated bx bx-plus"></i></button> -->
                                                </div>
                                                <!-- Repeater Items -->
                                                <div class="duplicate-field-tab">
                                                    <div class="items" data-group="test">
                                                        <!-- Repeater Content -->
                                                        <div class="item-content">
                                                            <div class="mb-3">
                                                                <label for="inputName1" class="form-label">Name</label>
                                                                <input type="text" class="form-control"
                                                                    id="inputName1" placeholder="Name" data-name="name">
                                                            </div>
                                                        </div>
                                                        <!-- Repeater Remove Btn -->
                                                        <div class="repeater-remove-btn">
                                                            <button class="btn btn-danger remove-btn px-4">
                                                                <i class="fadeIn animated bx bx-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="presentlyknownsas" class="form-label">Presently Known
                                                As</label>
                                            <input type="text" class="form-control" id="presentlyknownsas">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="presentlyknownsas" class="form-label">Area</label>
                                            <div class="unit-field">
                                                <input type="text" class="form-control unit-input" id="unit">
                                                <select class="form-select unit-dropdown" id="unit"
                                                    aria-label="Select Unit">
                                                    <option selected>Select Unit</option>
                                                    <option value="1">One</option>
                                                    <option value="2">Two</option>
                                                    <option value="3">Three</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="presentlyknownsas" class="form-label">Premium</label>
                                            <div class="unit-field">
                                                <input type="text" class="form-control unit-input" id="unit">
                                                <select class="form-select unit-dropdown" id="unit"
                                                    aria-label="Select Unit">
                                                    <option selected>Select Unit</option>
                                                    <option value="1">One</option>
                                                    <option value="2">Two</option>
                                                    <option value="3">Three</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="presentlyknownsas" class="form-label">Ground Rent</label>
                                            <div class="unit-field">
                                                <input type="text" class="form-control unit-input" id="unit">
                                                <select class="form-select unit-dropdown" id="unit"
                                                    aria-label="Select Unit">
                                                    <option selected>Select Unit</option>
                                                    <option value="1">One</option>
                                                    <option value="2">Two</option>
                                                    <option value="3">Three</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="startdateGR" class="form-label">Start Date of Ground
                                                Rent</label>
                                            <input type="date" class="form-control" id="startdateGR">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="frevisiondateGR" class="form-label">First Revision of GR due
                                                on</label>
                                            <input type="date" class="form-control" id="frevisiondateGR">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="TypeLease" class="form-label">Purpose for which leased/
                                                allotted (As per lease)</label>
                                            <select class="form-select" id="TypeLease" aria-label="Type of Lease">
                                                <option selected>Select</option>
                                                @foreach ($propertyTypes[0]->items as $propertyType)
                                                    <option value="{{ $propertyType->id }}">
                                                        {{ $propertyType->item_name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-12">
                                            <div class="d-flex align-items-center">
                                                <h6 class="mr-2 mb-0">Land Use Change</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="landusechange">
                                                    <label class="form-check-label" for="landusechange">
                                                        <h6 class="mb-0">Yes</h6>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12" id="hideFields">
                                            <div class="row">
                                                <div class="col-12 col-lg-6">
                                                    <label for="TypeLease" class="form-label">Purpose for which
                                                        leased/ allotted (At present)</label>
                                                    <select class="form-select" id="propertyType"
                                                        aria-label="Type of Lease">
                                                        <option selected>Select</option>
                                                        @foreach ($propertyTypes[0]->items as $propertyType)
                                                            <option value="{{ $propertyType->id }}">
                                                                {{ $propertyType->item_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 col-lg-6">
                                                    <label for="TypeLease" class="form-label">Sub-Type (Purpose , at
                                                        present)</label>
                                                    <select class="form-select" id="propertySubType"
                                                        aria-label="Type of Lease">
                                                        <option selected>Select</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center gap-3">
                                                <button class="btn btn-outline-secondary px-4"
                                                    onclick="stepper3.previous()"><i
                                                        class='bx bx-left-arrow-alt me-2'></i>Previous</button>

                                                <!-- <button class="btn btn-primary px-4" onclick="stepper3.next()">Next<i
                                                        class='bx bx-right-arrow-alt ms-2'></i></button> -->
                                                <a href="javascript:void(0)" class="btn btn-primary px-4"
                                                    onclick="stepper3.next()">Next<i
                                                        class='bx bx-right-arrow-alt ms-2'></i></a>
                                            </div>
                                        </div>
                                    </div><!---end row-->

                                </div>

                                <div id="test-vl-3" role="tabpane3" class="bs-stepper-pane content fade"
                                    aria-labelledby="stepper3trigger3">
                                    <h5 class="mb-1">LAND TRANSFER DETAILS</h5>
                                    <p class="mb-4">Enter Land Transfer Details</p>

                                    <div class="row g-3">
                                        <div class="col-12 col-lg-12">
                                            <div class="d-flex align-items-center">
                                                <h6 class="mr-2 mb-0">Transferred</h6>
                                                <div class="form-check mr-2">
                                                    <input class="form-check-input" type="radio" name="transferred"
                                                        value="Yes" id="transferredFormYes">
                                                    <label class="form-check-label" for="transferredFormYes">
                                                        <h6 class="mb-0">Yes</h6>
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="transferred"
                                                        value="No" id="transferredFormNo">
                                                    <label class="form-check-label" for="transferredFormNo">
                                                        <h6 class="mb-0">No</h6>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <!-- Start Duplicate Form -->
                                        <div class="col-lg-12">
                                            <div class="transferred-container" id="transferredContainer">
                                                <div class="row">
                                                    <div class="col-12 col-lg-12">
                                                        <!-- Repeater Content -->
                                                        <div id="repeater2">
                                                            <div class="col-12 col-lg-12">
                                                                <label for="plotno" class="form-label">Add
                                                                    More</label>
                                                                <button type="button"
                                                                    class="btn btn-outline-primary repeater-add-btn"><i
                                                                        class="bx bx-plus me-0"></i></button>
                                                                <!-- <button class="btn btn-primary repeater-add-btn px-4"><i class="fadeIn animated bx bx-plus"></i></button> -->
                                                            </div>
                                                            <!-- Repeater Items -->
                                                            <div class="duplicate-field-tab">
                                                                <div class="items" data-group="test">
                                                                    <!-- Repeater Content -->
                                                                    <div class="item-content">
                                                                        <div class="row mb-3">
                                                                            <div class="col-12 col-lg-4 my-4">
                                                                                <label for="BoardName"
                                                                                    class="form-label">Select
                                                                                    Type</label>
                                                                                <select class="form-select" id="TypeLease"
                                                                                    aria-label="Type of Lease">
                                                                                    <option selected>Select</option>
                                                                                    @foreach ($landTransferTypes[0]->items as $landTransferType)
                                                                                        <option
                                                                                            value="{{ $landTransferType->id }}">
                                                                                            {{ $landTransferType->item_name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-12 col-lg-4 my-4">
                                                                                <label for="transferredDate"
                                                                                    class="form-label">Date</label>
                                                                                <input type="date" class="form-control"
                                                                                    id="transferredDate">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-lg-12">
                                                                                <div id="repeater3">

                                                                                    <div class="col-12 col-lg-12">
                                                                                        <label for="plotno"
                                                                                            class="form-label">1st
                                                                                            Transfer</label>
                                                                                        <button type="button"
                                                                                            class="btn btn-outline-primary repeater-add-btn"><i
                                                                                                class="bx bx-plus me-0"></i></button>
                                                                                        <!-- <button class="btn btn-primary repeater-add-btn px-4"><i class="fadeIn animated bx bx-plus"></i></button> -->
                                                                                    </div>
                                                                                    <!-- Repeater Items -->
                                                                                    <div class="duplicate-field-tab">
                                                                                        <div class="items"
                                                                                            data-group="test">
                                                                                            <!-- Repeater Content -->
                                                                                            <div class="item-content row">
                                                                                                <div class="col-lg-4 mb-3">
                                                                                                    <label for="name"
                                                                                                        class="form-label">Name</label>
                                                                                                    <input type="text"
                                                                                                        class="form-control"
                                                                                                        id="name"
                                                                                                        placeholder="Name"
                                                                                                        data-name="name">
                                                                                                </div>
                                                                                                <div class="col-lg-4 mb-3">
                                                                                                    <label for="age"
                                                                                                        class="form-label">Age</label>
                                                                                                    <input type="number"
                                                                                                        class="form-control"
                                                                                                        id="age"
                                                                                                        placeholder="Age"
                                                                                                        data-name="age">
                                                                                                </div>
                                                                                                <div class="col-lg-4 mb-3">
                                                                                                    <label for="share"
                                                                                                        class="form-label">Share</label>
                                                                                                    <input type="number"
                                                                                                        class="form-control"
                                                                                                        id="share"
                                                                                                        placeholder="Share"
                                                                                                        data-name="share">
                                                                                                </div>
                                                                                                <div class="col-lg-4 mb-3">
                                                                                                    <label for="pannumber"
                                                                                                        class="form-label">PAN
                                                                                                        Number</label>
                                                                                                    <input type="text"
                                                                                                        class="form-control text-uppercase"
                                                                                                        id="pannumber"
                                                                                                        placeholder="PAN Number"
                                                                                                        data-name="pannumber">
                                                                                                </div>
                                                                                                <div class="col-lg-4 mb-3">
                                                                                                    <label
                                                                                                        for="aadharnumber"
                                                                                                        class="form-label">Aadhar
                                                                                                        Number</label>
                                                                                                    <input type="text"
                                                                                                        class="form-control text-uppercase"
                                                                                                        id="aadharnumber"
                                                                                                        placeholder="Aadhaar Number"
                                                                                                        data-name="aadharnumber"
                                                                                                        maxlength="12">
                                                                                                </div>
                                                                                            </div>
                                                                                            <!-- Repeater Remove Btn -->
                                                                                            <div
                                                                                                class="repeater-remove-btn">
                                                                                                <button
                                                                                                    class="btn btn-danger remove-btn px-4">
                                                                                                    <i
                                                                                                        class="fadeIn animated bx bx-trash"></i>
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Repeater Remove Btn -->
                                                                    <div class="repeater-remove-btn">
                                                                        <button class="btn btn-danger remove-btn px-4">
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
                                        <!-- Duplicate Form End -->

                                        <div class="col-12 mt-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <button class="btn btn-outline-secondary px-4"
                                                    onclick="stepper3.previous()"><i
                                                        class='bx bx-left-arrow-alt me-2'></i>Previous</button>
                                                <!-- <button class="btn btn-primary px-4" onclick="stepper3.next()">Next<i
                                                        class='bx bx-right-arrow-alt ms-2'></i></button> -->
                                                <a href="javascript:void(0)" class="btn btn-primary px-4"
                                                    onclick="stepper3.next()">Next<i
                                                        class='bx bx-right-arrow-alt ms-2'></i></a>
                                            </div>
                                        </div>
                                    </div><!---end row-->

                                </div>

                                <div id="test-vl-4" role="tabpane3" class="bs-stepper-pane content fade"
                                    aria-labelledby="stepper3trigger4">
                                    <h5 class="mb-1">PROPERTY STATUS DETAILS</h5>
                                    <p class="mb-4">Please enter Property Status Details</p>

                                    <div class="row g-3">
                                        <div class="col-12 col-lg-12">
                                            <div class="d-flex align-items-center">
                                                <h6 class="mr-2 mb-0">Free Hold (F/H)</h6>
                                                <div class="form-check mr-2">
                                                    <input class="form-check-input" type="radio" name="freeHold"
                                                        value="Yes" id="freeHoldFormYes">
                                                    <label class="form-check-label" for="freeHoldFormYes">
                                                        <h6 class="mb-0">Yes</h6>
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="freeHold"
                                                        value="No" id="freeHoldFormNo">
                                                    <label class="form-check-label" for="freeHoldFormNo">
                                                        <h6 class="mb-0">No</h6>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="freehold-container" id="freeHoldContainer"
                                                style="display: none;">
                                                <div class="col-12 col-lg-4">
                                                    <label for="ConveyanceDate" class="form-label">Date of
                                                        Conveyance Deed</label>
                                                    <input type="date" class="form-control" id="ConveyanceDate">
                                                </div>
                                                <div class="col-12 col-lg-12 mt-4">
                                                    <!-- Repeater Content -->
                                                    <div id="repeater4">
                                                        <div class="col-12 col-lg-12">
                                                            <label for="plotno" class="form-label">In favour
                                                                of</label>
                                                            <button type="button"
                                                                class="btn btn-outline-primary repeater-add-btn"><i
                                                                    class="bx bx-plus me-0"></i></button>
                                                            <!-- <button class="btn btn-primary repeater-add-btn px-4"><i class="fadeIn animated bx bx-plus"></i></button> -->
                                                        </div>
                                                        <!-- Repeater Items -->
                                                        <div class="duplicate-field-tab">
                                                            <div class="items" data-group="test">
                                                                <!-- Repeater Content -->
                                                                <div class="item-content">
                                                                    <div class="mb-3">
                                                                        <label for="inputName1"
                                                                            class="form-label">Name</label>
                                                                        <input type="text" class="form-control"
                                                                            id="inputName1" placeholder="Name"
                                                                            data-name="name">
                                                                    </div>
                                                                </div>
                                                                <!-- Repeater Remove Btn -->
                                                                <div class="repeater-remove-btn">
                                                                    <button class="btn btn-danger remove-btn px-4">
                                                                        <i class="fadeIn animated bx bx-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-12">
                                            <div class="d-flex align-items-center">
                                                <h6 class="mr-2 mb-0">Land Type: Vacant</h6>
                                                <div class="form-check mr-2">
                                                    <input class="form-check-input" type="radio" name="landType"
                                                        value="Yes" id="landTypeFormYes">
                                                    <label class="form-check-label" for="landTypeFormYes">
                                                        <h6 class="mb-0">Yes</h6>
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="landType"
                                                        value="No" id="landTypeFormNo">
                                                    <label class="form-check-label" for="landTypeFormNo">
                                                        <h6 class="mb-0">No</h6>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="landType-container row" id="landTypeContainer"
                                                style="display: none;">
                                                <div class="row">
                                                    <div class="col-12 col-lg-6">
                                                        <label for="ConveyanceDate" class="form-label">In possession
                                                            of</label>
                                                        <select class="form-select" id="TypeLease"
                                                            aria-label="Type of Lease">
                                                            <option selected>Select</option>
                                                            <option value="1">DDA</option>
                                                            <option value="2">NDMC</option>
                                                            <option value="3">MCD</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-lg-6">
                                                        <label for="dateTransfer" class="form-label">Date of
                                                            Transfer</label>
                                                        <input type="date" class="form-control" id="dateTransfer">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-12">
                                            <div class="d-flex align-items-center">
                                                <h6 class="mr-2 mb-0">Land Type : Others</h6>
                                                <div class="form-check mr-2">
                                                    <input class="form-check-input" type="radio" name="landTypeOthers"
                                                        value="Yes" id="landTypeFormOthersYes">
                                                    <label class="form-check-label" for="landTypeFormOthersYes">
                                                        <h6 class="mb-0">Yes</h6>
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="landTypeOthers"
                                                        value="No" id="landTypeFormOthersNo">
                                                    <label class="form-check-label" for="landTypeFormOthersNo">
                                                        <h6 class="mb-0">No</h6>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="landType-container row" id="landTypeOthersContainer"
                                                style="display: none;">

                                                <div class="col-12 col-lg-4">
                                                    <label for="remarks" class="form-label">Remarks</label>
                                                    <input type="text" class="form-control" id="remarks">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <button class="btn btn-outline-secondary px-4"
                                                    onclick="stepper3.previous()"><i
                                                        class='bx bx-left-arrow-alt me-2'></i>Previous</button>
                                                        <!-- <button class="btn btn-primary px-4" onclick="stepper3.next()">Next<i
                                                    class='bx bx-right-arrow-alt ms-2'></i></button> -->
                                                    <a href="javascript:void(0)" class="btn btn-primary px-4" onclick="stepper3.next()">Next<i
                                                    class='bx bx-right-arrow-alt ms-2'></i></a>
                                            </div>
                                        </div>
                                    </div><!---end row-->

                                </div>

                                <div id="test-vl-5" role="tabpane3" class="bs-stepper-pane content fade"
                                    aria-labelledby="stepper3trigger5">
                                    <h5 class="mb-1">INSPECTION & DEMAND DETAILS</h5>
                                    <p class="mb-4">Please enter Inspection & Demand Details</p>

                                    <div class="row g-3">
                                        <div class="col-12 col-lg-12">
                                            <label for="lastInsReport" class="form-label">Date of Last Inspection
                                                Report</label>
                                            <input type="date" class="form-control" id="lastInsReport">
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="LastDemandLetter" class="form-label">Date of Last Demand
                                                Letter</label>
                                            <input type="date" class="form-control" id="LastDemandLetter">
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="DemandID" class="form-label">Demand ID</label>
                                            <input type="text" class="form-control" id="DemandID"
                                                placeholder="Demand ID">
                                        </div>
                                        <div class="col-12 col-lg-12">
                                            <label for="amountDemandLetter" class="form-label">Amount of Last Demand
                                                Letter</label>
                                            <input type="text" class="form-control" id="amountDemandLetter">
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="LastAmount" class="form-label">Last Amount Received</label>
                                            <input type="text" class="form-control" id="LastAmount"
                                                placeholder="Last Amount Received">
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="lastamountdate" class="form-label">Date</label>
                                            <input type="date" class="form-control" id="lastamountdate">
                                        </div>
                                        <div class="col-12 mt-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <button class="btn btn-outline-secondary px-4"
                                                    onclick="stepper3.previous()"><i
                                                        class='bx bx-left-arrow-alt me-2'></i>Previous</button>
                                                        <!-- <button class="btn btn-primary px-4" onclick="stepper3.next()">Next<i
                                                    class='bx bx-right-arrow-alt ms-2'></i></button> -->
                                                    <a href="javascript:void(0)" class="btn btn-primary px-4" onclick="stepper3.next()">Next<i
                                                    class='bx bx-right-arrow-alt ms-2'></i></a>
                                            </div>
                                        </div>
                                    </div><!---end row-->

                                </div>

                                <div id="test-vl-6" role="tabpane3" class="bs-stepper-pane content fade"
                                    aria-labelledby="stepper3trigger6">
                                    <h5 class="mb-1">MISCELLANEOUS DETAILS</h5>
                                    <p class="mb-4">Please enter Miscellaneous Details</p>

                                    <div class="row g-3">
                                        <div class="col-12 col-lg-12">
                                            <div class="d-flex align-items-center">
                                                <h6 class="mr-2 mb-0">GR Revised Ever</h6>
                                                <div class="form-check mr-2">
                                                    <input class="form-check-input" type="radio" name="GR"
                                                        value="Yes" id="GRFormYes">
                                                    <label class="form-check-label" for="GRFormYes">
                                                        <h6 class="mb-0">Yes</h6>
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="GR"
                                                        value="No" id="GRFormNo">
                                                    <label class="form-check-label" for="GRFormNo">
                                                        <h6 class="mb-0">No</h6>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="GR-container" id="GRContainer" style="display: none;">
                                                <div class="col-12 col-lg-4">
                                                    <label for="GRrevisedDate" class="form-label">Date</label>
                                                    <input type="date" class="form-control" id="GRrevisedDate">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-12">
                                            <div class="d-flex align-items-center">
                                                <h6 class="mr-2 mb-0">Supplementary Lease Deed Executed</h6>
                                                <div class="form-check mr-2">
                                                    <input class="form-check-input" type="radio" name="Supplementary"
                                                        value="Yes" id="SupplementaryFormYes">
                                                    <label class="form-check-label" for="SupplementaryFormYes">
                                                        <h6 class="mb-0">Yes</h6>
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="Supplementary"
                                                        value="No" id="SupplementaryFormNo">
                                                    <label class="form-check-label" for="SupplementaryFormNo">
                                                        <h6 class="mb-0">No</h6>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="Supplementary-container row" id="SupplementaryContainer"
                                                style="display: none;">
                                                <div class="row">
                                                    <div class="col-12 col-lg-6">
                                                        <label for="SupplementaryDate" class="form-label">Date</label>
                                                        <input type="date" class="form-control"
                                                            id="SupplementaryDate">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-12">
                                            <div class="d-flex align-items-center">
                                                <h6 class="mr-2 mb-0">Re-entered</h6>
                                                <div class="form-check mr-2">
                                                    <input class="form-check-input" type="radio" name="Reentered"
                                                        value="Yes" id="ReenteredFormYes">
                                                    <label class="form-check-label" for="ReenteredFormYes">
                                                        <h6 class="mb-0">Yes</h6>
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="Reentered"
                                                        value="No" id="ReenteredFormNo">
                                                    <label class="form-check-label" for="ReenteredFormNo">
                                                        <h6 class="mb-0">No</h6>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="Reentered-container row" id="ReenteredContainer"
                                                style="display: none;">

                                                <div class="col-12 col-lg-4">
                                                    <label for="reentryDate" class="form-label">Date of
                                                        re-entry</label>
                                                    <input type="date" class="form-control" id="reentryDate">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <button class="btn btn-outline-secondary px-4"
                                                    onclick="stepper3.previous()"><i
                                                        class='bx bx-left-arrow-alt me-2'></i>Previous</button>
                                                        <!-- <button class="btn btn-primary px-4" onclick="stepper3.next()">Next<i
                                                    class='bx bx-right-arrow-alt ms-2'></i></button> -->
                                                    <a href="javascript:void(0)" class="btn btn-primary px-4" onclick="stepper3.next()">Next<i
                                                    class='bx bx-right-arrow-alt ms-2'></i></a>
                                            </div>
                                        </div>
                                    </div><!---end row-->

                                </div>

                                <div id="test-vl-7" role="tabpane3" class="bs-stepper-pane content fade"
                                    aria-labelledby="stepper3trigger7">
                                    <h5 class="mb-1">Latest Contact Details</h5>
                                    <p class="mb-4">Please enter Latest Contact Details</p>

                                    <div class="row g-3">
                                        <div class="col-12 col-lg-4">
                                            <label for="address" class="form-label">Address</label>
                                            <input type="text" class="form-control" id="address"
                                                placeholder="Address">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="phoneno" class="form-label">Phone No.</label>
                                            <input type="text" class="form-control" id="phoneno"
                                                placeholder="Phone No.">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="Email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="Email"
                                                placeholder="Email">
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center gap-3">
                                                <button class="btn btn-primary px-4" onclick="stepper3.previous()"><i
                                                        class='bx bx-left-arrow-alt me-2'></i>Previous</button>
                                                <button class="btn btn-success px-4" type="submit">Submit</button>
                                            </div>
                                        </div>
                                    </div><!---end row-->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--end stepper three-->


        </div>
    </div>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#propertyType').on('change', function() {
                var idPropertyType = this.value;
                $("#propertySubType").html('');
                $.ajax({
                    url: "{{ route('prpertySubTypes') }}",
                    type: "POST",
                    data: {
                        property_type_id: idPropertyType,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {

                        $('#propertySubType').html('<option value="">Select Sub Type</option>');
                        $.each(result, function(key, value) {
                            $("#propertySubType").append('<option value="' + value
                                .id + '">' + value.item_name + '</option>');
                        });
                    }
                });
            });
        });
    </script>

<script src="{{asset('assets/plugins/bs-stepper/js/bs-stepper.min.js')}}"></script>
<script src="{{asset('assets/plugins/bs-stepper/js/main.js')}}"></script>
<script src="{{asset('assets/plugins/form-repeater/repeater.js')}}"></script>
<script src="{{asset('assets/plugins/form-repeater/repeaterChild.js')}}"></script>
<script src="{{asset('assets/js/mis.js')}}"></script>

<script>
/* Create Repeater */
        $("#repeater").createRepeater({
			showFirstItemToDefault: true,
		});

		$("#repeater2").createRepeater({
			showFirstItemToDefault: true,
		});

		$("#repeater3").createRepeater({
			showFirstItemToDefault: true,
		});

		$("#repeater4").createRepeater({
			showFirstItemToDefault: true,
		});
</script>

@endsection
