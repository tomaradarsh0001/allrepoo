@extends('layouts.app')

@section('title', 'Applicant Property History')

@section('content')
<link href="{{ asset('assets/plugins/bs-stepper/css/bs-stepper.css') }}" rel="stylesheet" />
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
        <form method="POST" action="#" enctype="multipart/form-data">
            @csrf

            @if (isset($application))
            <input type="hidden" id="updateId" name="updateId" value="{{ $application->id }}">
            <input type="hidden" id="draftApplicationPropertyId" value="{{ $application->old_property_id }}">
            @else
            <input type="hidden" value="0" name="updateId">
            <input type="hidden" value="0" name="lastPropertyId">
            @endif
            <div class="new-app-form">
                <h5 class="mb-1">FILL APPLICATION DETAILS</h5>
                <p class="mb-4">Enter Your Application Information</p>
                <!-- begin -->
                <div class="radio-buttons-0">
                    <div class="row">
                        <div class="col-lg-3 col-12">
                            <div class="form-group">
                                <label for="gender" class="form-label">Property ID<span class="text-danger">*</span></label>
                                @if (isset($application))
                                <input type="text" class="form-control alpha-only" name="propertyid"
                                    id="propertyid" value="{{ $application->old_property_id }}" readonly>
                                @else
                                <select class="form-select" name="propertyid" id="propertyid">
                                    <option value="">Select</option>
                                    @foreach ($userProperties as $id => $userProperty)
                                    <option value="{{ $id }}">{{ $id }} ({{$userProperty}})</option>
                                    @endforeach
                                </select>
                                @endif
                                <div class="text-danger" id="propertyIdError"></div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="form-group">
                                <label for="statusapplicant" class="form-label">Property Status</label>
                                <input type="text" name="applicationStatus" class="form-control alpha-only"
                                    id="propertyStatus" placeholder="Property Status" readonly>
                                <div id="propertyStatusError" class="text-danger text-left"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group form-box">
                                <label for="applicationType" class="form-label">Application Type<span class="text-danger">*</span></label>
                                <select name="applicationType" id="applicationType" class="form-select"
                                    id="applicationType">

                                </select>
                                <div id="applicationTypeError" class="text-danger text-left"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="statusofapplicant" class="form-label">Status of Applicant<span class="text-danger">*</span></label>
                                <select class="form-select" name="mutStatusOfApplicant" id="statusofapplicant">
                                    <option value="">Select</option>
                                    @foreach ($applicantStatus[0]->items as $propertyType)
                                    <option value="{{ $propertyType->id }}"
                                        {{ isset($application) && $application->status_of_applicant == $propertyType->id ? 'selected' : '' }}>
                                        {{ $propertyType->item_name }}
                                    </option>
                                    @endforeach
                                </select>
                                <div id="statusofapplicantError" class="text-danger text-left"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- For showing property details in all applications - SOURAV CHAUHAN -(10/oct/2024) -->
                <div id="applicationPropertyDetails"></div>
                <!-- End -->
            </div>

            <!-- Stepper3 Starts => Substitution/Mutation by Diwakar Sinha, 30-09-2024 -->
            <div id="stepper3" class="stepper bs-stepper gap-4 vertical FHSubstitutionMutationdiv"
                style="display: none;" data-linear="true" data-animation="true">
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
                    <div id="newstep-vl-1" role="tabpane3" class="bs-stepper-pane content fade"
                        aria-labelledby="stepper3trigger1">


                        <!-- substitution/mutation application form - SOURAV CHAUHAN (13sep/2024)-->
                        @include('application.mutation.include.mutation')

                        <div class="row g-3 mt-2">
                            <div class="col-12 col-lg-4">
                                <!-- <button type="button" class="btn btn-primary px-4" id="submitbtn1">Next<i class='bx bx-right-arrow-alt ms-2'></i></button> -->
                                <!-- <button type="button" class="btn btn-primary px-4" onclick="steppers['stepper3'].next();">Next<i class='bx bx-right-arrow-alt ms-2'></i></button> -->
                                <button type="button" class="btn btn-primary px-4 submitbtn1">Next<i class='bx bx-right-arrow-alt ms-2'></i></button>
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

                        <div class="row g-3 mt-2">

                            <div class="col-lg-6 col-12">
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" class="btn btn-outline-secondary px-4"
                                        onclick="steppers['stepper3'].previous()"><i
                                            class='bx bx-left-arrow-alt me-2'></i>Previous</button>

                                    <button type="button" class="btn btn-primary px-4 submitbtn2">Next<i
                                            class='bx bx-right-arrow-alt ms-2'></i></button>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12 text-end">
                                <label class="note text-danger text-sm"><strong>Note<span
                                            class="text-danger">*</span>:</strong> Upload documents (pdf file, up to 5
                                    MB)</label>
                            </div>
                        </div><!---end row-->

                    </div>

                    <div id="newstep-vl-3" role="tabpane3" class="bs-stepper-pane content fade"
                        aria-labelledby="stepper3trigger3">
                        <h5 class="mb-1" id="finalStateTitle">Additional/Optional Documents</h5>
                        <p class="mb-4" id="finalStateSubtitle">Please Enter Additional Details</p>

                        <!-- mutation step three SOURAV CHAUHAN (20/sep/2024)**********************-->
                        @include('application.mutation.include.mutation-step-three')

                        <div class="row mt-3">
                            <div class="col-lg-6 col-12">
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" class="btn btn-outline-secondary px-4"
                                        onclick="steppers['stepper3'].previous()"><i
                                            class='bx bx-left-arrow-alt me-2'></i>Previous</button>
                                    <button type="button" class="btn btn-primary px-4 btnfinalsubmit">Proceed to
                                        Pay</button>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12 text-end">
                                <label class="note text-danger text-sm"><strong>Note<span
                                            class="text-danger">*</span>:</strong> Upload documents (pdf file, up to 5
                                    MB)</label>
                            </div>
                        </div><!---end row-->
                    </div>
                </div>
            </div>
            <!-- Stepper3 End -->

            <!-- Stepper4 Starts => Conversion by Diwakar Sinha, 30-09-2024 -->
            <div id="stepper4" class="stepper bs-stepper gap-4 vertical LHConversiondiv" style="display: none;"
                data-linear="true" data-animation="true">
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
                    <div id="newstep-vl-1" role="tabpane3" class="bs-stepper-pane content fade"
                        aria-labelledby="stepper3trigger1">

                        <!-- For Conversion Step-1 -->
                        @include('application.conversion.include.step-1')
                        <!-- End -->

                        <div class="row g-3 mt-2">
                            <div class="col-12 col-lg-4">
                                <button type="button" class="btn btn-primary px-4 submitbtn1">Next<i class='bx bx-right-arrow-alt ms-2'></i></button>
                            </div>
                        </div>
                        <!---end row-->
                    </div>

                    <div id="newstep-vl-2" role="tabpane3" class="bs-stepper-pane content fade"
                        aria-labelledby="stepper3trigger2">

                        <h5 class="mb-1">Compulsory/Mandatory Documents</h5>
                        <p class="mb-4">Please Enter Compulsory Details</p>

                        <!-- Conversion 2 -->
                        @include('application.conversion.include.step-2')
                        <!-- End -->

                        <div class="row g-3 mt-2">

                            <div class="col-lg-6 col-12">
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" class="btn btn-outline-secondary px-4"
                                        onclick="steppers['stepper4'].previous()"><i
                                            class='bx bx-left-arrow-alt me-2'></i>Previous</button>

                                    <button type="button" class="btn btn-primary px-4 submitbtn2" onclick="steppers['stepper4'].next()">Next<i
                                            class='bx bx-right-arrow-alt ms-2'></i></button>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12 text-end">
                                <label class="note text-danger text-sm"><strong>Note<span
                                            class="text-danger">*</span>:</strong> Upload documents (pdf file, up to 5
                                    MB)</label>
                            </div>
                        </div><!---end row-->

                    </div>

                    <div id="newstep-vl-3" role="tabpane3" class="bs-stepper-pane content fade"
                        aria-labelledby="stepper3trigger3">
                        <h5 class="mb-1" id="finalStateTitle">Additional/Optional Documents</h5>
                        <p class="mb-4" id="finalStateSubtitle">Please Enter Additional Details</p>


                        <!-- Conversion 3 -->
                        @include('application.conversion.include.step-3')
                        <!-- End -->
                        <div class="row mt-3">
                            <div class="col-lg-6 col-12">
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" class="btn btn-outline-secondary px-4"
                                        onclick="steppers['stepper4'].previous()"><i
                                            class='bx bx-left-arrow-alt me-2'></i>Previous</button>

                                    <button type="button" class="btn btn-primary px-4 btnfinalsubmit">Proceed to
                                        Pay</button>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12 text-end">
                                <label class="note text-danger text-sm"><strong>Note<span
                                            class="text-danger">*</span>:</strong> Upload documents (pdf file, up to 5
                                    MB)</label>
                            </div>
                        </div>
                        <!---end row-->

                    </div>
                </div>
            </div>
            <!-- Stepper4 End -->

            <!-- Stepper5 Starts => Land Use Change by Diwakar Sinha, 30-09-2024 -->
            <div id="stepper5" class="stepper bs-stepper gap-4 vertical landusechangeDiv" style="display: none;" data-linear="true" data-animation="true">
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

                </div>

                <div class="bs-stepper-content">
                    <div id="newstep-vl-1" role="tabpane3" class="bs-stepper-pane content fade"
                        aria-labelledby="stepper3trigger1">

                        <!-- Land Use Change Step-1 -->
                        @include('application.luc.include.step-1')
                        <!-- End -->

                        <div class="row g-3 mt-2">
                            <div class="col-12 col-lg-4">
                                <button type="button" class="btn btn-primary px-4 submitbtn1">Next<i class='bx bx-right-arrow-alt ms-2'></i></button>
                            </div>
                        </div>
                        <!---end row-->
                    </div>

                    <div id="newstep-vl-2" role="tabpane3" class="bs-stepper-pane content fade"
                        aria-labelledby="stepper3trigger2">

                        <h5 class="mb-1">Compulsory/Mandatory Documents</h5>
                        <p class="mb-4">Please Enter Compulsory Details</p>

                        <!-- =========== Begin Land Use Change Div ========== -->
                        @include('application.luc.include.step-2')
                        <!-- ========== End Land Use Change Div =============== -->
                        <!-- =========== Begin Land Use Change Div ========== -->
                        @include('application.luc.include.step-3')
                        <!-- ========== End Land Use Change Div =============== -->

                        <div class="row g-3 mt-2">

                            <div class="col-lg-6 col-12">
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" class="btn btn-outline-secondary px-4" onclick="steppers['stepper5'].previous()"><i class='bx bx-left-arrow-alt me-2'></i>Previous</button>
                                    <button type="button" class="btn btn-primary px-4 btnfinalsubmit">Proceed to Pay</button>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12 text-end">
                                <label class="note text-danger text-sm"><strong>Note<span class="text-danger">*</span>:</strong> Upload documents (pdf file, up to 5 MB)</label>
                            </div>
                        </div><!---end row-->

                    </div>
                </div>
            </div>
            <!-- Stepper5 End -->

            <!-- Stepper6 Starts => Deed of Apartment by Diwakar Sinha, 30-09-2024 -->
            <div id="stepper6" class="stepper bs-stepper gap-4 vertical deedofappartmentDiv" style="display: none;"
                data-linear="true" data-animation="true">
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

                </div>

                <div class="bs-stepper-content">
                    <div id="newstep-vl-1" role="tabpane3" class="bs-stepper-pane content fade"
                        aria-labelledby="stepper3trigger1">

                        <!-- Land Use Change Step-1 -->
                        @include('application.deed_of_appartment.include.step-1')
                        <!-- End -->

                        <div class="row g-3 mt-2">
                            <div class="col-12 col-lg-4">
                                <!-- onclick="steppers['stepper6'].next()" -->
                                <button type="button" class="btn btn-primary px-4 submitbtn1">Next<i class='bx bx-right-arrow-alt ms-2'></i></button>
                            </div>
                        </div>
                        <!---end row-->
                    </div>

                    <div id="newstep-vl-2" role="tabpane3" class="bs-stepper-pane content fade"
                        aria-labelledby="stepper3trigger2">

                        <h5 class="mb-1">Compulsory/Mandatory Documents</h5>
                        <p class="mb-4">Please Enter Compulsory Details</p>

                        <!-- =========== Begin Deed of Apartment Div ========== -->
                        @include('application.deed_of_appartment.include.step-2')
                        <!-- ========== End Deed of Apartment Div =============== -->

                        <div class="row g-3 mt-2">

                            <div class="col-lg-6 col-12">
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" class="btn btn-outline-secondary px-4"
                                        onclick="steppers['stepper6'].previous()"><i
                                            class='bx bx-left-arrow-alt me-2'></i>Previous</button>
                                    <button type="button" class="btn btn-primary px-4 btnfinalsubmit">Proceed to
                                        Pay</button>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12 text-end">
                                <label class="note text-danger text-sm"><strong>Note<span
                                            class="text-danger">*</span>:</strong> Upload documents (pdf file, up to 5
                                    MB)</label>
                            </div>
                        </div><!---end row-->

                    </div>
                </div>
            </div>
            <!-- Stepper6 End -->

        </form>
    </div>
