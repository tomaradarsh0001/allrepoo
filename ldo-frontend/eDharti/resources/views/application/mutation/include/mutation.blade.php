<div class="mt-3">
    <div class="container-fluid">
        <div class="row g-2">
            <div class="col-lg-12">
                <div class="part-title mb-2">
                    <h5>Name & details of Registered Applicant</h5>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="mutNameApp" class="form-label">Name</label>
                    <input type="text" name="mutNameApp" class="form-control alpha-only" id="mutNameApp" placeholder="Name" readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="mutGenderApp" class="form-label">Gender</label>
                    <input type="text" name="mutGenderApp" class="form-control alpha-only" id="mutGenderApp" placeholder="Gender" readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="mutAgeApp" class="form-label">Age</label>
                    <input type="text" name="mutAgeApp" class="form-control numericOnly" id="mutAgeApp" maxlength="2"
                        placeholder="Age" readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <label for="mutFathernameApp" class="form-label">Father's name</label>
                <div class="input-group mb-3"> <span class="input-group-text" id="mutprefixApp"></span>
                    <input type="text" name="mutFathernameApp" id="mutFathernameApp" class="form-control alpha-only" placeholder="Full Name*" readonly>
                </div>   
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="mutAadharApp" class="form-label">Aadhar</label>
                    <input type="text" name="mutAadharApp" class="form-control numericOnly" id="mutAadharApp" maxlength="12"
                        placeholder="Aadhar Number" readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="mutPanApp" class="form-label">PAN</label>
                    <input type="text" name="mutPanApp" class="form-control pan_number_format text-uppercase" id="mutPanApp"
                        maxlength="10" placeholder="PAN Number" readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="mutMobilenumberApp" class="form-label">Mobile Number</label>
                    <input type="text" name="mutMobilenumberApp" class="form-control numericOnly" id="mutMobilenumberApp"
                        maxlength="10" placeholder="Mobile Number" readonly>
                </div>
            </div>
        </div>
        <!-- row end -->

         <!-- co applicants ******************************************************** -->
        @include('application.mutation.include.coapplicant')

        <div class="row g-2 mt-2">
            <div class="col-lg-12">
                <div class="part-title mb-2">
                    <h5 id="freeleasetitle"></h5>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="namergapp" class="form-label">Executed in favour of<span class="text-danger">*</span></label>
                    <input type="text" name="mutNameAsConLease" class="form-control alpha-only" id="namergapp" placeholder="Executed in favour of" value="{{ isset($application) ? $application->name_as_per_lease_conv_deed : '' }}">
                    <div id="namergappError" class="text-danger text-left"></div>
                </div>
            </div>
            <!-- <div class="col-lg-4">
                <div class="form-group">
                    <label for="fathername" class="form-label">Father's name<span class="text-danger">*</span></label>
                    <input type="text" name="mutFathernameAsConLease" class="form-control alpha-only" id="fathername"
                        placeholder="Father's Name" value="{{ isset($application) ? $application->father_name_as_per_lease_conv_deed : '' }}">
                </div>
            </div> -->
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="fathername" class="form-label">Executed On<span class="text-danger">*</span></label>
                    <input type="date" name="mutExecutedOnAsConLease" class="form-control" id="mutExecutedOnAsConLease"
                        placeholder="Executed On" value="{{ isset($application) ? $application->executed_on : '' }}">
                        <div id="mutExecutedOnAsConLeaseError" class="text-danger text-left"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="regno" class="form-label">Regn. No.<span class="text-danger">*</span></label>
                    <input type="text" name="mutRegnoAsConLease" class="form-control numericOnly" id="regno" placeholder="Registration No." value="{{ isset($application) ? $application->reg_no_as_per_lease_conv_deed : '' }}">
                    <div id="regnoError" class="text-danger text-left"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="bookno" class="form-label">Book No.<span class="text-danger">*</span></label>
                    <input type="text" name="mutBooknoAsConLease" class="form-control numericOnly" id="bookno" placeholder="Book No." value="{{ isset($application) ? $application->book_no_as_per_lease_conv_deed : '' }}">
                    <div id="booknoError" class="text-danger text-left"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="volumeno" class="form-label">Volume No.<span class="text-danger">*</span></label>
                    <input type="text" name="mutVolumenoAsConLease" class="form-control numericOnly" id="volumeno" placeholder="Volume No." value="{{ isset($application) ? $application->volume_no_as_per_lease_conv_deed : '' }}">
                    <div id="volumenoError" class="text-danger text-left"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="pageno" class="form-label">Page No.<span class="text-danger">*</span></label>
                    <input type="text" name="mutPagenoAsConLease" class="form-control numericDecimalHyphen" id="pageno" placeholder="Page No." value="{{ isset($application) ? $application->page_no_as_per_lease_conv_deed : '' }}">
                    <div id="pagenoError" class="text-danger text-left"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="regdate" class="form-label">Regn. Date.<span class="text-danger">*</span></label>
                    <input type="date" name="mutRegdateAsConLease" class="form-control" id="regdate" value="{{ isset($application) ? $application->reg_date_as_per_lease_conv_deed : '' }}">
                    <div id="regdateError" class="text-danger text-left"></div>
                </div>
            </div>
        </div>

        <div class="row g-2">
            <div class="col-lg-12">
                <div class="part-title mb-2">
                    <h5>Mutation/Substitution sought by applicant on basis of</h5>
                </div>
            </div>
            <!-- <div class="col-lg-4">
                <div class="form-group">
                    <select class="form-select" name="soughtByApplicant" id="soughtByApplicant">
                        <option value="">Select</option>
                        <option value="56" {{ isset($application) && $application->sought_on_basis_of == 56 ? 'selected' : '' }}>Sale Deed</option>
                        <option value="57" {{ isset($application) && $application->sought_on_basis_of == 57 ? 'selected' : '' }}>Death Certificate</option>
                        <option value="58" {{ isset($application) && $application->sought_on_basis_of == 58 ? 'selected' : '' }}>Relinquishment Deed</option>
                        <option value="67" {{ isset($application) && $application->sought_on_basis_of == 67 ? 'selected' : '' }}>WILL</option>
                        <option value="34" {{ isset($application) && $application->sought_on_basis_of == 34 ? 'selected' : '' }}>Gift Deed</option>
                        <option value="35" {{ isset($application) && $application->sought_on_basis_of == 35 ? 'selected' : '' }}>Court Order/Decree</option>
                        <option value="45" {{ isset($application) && $application->sought_on_basis_of == 45 ? 'selected' : '' }}>Others</option>
                    </select>
                    <div id="soughtByApplicantError" class="text-danger text-left"></div>
                </div>
            </div> -->

            <div>
            <h6 class="mr-5 mb-3">Select Documents</h6>
                <div class="d-flex align-items-center gap-3 flex-wrap ">
                    @foreach($documentTypes as $document)
                        <div class="form-check form-switch form-check-success">
                            <input class="form-check-input documentType" type="checkbox" value="{{$document->item_code}}" id="">
                            <label class="form-check-label" for="flexCheckSuccess">
                            {{$document->item_name}}
                            </label>
                        </div>
                    @endforeach
                </div>
                <div id="soughtByApplicantError" class="text-danger text-left"></div>
            </div>
        </div>
        <!-- row end -->
        <div class="row g-3 align-items-end mt-3">
        
            <div class="col-lg-6">
                <div class="d-flex align-items-center">
                    <h6 class="mr-5 mb-0">Whether property stands mortgaged?</h6>
                    <div class="form-check mr-5">
                    <input class="form-check-input" name="mutPropertyMortgaged" type="radio" value="1" id="YesMortgaged" 
                        {{ isset($application) && $application->property_stands_mortgaged == 1 ? 'checked' : '' }}>

                        <label class="form-check-label" for="YesMortgaged">
                            <h6 class="mb-0">Yes</h6>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" name="mutPropertyMortgaged" type="radio" value="0"
                            id="NoMortgaged" {{ isset($application) && $application->property_stands_mortgaged == 1 ? '' : 'checked' }}>
                        <label class="form-check-label" for="NoMortgaged">
                            <h6 class="mb-0">No</h6>
                        </label>
                    </div>
                </div>
                
            </div>

            <div class="col-lg-12" id="yesRemarksDiv" style="{{ isset($application) && $application->property_stands_mortgaged == 1 ? '' : 'display:none' }}" >
                <div class="form-group">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea class="form-control" name="mutMortgagedRemarks" id="remarks" placeholder="Remarks">{{isset($application) && $application->mortgaged_remark ? $application->mortgaged_remark: ''}}</textarea>
                    <div id="YesMortgagedError" class="text-danger text-left"></div>
                </div>
                
            </div>
            <div class="col-lg-12"></div>
            <div class="col-lg-6">
                <div class="d-flex align-items-center">
                    <h6 class="mr-5 mb-0">Whether the application is on basis of court order?
                    </h6>
                    <div class="form-check mr-5">
                        <input class="form-check-input" name="mutCourtorder" type="radio" value="1" id="YesCourtOrder" {{ isset($application) && $application->is_basis_of_court_order == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="YesCourtOrder">
                            <h6 class="mb-0">Yes</h6>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" name="mutCourtorder" type="radio" value="0" id="NoCourtOrder"
                        {{ isset($application) && $application->is_basis_of_court_order == 1 ? '' : 'checked' }}>
                        <label class="form-check-label" for="NoCourtOrder">
                            <h6 class="mb-0">No</h6>
                        </label>
                    </div>
                </div>
                <div id="YesCourtOrderError" class="text-danger text-left"></div>
            </div>
        </div>
        <!-- row end -->

    </div>
</div>