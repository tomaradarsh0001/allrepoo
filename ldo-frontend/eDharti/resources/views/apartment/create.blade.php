@extends('layouts.app')
@section('title', 'Application For Deed Of Apartment')
@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Application</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item">Deed Of Apartment Form</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="part-title">
                <h5>Fill Apartment Details</h5>
            </div>
            {{-- <form action="{{ route('application.apartment.store') }}" method="POST" enctype="multipart/form-data" id="deedOfApartmentForm"> --}}
            <form id="deedOfApartmentForm" method="POST">
                @csrf
                <input type="hidden" id="old_property_id" name="old_property_id">
                <input type="hidden" id="property_master_id" name="property_master_id">
                <input type="hidden" id="new_property_id" name="new_property_id">
                <input type="hidden" id="splited_property_detail_id" name="splited_property_detail_id">
                <div class="part-details">
                    <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-12">
                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <label for="applicantName" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="applicantName" id="applicantName"
                                        placeholder="Enter Name" value="{{ old('applicantName') }}">
                                    @error('applicantName')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="applicantNameError"></div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="applicantAddress" class="form-label">Communication Address</label>
                                    <input type="text" class="form-control" name="applicantAddress" id="applicantAddress"
                                        placeholder="Enter Communication Address" value="{{ old('applicantAddress') }}">
                                    @error('applicantAddress')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="applicantAddressError"></div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="buildingName" class="form-label">Building Name <small
                                            class="form-text text-muted">(In
                                            which the apartment exists.)</small></label>
                                    <input type="text" class="form-control" name="buildingName" id="buildingName"
                                        placeholder="Building name" value="{{ old('buildingName') }}">
                                    @error('buildingName')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="buildingNameError"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <label for="locality" class="form-label">Locality</label>
                                    <select name="locality" id="locality" class="form-select">
                                        <option value="">Select</option>
                                        @foreach ($colonyList as $colony)
                                            <option value="{{ $colony->id }}">{{ $colony->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('locality')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="localityError"></div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="block" class="form-label">Block</label>
                                    <select name="block" id="block" class="form-select">
                                        <option value="">Select</option>
                                    </select>
                                    @error('block')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="blockError"></div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="plot" class="form-label">Plot No. <small class="form-text text-muted">(Where
                                            the
                                            building/property exists.)</small></label>
                                    <select name="plot" id="plot" class="form-select">
                                        <option value="">Select</option>
                                    </select>
                                    @error('plot')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="plotError"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <label for="knownas" class="form-label">Prasently Known As</label>
                                    <select name="knownas" id="knownas" class="form-select">
                                        <option value="">Select</option>
                                    </select>
                                    @error('knownas')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="knownasError"></div>
                                </div>

                                <div class="col-lg-3">
                                    <label for="flatId" class="form-label">Flat</label>
                                    <select name="flatId" id="flatId" class="form-select">
                                        <option value="">Select</option>
                                    </select>
                                    @error('flatId')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="flatIdError"></div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="checkbox-options" style="padding-top:36px !important;">
                                        <div class="form-check form-check-success">
                                            <label class="form-check-label" for="isFlatNotInList">
                                                is Flat not listed?
                                            </label>
                                            <input class="form-check-input required-for-approve" name="isFlatNotInList"
                                                type="checkbox" id="isFlatNotInList">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label for="flatNumber" class="form-label">Flat No.</label>
                                    <input type="text" class="form-control" name="flatNumber" id="flatNumber"
                                        placeholder="Flat Number" value="{{ old('flatNumber') }}" readonly>
                                    @error('flatNumber')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="flatNumberError"></div>
                                </div>
    
                                <div class="col-lg-3">
                                    <label for="builderName" class="form-label">Name of Builder / Developer</label>
                                    <input type="text" class="form-control" name="builderName" id="builderName"
                                        placeholder="Name of Builder / Developer" value="{{ old('builderName') }}" readonly>
                                    @error('builderName')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="builderNameError"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <label for="originalBuyerName" class="form-label">Name Of Original Buyer</label>
                                    <input type="text" class="form-control" name="originalBuyerName"
                                        id="originalBuyerName" placeholder="Enter Name Of Original Buyer"
                                        value="{{ old('originalBuyerName') }}">
                                    @error('originalBuyerName')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="originalBuyerNameError"></div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="presentOccupantName" class="form-label">Name Of Present Occupant</label>
                                    <input type="text" class="form-control" name="presentOccupantName"
                                        id="presentOccupantName" placeholder="Enter Name Of Present Occupant"
                                        value="{{ old('presentOccupantName') }}">
                                    @error('presentOccupantName')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="presentOccupantNameError"></div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="purchasedFrom" class="form-label">Purchased From</label>
                                    <input type="text" class="form-control" name="purchasedFrom" id="purchasedFrom"
                                        placeholder="Enter Purchased From" value="{{ old('purchasedFrom') }}">
                                    @error('purchasedFrom')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="purchasedFromError"></div>
                                </div>

                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <label for="purchaseDate" class="form-label">Date of Purchase</label>
                                        <input type="date" name="purchaseDate" class="form-control" id="purchaseDate"
                                            pattern="\d{2} \d{2} \d{4}" value="{{ old('purchase_date') }}">
                                        @error('purchaseDate')
                                            <span class="errorMsg">{{ $message }}</span>
                                        @enderror
                                        <div id="purchaseDateError" class="text-danger"></div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="apartmentArea" class="form-label">Flat Area <small
                                        class="form-text text-muted">(In Sq.
                                        Mtr. including common area.)</small></label>
                                <input type="text" class="form-control" name="apartmentArea" id="apartmentArea"
                                    placeholder="Enter Total Flat Area" value="{{ old('apartmentArea') }}">
                                @error('apartmentArea')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="text-danger" id="apartmentAreaError"></div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="plotArea" class="form-label">Plot Area <small
                                        class="form-text text-muted">(Leased
                                        From L&DO in Sq. Mtr.)</small> </label>
                                <input type="text" class="form-control" name="plotArea" id="plotArea"
                                    placeholder="Enter Total Plot Area" value="{{ old('plotArea') }}">
                                @error('plotArea')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="text-danger" id="plotAreaError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" id="submitDeedOfApartmentFormBtn"
                                class="btn btn-primary btn-theme">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="part-title mt-2">
                <h5>OWNERSHIP DOCUMENTS</h5>
            </div>
            <div class="part-details">
                <div class="container-fluid">
                </div class="row">
                <form method="POST" action="#" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="updateId" name="updateId">
                    <input type="hidden" id="propertyid" name="propertyid">
                    <div class="col-lg-12 col-12">
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="builderAgreementDoc" class="form-label">Builder & Buyer
                                        Agreement</label>
                                        <input type="file" name="builderAgreementDoc" class="form-control" accept="application/pdf" id="builderAgreementDoc" onchange="handleFileUpload(this.files[0], 'BuilderAgreement', 'deed_of_apartment', 'DOA')">
                                    <small class="text-muted">Upload documents (pdf file, up to 50 MB)</small>

                                    @error('builderAgreementDoc')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="builderAgreementDocError"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label for="saleDeedDoc" class="form-label">Sale Deed <small
                                        class="form-text text-muted">(PDF)</small></label>
                                {{-- <input type="file" name="saleDeedDoc" id="saleDeedDoc" class="form-control"
                                    accept="application/pdf"> --}}
                                    <input type="file" name="saleDeedDoc" class="form-control" accept="application/pdf" id="saleDeedDoc" onchange="handleFileUpload(this.files[0], 'SaleDeed', 'deed_of_apartment', 'DOA')">
                                <small class="form-text text-muted">Upload PDF document (Max: 5MB)</small>
                                @error('saleDeedDoc')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="text-danger" id="saleDeedDocError"></div>
                            </div>
                            <div class="col-lg-4">
                                <label for="otherDoc" class="form-label">Other Documents <small
                                        class="form-text text-muted">(PDF)</small></label>
                                {{-- <input type="file" name="otherDoc" id="otherDoc" class="form-control"
                                    accept="application/pdf"> --}}
                                <input type="file" name="otherDoc" class="form-control" accept="application/pdf" id="otherDoc" onchange="handleFileUpload(this.files[0], 'Other', 'deed_of_apartment', 'DOA')">
                                <small class="form-text text-muted">Upload PDF document (Max: 5MB)</small>
                                @error('otherDoc')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="text-danger" id="otherDocError"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            
                            <div class="col-lg-4">
                                <label for="buildingPlanDoc" class="form-label">Building Plan <small
                                        class="form-text text-muted">(PDF)</small></label>
                                {{-- <input type="file" name="buildingPlanDoc" id="buildingPlanDoc" class="form-control"
                                    accept="application/pdf"> --}}
                                <input type="file" name="buildingPlanDoc" class="form-control" accept="application/pdf" id="buildingPlanDoc" onchange="handleFileUpload(this.files[0], 'BuildingPlan', 'deed_of_apartment', 'DOA')">
                                <small class="form-text text-muted">Upload PDF document (Max: 5MB)</small>
                                @error('buildingPlanDoc')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="text-danger" id="buildingPlanDocError"></div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>

    {{-- Dynamic Element --}}
@endsection
@section('footerScript')
    <script>
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


        //get all blocks of selected locality
        $('#locality').on('change', function() {
            var locality = this.value;
            $("#block").html('');
            $.ajax({
                url: "{{ route('localityBlocks') }}",
                type: "POST",
                data: {
                    locality: locality,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#block').html('<option value="">Select</option>');
                    $.each(result, function(key, value) {
                        $("#block").append('<option value="' + value.block_no + '">' + value
                            .block_no + '</option>');
                    });
                }
            });
        });

        //get all plots of selected block
        $('#block').on('change', function() {
            var locality = $('#locality').val();
            var block = this.value;
            $("#plot").html('');
            $.ajax({
                url: "{{ route('blockPlots') }}",
                type: "POST",
                data: {
                    locality: locality,
                    block: block,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    // console.log(result);
                    $('#plot').html('<option value="">Select</option>');
                    $.each(result, function(key, value) {

                        $("#plot").append('<option value="' + value + '">' +
                            value + '</option>');
                    });
                }
            });
        });

        //get known as of selected plot
        $('#plot').on('change', function() {
            var locality = $('#locality').val();
            var block = $('#block').val();
            var plot = this.value;
            $("#knownas").html('');
            $.ajax({
                url: "{{ route('plotKnownas') }}",
                type: "POST",
                data: {
                    locality: locality,
                    block: block,
                    plot: plot,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    // console.log(result);
                    $('#knownas').html('<option value="">Select</option>');
                    $.each(result, function(key, value) {

                        $("#knownas").append('<option value="' + value + '">' + value +
                            '</option>');
                    });
                }
            });
        });

        //get flat of selected locality, block, plot
        $('#knownas').on('change', function() {
            var locality = $('#locality').val();
            var block = $('#block').val();
            var plot = $('#plot').val();
            var known_as = this.value;
            $("#flatId").html('');
            $.ajax({
                url: "{{ route('knownAsFlat') }}",
                type: "POST",
                data: {
                    locality: locality,
                    block: block,
                    plot: plot,
                    known_as: known_as,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    // console.log(result);
                    $('#flatId').html('<option value="">Select</option>');
                    $.each(result, function(key, value) {
                        $("#flatId").append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                }
            });
        });

        //get flat of selected locality, block, plot
        $('#flatId').on('change', function() {
            var flatId = this.value;
            $.ajax({
                url: "{{ route('getFlatDetails') }}",
                type: "POST",
                data: {
                    flatId: flatId,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    console.log(result);
                    if (result.flat_number != '') {
                        $('#flatNumber').val(result.flat_number);
                    }
                    if (result.builder_developer_name != '') {
                        $('#builderName').val(result.builder_developer_name);
                    }
                    if (result.old_property_id != '') {
                        $('#old_property_id').val(result.old_property_id);
                    }
                    if (result.property_master_id  != '') {
                        $('#property_master_id').val(result.property_master_id );
                    }
                    if (result.unique_property_id != '') {
                        $('#new_property_id').val(result.unique_property_id);
                    }
                    if (result.splitted_property_id != '') {
                        $('#splited_property_detail_id').val(result.splitted_property_id);
                    }
                }
            });
        });


        $(document).ready(function() {
            $('#submitDeedOfApartmentFormBtn').click(function(e) {
                e.preventDefault(); // Prevent default form submission

                // Clear previous error messages
                $('.text-danger').text('');

                let isValid = true;

                // Validate primary form fields
                if (!$('#applicantName').val()) {
                    $('#applicantName').next('.text-danger').text('Please enter the applicant name.');
                    isValid = false;
                }

                if (!$('#applicantAddress').val()) {
                    $('#applicantAddress').next('.text-danger').text('Please enter the address.');
                    isValid = false;
                }

                if (!$('#buildingName').val()) {
                    $('#buildingName').next('.text-danger').text('Please enter the building name.');
                    isValid = false;
                }

                if (!$('#locality').val()) {
                    $('#locality').next('.text-danger').text('Please select a locality.');
                    isValid = false;
                }

                if (!$('#block').val()) {
                    $('#block').next('.text-danger').text('Please select a block.');
                    isValid = false;
                }

                if (!$('#plot').val()) {
                    $('#plot').next('.text-danger').text('Please select a plot.');
                    isValid = false;
                }

                if (!$('#knownas').val()) {
                    $('#knownas').next('.text-danger').text('Please select a known as.');
                    isValid = false;
                }

                if (!$('#originalBuyerName').val()) {
                    $('#originalBuyerName').next('.text-danger').text(
                        'Please enter the original buyer\'s name.');
                    isValid = false;
                }

                if (!$('#presentOccupantName').val()) {
                    $('#presentOccupantName').next('.text-danger').text(
                        'Please enter the present occupant\'s name.');
                    isValid = false;
                }

                if (!$('#purchasedFrom').val()) {
                    $('#purchasedFrom').next('.text-danger').text('Please enter the firm name.');
                    isValid = false;
                }

                if (!$('#plotArea').val()) {
                    $('#plotArea').next('.text-danger').html(
                        'Please enter the plot area <small class="form-text text-danger" style="font-size:x-small;">(Leased From L&DO in Sq. Mtr.)</small>'
                    );

                    isValid = false;
                }

                if (!$('#flatArea').val()) {
                    $('#flatArea').next('.text-danger').html(
                        'Please enter the  flat area <small class="form-text text-danger" style="font-size:x-small;">(In Sq. Mtr. including common area).</small>'
                    );
                    isValid = false;
                }

                if (!$('#flatNumber').val()) {
                    $('#flatNumber').next('.text-danger').text('Please enter the flat no.');
                    isValid = false;
                }


                if (!$('#builderName').val()) {
                    $('#builderName').next('.text-danger').text(
                        'Please enter the builder / developer name.');
                    isValid = false;
                }

                if (!$('#purchaseDate').val()) {
                    $('#purchaseDate').next('.text-danger').text('Please enter date of purchase');
                    isValid = false;
                }



                // const fileInputs = [{
                //         id: 'builderAgreementDoc',
                //         errorId: 'builderAgreementDocError',
                //         label: 'Builder & Buyer Agreement'
                //     },
                //     {
                //         id: 'saleDeedDoc',
                //         errorId: 'saleDeedDocError',
                //         label: 'Sale Deed'
                //     },
                //     {
                //         id: 'otherDoc',
                //         errorId: 'otherDocError',
                //         label: 'Other Documents'
                //     },
                //     {
                //         id: 'buildingPlanDoc',
                //         errorId: 'buildingPlanDocError',
                //         label: 'Building Plan'
                //     }
                // ];

                // fileInputs.forEach(input => {
                //     const fileInput = document.getElementById(input.id);
                //     if (fileInput.files.length === 0) {
                //         $('#' + input.errorId).text('Please upload the ' + input.label + '.');
                //         isValid = false;
                //     } else if (fileInput.files[0].size > 50 * 1024 * 1024) { // 50 MB limit
                //         $('#' + input.errorId).text(input.label + ' file must be less than 50 MB.');
                //         isValid = false;
                //     }
                // });

                if (!isValid) {
                    return; // Prevent form submission if validation fails
                }

                // Submit the form if valid
                $('#submitDeedOfApartmentFormBtn').prop('disabled', true);
                $('#submitDeedOfApartmentFormBtn').html('Submitting...');
                // $('#deedOfApartmentForm').submit();
                let formData = new FormData($('#deedOfApartmentForm')[0]); // Get form data
                $.ajax({
                    url: "{{ route('application.apartment.store') }}", // Form submission route
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            // alert('Form submitted successfully. Last inserted ID: ' + response.last_inserted_id);
                            $('#updateId').val(response.last_inserted_id);
                            $('#propertyid').val(response.propertyid);
                            $('#submitDeedOfApartmentFormBtn').html('Submitted'+' id is => '+response.last_inserted_id);
                        } else {
                            alert('Error occurred.');
                        }
                    },
                    error: function(xhr) {
                        // Handle validation errors
                        let errors = xhr.responseJSON.errors;
                        if (errors) {
                            $('#applicantNameError').text(errors.applicantName ? errors
                                .applicantName[0] : '');
                            $('#applicantAddressError').text(errors.applicantAddress ? errors
                                .applicantAddress[0] : '');
                            $('#buildingNameError').text(errors.buildingName ? errors
                                .buildingName[0] : '');
                            // Handle other field errors...
                        }
                    }
                });
            });
        });

        //handle upload file
    function handleFileUpload(file, name, type, processType) {
        // const spinnerOverlay = document.getElementById('spinnerOverlay');
        // spinnerOverlay.style.display = 'flex';

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
    </script>
@endsection
