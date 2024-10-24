<div id="organizationDiv" class="contentDiv">
                            <!-- Organization -->
                            <h5 class="form_section_title">Property Owner/Lessee/Allottee Details</h5>
                            <div class="row">
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <input type="text" name="nameOrg" class="form-control alpha-only"
                                            placeholder="Organization Name*" id="OrgName">
                                        <div id="OrgNameError" class="text-danger text-left"></div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <input type="text" name="pannumberOrg"
                                            class="form-control text-transform-uppercase pan_number_format"
                                            placeholder="Organisation PAN Number*" maxlength="10" id="OrgPAN">
                                        <div id="OrgPANError" class="text-danger text-left"></div>
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
                                        <input type="text" name="nameauthsignatory" class="form-control alpha-only"
                                            placeholder="Name of Authorised Signatory*" id="OrgNameAuthSign">
                                        <div id="OrgNameAuthSignError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box relative-input">
                                        <input type="text" data-id="0" name="mobileauthsignatory" id="authsignatory_mobile"
                                            maxlength="10" class="form-control numericOnly"
                                            placeholder="Mobile No. of Authorised Signatory*">
                                        <a href="javascript:void(0);" class="verify_otp"
                                            id="org_verify_mobile_otp">Verify</a>
                                        <img src="{{asset('assets/frontend/assets/img/Green-check-mark-icon2.png')}}"
                                            id="org_green-tick-icon" style="
                                            width: 28px;
                                            position: absolute;
                                            right: 12px;
                                            top: 10px;
                                            display:none;
                                        " />
                                        <div class="loader" id="org_mobile_loader"></div>
                                    </div>
                                    <div id="OrgMobileAuthError" class="text-danger text-left"
                                        style="margin-top: -12px;"></div>
                                    <div class="text-danger text-start" id="org_verify_mobile_otp_error"></div>
                                    <div class="text-success text-start" id="org_verify_mobile_otp_success"></div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box relative-input">
                                        <input type="email" data-id="0" name="emailauthsignatory" id="emailauthsignatory"
                                            class="form-control" placeholder="Email of Authorised Signatory*">
                                        <a href="javascript:void(0);" class="verify_otp"
                                            id="org_verify_email_otp">Verify</a>
                                        <img src="{{asset('assets/frontend/assets/img/Green-check-mark-icon2.png')}}"
                                            id="org_green-tick-icon-email" style="
                                            width: 28px;
                                            position: absolute;
                                            right: 12px;
                                            top: 10px;
                                            display:none;
                                            " />
                                        <div class="loader" id="org_email_loader"></div>
                                    </div>
                                    <div id="OrgEmailAuthSignError" class="text-danger text-left"
                                        style="margin-top: -12px;"></div>
                                    <div class="text-danger text-start" id="org_verify_email_otp_error"></div>
                                    <div class="text-success text-start" id="org_verify_email_otp_success"></div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <input type="text" name="orgAddharNo" class="form-control numericOnly"
                                            placeholder="Adhaar No. of Authorised Signatory*" id="orgAadharAuth"
                                            maxlength="12">
                                        <div id="orgAadharAuthError" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-lg-8"></div>
                                <div class="col-lg-12 pt-3">
                                    <h6 class="text-start mb-0">Property Details</h6>
                                </div>

                                <div class="col-lg-12">
                                    <div id="ifYesNotCheckedOrg" class="row child_columns">
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <select name="localityOrg" id="locality_org" class="form-select form-group">
                                                <option value="">Select Locality</option>
                                                @foreach($colonyList as $colony)
                                                <option value="{{$colony->id}}">{{$colony->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <select name="blockOrg" id="block_org"
                                                class="form-select form-group alphaNum-hiphenForwardSlash">
                                                <option value="">Select Block</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <select name="plotOrg" id="plot_org"
                                                class="form-select form-group plotNoAlpaMix">
                                                <option value="">Select Plot</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <select name="knownasOrg" id="knownas_org"
                                                class="form-select form-group alpha-only">
                                                <option value="">Full Address (Optional)</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-12">
                                            <select name="landUseOrg" id="landUse_org" onchange="getSubTypesByType('landUse_org','landUseSubtype_org')" class="form-select form-group">
                                                <option value="">Select land use</option>
                                                @foreach ($propertyTypes[0]->items as $propertyType)
                                                    <option value="{{$propertyType->id}}">{{ $propertyType->item_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-12">
                                            <select name="landUseSubtypeOrg" id="landUseSubtype_org" class="form-select form-group">
                                                <option value="">Select land use subtype</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group form-box">
                                        <div class="mix-field">
                                            <label for="propertyId_property" class="quesLabel">Is property details not
                                                found in the above list?</label>
                                            <div class="radio-options ml-5">
                                                <label for="YesOrg"><input type="checkbox" name="propertyIdOrg"
                                                        value="1" class="form-check" id="YesOrg"> Yes</label>
                                            </div>
                                        </div>

                                        <div class="ifyes internal_container my-3" id="ifyesOrg" style="display: none;">
                                            <div class="row less-padding-input">
                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="form-group form-box">
                                                        <select name="localityOrgFill" id="localityOrgFill"
                                                            class="form-select">
                                                            <option value="">Select Locality</option>
                                                            @foreach($colonyList as $colony)
                                                            <option value="{{$colony->id}}">{{$colony->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="form-group form-box">
                                                        <input type="text" name="blocknoOrgFill" id="blocknoOrgFill"
                                                            class="form-control alphaNum-hiphenForwardSlash"
                                                            placeholder="Block No.">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="form-group form-box">
                                                        <input type="text" name="plotnoOrgFill" id="plotnoOrgFill"
                                                            class="form-control plotNoAlpaMix"
                                                            placeholder="Property/Plot No.">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="form-group form-box">
                                                        <input type="text" name="knownasOrgFill" id="knownasOrgFill"
                                                            class="form-control"
                                                            placeholder="Full Address (Optional)">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <select name="landUseOrgFill" id="landUseOrgFill"
                                                        onchange="getSubTypesByType('landUseOrgFill','landUseSubtypeOrgFill')"
                                                        class="form-select form-group">
                                                        <option value="">Select land use</option>
                                                        @foreach ($propertyTypes[0]->items as $propertyType)
                                                        <option value="{{$propertyType->id}}">{{
                                                            $propertyType->item_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <select name="landUseSubtypeOrgFill" id="landUseSubtypeOrgFill"
                                                        class="form-select form-group">
                                                        <option value="">Select land use subtype</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <textarea name="remarkOrg" id="remarkOrg" class="form-control" placeholder="Remark" spellcheck="false"></textarea>
                                    <div id="errorOrg" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="row less-padding-input pt-4">
                                <div class="col-lg-12">
                                    <h6 class="text-start mb-0">Document showing signatory authority</h6>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="form-group form-box">
                                        <input type="file" name="propDoc" class="form-control fileInput"
                                            accept="application/pdf" id="OrgSignAuthDoc">
                                        <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf
                                            file, up to 5 MB)</label>
                                    </div>
                                    <div id="OrgSignAuthDocError" class="text-danger text-left"
                                        style="margin-top: -12px;"></div>
                                </div>
                            </div>
                            <div class="row less-padding-input pt-4">
                                <div class="col-lg-12">
                                    <h6 class="text-start mb-0">Ownership document</h6>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <label for="saleDeedOrg" class="quesLabel">Sale Deed</label>
                                        <input type="file" name="saleDeedOrg" class="form-control"
                                            accept="application/pdf" id="OrgSaleDeedDoc">
                                        <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf
                                            file, up to 5 MB)</label>
                                    </div>
                                    <div id="OrgSaleDeedDocError" class="text-danger text-left"
                                        style="margin-top: -12px;"></div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <label for="builBuyerAggrmentDoc" class="quesLabel">Builder & Buyer
                                            Agreement</label>
                                        <input type="file" name="builBuyerAggrmentDoc" class="form-control"
                                            accept="application/pdf" id="OrgBuildAgreeDoc">
                                        <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf
                                            file, up to 5 MB)</label>
                                    </div>
                                    <div id="OrgBuildAgreeDocError" class="text-danger text-left"
                                        style="margin-top: -12px;"></div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <label for="leaseDeedDoc" class="quesLabel">Lease Deed</label>
                                        <input type="file" name="leaseDeedDoc" class="form-control"
                                            accept="application/pdf" id="OrgLeaseDeedDoc">
                                        <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf
                                            file, up to 5 MB)</label>
                                    </div>
                                    <div id="OrgLeaseDeedDocError" class="text-danger text-left"
                                        style="margin-top: -12px;"></div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <label for="subMutLetterDoc" class="quesLabel">Substitution/Mutation
                                            Letter</label>
                                        <input type="file" name="subMutLetterDoc" class="form-control"
                                            accept="application/pdf" id="OrgSubMutDoc">
                                        <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf
                                            file, up to 5 MB)</label>
                                    </div>
                                    <div id="OrgSubMutDocError" class="text-danger text-left"
                                        style="margin-top: -12px;"></div>
                                </div>

                                <div class="col-lg-4 col-12">
                                    <div class="form-group form-box">
                                        <label for="otherDoc" class="quesLabel">Other Documents</label>
                                        <input type="file" name="otherDoc" class="form-control" accept="application/pdf" id="OrgOther">
                                        <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf file, up to 5 MB)</label>
                                        <div id="OrgOtherError" class="text-danger text-left"></div>
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
                            <button type="button" class="btn btn-primary btn-lg btn-theme" id="OrgsubmitButton"
                                style="display: none;">Register</button>
                        </div>