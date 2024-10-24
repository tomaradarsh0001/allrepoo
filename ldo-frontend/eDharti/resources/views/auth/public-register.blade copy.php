@extends('layouts.public.app')
@section('title', 'Registration')

@section('content')
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
            <div class="col-lg-12" id="title3d">
                <div class="fixed_login_container">
                    <div class="title">
                        <div class="bottom-container">
                            Welcome to eDharti
                        </div>
                        <div class="top-container">
                            Welcome to eDharti
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 mx-auto form-section" id="formColumnIncrease">
                <div class="form-inner">
                    <div class="form-inner-head" style="display: none;" id="title2">
                        <div class="d-block text-start">
                            <a href="javascript:void(0);" class="btn btn-light backButton"><i class="lni lni-arrow-left"></i></a>
                        </div>
                        <h3>Registration</h3>
                    </div>
                    <h3 id="title1">Registration</h3>
                    <form action="{{route('publicRegisterCreate')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="radio-buttons-0">
                            <label for="existingProperty" class="custom-radio">
                                <div class="radio-btn">
                                    <div class="content">
                                        <div class="profile-card">
                                            <h4>Services in existing property</h4>
                                        </div>
                                    </div>
                                </div>
                                <input type="radio" name="purposeReg" value="existing_property" id="existingProperty" class="radio_input_0">
                            </label>
                            <label for="allotment" class="custom-radio">
                                <div class="radio-btn">
                                    <div class="content">
                                        <div class="profile-card">
                                            <h4>Allotment</h4>
                                        </div>
                                    </div>
                                </div>
                                <input type="radio" name="purposeReg" value="allotment" id="allotment" class="radio_input_0">
                            </label>
                        </div>
                        <div class="radio-buttons" style="display: none;">
                            <div class="d-block text-start">
                                <a href="javascript:void(0);" class="btn btn-dark backButton0"><i class="lni lni-arrow-left"></i></a>
                            </div>
                            <label for="propertyowner" class="custom-radio">
                                <div class="radio-btn">
                                    <div class="content">
                                        <div class="profile-card">
                                            <span class="profile-icon">
                                                <img src="{{asset('assets/frontend/assets/img/user.svg')}}" alt="Property Owner">
                                            </span>
                                            <h4>Property Owner/Lessee</h4>
                                        </div>
                                    </div>
                                </div>
                                <input type="radio" name="newUser" value="propertyowner" id="propertyowner" class="radio_input">
                            </label>
                            <label for="organization" class="custom-radio">
                                <div class="radio-btn">
                                    <div class="content">
                                        <div class="profile-card">
                                            <span class="profile-icon">
                                                <img src="{{asset('assets/frontend/assets/img/organization.svg')}}" alt="organization">
                                            </span>
                                            <h4>Organization</h4>
                                        </div>
                                    </div>
                                </div>
                                <input type="radio" name="newUser" value="organization" id="organization" class="radio_input">
                            </label>
                        </div>
                        <div id="propertyownerDiv" class="contentDiv">
                            <h5 class="mb-3">Property Owner/Lessee</h5>
                            <div class="row less-padding-input">
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <input type="text" name="nameInv" class="form-control alpha-only" placeholder="Full Name*" id="indfullname">
                                        <div id="IndFullNameError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <select name="genderInv" id="Indgender" class="form-select form-group">
                                        <option value="">Gender*</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Others">Others</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                    <div id="IndGenderError" class="text-danger text-left" style="margin-top: -12px;"></div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="mix-field">
                                        <select name="prefixInv" id="prefix" class="form-select form-group prefix">
                                            <option value="S/o">S/o</option>
                                            <option value="D/o">D/o</option>
                                            <option value="Spouse">Spouse</option>
                                        </select>
                                        <input type="text" name="secondnameInv" id="IndSecondName" class="form-control alpha-only" placeholder="Full Name*">
                                    </div>
                                    <div id="IndSecondNameError" class="text-danger text-left" style="margin-top: -12px;"></div>
                                </div>
                                
                                <div class="col-lg-6 col-12">
                                    <div class="form-group form-box relative-input">
                                        <input type="text" name="mobileInv" id="mobileInv" maxlength="10" class="form-control numericOnly" placeholder="Mobile Number*">
                                        <a href="javascript:void()" class="verify_otp" id="verify_mobile_otp">Verify</a>
                                        <img src="{{asset('assets/frontend/assets/img/Green-check-mark-icon2.png')}}" id="green-tick-icon" style="
                                            width: 28px;
                                            position: absolute;
                                            right: 12px;
                                            top: 10px;
                                            display:none;
                                        " />
                                        <div class="loader" id="mobile_loader"></div>
                                    </div>
                                    <div id="IndMobileError" class="text-danger text-left" style="margin-top: -12px;"></div>
                                    <div class="text-danger text-start" id="verify_mobile_otp_error"></div>
                                    <div class="text-success text-start" id="verify_mobile_otp_success"></div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="form-group form-box relative-input">
                                        <input type="email" name="emailInv" id="emailInv" class="form-control" placeholder="Email Address*">
                                        <a href="javascript:void()" class="verify_otp" id="verify_email_otp">Verify</a>
                                        <img src="{{asset('assets/frontend/assets/img/Green-check-mark-icon2.png')}}" id="green-tick-icon-email" style="
                                            width: 28px;
                                            position: absolute;
                                            right: 12px;
                                            top: 10px;
                                            display:none;
                                            " />
                                        <div class="loader" id="email_loader"></div>
                                    </div>
                                    <div id="IndEmailError" class="text-danger text-left" style="margin-top: -12px;"></div>
                                    <div class="text-danger text-start" id="verify_email_otp_error"></div>
                                    <div class="text-success text-start" id="verify_email_otp_success"></div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <input type="text" name="pannumberInv" id="IndPanNumber" class="form-control text-transform-uppercase pan_number_format" placeholder="Pan Number*" maxlength="10">
                                        <div id="IndPanNumberError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-12">
                                    <div class="form-group form-box">
                                        <input type="text" name="adharnumberInv" id="IndAadhar" class="form-control text-transform-uppercase numericOnly" placeholder="Aadhaar Number*" maxlength="12">
                                        <div id="IndAadharError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group form-box">
                                        <textarea name="commAddressInv" id="commAddress" class="form-control" placeholder="Communication Address"></textarea>
                                    </div>
                                </div>
                                <div id="ifYesNotChecked" class="row">
                                    <div class="col-lg-4 col-12">
                                        <select name="localityInv" id="locality" class="form-select form-group">
                                            <option value="">Select Locality</option>
                                            @foreach($colonyList as $colony)
                                                <option value="{{$colony->id}}">{{$colony->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <select name="blockInv" id="block" class="form-select form-group">
                                            <option value="">Select Block</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <select name="plotInv" id="plot" class="form-select form-group">
                                            <option value="">Select Plot</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <select name="knownasInv" id="knownas" class="form-select form-group">
                                            <option value="">Known As</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <select name="landUseInv" id="landUse" onchange="getSubTypesByType('landUse','landUseSubtype')" class="form-select form-group">
                                            <option value="">Select land use</option>
                                            @foreach ($propertyTypes[0]->items as $propertyType)
                                                <option value="{{$propertyType->id}}">{{ $propertyType->item_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <select name="landUseSubtypeInv" id="landUseSubtype" class="form-select form-group">
                                            <option value="">Select land use subtype</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group form-box">
                                       <div class="mix-field">
                                        <label for="propertyId_property" class="quesLabel">Is property details not found in the above list?</label>
                                        <div class="radio-options ml-5">
                                            <label for="Yes"><input type="checkbox" name="propertyId" value="1" class="form-check" id="Yes"> Yes</label>
                                        </div>
                                       </div>
                                       
                                        <div class="ifyes internal_container my-3" id="ifyes" style="display: none;">
                                            <div class="row less-padding-input">
                                                <div class="col-lg-4 col-12">
                                                    <div class="form-group form-box">
                                                        <select name="localityInvFill" id="localityFill" class="form-select">
                                                            <option value="">Select Locality</option>
                                                            @foreach($colonyList as $colony)
                                                                <option value="{{$colony->id}}">{{$colony->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="text-danger text-left"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <div class="form-group form-box">
                                                        <input type="text" name="blocknoInvFill" id="blocknoInvFill" class="form-control alphaNum-hiphenForwardSlash" placeholder="Block No.">
                                                        <div class="text-danger text-left"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <div class="form-group form-box">
                                                        <input type="text" name="plotnoInvFill" id="plotnoInvFill" class="form-control plotNoAlpaMix" placeholder="Property/Plot No.">
                                                        <div class="text-danger text-left"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <div class="form-group form-box">
                                                        <input type="text" name="knownasInvFill" id="knownasInvFill" class="form-control" placeholder="Known As (Optional)">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <select name="landUseInvFill" id="landUseInvFill" onchange="getSubTypesByType('landUseInvFill','landUseSubtypeInvFill')" class="form-select form-group">
                                                        <option value="">Select land use</option>
                                                        @foreach ($propertyTypes[0]->items as $propertyType)
                                                            <option value="{{$propertyType->id}}">{{ $propertyType->item_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="text-danger text-left"></div>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <select name="landUseSubtypeInvFill" id="landUseSubtypeInvFill" class="form-select form-group">
                                                        <option value="">Select land use subtype</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                  
                                </div>
                            </div>
                            <div class="row less-padding-input">
                                <div class="col-lg-12">
                                    <h6 class="text-start mb-0">Ownership documents</h6>
                                </div>
                                <div class="col-lg-3 col-12">
                                    <div class="form-group form-box">
                                        <label for="propDoc" class="quesLabel">Sale Deed</label>
                                        <input type="file" name="saleDeedDocInv" class="form-control" accept="application/pdf" id="IndSaleDeed">
                                        <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf file, up to 50 MB)</label>
                                        <div id="IndSaleDeedError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-12">
                                    <div class="form-group form-box">
                                        <label for="propDoc" class="quesLabel">Builder & Buyer Agreement</label>
                                        <input type="file" name="BuilAgreeDocInv" class="form-control" accept="application/pdf" id="IndBuildAgree">
                                        <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf file, up to 50 MB)</label>
                                        <div id="IndBuildAgreeError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-12">
                                    <div class="form-group form-box">
                                        <label for="propDoc" class="quesLabel">Lease Deed</label>
                                        <input type="file" name="leaseDeedDocInv" class="form-control" accept="application/pdf" id="IndLeaseDeed">
                                        <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf file, up to 50 MB)</label>
                                        <div id="IndLeaseDeedError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-12">
                                    <div class="form-group form-box">
                                        <label for="propDoc" class="quesLabel">Substitution/Mutation Letter</label>
                                        <input type="file" name="subMutLtrDocInv" class="form-control" accept="application/pdf" id="IndSubMut">
                                        <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf file, up to 50 MB)</label>
                                        <div id="IndSubMutError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row less-padding-input">
                                <div class="col-lg-12">
                                    <h6 class="text-start mb-0">Document showing relationship with owner/lessee</h6>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="form-group form-box">
                                        <label for="propDoc" class="quesLabel">Owner/Lessee</label>
                                        <input type="file" name="ownLeaseDocInv" class="form-control" accept="application/pdf" id="IndOwnerLess">
                                        <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf file, up to 50 MB)</label>
                                        <div id="IndOwnerLessError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row less-padding-input">
                                <div class="col-lg-12">
                                    <div class="form-group checkbox-consent">
                                        <input type="checkbox" name="consentInv" id="consent" class="form-check">
                                        <label for="consent">I agree...</label>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-lg btn-theme" id="IndsubmitButton" style="display: none;">Register</button>
                        </div>
                        <div id="organizationDiv" class="contentDiv">
                                <!-- Organization -->
                                <h5>Organization</h5>
                                <div class="row less-padding-input">
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <input type="text" name="nameOrg" class="form-control"
                                                placeholder="Organization Name">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <input type="text" name="pannumberOrg"
                                                class="form-control text-transform-uppercase"
                                                placeholder="Organisation PAN Number" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-12">
                                        <div class="form-group form-box">
                                            <textarea name="orgAddressOrg" id="orgAddressOrg" class="form-control"
                                                placeholder="Organisation Address"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <input type="text" name="nameauthsignatory" class="form-control"
                                                placeholder="Name of Authorised Signatory">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box relative-input">
                                        <input type="text" name="mobileauthsignatory" id="authsignatory_mobile" maxlength="10" class="form-control numericOnly" placeholder="Mobile No. of Authorised Signatory">
                                        <a href="javascript:void()" class="verify_otp" id="org_verify_mobile_otp">Verify</a>
                                        <img src="{{asset('assets/frontend/assets/img/Green-check-mark-icon2.png')}}" id="org_green-tick-icon" style="
                                            width: 28px;
                                            position: absolute;
                                            right: 12px;
                                            top: 10px;
                                            display:none;
                                        " />
                                        <div class="loader" id="org_mobile_loader"></div>
                                    </div>
                                    <div class="text-danger text-start" id="org_verify_mobile_otp_error"></div>
                                    <div class="text-success text-start" id="org_verify_mobile_otp_success"></div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box relative-input">
                                        <input type="email" name="emailauthsignatory" id="emailauthsignatory" class="form-control" placeholder="Email of Authorised Signatory">
                                        <a href="javascript:void()" class="verify_otp" id="org_verify_email_otp">Verify</a>
                                        <img src="{{asset('assets/frontend/assets/img/Green-check-mark-icon2.png')}}" id="org_green-tick-icon-email" style="
                                            width: 28px;
                                            position: absolute;
                                            right: 12px;
                                            top: 10px;
                                            display:none;
                                            " />
                                        <div class="loader" id="org_email_loader"></div>
                                    </div>
                                    <div class="text-danger text-start" id="org_verify_email_otp_error"></div>
                                    <div class="text-success text-start" id="org_verify_email_otp_success"></div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group form-box">
                                            <input type="text" name="orgAddharNo" class="form-control"
                                                placeholder="Adhaar No. of Authorised Signatory">
                                        </div>
                                    </div>
                                    <div class="col-lg-8"></div>
                                    <div class="col-lg-12">
                                        <h6 class="text-start mb-0">Enter Property Details</h6>
                                    </div>
                                    
                                    <div id="ifYesNotCheckedOrg" class="row">
                                    <div class="col-lg-4 col-12">
                                        <select name="localityOrg" id="locality_org" class="form-select form-group">
                                            <option value="">Select Locality</option>
                                            @foreach($colonyList as $colony)
                                                <option value="{{$colony->id}}">{{$colony->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <select name="blockOrg" id="block_org" class="form-select form-group">
                                            <option value="">Select Block</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <select name="plotOrg" id="plot_org" class="form-select form-group">
                                            <option value="">Select Plot</option>
                                        </select>
                                    </div>
                                    <!-- <div class="col-lg-4 col-12">
                                        <select name="flatInv" id="flat" class="form-select form-group">
                                            <option value="">Select Flat No.</option>
                                        </select>
                                    </div> -->
                                    <div class="col-lg-4 col-12">
                                        <select name="knownasOrg" id="knownas_org" class="form-select form-group">
                                            <option value="">Known As</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <select name="landUseOrg" id="landUseOrg" onchange="getSubTypesByType('landUseOrg','landUseSubtypeOrg')" class="form-select form-group">
                                            <option value="">Select land use</option>
                                            @foreach ($propertyTypes[0]->items as $propertyType)
                                                <option value="{{$propertyType->id}}">{{ $propertyType->item_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                        <div class="col-lg-4 col-12">
                                            <select name="landUseSubtypeOrg" id="landUseSubtypeOrg" class="form-select form-group">
                                                <option value="">Select land use subtype</option>
                                            </select>
                                        </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group form-box">
                                       <div class="mix-field">
                                        <label for="propertyId_property" class="quesLabel">Is property details not found in the above list?</label>
                                        <div class="radio-options ml-5">
                                            <label for="Yes"><input type="checkbox" name="propertyIdOrg" value="1" class="form-check" id="YesOrg"> Yes</label>
                                        </div>
                                       </div>
                                       
                                        <div class="ifyes internal_container my-3" id="ifyesOrg" style="display: none;">
                                            <div class="row less-padding-input">
                                                <div class="col-lg-4 col-12">
                                                    <div class="form-group form-box">
                                                        <select name="localityOrgFill" id="localityOrgFill" class="form-select">
                                                            <option value="">Select Locality</option>
                                                            @foreach($colonyList as $colony)
                                                                <option value="{{$colony->id}}">{{$colony->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <div class="form-group form-box">
                                                        <input type="text" name="blocknoOrgFill" id="blocknoOrgFill" class="form-control" placeholder="Block No.">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <div class="form-group form-box">
                                                        <input type="text" name="plotnoOrgFill" id="plotnoOrgFill" class="form-control" placeholder="Property/Plot No.">
                                                    </div>
                                                </div>
                                                <!-- <div class="col-lg-4 col-12">
                                                    <div class="form-group form-box">
                                                        <input type="text" name="flatnoInvFill" class="form-control" placeholder="Flat No.">
                                                    </div>
                                                </div> -->
                                                <div class="col-lg-4 col-12">
                                                    <div class="form-group form-box">
                                                        <input type="text" name="knownasOrgFill" id="knownasOrgFill" class="form-control" placeholder="Known As (Optional)">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <select name="landUseOrgFill" id="landUseOrgFill" onchange="getSubTypesByType('landUseOrgFill','landUseSubtypeOrgFill')" class="form-select form-group">
                                                        <option value="">Select land use</option>
                                                        @foreach ($propertyTypes[0]->items as $propertyType)
                                                            <option value="{{$propertyType->id}}">{{ $propertyType->item_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <select name="landUseSubtypeOrgFill" id="landUseSubtypeOrgFill" class="form-select form-group">
                                                        <option value="">Select land use subtype</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                  
                                </div>









                                </div>
                                <div class="row less-padding-input">
                                    <div class="col-lg-12">
                                        <h6 class="text-start mb-0">Document showing signatory authority</h6>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="form-group form-box">
                                            <input type="file" name="propDoc" id="fileInput" class="form-control fileInput">
                                            <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf
                                                file, up to 50 MB)</label>
                                                <p id="errorMessage" class="text-start text-danger"></p>
                                            </div>
                                    </div>
                                </div>
                                <div class="row less-padding-input">
                                    <div class="col-lg-12">
                                        <h6 class="text-start mb-0">Ownership document</h6>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="form-group form-box">
                                            <label for="saleDeedOrg" class="quesLabel">Sale Deed</label>
                                            <input type="file" name="saleDeedOrg" class="form-control">
                                            <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf
                                                file, up to 50 MB)</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="form-group form-box">
                                            <label for="builBuyerAggrmentDoc" class="quesLabel">Builder & Buyer Agreement</label>
                                            <input type="file" name="builBuyerAggrmentDoc" class="form-control">
                                            <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf
                                                file, up to 50 MB)</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="form-group form-box">
                                            <label for="leaseDeedDoc" class="quesLabel">Lease Deed</label>
                                            <input type="file" name="leaseDeedDoc" class="form-control">
                                            <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf
                                                file, up to 50 MB)</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="form-group form-box">
                                            <label for="subMutLetterDoc" class="quesLabel">Substitution/Mutation Letter</label>
                                            <input type="file" name="subMutLetterDoc" class="form-control">
                                            <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf
                                                file, up to 50 MB)</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row less-padding-input">
                                    <div class="col-lg-12">
                                        <div class="form-group checkbox-consent">
                                            <input type="checkbox" name="consentOrg" id="consentOrg" class="form-check">
                                            <label for="consent">I agree...</label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg btn-theme" id="OrgsubmitButton" style="display: none;">Register</button>
                            </div>
                        <div class="form-group">
                            <a href="javascript:void(0);" class="btn btn-primary btn-theme" id="continueButton0">Continue</a>
                            <a href="javascript:void(0);" class="btn btn-primary btn-theme" id="continueButton" style="display: none;">Continue</a>
                            <span class="text-danger d-none" id="radioErr">Please select an option.</span>
                        </div>
                    </form>
                    <div class="clearfix"></div>
                    <p>Already a member? <a href="{{url('login')}}">Login here</a></p>
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
<script>
    
</script>