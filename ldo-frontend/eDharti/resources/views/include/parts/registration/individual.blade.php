                 
                                <form action="{{route('publicRegisterCreate')}}" method="POST" enctype="multipart/form-data" id="propertyownerDiv" class="contentDiv">
                                    @csrf
                            <h5 class="mb-3 form_section_title">Property Owner/Lessee/Allottee Details</h5>
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
                                            <option value="Spouse Of">Spouse Of</option>
                                        </select>
                                        <input type="text" name="secondnameInv" id="IndSecondName" class="form-control alpha-only" placeholder="Full Name*">
                                    </div>
                                    <div id="IndSecondNameError" class="text-danger text-left" style="margin-top: -12px;"></div>
                                </div>
                                
                                <!-- <div class="col-lg-6 col-12">
                                    <div class="form-group form-box relative-input">
                                        <input type="text" name="mobileInv" data-id="0" id="mobileInv" maxlength="10" class="form-control numericOnly" placeholder="Mobile Number*">
                                        <a href="javascript:void(0);" class="verify_otp" id="verify_mobile_otp">Verify</a>
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
                                </div> -->
                                <!-- <div class="col-lg-6 col-12">
                                    <div class="form-group form-box relative-input">
                                        <input type="email" name="emailInv" data-id="0" id="emailInv" class="form-control" placeholder="Email Address*">
                                        <a href="javascript:void(0);" class="verify_otp" id="verify_email_otp">Verify</a>
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
                                </div> -->
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <input type="text" name="pannumberInv" id="IndPanNumber" class="form-control text-transform-uppercase pan_number_format" placeholder="Pan Number*" maxlength="10">
                                        <div id="IndPanNumberError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-12">
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
                                <div class="col-lg-12 pt-3">
                                    <h6 class="text-start mb-0">Property Details</h6>
                                </div>
                                <div id="ifYesNotChecked" class="row">
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                            <select name="localityInv" id="locality" class="form-select">
                                                <option value="">Select Locality</option>
                                                @foreach($colonyList as $colony)
                                                    <option value="{{$colony->id}}">{{$colony->name}}</option>
                                                @endforeach
                                            </select>
                                            <div id="localityError" class="text-danger text-left"></div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                            <select name="blockInv" id="block" class="form-select">
                                                <option value="">Select Block</option>
                                            </select>
                                            <div id="blockError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                            <select name="plotInv" id="plot" class="form-select">
                                                <option value="">Select Plot</option>
                                            </select>
                                            <div id="plotError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                            <select name="knownasInv" id="knownas" class="form-select">
                                                <option value="">Full Address (Optional)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                            <select name="landUseInv" id="landUse" onchange="getSubTypesByType('landUse','landUseSubtype')" class="form-select">
                                                <option value="">Select land use</option>
                                                @foreach ($propertyTypes[0]->items as $propertyType)
                                                    <option value="{{$propertyType->id}}">{{ $propertyType->item_name }}</option>
                                                @endforeach
                                            </select>
                                            <div id="landUseError" class="text-danger text-left"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                            <select name="landUseSubtypeInv" id="landUseSubtype" class="form-select">
                                                <option value="">Select land use subtype</option>
                                            </select>
                                            <div id="landUseSubtypeError" class="text-danger text-left"></div>
                                        </div>
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
                                                        <div id="localityFillError" class="text-danger text-left"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <div class="form-group form-box">
                                                        <input type="text" name="blocknoInvFill" id="blocknoInvFill" class="form-control alphaNum-hiphenForwardSlash" placeholder="Block No.">
                                                        <div id="blocknoInvFillError" class="text-danger text-left"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <div class="form-group form-box">
                                                        <input type="text" name="plotnoInvFill" id="plotnoInvFill" class="form-control plotNoAlpaMix" placeholder="Property/Plot No.">
                                                        <div id="plotnoInvFillError" class="text-danger text-left"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <div class="form-group form-box">
                                                        <input type="text" name="knownasInvFill" id="knownasInvFill" class="form-control" placeholder="Full Address (Optional)">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <select name="landUseInvFill" id="landUseInvFill" onchange="getSubTypesByType('landUseInvFill','landUseSubtypeInvFill')" class="form-select form-group">
                                                        <option value="">Select land use</option>
                                                        @foreach ($propertyTypes[0]->items as $propertyType)
                                                            <option value="{{$propertyType->id}}">{{ $propertyType->item_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div id="landUseInvFillError" class="text-danger text-left"></div>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <select name="landUseSubtypeInvFill" id="landUseSubtypeInvFill" class="form-select form-group">
                                                        <option value="">Select land use subtype</option>
                                                    </select>
                                                    <div id="landUseSubtypeInvFillError" class="text-danger text-left"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <textarea name="remarkInv" id="remarkInv" class="form-control" placeholder="Remark" spellcheck="false"></textarea>
                                    <div id="errorInv" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="row less-padding-input pt-4">
                                <div class="col-lg-12">
                                    <h6 class="text-start mb-0">Ownership documents</h6>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <label for="propDoc" class="quesLabel">Sale Deed</label>
                                        <input type="file" name="saleDeedDocInv" class="form-control" accept="application/pdf" id="IndSaleDeed">
                                        <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf file, up to 5 MB)</label>
                                        <div id="IndSaleDeedError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <label for="propDoc" class="quesLabel">Builder & Buyer Agreement</label>
                                        <input type="file" name="BuilAgreeDocInv" class="form-control" accept="application/pdf" id="IndBuildAgree">
                                        <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf file, up to 5 MB)</label>
                                        <div id="IndBuildAgreeError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <label for="propDoc" class="quesLabel">Lease Deed</label>
                                        <input type="file" name="leaseDeedDocInv" class="form-control" accept="application/pdf" id="IndLeaseDeed">
                                        <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf file, up to 5 MB)</label>
                                        <div id="IndLeaseDeedError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <label for="propDoc" class="quesLabel">Substitution/Mutation Letter</label>
                                        <input type="file" name="subMutLtrDocInv" class="form-control" accept="application/pdf" id="IndSubMut">
                                        <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf file, up to 5 MB)</label>
                                        <div id="IndSubMutError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <label for="otherDocInv" class="quesLabel">Other Documents</label>
                                        <input type="file" name="otherDocInv" class="form-control" accept="application/pdf" id="IndOther">
                                        <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf file, up to 5 MB)</label>
                                        <div id="IndOtherError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row less-padding-input">
                                <div class="col-lg-12">
                                    <h6 class="text-start mb-0">Document showing relationship with owner/lessee</h6>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="form-group form-box">
                                        <input type="file" name="ownLeaseDocInv" class="form-control" accept="application/pdf" id="IndOwnerLess">
                                        <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf file, up to 5 MB)</label>
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
                            <!-- <input type="submit" class="btn btn-primary btn-lg btn-theme" id="IndsubmitButton" style="display: none;" value="Register" /> -->
                            <button type="button" class="btn btn-primary btn-lg btn-theme" id="IndsubmitButton" style="display: none;">Register</button>
                        </form>
             