@extends('layouts.public.app')
@section('title', 'Registration')

@section('content')

<link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
<style>
    .age-box h4 {
        font-size: 16px;
        margin: 0px 8px 0px 0px;
    }

    .age-box {
        display: flex;
        align-items: center;
        width: 45%;
        background: #e9ecef;
        border: 1px solid #b0b0b0;
        border-left: 0px;
        padding: 0px 0px 0px 10px;
    }

    .age-box input {
        border: 0px !important;
    }
</style>
<div class="login-8">
    <div class="container">
        <div class="row login-box">

            @if (session('success'))
            <div class="alert alert-success border-0 bg-success alert-dismissible">
                <div class="text-white">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if (session('failure'))
            <div class="alert alert-danger border-0 bg-danger alert-dismissible">
                <div class="text-white">{{ session('failure') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="col-lg-12 mx-auto form-section" id="formColumnIncrease">
                <div class="form-inner">
                    <div class="form-inner-head">
                        <h3>Registration</h3>
                    </div>

                    <form action="{{ route('publicRegisterCreate') }}" method="POST" enctype="multipart/form-data"
                        class="dynamicForm">

                        @csrf
                        <!-- <h5 id="title1">Services in Owned/Leased/Alloted Property</h5> -->
                        <div class="radio-buttons-0">
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <label for="existingProperty" class="custom-radio">
                                        <div class="radio-btn">
                                            <div class="content">
                                                <div class="profile-card">
                                                    <h4>Services in existing property</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="radio" name="purposeReg" value="existing_property"
                                            id="existingProperty" class="radio_input_0">
                                    </label>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <label for="allotment" class="custom-radio">
                                        <div class="radio-btn">
                                            <div class="content">
                                                <div class="profile-card">
                                                    <h4>Allotment of New Property</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="radio" name="purposeReg" value="allotment" id="allotment"
                                            class="radio_input_0">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="radio-buttons" style="display: none;">
                            <!-- <div class="d-block text-start">
                                    <a href="javascript:void(0);" class="btn btn-dark backButton0"><i class="lni lni-arrow-left"></i></a>
                                </div> -->
                            <h5 class="mb-0 mt-2">Registration As</h5>
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <label for="propertyowner" class="custom-radio">
                                        <div class="radio-btn">
                                            <div class="content">
                                                <div class="profile-card">
                                                    <h4>Individual Owner/Lessee/Allottee</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="radio" name="newUser" value="propertyowner" id="propertyowner"
                                            class="radio_input">
                                    </label>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <label for="organization" class="custom-radio">
                                        <div class="radio-btn">
                                            <div class="content">
                                                <div class="profile-card">
                                                    <h4>Organization Owner/Lessee/Allottee</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="radio" name="newUser" value="organization" id="organization"
                                            class="radio_input">
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Individual Form by Diwakar Sinha at 04-09-2024 -->
                        <div class="contentDiv" id="propertyownerDiv">
                            <h5 class="form_section_title mb-0 mt-2">Property Owner/Lessee/Allottee Details</h5>
                            <div class="row less-padding-input" id="basicDetails">
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                    <label for="indfullname" class="quesLabel">Full Name<span class="text-danger">*</span></label>
                                        <input type="text" name="nameInv" class="form-control alpha-only"
                                            placeholder="Full Name" id="indfullname">
                                        <div id="IndFullNameError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group">
                                    <label for="Indgender" class="quesLabel">Gender<span class="text-danger">*</span></label>
                                    <select name="genderInv" id="Indgender" class="form-select">
                                        <option value="">Select</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Others">Others</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                    <div id="IndGenderError" class="text-danger text-left"></div>
                                    </div>
                                
                                </div>
                                <!-- Col Added By Nitin to input date of birth -->
                                <div class="col-lg-4 col-12">
                                <div class="form-group">
                                    <label for="dateOfBirth" class="quesLabel">Date of Birth<span class="text-danger">*</span></label>
                                    <div class="mix-field">
                                        <input type="date" id="dateOfBirth" name="dateOfBirth" max="{{date('Y-m-d')}}" class="form-control" />
                                        <div class="age-box">
                                            <h4>Age: </h4>
                                            <input type="text" id="age" name="age" class="form-control" placeholder="0" readonly />
                                        </div>
                                    </div>
                                    <div id="dateOfBirthError" class="text-danger text-left"></div>
                                </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group">
                                        <label for="mobileInv" class="quesLabel">Mobile Number<span class="text-danger">*</span></label>
                                        <div class="mix-field">
                                            @if (!empty($countries) && count($countries) > 0)
                                            <select name="countryCode" id="countryCode" class="form-select prefix">
                                                <option value="">Select</option>
                                                @foreach ($countries as $country)
                                                <option value="{{ $country->phonecode }}" @if ($country->phonecode === 91) @selected(true) @endif>
                                                    {{ $country->iso }} (+{{ $country->phonecode }})
                                                </option>
                                                @endforeach
                                            </select>
                                        @endif
                                        <div class="form-box relative-input" style="width: 70%;">
                                            <input type="text" name="mobileInv" data-id="0" id="mobileInv"
                                                maxlength="10" class="form-control numericOnly"
                                                placeholder="Mobile Number">
                                            <a href="javascript:void(0);" class="verify_otp"
                                                id="verify_mobile_otp">Verify</a>
                                            <img src="{{ asset('assets/frontend/assets/img/Green-check-mark-icon2.png') }}"
                                                id="green-tick-icon"
                                                style="
                                                width: 28px;
                                                position: absolute;
                                                right: 12px;
                                                top: 10px;
                                                display:none;
                                            " />
                                            <div class="loader" id="mobile_loader"></div>
                                        </div>
                                        </div>
                                        <div id="IndMobileError" class="text-danger text-left"></div>
                                        <div class="text-danger text-start" id="verify_mobile_otp_error"></div>
                                        <div class="text-success text-start" id="verify_mobile_otp_success"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group">
                                        <label for="emailInv" class="quesLabel">Email Address<span class="text-danger">*</span></label>
                                        <div class="form-box relative-input">
                                            <input type="email" name="emailInv" data-id="0" id="emailInv"
                                                class="form-control" placeholder="Email Address">
                                            <a href="javascript:void(0);" class="verify_otp"
                                                id="verify_email_otp">Verify</a>
                                            <img src="{{ asset('assets/frontend/assets/img/Green-check-mark-icon2.png') }}"
                                                id="green-tick-icon-email"
                                                style="
                                                width: 28px;
                                                position: absolute;
                                                right: 12px;
                                                top: 10px;
                                                display:none;
                                                " />
                                            <div class="loader" id="email_loader"></div>
                                        </div>
                                        <div id="IndEmailError" class="text-danger text-left">
                                        </div>
                                        <div class="text-danger text-start" id="verify_email_otp_error"></div>
                                        <div class="text-success text-start" id="verify_email_otp_success"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group">
                                        <label for="IndSecondName" class="quesLabel">Relation<span class="text-danger">*</span></label>
                                    <div class="mix-field">
                                        <select name="prefixInv" id="prefix"
                                            class="form-select prefix">
                                            <option value="S/o">S/o</option>
                                            <option value="D/o">D/o</option>
                                            <option value="Spouse Of">Spouse Of</option>
                                        </select>
                                        <input type="text" name="secondnameInv" id="IndSecondName"
                                            class="form-control alpha-only" placeholder="Relation">
                                    </div>
                                    <div id="IndSecondNameError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <label for="IndPanNumber" class="quesLabel">PAN Number<span class="text-danger">*</span></label>
                                        <input type="text" name="pannumberInv" id="IndPanNumber"
                                            class="form-control text-transform-uppercase pan_number_format"
                                            placeholder="Pan Number" maxlength="10">
                                        <div id="IndPanNumberError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-12">
                                    <div class="form-group form-box">
                                        <label for="IndAadhar" class="quesLabel">Aadhaar Number<span class="text-danger">*</span></label>
                                        <input type="text" name="adharnumberInv" id="IndAadhar"
                                            class="form-control text-transform-uppercase numericOnly"
                                            placeholder="Aadhaar Number" maxlength="12">
                                        <div id="IndAadharError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group form-box">
                                        <label for="commAddress" class="quesLabel">Communication Address<span class="text-danger">*</span></label>
                                        <textarea name="commAddressInv" id="commAddress" class="form-control" placeholder="Communication Address"></textarea>
                                        <div id="IndCommAddressError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row less-padding-input" id="addressDetails">
                                <div class="col-lg-12 pt-3">
                                    <h5 class="text-start mb-0">Property Details</h5>
                                </div>
                                <div id="ifYesNotChecked" class="row">
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                            <label for="locality" class="quesLabel">Locality<span class="text-danger">*</span></label>
                                            <select name="localityInv" id="locality" class="form-select">
                                                <option value="">Select</option>
                                                @foreach ($colonyList as $colony)
                                                <option value="{{ $colony->id }}">{{ $colony->name }}</option>
                                                @endforeach
                                            </select>
                                            <div id="localityError" class="text-danger text-left"></div>
                                        </div>

                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                            <label for="block" class="quesLabel">Block<span class="text-danger">*</span></label>
                                            <select name="blockInv" id="block" class="form-select">
                                                <option value="">Select</option>
                                            </select>
                                            <div id="blockError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                            <label for="plot" class="quesLabel">Plot<span class="text-danger">*</span></label>
                                            <select name="plotInv" id="plot" class="form-select">
                                                <option value="">Select</option>
                                            </select>
                                            <div id="plotError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                            <label for="knownas" class="quesLabel">Known As</label>
                                            <select name="knownasInv" id="knownas" class="form-select">
                                                <option value="">Select </option>
                                            </select>
                                            <div id="knownasError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                        <label for="landUse" class="quesLabel">Land Use<span class="text-danger">*</span></label>
                                            <select name="landUseInv" id="landUse"
                                                onchange="getSubTypesByType('landUse','landUseSubtype')"
                                                class="form-select">
                                                <option value="">Select</option>
                                                @foreach ($propertyTypes[0]->items as $propertyType)
                                                <option value="{{ $propertyType->id }}">
                                                    {{ $propertyType->item_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <div id="landUseError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                        <label for="landUseSubtype" class="quesLabel">Land Use Sub Type<span class="text-danger">*</span></label>
                                            <select name="landUseSubtypeInv" id="landUseSubtype" class="form-select">
                                                <option value="">Select</option>
                                            </select>
                                            <div id="landUseSubtypeError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="isIndividualFlatDatabaseRecordFound" name="isIndividualFlatDatabaseRecordFound">
                                    <div class="col-lg-4 col-12 isPropertyDetailsNotFoundUnChecked"
                                            style="display: none;">
                                            <div class="form-group">
                                            <label for="flat" class="quesLabel">Flat<span class="text-danger">*</span></label>
                                                <select name="flat" id="flat" class="form-select">
                                                    <option value="">Select</option>
                                                </select>
                                                <div id="flatError" class="text-danger text-left"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-12 isPropertyDetailsNotFoundUnChecked"
                                            style="display: none;">
                                            <div class="form-group">
                                                <div class="mix-field">
                                                    <label for="isFlatNotInList" class="quesLabel">is Flat not
                                                        listed?</label>
                                                    <div class="radio-options ml-5">
                                                        <label for="Yes"><input
                                                                class="form-check required-for-approve"
                                                                name="isFlatNotInList" type="checkbox"
                                                                id="isFlatNotInList"> Yes</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-12 isPropertyDetailsNotFoundUnChecked"
                                            style="display: none;">
                                            <div class="form-group form-box">
                                            <label for="flat_no" class="quesLabel">Flat No.<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="flat_no" id="flat_no"
                                                    placeholder="Enter Flat Number" readonly>
                                                <div id="flat_noError" class="text-danger text-left"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-12 isPropertyDetailsRecordNotFoundUnChecked"
                                            style="display: none;">
                                            <div class="form-group form-box">
                                            <label for="flat_no_rec_not_found" class="quesLabel">Flat No.<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="flat_no_rec_not_found" id="flat_no_rec_not_found"
                                                    placeholder="Enter Flat Number">
                                                <div id="flat_no_rec_not_foundError" class="text-danger text-left"></div>
                                            </div>
                                        </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mix-field" style="margin-bottom: 10px;">
                                        <label for="propertyId_property" class="quesLabel">Is your property
                                            flat?</label>
                                        <div class="radio-options ml-5">
                                            <label for="Yes">
                                                <input type="checkbox" name="isPropertyFlat" value="1"
                                                    class="form-check" id="isPropertyFlat"> Yes
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mix-field">
                                        <label for="propertyId_property" class="quesLabel">Is property details not
                                            found in the above list?</label>
                                        <div class="radio-options ml-5">
                                            <label for="Yes"><input type="checkbox" name="propertyId"
                                                    value="1" class="form-check" id="Yes"> Yes</label>
                                        </div>
                                    </div>

                                    <div class="ifyes internal_container my-3" id="ifyes"
                                        style="display: none;">
                                        <div class="row less-padding-input">
                                            <div class="col-lg-4 col-12">
                                                <div class="form-group form-box">
                                                <label for="localityFill" class="quesLabel">Locality<span class="text-danger">*</span></label>
                                                    <select name="localityInvFill" id="localityFill"
                                                        class="form-select">
                                                        <option value="">Select</option>
                                                        @foreach ($colonyList as $colony)
                                                        <option value="{{ $colony->id }}">
                                                            {{ $colony->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <div id="localityFillError" class="text-danger text-left">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-12">
                                                <div class="form-group form-box">
                                                <label for="blocknoInvFill" class="quesLabel">Block No.<span class="text-danger">*</span></label>
                                                    <input type="text" name="blocknoInvFill"
                                                        id="blocknoInvFill"
                                                        class="form-control alphaNum-hiphenForwardSlash"
                                                        placeholder="Block No.">
                                                    <div id="blocknoInvFillError" class="text-danger text-left">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-12">
                                                <div class="form-group form-box">
                                                <label for="plotnoInvFill" class="quesLabel">Property/Plot No.<span class="text-danger">*</span></label>
                                                    <input type="text" name="plotnoInvFill" id="plotnoInvFill"
                                                        class="form-control plotNoAlpaMix"
                                                        placeholder="Property/Plot No.">
                                                    <div id="plotnoInvFillError" class="text-danger text-left">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-12">
                                                <div class="form-group form-box">
                                                <label for="knownasInvFill" class="quesLabel">Known As (Optional)</label>
                                                    <input type="text" name="knownasInvFill"
                                                        id="knownasInvFill" class="form-control"
                                                        placeholder="Known As (Optional)">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-12">
                                            <div class="form-group">
                                            <label for="plotnoInvFill" class="quesLabel">Land Use<span class="text-danger">*</span></label>
                                                <select name="landUseInvFill" id="landUseInvFill"
                                                    onchange="getSubTypesByType('landUseInvFill','landUseSubtypeInvFill')"
                                                    class="form-select">
                                                    <option value="">Select</option>
                                                    @foreach ($propertyTypes[0]->items as $propertyType)
                                                    <option value="{{ $propertyType->id }}">
                                                        {{ $propertyType->item_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <div id="landUseInvFillError" class="text-danger text-left"></div>
                                            </div>
                                            </div>
                                            <div class="col-lg-4 col-12">
                                            <div class="form-group">
                                                <label for="landUseSubtypeInvFill" class="quesLabel">Land Use Sub Type<span class="text-danger">*</span></label>
                                                <select name="landUseSubtypeInvFill" id="landUseSubtypeInvFill" class="form-select">
                                                    <option value="">Select</option>
                                                </select>
                                                <div id="landUseSubtypeInvFillError" class="text-danger text-left"></div>
                                            </div>
                                            </div>
                                            <div class="col-lg-4 col-12 isPropertyDetailsNotFoundChecked"
                                                    style="display: none;">
                                                    <div class="form-group form-box">
                                                    <label for="flat_no" class="quesLabel">Flat Number<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="propertyId_flat_no"
                                                            id="flat_no" placeholder="Enter Flat Number">
                                                        <div id="flat_noError" class="text-danger text-left"></div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="fileUploadSection">
                                <div class="row less-padding-input">
                                    <div class="col-lg-12">
                                        <h5 class="text-start mb-0 mt-2">Ownership Documents</h5>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <label for="propDoc" class="quesLabel">Sale Deed/Agreement to Sale/Power of Atorney</label>
                                            <input type="file" name="saleDeedDocInv" class="form-control"
                                                accept="application/pdf" id="IndSaleDeed">
                                            <div id="IndSaleDeedError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <label for="propDoc" class="quesLabel">Builder & Buyer Agreement</label>
                                            <input type="file" name="BuilAgreeDocInv" class="form-control"
                                                accept="application/pdf" id="IndBuildAgree">
                                            <div id="IndBuildAgreeError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <label for="propDoc" class="quesLabel">Lease Deed/Conveyance Deed</label>
                                            <input type="file" name="leaseDeedDocInv" class="form-control"
                                                accept="application/pdf" id="IndLeaseDeed">
                                            <div id="IndLeaseDeedError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <label for="propDoc" class="quesLabel">Substitution/Mutation Letter</label>
                                            <input type="file" name="subMutLtrDocInv" class="form-control"
                                                accept="application/pdf" id="IndSubMut">
                                            <div id="IndSubMutError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <label for="otherDocInv" class="quesLabel">Other Documents</label>
                                            <input type="file" name="otherDocInv" class="form-control"
                                                accept="application/pdf" id="IndOther">
                                            <div id="IndOtherError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row less-padding-input">
                                    <div class="col-lg-6 col-12">
                                        <div class="form-group form-box">
                                            <label for="IndOwnerLess" class="quesLabel">Scanned Copy of ID Proof/Document showing relationship with owner/lessee<span class="text-danger">*</span></label>
                                            <input type="file" name="ownLeaseDocInv" class="form-control"
                                                accept="application/pdf" id="IndOwnerLess">
                                            <div id="IndOwnerLessError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div id="generalError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="remarkInv" class="quesLabel">Any Additional Information</label>
                                        <textarea name="remarkInv" id="remarkInv" class="form-control" placeholder="Write..." spellcheck="false"></textarea>
                                        <div id="errorInv" class="text-danger text-left"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row less-padding-input" id="agreementSection">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="checkbox-consent">
                                            <input type="checkbox" name="consentInv" id="IndConsent"
                                                class="form-check" value="on">
                                            <label for="IndConsent">I agree, all the information provided by me is
                                                accurate to the best of my knowledge. I take full responsibility for any
                                                issues or failures that may arise from its use.</label>
                                        </div>
                                        <div id="IndConsentError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-2">
                                    <label class="note text-danger"><strong>Note:</strong> Allowed PDF file size 2MB - 5MB</label>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-lg btn-theme" id="IndsubmitButton"
                                style="display: none;">Register</button>
                        </div>
                        <!-- Individual Form End -->
                        <!-- Individual Form by Diwakar Sinha at 04-09-2024 -->


                        <!-- Organization -->
                        <div class="contentDiv" id="organizationDiv">
                            <h5 class="form_section_title mb-0 mt-2">Property Owner/Lessee/Allottee Details</h5>
                            <div class="row" id="OrgBasicDetails">
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <label for="OrgName" class="quesLabel">Organization Name<span class="text-danger">*</span></label>
                                        <input type="text" name="nameOrg" class="form-control alpha-only"
                                            placeholder="Organization Name" id="OrgName">
                                        <div id="OrgNameError" class="text-danger text-left"></div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <label for="OrgPAN" class="quesLabel">Organisation PAN Number<span class="text-danger">*</span></label>
                                        <input type="text" name="pannumberOrg" class="form-control text-transform-uppercase pan_number_format" placeholder="Organisation PAN Number" maxlength="10" id="OrgPAN">
                                        <div id="OrgPANError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-12">
                                    <div class="form-group form-box">
                                        <label for="orgAddressOrg" class="quesLabel">Organisation Address</label>
                                        <textarea name="orgAddressOrg" id="orgAddressOrg" class="form-control" placeholder="Organisation Address"></textarea>
                                        <div id="orgAddressOrgError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <label for="OrgNameAuthSign" class="quesLabel">Name of Authorised Signatory<span class="text-danger">*</span></label>
                                        <input type="text" name="nameauthsignatory"
                                            class="form-control alpha-only"
                                            placeholder="Name of Authorised Signatory" id="OrgNameAuthSign">
                                        <div id="OrgNameAuthSignError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                            
                                <div class="col-lg-4 col-12">
                                    <div class="form-group">
                                        <label for="authsignatory_mobile" class="quesLabel">Mobile No. of Authorised Signatory<span class="text-danger">*</span></label>
                                        <div class="mix-field">
                                            @if (!empty($countries) && count($countries) > 0)
                                                <select name="countryCodeAuthSignatory" id="countryCodeAuthSignatory" class="form-select prefix">
                                                    <option value="">Select</option>
                                                    @foreach ($countries as $country)
                                                    <option value="{{ $country->phonecode }}" @if ($country->phonecode === 91) @selected(true) @endif>
                                                        {{ $country->iso }} (+{{ $country->phonecode }})
                                                    </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        <div class="form-box relative-input" style="width: 70%;">
                                            <input type="text" data-id="0" name="mobileauthsignatory" id="authsignatory_mobile" maxlength="10" class="form-control numericOnly" placeholder="Mobile No. of Authorised Signatory">
                                            <a href="javascript:void(0);" class="verify_otp"
                                                id="org_verify_mobile_otp">Verify</a>
                                            <img src="{{ asset('assets/frontend/assets/img/Green-check-mark-icon2.png') }}"
                                                id="org_green-tick-icon"
                                                style="
                                                    width: 28px;
                                                    position: absolute;
                                                    right: 12px;
                                                    top: 10px;
                                                    display:none;
                                                " />
                                            <div class="loader" id="org_mobile_loader"></div>
                                        </div>
                                        </div>
                                        <div id="OrgMobileAuthError" class="text-danger text-left"></div>
                                        <div class="text-danger text-start" id="org_verify_mobile_otp_error"></div>
                                        <div class="text-success text-start" id="org_verify_mobile_otp_success"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group">
                                        <label for="emailauthsignatory" class="quesLabel">Email of Authorised Signatory<span class="text-danger">*</span></label>
                                        <div class="form-box relative-input">
                                            <input type="email" data-id="0" name="emailauthsignatory"
                                                id="emailauthsignatory" class="form-control"
                                                placeholder="Email of Authorised Signatory">
                                            <a href="javascript:void(0);" class="verify_otp"
                                                id="org_verify_email_otp">Verify</a>
                                            <img src="{{ asset('assets/frontend/assets/img/Green-check-mark-icon2.png') }}"
                                                id="org_green-tick-icon-email"
                                                style="
                                                    width: 28px;
                                                    position: absolute;
                                                    right: 12px;
                                                    top: 10px;
                                                    display:none;
                                                    " />
                                            <div class="loader" id="org_email_loader"></div>
                                        </div>
                                        <div id="OrgEmailAuthSignError" class="text-danger text-left"></div>
                                        <div class="text-danger text-start" id="org_verify_email_otp_error"></div>
                                        <div class="text-success text-start" id="org_verify_email_otp_success"></div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <label for="orgAadharAuth" class="quesLabel">Aadhaar No. of Authorised Signatory<span class="text-danger">*</span></label>
                                        <input type="text" name="orgAddharNo" class="form-control numericOnly"
                                            placeholder="Aadhaar No. of Authorised Signatory" id="orgAadharAuth"
                                            maxlength="12">
                                        <div id="orgAadharAuthError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="OrgAddressDetails">
                                <div class="col-lg-8"></div>
                                <div class="col-lg-12 pt-3">
                                <h5 class="form_section_title mb-0 mt-2">Property Details</h5>
                                </div>

                                <div class="col-lg-12">
                                    <div id="ifYesNotCheckedOrg" class="row child_columns">
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="locality_org" class="quesLabel">Locality<span class="text-danger">*</span></label>
                                                <select name="localityOrg" id="locality_org" class="form-select">
                                                    <option value="">Select</option>
                                                    @foreach ($colonyList as $colony)
                                                    <option value="{{ $colony->id }}">{{ $colony->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <div id="locality_orgError" class="text-danger text-left"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="block_org" class="quesLabel">Block<span class="text-danger">*</span></label>
                                                <select name="blockOrg" id="block_org"
                                                    class="form-select alphaNum-hiphenForwardSlash">
                                                    <option value="">Select</option>
                                                </select>
                                                <div id="block_orgError" class="text-danger text-left"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="plot_org" class="quesLabel">Plot<span class="text-danger">*</span></label>
                                                <select name="plotOrg" id="plot_org"
                                                    class="form-select plotNoAlpaMix">
                                                    <option value="">Select</option>
                                                </select>
                                                <div id="plot_orgError" class="text-danger text-left"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="knownas_org" class="quesLabel">Known As (Optional)</label>
                                                <select name="knownasOrg" id="knownas_org" class="form-select">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-12">
                                            <div class="form-group">
                                                <label for="landUse_org" class="quesLabel">Land Use<span class="text-danger">*</span></label>
                                                <select name="landUseOrg" id="landUse_org"
                                                    onchange="getSubTypesByType('landUse_org','landUseSubtype_org')"
                                                    class="form-select">
                                                    <option value="">Select</option>
                                                    @foreach ($propertyTypes[0]->items as $propertyType)
                                                    <option value="{{ $propertyType->id }}">
                                                        {{ $propertyType->item_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <div id="landUse_orgError" class="text-danger text-left"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-12">
                                            <div class="form-group">
                                                <label for="landUseSubtype_org" class="quesLabel">Land Use Sub Type<span class="text-danger">*</span></label>
                                                <select name="landUseSubtypeOrg" id="landUseSubtype_org"
                                                    class="form-select">
                                                    <option value="">Select</option>
                                                </select>
                                                <div id="landUseSubtype_orgError" class="text-danger text-left"></div>
                                            </div>
                                        </div>
                                        <input type="hidden" id="isOrganisationFlatDatabaseRecordFound" name="isOrganisationFlatDatabaseRecordFound">
                                        <div class="col-lg-4 col-12 isPropertyDetailsNotFoundUnCheckedOrg"
                                                style="display: none;">
                                                <div class="form-group">
                                                    <label for="flatOrg" class="quesLabel">Flat<span class="text-danger">*</span></label>
                                                    <select name="flatOrg" id="flatOrg" class="form-select">
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div id="flatOrgError" class="text-danger text-left"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-12 isPropertyDetailsNotFoundUnCheckedOrg"
                                                style="display: none;">
                                                <div class="form-group">
                                                    <div class="mix-field">
                                                        <label for="isFlatNotInListOrg" class="quesLabel">is Flat not
                                                            listed?</label>
                                                        <div class="radio-options ml-5">
                                                            <label for="Yes"><input
                                                                    class="form-check required-for-approve"
                                                                    name="isFlatNotInListOrg" type="checkbox"
                                                                    id="isFlatNotInListOrg"> Yes</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-12 isPropertyDetailsNotFoundUnCheckedOrg"
                                                style="display: none;">
                                                <div class="form-group form-box">
                                                    <label for="flat_no_org" class="quesLabel">Flat Number<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="flat_no_org"
                                                        id="flat_no_org" placeholder="Enter Flat Number" readonly>
                                                    <div id="flat_no_orgError" class="text-danger text-left"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-12 isPropertyDetailsRecordNotFoundUnCheckedOrg"
                                                style="display: none;">
                                                <div class="form-group form-box">
                                                    <label for="flat_no_org_rec_not_found" class="quesLabel">Flat Number<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="flat_no_org_rec_not_found"
                                                        id="flat_no_org_rec_not_found" placeholder="Enter Flat Number">
                                                    <div id="flat_no_org_rec_not_foundError" class="text-danger text-left"></div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group form-box">
                                        <div class="mix-field" style="margin-bottom: 10px;">
                                            <label for="propertyId_property" class="quesLabel">Is your property
                                                flat?</label>
                                            <div class="radio-options ml-5">
                                                <label for="Yes">
                                                    <input type="checkbox" name="isPropertyFlatOrg" value="1"
                                                        class="form-check" id="isPropertyFlatOrg"> Yes
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mix-field">
                                            <label for="propertyId_property" class="quesLabel">Is property details not
                                                found in the above list?</label>
                                            <div class="radio-options ml-5">
                                                <label for="YesOrg"><input type="checkbox" name="propertyIdOrg"
                                                        value="1" class="form-check" id="YesOrg"> Yes</label>
                                            </div>
                                        </div>

                                        <div class="ifyes internal_container my-3" id="ifyesOrg"
                                            style="display: none;">
                                            <div class="row less-padding-input">
                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="form-group form-box">
                                                        <label for="localityOrgFill" class="quesLabel">Locality<span class="text-danger">*</span></label>
                                                        <select name="localityOrgFill" id="localityOrgFill"
                                                            class="form-select">
                                                            <option value="">Select</option>
                                                            @foreach ($colonyList as $colony)
                                                            <option value="{{ $colony->id }}">
                                                                {{ $colony->name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                        <div id="localityOrgFillError" class="text-danger text-left">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="form-group form-box">
                                                        <label for="blocknoOrgFill" class="quesLabel">Block No.<span class="text-danger">*</span></label>
                                                        <input type="text" name="blocknoOrgFill"
                                                            id="blocknoOrgFill"
                                                            class="form-control alphaNum-hiphenForwardSlash"
                                                            placeholder="Block No.">
                                                        <div id="blocknoOrgFillError" class="text-danger text-left">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="form-group form-box">
                                                        <label for="plotnoOrgFill" class="quesLabel">Property/Plot No.<span class="text-danger">*</span></label>
                                                        <input type="text" name="plotnoOrgFill" id="plotnoOrgFill"
                                                            class="form-control plotNoAlpaMix"
                                                            placeholder="Property/Plot No.">
                                                        <div id="plotnoOrgFillError" class="text-danger text-left">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="form-group form-box">
                                                        <label for="knownasOrgFill" class="quesLabel">Known As (Optional)</label>
                                                        <input type="text" name="knownasOrgFill"
                                                            id="knownasOrgFill" class="form-control"
                                                            placeholder="Known As (Optional)">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <div class="form-group">
                                                        <label for="landUseOrgFill" class="quesLabel">Land Use<span class="text-danger">*</span></label>
                                                        <select name="landUseOrgFill" id="landUseOrgFill"
                                                            onchange="getSubTypesByType('landUseOrgFill','landUseSubtypeOrgFill')"
                                                            class="form-select form-group">
                                                            <option value="">Select</option>
                                                            @foreach ($propertyTypes[0]->items as $propertyType)
                                                            <option value="{{ $propertyType->id }}">
                                                                {{ $propertyType->item_name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                        <div id="landUseOrgFillError" class="text-danger text-left">
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <div class="form-group">
                                                        <label for="landUseSubtypeOrgFill" class="quesLabel">Land Use Sub Type<span class="text-danger">*</span></label>
                                                        <select name="landUseSubtypeOrgFill"
                                                            id="landUseSubtypeOrgFill" class="form-select">
                                                            <option value="">Select</option>
                                                        </select>
                                                        <div id="landUseSubtypeOrgFillError"
                                                            class="text-danger text-left"></div>
                                                    </div>

                                                </div>
                                                <div class="col-lg-4 col-12 isPropertyDetailsNotFoundUnCheckedOrg"
                                                        style="display: none;">
                                                        <div class="form-group form-box">
                                                            <label for="flat_no_org" class="quesLabel">Flat Number<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="propertyIdOrg_flat_no_org"
                                                                id="flat_no_org" placeholder="Enter Flat Number">
                                                            <div id="flat_no_orgError" class="text-danger text-left">
                                                            </div>
                                                        </div>
                                                    </div>

                                            </div>
                                        </div>
                                    </div>

                                    
                                </div>
                            </div>
                            <div id="OrgfileUploadSection">
                                <div class="row less-padding-input pt-2">
                                    <div class="col-lg-6 col-12">
                                        <label for="OrgSignAuthDoc" class="quesLabel">Document showing signatory authority<span class="text-danger">*</span></label>
                                        <div class="form-group form-box">
                                            <input type="file" name="propDoc" class="form-control"
                                                accept="application/pdf" id="OrgSignAuthDoc">
                                                <div id="OrgSignAuthDocError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="form-group form-box">
                                            <label for="scannedIDOrg" class="quesLabel">Scanned Copy of ID Proof<span class="text-danger">*</span></label>
                                            <input type="file" name="scannedIDOrg" class="form-control"
                                                accept="application/pdf" id="scannedIDOrg">
                                            <div id="scannedIDOrgError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row less-padding-input pt-2">
                                    <div class="col-lg-12">
                                        <h5 class="form_section_title mb-0 mt-2">Ownership Document</h5>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <label for="saleDeedOrg" class="quesLabel">Sale Deed/Agreement to Sale/Power of Atorney</label>
                                            <input type="file" name="saleDeedOrg" class="form-control"
                                                accept="application/pdf" id="OrgSaleDeedDoc">
                                                <div id="OrgSaleDeedDocError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <label for="builBuyerAggrmentDoc" class="quesLabel">Builder & Buyer
                                                Agreement</label>
                                            <input type="file" name="builBuyerAggrmentDoc" class="form-control"
                                                accept="application/pdf" id="OrgBuildAgreeDoc">
                                                <div id="OrgBuildAgreeDocError" class="text-danger text-left"></div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <label for="leaseDeedDoc" class="quesLabel">Lease Deed/Conveyance Deed</label>
                                            <input type="file" name="leaseDeedDoc" class="form-control"
                                                accept="application/pdf" id="OrgLeaseDeedDoc">
                                                <div id="OrgLeaseDeedDocError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <label for="subMutLetterDoc" class="quesLabel">Substitution/Mutation
                                                Letter</label>
                                            <input type="file" name="subMutLetterDoc" class="form-control"
                                                accept="application/pdf" id="OrgSubMutDoc">
                                                <div id="OrgSubMutDocError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <label for="otherDoc" class="quesLabel">Other Documents</label>
                                            <input type="file" name="otherDoc" class="form-control"
                                                accept="application/pdf" id="OrgOther">
                                            <div id="OrgOtherError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div id="generalError2" class="text-danger text-left"></div>
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="remarkOrg" class="quesLabel">Any Additional Information</label>
                                        <textarea name="remarkOrg" id="remarkOrg" class="form-control" placeholder="Write..." spellcheck="false"></textarea>
                                        <div id="errorOrg" class="text-danger text-left"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row less-padding-input" id="OrgAgreementSection">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="checkbox-consent">
                                            <input type="checkbox" name="consentOrg" id="OrgConsent"
                                                class="form-check" value="on">
                                            <label for="OrgConsent">I agree, all the information provided by me is
                                                accurate to the best of my knowledge. I take full responsibility for any
                                                issues or failures that may arise from its use.</label>
                                        </div>
                                        <div id="OrgConsentError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-2">
                                    <label class="note text-danger"><strong>Note:</strong> Allowed PDF file size 2MB - 5MB</label>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-lg btn-theme" id="OrgsubmitButton"
                                style="display: none;">Register</button>
                        </div>
                    </form>

                    <div class="clearfix"></div>
                    <p class="mt-2">Already a member? <a href="{{ url('login') }}">Login here</a></p>
                    <!-- <div class="alert alert-success border-0 bg-success alert-dismissible mt-4">
                            <div class="text-white">You are Registered successfully, and your registration no. is:- ALP0004345</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <div class="alert alert-danger border-0 bg-danger alert-dismissible">
                            <div class="text-white">Registration not successfull!</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div> -->
                </div>
            </div>

        </div>
    </div>
    <div class="ocean">
        <div class="wave"></div>
        <div class="wave"></div>
    </div>
</div>
@endsection

@section('footerScript')
<script src="{{asset('assets/frontend/assets/js/otp-input.js')}}"></script>

<script>
    function isValidMobile(mobile) {
        var mobilePattern = /^[0-9]{10}$/;
        return mobilePattern.test(mobile);
    }

    function isValidEmail(email) {
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }
    $(document).ready(function() {
        var selectedRadio0 = null;
        var selectedRadio = null;

        $('.radio_input_0').change(function() {
            selectedRadio0 = $(this).val();
            if (selectedRadio0) {
                $('.radio-buttons').show()
                $('#continueButton').show()
                $('#important_news_container').hide();
                $('.radio-buttons').show();
                $('.backButton').show();
                $("#radioErr").removeClass('d-inline')
                $("#radioErr").addClass('d-none')
            } else {
                $("#radioErr").addClass('d-inline')
                $("#radioErr").removeClass('d-none')
            }
        });
        $('.radio_input').change(function() {
            selectedRadio = $(this).val();
            $('#title3d').hide()
            $('#title2').show()
            $('#important_news_container').hide();
            $('.hide-after-select-usertype').hide();
            $('#formColumnIncrease').attr('class', 'col-lg-12 form-section');
            $('#' + selectedRadio + 'Div').show();
            $('#IndsubmitButton').show();
            $('#OrgsubmitButton').show();
            $('.backButton').show();
            $("#radioErr").removeClass('d-inline')
            $("#radioErr").addClass('d-none')
        });
        $('#propertyowner').change(function() {
            $('#propertyownerDiv').show();
            $('#organizationDiv').hide();
        });
        $('#organization').change(function() {
            $('#propertyownerDiv').hide();
            $('#organizationDiv').show();
        });


    });

    // Yes/No Do you Know Property ID?
    $(document).ready(function() {
        $('#Yes').change(function() {
            if ($(this).is(':checked')) {
                $('#ifyes').show();
                $('#locality').val('')
                $('#block').val('')
                $('#plot').val('')
                $('#knownas').val('')
                $('#landUse').val('');
                $('#landUseSubtype').val('');
                $('#flat').val('');
                $('#flat_no').val('');
                $('#ifYesNotChecked').hide();
            } else {
                $('#localityFill').val('')
                $('#blocknoInvFill').val('')
                $('#plotnoInvFill').val('')
                $('#knownasInvFill').val('')
                $('#landUseInvFill').val('')
                $('#landUseSubtypeInvFill').val('')
                $('#flat').val('');
                $('#flat_no').val('');
                $('#ifyes').hide();
                $('#ifYesNotChecked').show();
            }
        });

        $('#YesOrg').change(function() {
            if ($(this).is(':checked')) {
                $('#ifyesOrg').show();
                $('#locality_org').val('')
                $('#block_org').val('')
                $('#plot_org').val('')
                $('#knownas_org').val('')
                $('#landUseOrg').val('')
                $('#flatOrg').val('');
                $('#flat_no_org').val('');
                $('#landUseSubtypeOrg').val('')
                $('#ifYesNotCheckedOrg').hide();
            } else {
                $('#localityOrgFill').val('')
                $('#blocknoOrgFill').val('')
                $('#plotnoOrgFill').val('')
                $('#knownasOrgFill').val('')
                $('#landUseOrgFill').val('')
                $('#landUseSubtypeOrgFill').val('')
                $('#flatOrg').val('');
                $('#flat_no_org').val('');
                $('#ifyesOrg').hide();
                $('#ifYesNotCheckedOrg').show();
            }
        });
    });


    $(document).ready(function() {
        $(".numericOnly").on("input", function(e) {
            $(this).val($(this).val().replace(/[^0-9]/g, ""));
        });
        //for verifying mobile
        $('#verify_mobile_otp').click(function() {
            console.log('called');
            var countryCode = $('#countryCode').val();
            var mobile = $('#mobileInv').val().trim();
            var emailToVerify = $('#emailInv').val().trim();
            var errorDiv = $('#verify_mobile_otp_error');
            var successDiv = $('#verify_mobile_otp_success');
            var countryCodeDiv = $('#countryCodeError');
            if (mobile == '') {
                errorDiv.html('Mobile number is required')
            } else if (!isValidMobile(mobile)) {
                errorDiv.html('Invalid mobile number');
            } else {
                $('#verify_mobile_otp').hide();
                $('#mobile_loader').show();
                $.ajax({
                    url: "{{route('saveOtp')}}",
                    type: "POST",
                    data: {
                        emailToVerify: emailToVerify,
                        mobile: mobile,
                        countryCode:countryCode,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        if (result.success) {
                            $('#verify_mobile_otp').show();
                            $('#mobile_loader').hide();
                            errorDiv.html('')
                            successDiv.html(result.message)
                            $('#otpMobile').modal("show")
                            // $("#otpMobile").css("display", "block");
                        } else {
                            $('#verify_mobile_otp').show();
                            $('#mobile_loader').hide();
                            successDiv.html('')
                            errorDiv.html(result.message)
                        }
                    }
                });
            }

        });

        //for verifying email
        $('#verify_email_otp').click(function() {
            var email = $('#emailInv').val().trim();
            var mobileToVerify = $('#mobileInv').val().trim();
            var errorDiv = $('#verify_email_otp_error');
            var successDiv = $('#verify_email_otp_success');
            if (email == '') {
                errorDiv.html('Email is required')
            } else if (!isValidEmail(email)) {
                errorDiv.html('Invalid Email');
            } else {
                $('#verify_email_otp').hide();
                $('#email_loader').show();
                $.ajax({
                    url: "{{route('saveOtp')}}",
                    type: "POST",
                    data: {
                        email: email,
                        mobileToVerify: mobileToVerify,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        if (result.success) {
                            $('#verify_email_otp').show();
                            $('#email_loader').hide();
                            errorDiv.html('')
                            successDiv.html(result.message)
                            $('#otpEmail').modal("show")

                            // $("#otpMobile").css("display", "block");
                        } else {
                            $('#verify_email_otp').show();
                            $('#email_loader').hide();
                            successDiv.html('')
                            errorDiv.html(result.message)
                        }
                    }
                });
            }
        });

        //for organization
        //for verifying mobile
        $('#org_verify_mobile_otp').click(function() {
            var countryCode = $('#countryCodeAuthSignatory').val();
            var mobile = $('#authsignatory_mobile').val().trim();
            var emailToVerify = $('#emailauthsignatory').val().trim();
            var errorDiv = $('#org_verify_mobile_otp_error');
            var successDiv = $('#org_verify_mobile_otp_success');
            if (mobile == '') {
                errorDiv.html('Mobile number is required')
            } else if (!isValidMobile(mobile)) {
                errorDiv.html('Invalid mobile number');
            } else {
                $('#org_verify_mobile_otp').hide();
                $('#org_mobile_loader').show();
                $.ajax({
                    url: "{{route('saveOtp')}}",
                    type: "POST",
                    data: {
                        emailToVerify: emailToVerify,
                        mobile: mobile,
                        countryCode:countryCode,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        if (result.success) {
                            $('#org_verify_mobile_otp').show();
                            $('#org_mobile_loader').hide();
                            errorDiv.html('')
                            successDiv.html(result.message)
                            $('#orgOtpMobile').modal("show")
                            // $("#otpMobile").css("display", "block");
                        } else {
                            $('#org_verify_mobile_otp').show();
                            $('#org_mobile_loader').hide();
                            successDiv.html('')
                            errorDiv.html(result.message)
                        }
                    }
                });
            }

        });

        //for verifying email
        $('#org_verify_email_otp').click(function() {
            console.log("Called");
            var email = $('#emailauthsignatory').val().trim();
            var mobileToVerify = $('#authsignatory_mobile').val().trim();
            var errorDiv = $('#org_verify_email_otp_error');
            var successDiv = $('#org_verify_email_otp_success');
            if (email == '') {
                errorDiv.html('Email is required')
            } else if (!isValidEmail(email)) {
                errorDiv.html('Invalid Email');
            } else {
                $('#org_verify_email_otp').hide();
                $('#org_email_loader').show();
                $.ajax({
                    url: "{{route('saveOtp')}}",
                    type: "POST",
                    data: {
                        email: email,
                        mobileToVerify: mobileToVerify,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        if (result.success) {
                            $('#org_verify_email_otp').show();
                            $('#org_email_loader').hide();
                            errorDiv.html('')
                            successDiv.html(result.message)
                            $('#orgOtpEmail').modal("show")

                            // $("#otpMobile").css("display", "block");
                        } else {
                            $('#org_verify_email_otp').show();
                            $('#org_email_loader').hide();
                            successDiv.html('')
                            errorDiv.html(result.message)
                        }
                    }
                });
            }

        });


        $("#verifyMobileOtpBtn").click(function() {
            var otpDigits = [];
            var verifyMobileBtn = $('#verify_mobile_otp')
            var mobileVerifyErrorDiv = $('#mobileOptVerifyError');
            var mobileVerifySuccessDiv = $('#mobileOptVerifySuccess');
            var errorDiv = $('#verify_mobile_otp_error');
            var successDiv = $('#verify_mobile_otp_success');
            $(".otp_input").each(function() {
                otpDigits.push($(this).val());
            });
            var mobileOtp = otpDigits.join('');
            var mobile = $('#mobileInv').val();

            if (mobile != '' && mobileOtp != '') {
                $.ajax({
                    url: "{{route('verifyOtp')}}",
                    method: 'POST',
                    data: {
                        mobileOtp: mobileOtp,
                        mobile: mobile,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(response) {
                        if (response.success) {
                            verifyMobileBtn.hide()
                            errorDiv.html('')
                            successDiv.html('')
                            mobileVerifyErrorDiv.html('')
                            $("#mobileInv").prop("readonly", true);
                            $('#green-tick-icon').css("display", "block");
                            $('#otp-form')[0].reset();
                            mobileVerifySuccessDiv.html(response.message)
                            $('#otpMobile').modal("hide")
                            var Indmobile = document.getElementById('mobileInv');
                            Indmobile.setAttribute('data-id', '1');
                        } else {
                            mobileVerifySuccessDiv.html('')
                            mobileVerifyErrorDiv.html(response.message)
                        }

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle error response (e.g., show error message)
                        alert('Error verifying OTP');
                    }
                });
            } else {
                mobileVerifySuccessDiv.html('')
                mobileVerifyErrorDiv.html('Please enter Otp')
            }

        });


        $("#verifyEmailOtpBtn").click(function() {
            var otpDigits = [];
            var verifyMobileBtn = $('#verify_email_otp')
            var emailVerifyErrorDiv = $('#emailOptVerifyError');
            var emailVerifySuccessDiv = $('#emailOptVerifySuccess');
            var errorDiv = $('#verify_email_otp_error');
            var successDiv = $('#verify_email_otp_success');
            $(".otp_input_email").each(function() {
                otpDigits.push($(this).val());
            });
            var emailOtp = otpDigits.join('');
            var email = $('#emailInv').val();

            if (email != '' && emailOtp != '') {
                $.ajax({
                    url: "{{route('verifyOtp')}}",
                    method: 'POST',
                    data: {
                        emailOtp: emailOtp,
                        email: email,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(response) {
                        if (response.success) {
                            verifyMobileBtn.hide()
                            errorDiv.html('')
                            successDiv.html('')
                            emailVerifyErrorDiv.html('')
                            $("#emailInv").prop("readonly", true);
                            $('#green-tick-icon-email').css("display", "block");
                            $('#otp-form-email')[0].reset();
                            emailVerifySuccessDiv.html(response.message)
                            $('#otpEmail').modal("hide")
                            var emailInv = document.getElementById('emailInv');
                            emailInv.setAttribute('data-id', '1');
                        } else {
                            emailVerifySuccessDiv.html('')
                            emailVerifyErrorDiv.html(response.message)
                        }

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle error response (e.g., show error message)
                        alert('Error verifying OTP');
                    }
                });
            } else {
                emailVerifySuccessDiv.html('')
                emailVerifyErrorDiv.html('Please enter Otp')
            }

        });


        //for organization
        $("#orgVerifyMobileOtpBtn").click(function() {
            var otpDigits = [];
            var verifyMobileBtn = $('#org_verify_mobile_otp')
            var mobileVerifyErrorDiv = $('#orgMobileOptVerifyError');
            var mobileVerifySuccessDiv = $('#orgMobileOptVerifySuccess');
            var errorDiv = $('#org_verify_mobile_otp_error');
            var successDiv = $('#org_verify_mobile_otp_success');
            $(".org_mobile_otp_nput").each(function() {
                otpDigits.push($(this).val());
            });
            var mobileOtp = otpDigits.join('');
            var mobile = $('#authsignatory_mobile').val();

            if (mobile != '' && mobileOtp != '') {
                $.ajax({
                    url: "{{route('verifyOtp')}}",
                    method: 'POST',
                    data: {
                        mobileOtp: mobileOtp,
                        mobile: mobile,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(response) {
                        if (response.success) {
                            verifyMobileBtn.hide()
                            errorDiv.html('')
                            successDiv.html('')
                            mobileVerifyErrorDiv.html('')
                            $("#authsignatory_mobile").prop("readonly", true);
                            $('#org_green-tick-icon').css("display", "block");
                            $('#org-otp-form')[0].reset();
                            mobileVerifySuccessDiv.html(response.message)
                            $('#orgOtpMobile').modal("hide")
                            var Indmobile = document.getElementById('authsignatory_mobile');
                            Indmobile.setAttribute('data-id', '1');
                        } else {
                            mobileVerifySuccessDiv.html('')
                            mobileVerifyErrorDiv.html(response.message)
                        }

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle error response (e.g., show error message)
                        alert('Error verifying OTP');
                    }
                });
            } else {
                mobileVerifySuccessDiv.html('')
                mobileVerifyErrorDiv.html('Please enter Otp')
            }

        });


        $("#orgVerifyEmailOtpBtn").click(function() {
            var otpDigits = [];
            var verifyMobileBtn = $('#org_verify_email_otp')
            var emailVerifyErrorDiv = $('#orgEmailOptVerifyError');
            var emailVerifySuccessDiv = $('#orgEmailOptVerifySuccess');
            var errorDiv = $('#org_verify_email_otp_error');
            var successDiv = $('#org_verify_email_otp_success');
            $(".org_otp_input_email").each(function() {
                otpDigits.push($(this).val());
            });
            var emailOtp = otpDigits.join('');
            var email = $('#emailauthsignatory').val();

            if (email != '' && emailOtp != '') {
                $.ajax({
                    url: "{{route('verifyOtp')}}",
                    method: 'POST',
                    data: {
                        emailOtp: emailOtp,
                        email: email,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(response) {
                        if (response.success) {
                            verifyMobileBtn.hide()
                            errorDiv.html('')
                            successDiv.html('')
                            emailVerifyErrorDiv.html('')
                            $("#emailauthsignatory").prop("readonly", true);
                            $('#org_green-tick-icon-email').css("display", "block");
                            $('#org-otp-form-email')[0].reset();
                            emailVerifySuccessDiv.html(response.message)
                            $('#orgOtpEmail').modal("hide")
                            var emailInv = document.getElementById('emailauthsignatory');
                            emailInv.setAttribute('data-id', '1');
                        } else {
                            emailVerifySuccessDiv.html('')
                            emailVerifyErrorDiv.html(response.message)
                        }

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle error response (e.g., show error message)
                        alert('Error verifying OTP');
                    }
                });
            } else {
                emailVerifySuccessDiv.html('')
                emailVerifyErrorDiv.html('Please enter Otp')
            }

        });
    });


    //get all blocks of selected locality
    $('#locality').on('change', function() {
        var locality = this.value;
        $("#block").html('');
        $.ajax({
            url: "{{route('localityBlocks')}}",
            type: "POST",
            data: {
                locality: locality,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                $('#block').html('<option value="">Select</option>');
                $.each(result, function(key, value) {
                    $("#block").append('<option value="' + value.block_no + '">' + value.block_no + '</option>');
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
            url: "{{route('blockPlots')}}",
            type: "POST",
            data: {
                locality: locality,
                block: block,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                // console.log(result);
                $('#plot').html('<option value="">Select</option>');
                $.each(result, function(key, value) {

                    $("#plot").append('<option value="' + value + '">' + value + '</option>');
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
            url: "{{route('plotKnownas')}}",
            type: "POST",
            data: {
                locality: locality,
                block: block,
                plot: plot,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                // console.log(result);
                $('#knownas').html('<option value="">Select</option>');
                $.each(result, function(key, value) {
                    $("#knownas").append('<option value="' + value + '">' + value + '</option>');
                });
            }
        });
    });

    //get known as of selected plot
    $('#knownas').on('change', function() {
            var locality = $('#locality').val();
            var block = $('#block').val();
            var plot = $('#plot').val();
            var knownas = this.value;
            $("#flat").html('');
            $.ajax({
                url: "{{ route('knownAsFlat') }}",
                type: "POST",
                data: {
                    locality: locality,
                    block: block,
                    plot: plot,
                    known_as: knownas,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    // console.log(result);
                    $('#flat').html('<option value="">Select</option>');
                    if (result.length === 0) {
                        // If no data is returned, display a message or handle it as needed
                        $('#flat').append('<option value="">No options available</option>');
                        if($('#isPropertyFlat').is(':checked')){
                            $('.isPropertyDetailsRecordNotFoundUnChecked').show();
                            $('.isPropertyDetailsNotFoundUnChecked').hide();
                        }
                        $('#isIndividualFlatDatabaseRecordFound').val('');
                    } else {
                        if($('#isPropertyFlat').is(':checked')){
                            $('.isPropertyDetailsRecordNotFoundUnChecked').hide();
                            $('.isPropertyDetailsNotFoundUnChecked').show();
                        }
                        $('#isIndividualFlatDatabaseRecordFound').val(1);
                        
                        $.each(result, function(key, value) {
                            $("#flat").append('<option value="' + key + '">' + value +
                                '</option>');
                        });
                    }
                }
            });
        });

        $('#flat').on('change', function() {
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

                    $('#flat_no').val(result.flat_number || '');
                }
            });

        });

        //get known as of selected plot
        $('#knownas_org').on('change', function() {
            var locality = $('#locality_org').val();
            var block = $('#block_org').val();
            var plot = $('#plot_org').val();
            var knownas = this.value;
            $("#flatOrg").html('');
            $.ajax({
                url: "{{ route('knownAsFlat') }}",
                type: "POST",
                data: {
                    locality: locality,
                    block: block,
                    plot: plot,
                    known_as: knownas,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    // console.log(result);
                    $('#flatOrg').html('<option value="">Select</option>');
                    if (result.length === 0) {
                        // If no data is returned, display a message or handle it as needed
                        $('#flatOrg').append('<option value="">No options available</option>');
                        if($('#isPropertyFlatOrg').is(':checked')){
                            $('.isPropertyDetailsRecordNotFoundUnCheckedOrg').show();
                            $('.isPropertyDetailsNotFoundUnCheckedOrg').hide();
                        }
                        $('#isOrganisationFlatDatabaseRecordFound').val(''); 
                    } else {
                        if($('#isPropertyFlatOrg').is(':checked')){
                            $('.isPropertyDetailsRecordNotFoundUnCheckedOrg').hide();
                            $('.isPropertyDetailsNotFoundUnCheckedOrg').show();
                        }
                        $('#isOrganisationFlatDatabaseRecordFound').val(1);
                        $.each(result, function(key, value) {
                            $("#flatOrg").append('<option value="' + key + '">' + value +
                                '</option>');
                        });
                    }

                   
                }
            });
        });

        $('#flatOrg').on('change', function() {
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

                    $('#flat_no_org').val(result.flat_number || '');
                }
            });

        });

    //for organization 
    //get all blocks of selected locality
    $('#locality_org').on('change', function() {
        var locality = this.value;
        $("#block_org").html('');
        $.ajax({
            url: "{{route('localityBlocks')}}",
            type: "POST",
            data: {
                locality: locality,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                $('#block_org').html('<option value="">Select</option>');
                $.each(result, function(key, value) {
                    $("#block_org").append('<option value="' + value.block_no + '">' + value.block_no + '</option>');
                });
            }
        });
    });

    //get all plots of selected block
    $('#block_org').on('change', function() {
        var locality = $('#locality_org').val();
        var block = this.value;
        $("#plot_org").html('');
        $.ajax({
            url: "{{route('blockPlots')}}",
            type: "POST",
            data: {
                locality: locality,
                block: block,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                // console.log(result);
                $('#plot_org').html('<option value="">Select Plot</option>');
                $.each(result, function(key, value) {

                    $("#plot_org").append('<option value="' + value + '">' + value + '</option>');
                });
            }
        });
    });


    //get known as of selected plot
    $('#plot_org').on('change', function() {
        var locality = $('#locality_org').val();
        var block = $('#block_org').val();
        var plot = this.value;
        $("#knownas_org").html('');
        $.ajax({
            url: "{{route('plotKnownas')}}",
            type: "POST",
            data: {
                locality: locality,
                block: block,
                plot: plot,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                // console.log(result);
                $('#knownas_org').html('<option value="">Select</option>');
                $.each(result, function(key, value) {

                    $("#knownas_org").append('<option value="' + value + '">' + value + '</option>');
                });
            }
        });
    });

    function getSubTypesByType(type, subtype) {
        var idPropertyType = $(`#${type}`).val();
        $(`#${subtype}`).html('');
        $.ajax({
            url: "{{route('prpertySubTypes')}}",
            type: "POST",
            data: {
                property_type_id: idPropertyType,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {

                $(`#${subtype}`).html('<option value="">Select</option>');
                $.each(result, function(key, value) {
                    $(`#${subtype}`).append('<option value="' + value
                        .id + '">' + value.item_name + '</option>');
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const allowedCharsRegex = /^[0-9a-zA-Z\s\-\#\/\:\,\.\(\)]*$/;
        const maxLength = 250;

        const remarkInv = document.getElementById('remarkInv');
        const remarkOrg = document.getElementById('remarkOrg');
        const errorInv = document.getElementById('errorInv');
        const errorOrg = document.getElementById('errorOrg');

        function validateTextarea(textarea, errorDiv) {
            textarea.addEventListener('input', () => {
                const value = textarea.value;
                if (!allowedCharsRegex.test(value)) {
                    errorDiv.textContent = 'Invalid characters entered.';
                    textarea.value = value.replace(/[^0-9a-zA-Z\s\-\#\/\:\.\(\)]/g, '');
                } else {
                    errorDiv.textContent = '';
                }

                if (value.length > maxLength) {
                    textarea.value = value.substring(0, maxLength);
                    errorDiv.textContent = `Maximum length of ${maxLength} characters exceeded.`;
                }
            });
        }

        validateTextarea(remarkInv, errorInv);
        validateTextarea(remarkOrg, errorOrg);
    });


    //code added by Nitin after date of birth input is added

    function calculateAge(dob) {
        const today = new Date(); // Get today's date
        let age = today.getFullYear() - dob.getFullYear(); // Calculate the year difference

        // Adjust the age if the birthday hasn't occurred yet this year
        const monthDifference = today.getMonth() - dob.getMonth();
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dob.getDate())) {
            age--;
        }

        return age;
    }

    $('#dateOfBirth').change(function() {
        let selectedDate = $(this).val();
        if (selectedDate) {
            let dob = new Date(selectedDate);
            let age = calculateAge(dob);
            $('#age').val(age);
            if (age < 18) {
                $('#dateOfBirthError').html("You must be 18 or older to continue.")
            } else {
                $('#dateOfBirthError').html('');
            }
        }
    });

    //Code added by Lalit on 14 October for Flat Details
    $(document).ready(function() {
            //For Individual / Owner
            $('#isPropertyFlat').change(function() {
                // Check if the checkbox is checked
                let isChecked = $(this).is(':checked');
                if (isChecked) {
                    let checkFlatDataIsAvailable = document.getElementById('isIndividualFlatDatabaseRecordFound').value;
                    if(checkFlatDataIsAvailable){
                        $('.isPropertyDetailsNotFoundChecked').show();
                        $('.isPropertyDetailsNotFoundUnChecked').show();
                        $('.isPropertyDetailsRecordNotFoundUnChecked').hide();
                    } else {
                        $('.isPropertyDetailsNotFoundChecked').hide();
                        $('.isPropertyDetailsNotFoundUnChecked').hide();
                        $('.isPropertyDetailsRecordNotFoundUnChecked').show();
                    }
                    if($('#Yes').is(':checked')){
                        $('.isPropertyDetailsNotFoundChecked').show();
                    } else {
                        $('.isPropertyDetailsNotFoundChecked').show();
                    }
                   
                } else {
                    $('.isPropertyDetailsNotFoundChecked').hide();
                    $('.isPropertyDetailsNotFoundUnChecked').hide();
                    $('.isPropertyDetailsRecordNotFoundUnChecked').hide();
                }
            });

            //For Organization
            $('#isPropertyFlatOrg').change(function() {
                // Check if the checkbox is checked
                let isChecked = $(this).is(':checked');
                if (isChecked) {
                    let checkFlatDataIsAvailable = document.getElementById('isOrganisationFlatDatabaseRecordFound').value;
                    if(checkFlatDataIsAvailable){
                        $('.isPropertyDetailsNotFoundCheckedOrg').show();
                        $('.isPropertyDetailsNotFoundUnCheckedOrg').show();
                        $('.isPropertyDetailsRecordNotFoundUnCheckedOrg').hide();
                    } else {
                        $('.isPropertyDetailsNotFoundCheckedOrg').hide();
                        $('.isPropertyDetailsNotFoundUnCheckedOrg').hide();
                        $('.isPropertyDetailsRecordNotFoundUnCheckedOrg').show();
                    }
                    if($('#YesOrg').is(':checked')){
                        $('.isPropertyDetailsNotFoundUnCheckedOrg').show();
                    } else {
                        $('.isPropertyDetailsNotFoundUnCheckedOrg').show();
                    }
                } else {
                    $('.isPropertyDetailsNotFoundCheckedOrg').hide();
                    $('.isPropertyDetailsNotFoundUnCheckedOrg').hide();
                    $('.isPropertyDetailsRecordNotFoundUnCheckedOrg').hide();
                }
            });

            $('#isFlatNotInList').change(function() {
                if ($(this).is(':checked')) {
                    // Checkbox is checked, remove readonly attribute
                    $('#flat_no').removeAttr('readonly');
                } else {
                    // Checkbox is unchecked, add readonly attribute back
                    $('#flat_no').attr('readonly', true);
                }
            });
            $('#isFlatNotInListOrg').change(function() {
                if ($(this).is(':checked')) {
                    // Checkbox is checked, remove readonly attribute
                    $('#flat_no_org').removeAttr('readonly');
                } else {
                    // Checkbox is unchecked, add readonly attribute back
                    $('#flat_no_org').attr('readonly', true);
                }
            });
        });


    // Set the 18 years ago date in Date of Birth by Diwakar Sinha at 18-10-2024
    const today = new Date();
    const eighteenYearsAgo = new Date();
    eighteenYearsAgo.setFullYear(today.getFullYear() - 18);
    const maxDate = eighteenYearsAgo.toISOString().split('T')[0];
    document.getElementById('dateOfBirth').setAttribute('max', maxDate);

</script>
@endsection