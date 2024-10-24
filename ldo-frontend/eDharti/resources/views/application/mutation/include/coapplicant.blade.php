 <!-- row end -->
 <div class="row g-2">
     <div class="col-lg-12">
         <div id="repeater" class="position-relative">
             <div class="row part-title align-items-center">
                 <div class="col-12 col-lg-12">
                     <h5>Name & details of other Co-Applicants</h5>
                 </div>
             </div>
             <div class="position-sticky text-end mt-2"
                 style="top: 70px; margin-right: 10px; margin-bottom: 10px; z-index: 9;">
                 <button type="button" class="btn btn-primary repeater-add-btn fullwidthbtn"
                     data-toggle="tooltip" data-placement="bottom"
                     title="Click on to add more Co-Applicant below"><i class="bx bx-plus me-0"></i> Add
                     More</button>
             </div>
             <!-- Repeater Items -->
             <div class="duplicate-field-tab">
                 @if(isset($tempCoapplicant) && count($tempCoapplicant) > 0)
                 @foreach($tempCoapplicant as $index => $tc)
                 <div class="items" data-group="coapplicant">
                     <input type="hidden" value="{{$tc->id}}" data-name="id">
                     <!-- Repeater Content -->
                     <div class="item-content mb-2">
                         <div class="row">
                             <div class="col-lg-4">
                                 <div class="form-group form-box">
                                     <label for="coapplicant_photo" class="quesLabel">Upload Photo<span class="text-danger">*</span></label>
                                     <input type="file" name="coapplicant_photo" class="form-control" accept="application/pdf" id="coapplicant_photo">
                                 </div>
                             </div>
                             <div class="col-lg-4">
                                 <div class="form-group">
                                     <label for="" class="form-label">Name</label>
                                     <input type="text" name="testname" class="form-control alpha-only"
                                         placeholder="Name" id="" data-name="name" value="{{$tc->co_applicant_name}}">
                                 </div>
                             </div>
                             <div class="col-lg-4">
                                 <div class="form-group">
                                     <label for="gender" class="form-label">Gender</label>
                                     <select class="form-select" name="gender" id="gender" data-name="gender">
                                         <option value="">Select</option>
                                         <option value="Male" {{ $tc->co_applicant_gender == 'Male' ? 'selected' : '' }}>Male</option>
                                         <option value="Female" {{ $tc->co_applicant_gender == 'Female' ? 'selected' : '' }}>Female</option>
                                         <option value="Other" {{ $tc->co_applicant_gender == 'Other' ? 'selected' : '' }}>Other</option>
                                     </select>
                                 </div>
                             </div>
                             <div class="col-lg-4">
                                 <div class="form-group">
                                     <label for="age" class="form-label">Age</label>
                                     <input type="text" name="age" class="form-control numericOnly" id="age"
                                         maxlength="2" placeholder="Age" data-name="age" value="{{$tc->co_applicant_age}}">
                                 </div>
                             </div>
                             <div class="col-lg-4">
                                 <div class="form-group">
                                     <label for="fathername" class="form-label">Father's
                                         name</label>
                                     <input type="text" name="fathername" class="form-control alpha-only"
                                         id="fathername" placeholder="Father's Name" data-name="fathername" value="{{$tc->co_applicant_father_name}}">
                                 </div>
                             </div>
                             <div class="col-lg-4">
                                 <div class="form-group">
                                     <label for="aadhar" class="form-label">Aadhaar</label>
                                     <input type="text" name="aadhar" class="form-control numericOnly"
                                         id="aadhar" maxlength="12" placeholder="Aadhaar Number"
                                         data-name="aadharnumber" value="{{$tc->co_applicant_aadhar}}">
                                 </div>
                             </div>
                             <div class="col-lg-4">
                                 <div class="form-group">
                                     <label for="pan" class="form-label">PAN</label>
                                     <input type="text" name="pan"
                                         class="form-control pan_number_format text-uppercase" id="pan"
                                         maxlength="10" placeholder="PAN Number" data-name="pannumber" value="{{$tc->co_applicant_pan}}">
                                 </div>
                             </div>
                             <div class="col-lg-4">
                                 <div class="form-group">
                                     <label for="mobilenumber" class="form-label">Mobile
                                         Number</label>
                                     <input type="text" name="mobilenumber" class="form-control numericOnly"
                                         maxlength="10" id="mobilenumber" placeholder="Mobile Number"
                                         data-name="mobilenumber" value="{{$tc->co_applicant_mobile}}">
                                 </div>
                             </div>
                         </div>
                     </div>
                     <!-- Repeater Remove Btn -->
                     <div class="repeater-remove-btn">
                         <button type="button" class="btn btn-danger remove-btn px-4" data-toggle="tooltip"
                             data-placement="bottom" title="Click on to delete this form">
                             <i class="fadeIn animated bx bx-trash"></i>
                         </button>
                     </div>
                 </div>
                 @endforeach
                 @else
                 <div class="items" data-group="coapplicant">
                     <div class="item-content mb-2">
                         <div class="row">
                             <div class="col-lg-4">
                                 <div class="form-group form-box">
                                     <label for="coapplicant_photo" class="form-label">Upload Photo</label>
                                     <input type="file" name="coapplicant_photo" class="form-control" accept="application/pdf" id="coapplicant_photo">
                                 </div>
                             </div>
                             <div class="col-lg-4">
                                 <div class="form-group">
                                     <label for="namergapp" class="form-label">Name</label>
                                     <input type="text" name="namergapp" class="form-control alpha-only"
                                         placeholder="Name" id="namergapp" data-name="name">
                                 </div>
                             </div>
                             <div class="col-lg-4">
                                 <div class="form-group">
                                     <label for="gender" class="form-label">Gender</label>
                                     <select class="form-select" name="gender" id="gender" data-name="gender">
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
                                     <input type="text" name="age" class="form-control numericOnly" id="age"
                                         maxlength="2" placeholder="Age" data-name="age">
                                 </div>
                             </div>
                             <div class="col-lg-4">
                                 <div class="form-group">
                                     <label for="fathername" class="form-label">Father's
                                         name</label>
                                     <input type="text" name="fathername" class="form-control alpha-only"
                                         id="fathername" placeholder="Father's Name" data-name="fathername">
                                 </div>
                             </div>
                             <div class="col-lg-4">
                                 <div class="form-group">
                                     <label for="aadhar" class="form-label">Aadhaar</label>
                                     <input type="text" name="aadhar" class="form-control numericOnly"
                                         id="aadhar" maxlength="12" placeholder="Aadhaar Number"
                                         data-name="aadharnumber">
                                 </div>
                             </div>
                             <div class="col-lg-4">
                                 <div class="form-group">
                                     <label for="pan" class="form-label">PAN</label>
                                     <input type="text" name="pan"
                                         class="form-control pan_number_format text-uppercase" id="pan"
                                         maxlength="10" placeholder="PAN Number" data-name="pannumber">
                                 </div>
                             </div>
                             <div class="col-lg-4">
                                 <div class="form-group">
                                     <label for="mobilenumber" class="form-label">Mobile
                                         Number</label>
                                     <input type="text" name="mobilenumber" class="form-control numericOnly"
                                         maxlength="10" id="mobilenumber" placeholder="Mobile Number"
                                         data-name="mobilenumber">
                                 </div>
                             </div>
                         </div>
                     </div>
                     <!-- Repeater Remove Btn -->
                     <div class="repeater-remove-btn">
                         <button type="button" class="btn btn-danger remove-btn px-4" data-toggle="tooltip"
                             data-placement="bottom" title="Click on to delete this form">
                             <i class="fadeIn animated bx bx-trash"></i>
                         </button>
                     </div>
                 </div>
                 @endif
             </div>
         </div>
     </div>
 </div>
 <!-- row end -->