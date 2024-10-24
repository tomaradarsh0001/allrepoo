<div class="mt-3">
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
                    <label for="convname" class="form-label">Name</label>
                    <input type="text" name="convname" class="form-control alpha-only"
                        id="convname" placeholder="Name" readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="convgender" class="form-label">Gender</label>
                    <select class="form-select" name="convgender" id="convgender" disabled>
                        <option value="">Gender</option>
                        <option value="Male" selected>Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="convage" class="form-label">Age</label>
                    <input type="text" name="convage" class="form-control numericOnly" id="convage"
                        maxlength="2" placeholder="Age" readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="convfathername" class="form-label">Relation</label>
                    <div class="input-group mb-3"> <span class="input-group-text" id="convprefixApp"></span>
                        <input type="text" name="fathername" class="form-control alpha-only"
                            id="convfathername" placeholder="Name" readonly>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="convaadhar" class="form-label">Aadhaar</label>
                    <input type="text" name="convaadhar" class="form-control numericOnly"
                        id="convaadhar" maxlength="12" placeholder="Aadhar Number" readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="convpan" class="form-label">PAN</label>
                    <input type="text" name="convpan"
                        class="form-control pan_number_format text-uppercase" id="convpan"
                        maxlength="10" placeholder="PAN Number" readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="convmobilenumber" class="form-label">Mobile Number</label>
                    <input type="text" name="convmobilenumber" class="form-control numericOnly"
                        id="convmobilenumber" maxlength="10" placeholder="Mobile Number" readonly>
                </div>
            </div>
        </div>
        <!-- row end -->
        <div class="row g-2">
            <div class="col-lg-12">
                <div id="CONrepeater" class="position-relative conversion-coapplicants">

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
                        <div class="items" data-group="convcoapplicant">
                            <!-- Repeater Content -->
                            <div class="item-content mb-2">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="namergapp"
                                                class="form-label">Name</label>
                                            <input type="text" name="convcoapplicant"
                                                class="form-control alpha-only"
                                                placeholder="Name" id="convcoapplicant"
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
                                                maxlength="12" placeholder="Aadhar Number"
                                                data-name="aadharnumber">
                                        </div>
                                    </div>
                                    <!-- ------------------------ -->
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="photo" class="form-label">Upload Aadhaar</label>
                                            <input type="file" name="convcoapplicant[0][aadhaarFile]" class="form-control" accept=".pdf" data-name="aadhaarFile">
                                        </div>
                                    </div>
                                    <!-- ------------------------ -->
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="pan" class="form-label">PAN</label>
                                            <input type="text" name="pan"
                                                class="form-control pan_number_format text-uppercase"
                                                id="pan" maxlength="10" placeholder="PAN Number"
                                                data-name="pannumber">
                                        </div>
                                    </div>
                                    <!-- ------------------------ -->
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="photo" class="form-label">Upload PAN</label>
                                            <input type="file" name="convcoapplicant[0][panFile]" class="form-control" accept=".pdf" data-name="panFile">
                                        </div>
                                    </div>
                                    <!-- ------------------------ -->
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
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="photo" class="form-label">Photo</label>
                                            <input type="file" name="convcoapplicantphoto[0][photo]" class="form-control" accept=".jpg, .png, .jpeg" data-name="photo">
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
                    <label for="convNameAsOnLease" class="form-label">Executed in favour of<span class="text-danger">*</span></label>
                    <input type="text" name="convNameAsOnLease" class="form-control alpha-only" id="convNameAsOnLease" placeholder="Executed in favour of" value="{{ isset($application) ? $application->name_as_per_lease_conv_deed : '' }}">
                </div>
            </div>
            <!-- <div class="col-lg-4">
                <div class="form-group">
                    <label for="fathername" class="form-label">Father's name</label>
                    <input type="text" name="fathername" class="form-control alpha-only"
                        id="fathername" placeholder="Father's Name">
                </div>
            </div> -->
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="convExecutedOnAsOnLease" class="form-label">Executed On<span class="text-danger">*</span></label>
                    <input type="date" name="convExecutedOnAsOnLease" class="form-control" id="convExecutedOnAsOnLease"
                        placeholder="Executed On" value="{{ isset($application) ? $application->executed_on : '' }}">
                    <div id="convExecutedOnAsOnLeaseError" class="text-danger text-left"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="regno" class="form-label">Regn. No.<span class="text-danger">*</span></label>
                    <input type="text" name="convRegnoAsOnLease" class="form-control numericOnly" id="regno" placeholder="Registration No." value="{{ isset($application) ? $application->reg_no_as_per_lease_conv_deed : '' }}">
                    <div id="regnoError" class="text-danger text-left"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="bookno" class="form-label">Book No.<span class="text-danger">*</span></label>
                    <input type="text" name="convBooknoAsOnLease" class="form-control numericOnly" id="bookno" placeholder="Book No." value="{{ isset($application) ? $application->book_no_as_per_lease_conv_deed : '' }}">
                    <div id="booknoError" class="text-danger text-left"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="volumeno" class="form-label">Volume No.<span class="text-danger">*</span></label>
                    <input type="text" name="convVolumenoAsOnLease" class="form-control numericOnly" id="volumeno" placeholder="Volume No." value="{{ isset($application) ? $application->volume_no_as_per_lease_conv_deed : '' }}">
                    <div id="volumenoError" class="text-danger text-left"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="pageno" class="form-label">Page No.<span class="text-danger">*</span></label>
                    <div class="row">
                        <div class="col-lg-5"><input type="text" name="convPagenoFrom" class="form-control numericOnly" id="convPagenoFrom" placeholder="From" value="{{ isset($application) ? $application->page_no_from : '' }}"></div>
                        <div class="col-lg-2">&hyphen;</div>
                        <div class="col-lg-5"><input type="text" name="convPagenoTo" class="form-control numericOnly" id="convPagenoTo" placeholder="To" value="{{ isset($application) ? $application->page_no_to : '' }}"></div>
                    </div>
                    <!-- <input type="text" name="convPagenoAsOnLease" class="form-control numericOnly" id="pageno" placeholder="Page No." value="{{ isset($application) ? $application->page_no_as_per_lease_conv_deed : '' }}"> -->
                    <div id="pagenoError" class="text-danger text-left"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="regdate" class="form-label">Regn. Date.<span class="text-danger">*</span></label>
                    <input type="date" name="convRegdateAsOnLease" class="form-control" id="regdate" value="{{ isset($application) ? $application->reg_date_as_per_lease_conv_deed : '' }}">
                    <div id="regdateError" class="text-danger text-left"></div>
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
                            <input type="text" name="convCaseNo" class="form-control alphaNum-hiphenForwardSlash" id="convCaseNo" placeholder="Case No.">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="convCaseDetail" class="form-label">Details</label>
                            <textarea name="convCaseDetail" id="convCaseDetail" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- row end -->

    </div>
</div>