</div>
<div id="spinnerOverlay" style="display:none;">
    <div class="spinner"></div>
</div>
</div>

@include('include.alerts.application.change-property')
@include('include.alerts.ajax-alert')
{{-- Dynamic Element --}}
@endsection
@section('footerScript')
<script src="{{ asset('assets/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
<!-- <script src="{{ asset('assets/plugins/bs-stepper/js/main.js') }}"></script> -->
<script src="{{ asset('assets/plugins/form-repeater/repeater.js') }}"></script>

<script>
    var isEditing = "{{ isset($application) ? true : false }}";
    @if(isset($application))
    var application = @json($application)
    @else
    var application = null;
    @endif


    window.onload = function() {
        console.log(isEditing, application);
    }
</script>
<script src="{{ asset('assets/js/newApplicant.js') }}"></script>
<script>
    $('#appChangeProperty').on('hidden.bs.modal', function() {
        var lastPropertyId = $("input[name='lastPropertyId']").val();
        $('#propertyid').val(lastPropertyId);
    });

    //fetch proerty details when property Id change at creation time - Sourav Chauhan 13/sep/2024
    $('#propertyid').on('change', function() {
        var propertyId = $(this).val();
        isPropertyFree(propertyId).then(function(responseForFreeProp) {
            if (responseForFreeProp) {
                fetchPropertyDetails(propertyId, false);
            }
        }).catch(function(error) {
            console.error("Error occurred:", error); // Handle the error here
        });
    });

    function isPropertyFree(propertyId) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: "{{ route('isPropertyFree') }}",
                type: "POST",
                dataType: "JSON",
                data: {
                    propertyId: propertyId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === false) {
                        $('#propertyStatus').val('');
                        $('#applicationType').val('');
                        showError(response.message);
                    }
                    resolve(response.status); // Resolve the promise with the response status
                },
                error: function(response) {
                    reject(false); // Reject the promise if there's an error
                }
            });
        });
    }
    //fetch property details when editing the application from draft - Sourav Chauhan 26/sep/2024
    $(document).ready(function() {
        var draftApplicationPropertyId = $('#draftApplicationPropertyId').val();
        if (draftApplicationPropertyId) {
            fetchPropertyDetails(draftApplicationPropertyId, true); // Call the function with the property ID
        }
    })


    function fetchPropertyDetails(propertyId, draftApplicationPropertyId) {
        var updateId = $("input[name='updateId']").val();
        $.ajax({
            url: "{{ route('appGetPropertyDetails') }}",
            type: "POST",
            dataType: "JSON",
            data: {
                draftApplicationPropertyId: draftApplicationPropertyId,
                propertyId: propertyId,
                updateId: updateId,
                model: "{{ isset($decodedModel) ? $decodedModel : null }}",
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status) {
                    if (response.data.status == '952') {
                        $('#propertyStatus').val('Free Hold');
                        $('#freeleasetitle').html('Details of Conveyance Deed');
                        console.log($('#freeleasetitle'));

                    } else {
                        $('#propertyStatus').val('Lease Hold');
                        $('#freeleasetitle').html('Details of Lease Deed');
                        console.log($('#freeleasetitle'));

                    }
                    const items = response.data.items;

                    // Clear the existing options first (if necessary)
                    $("#applicationType").empty();

                    // Get the number of items
                    const itemCount = Object.keys(items).length;
                    $.each(items, function(key, value) {
                        $("#applicationType").append('<option value="' + key + '">' + value +
                            '</option>');
                    });

                    // Check the number of items
                    if (itemCount == 1) {
                        // If there's only one item, select it
                        $("#applicationType").val(Object.keys(items)[0])
                            .change(); // Select the first (and only) item
                    } else {
                        // If there are multiple items, add a default "Select" option
                        $("#applicationType").prepend('<option value="" selected>Select</option>');
                    }

                    // for showing property details
                    if (response.data.propertyDetails) {
                        $('#applicationPropertyDetails').empty();
                        var area = inFavourOf = leaseExectionDate = leaseType = propertyType = propertySubType = '';
                        var propertyDetails = response.data.propertyDetails;

                        area = propertyDetails.area
                        inFavourOf = propertyDetails.inFavourOf
                        leaseExectionDate = propertyDetails.leaseExectionDate
                        leaseType = propertyDetails.leaseType
                        propertyType = propertyDetails.propertyType
                        propertySubType = propertyDetails.propertySubType
                        presentlyKnownAs = propertyDetails.presentlyKnownAs

                        var propertyDetailContent = `
                                                    <div class="parent_table_container">
                                                        <table class="table report-item">
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="4" class="address_data">Presently known as: <span class="highlight_value address_address">` + presentlyKnownAs + `</span></td>
                                                                </tr>
                                                                <tr>     
                                                                    <td>Lease Type: <span class="highlight_value">` + leaseType + `</span></td>
                                                                    <td>Lease Executed On: <span class="highlight_value">` + leaseExectionDate + `</span></td>
                                                                    <td>Original Lessee: <span class="highlight_value lessee_address">` + inFavourOf + `</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Land Size: <span class="highlight_value">` + area + `Sq. Mtr.</span></td>
                                                                    <td>Property Type: <span class="highlight_value">` + propertyType + `</span></td>
                                                                    <td>Property Sub Type: <span class="highlight_value">` + propertySubType + `</span></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    `;

                        $('#applicationPropertyDetails').append(propertyDetailContent);
                    }
                } else {
                    if (response.data == 'deleteYes') {
                        $('#appModalId').val(updateId);
                        $('#appChangeProperty').modal('show');
                    }
                }
            },
            error: function(response) {
                // Handle error
            }
        });
    }


    //handle upload file
    function handleFileUpload(file, name, type, processType) {
        const spinnerOverlay = document.getElementById('spinnerOverlay');
        spinnerOverlay.style.display = 'flex';
        const baseUrl = "{{ asset('storage') }}";

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
                    var anchorTag = $('a[data-document-type="' + name + '"]');
                    if (anchorTag.length > 0) {
                        const newPath = baseUrl + '/' + response.path;
                        anchorTag.attr('href', newPath);
                    }
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
        var applicationType = $('#applicationType').val();
        $.ajax({
            url: "{{route('deleteApplication')}}",
            type: "POST",
            dataType: "JSON",
            data: {
                modalId: modalId,
                applicationType: applicationType,
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

    //Code for making flatNumber & buiderName readonly & vice versa.
    $(document).ready(function() {
        $('#isFlatNotInList').change(function() {
            if ($(this).is(':checked')) {
                // Checkbox is checked, remove readonly attribute
                $('#flatNumber, #builderName').removeAttr('readonly');
            } else {
                // Checkbox is unchecked, add readonly attribute back
                $('#flatNumber, #builderName').attr('readonly', true);
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Initial values for locality, block, plot, knownas, and flatId when editing the form
        var initialLocality = "{{ $application->locality ?? '' }}";
        var initialBlock = "{{ $application->block ?? '' }}";
        var initialPlot = "{{ $application->plot ?? '' }}";
        var initialKnownAs = "{{ $application->known_as ?? '' }}";
        var initialFlatId = "{{ $application->flat_id ?? '' }}";

        // Function to populate blocks based on locality
        function populateBlocks(localityId, selectedBlock = null) {
            $("#block").html('<option value="">Select</option>');
            if (localityId) {
                $.ajax({
                    url: "{{ route('localityBlocks') }}",
                    type: "POST",
                    data: {
                        locality: localityId,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $.each(result, function(key, value) {
                            let selected = selectedBlock == value.block_no ? 'selected' :
                                '';
                            $("#block").append('<option value="' + value.block_no + '" ' +
                                selected + '>' + value.block_no + '</option>');
                        });

                        if (selectedBlock) {
                            populatePlots(localityId, selectedBlock, initialPlot);
                        }
                    }
                });
            }
        }

        // Function to populate plots based on block
        function populatePlots(localityId, blockId, selectedPlot = null) {
            $("#plot").html('<option value="">Select</option>');
            if (localityId && blockId) {
                $.ajax({
                    url: "{{ route('blockPlots') }}",
                    type: "POST",
                    data: {
                        locality: localityId,
                        block: blockId,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $.each(result, function(key, value) {
                            let selected = selectedPlot == value ? 'selected' : '';
                            $("#plot").append('<option value="' + value + '" ' + selected +
                                '>' + value + '</option>');
                        });

                        if (selectedPlot) {
                            populateKnownAs(localityId, blockId, selectedPlot, initialKnownAs);
                        }
                    }
                });
            }
        }

        // Function to populate knownas based on plot
        function populateKnownAs(localityId, blockId, plotId, selectedKnownAs = null) {
            $("#knownas").html('<option value="">Select</option>');
            if (localityId && blockId && plotId) {
                $.ajax({
                    url: "{{ route('plotKnownas') }}",
                    type: "POST",
                    data: {
                        locality: localityId,
                        block: blockId,
                        plot: plotId,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $.each(result, function(key, value) {
                            let selected = selectedKnownAs == value ? 'selected' : '';
                            $("#knownas").append('<option value="' + value + '" ' +
                                selected + '>' + value + '</option>');
                        });

                        if (selectedKnownAs) {
                            populateFlats(localityId, blockId, plotId, selectedKnownAs,
                                initialFlatId);
                        }
                    }
                });
            }
        }

        // Function to populate flats based on knownas
        function populateFlats(localityId, blockId, plotId, knownAsId, selectedFlatId = null) {
            $("#flatId").html('<option value="">Select</option>');
            if (localityId && blockId && plotId && knownAsId) {
                $.ajax({
                    url: "{{ route('knownAsFlat') }}",
                    type: "POST",
                    data: {
                        locality: localityId,
                        block: blockId,
                        plot: plotId,
                        known_as: knownAsId,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $.each(result, function(key, value) {
                            let selected = selectedFlatId == key ? 'selected' : '';
                            $("#flatId").append('<option value="' + key + '" ' + selected +
                                '>' + value + '</option>');
                        });

                        if (selectedFlatId) {
                            populateFlatDetails(selectedFlatId);
                        }
                    }
                });
            }
        }

        // Function to populate flat details based on flatId
        function populateFlatDetails(flatId) {
            if (flatId) {
                $.ajax({
                    url: "{{ route('getFlatDetails') }}",
                    type: "POST",
                    data: {
                        flatId: flatId,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#flatNumber').val(result.flat_number || '');
                        $('#builderName').val(result.builder_developer_name || '');
                        $('#old_property_id').val(result.old_property_id || '');
                        $('#property_master_id').val(result.property_master_id || '');
                        $('#new_property_id').val(result.unique_property_id || '');
                        $('#splited_property_detail_id').val(result.splitted_property_id || '');
                    }
                });
            }
        }

        // When locality changes, populate blocks
        $('#locality').on('change', function() {
            populateBlocks(this.value);
        });

        // When block changes, populate plots
        $('#block').on('change', function() {
            populatePlots($('#locality').val(), this.value);
        });

        // When plot changes, populate knownas
        $('#plot').on('change', function() {
            populateKnownAs($('#locality').val(), $('#block').val(), this.value);
        });

        // When knownas changes, populate flats
        $('#knownas').on('change', function() {
            populateFlats($('#locality').val(), $('#block').val(), $('#plot').val(), this.value);
        });

        // When flatId changes, populate flat details
        $('#flatId').on('change', function() {
            populateFlatDetails(this.value);
        });

        // If in edit mode, populate all dropdowns
        if (initialLocality) {
            $('#locality').val(initialLocality).trigger('change');
            populateBlocks(initialLocality, initialBlock);
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Get the current date in YYYY-MM-DD format
        const today = new Date().toISOString().split('T')[0];

        // Set the max attribute of the date input to today's date
        document.getElementById('mutExecutedOnAsConLease').setAttribute('max', today);
    });
</script>
<script src="{{ asset('assets/js/newApplicantStepper.js') }}"></script>
@endsection