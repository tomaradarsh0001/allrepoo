@extends('layout.main')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div style="position: absolute; right: 0;">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="margin-bottom: 20px;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        @if(isset($directories) && $directories->id)
                            @if(isset($mode) && $mode == 'view')
                                <li class="breadcrumb-item"><a href="{{ route('listDirectory') }}">Directory List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">View Directory</li>
                            @else
                                <li class="breadcrumb-item"><a href="{{ route('listDirectory') }}">Directory List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Directory</li>
                            @endif
                        @else
                            <li class="breadcrumb-item active" aria-current="page">Add Directory</li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-account-card-details"></i>
                </span>
                @if(isset($mode) && $mode == 'view')
                    View Directory
                @else
                    Manage Directory
                @endif
            </h3>
            <a href="{{ route('listDirectory') }}" class="btn btn-gradient-primary">Directory List</a>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    @if(isset($directories) && $directories->id)
                        @if(isset($mode) && $mode == 'view')
                            <form class="forms-sample">
                        @else
                            <form class="forms-sample" method="POST" action="{{ route('updateDirectory', $directories->id) }}" enctype="multipart/form-data">
                            @method('PUT')
                        @endif
                    @else
                        <form class="forms-sample" method="POST" action="{{ route('storeDirectory') }}" enctype="multipart/form-data">
                    @endif
                        @csrf
                        <div class="card-header">
                            <h4 class="card-title">Directory Form</h4>
                            <p class="card-description"> 
                                @if(isset($mode) && $mode == 'view')
                                    View directory details
                                @else
                                    Add or edit directory
                                @endif
                            </p>
                        </div>
                        <div class="card-body">
                            @include('include.statusAlert')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nameEng"><b>Name English</b></label>
                                        <input type="text" class="form-control" id="nameEng" name="name_eng" placeholder="Name English" value="{{ $directories->name_eng ?? '' }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nameHin"><b>Name Hindi</b></label>
                                        <input type="text" class="form-control" id="nameHin" name="name_hin" placeholder="नाम हिन्दी" value="{{ $directories->name_hin ?? '' }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="designationEng"><b>Designation English</b></label>
                                        <input type="text" class="form-control" id="designationEng" name="designation_eng" placeholder="Designation English" value="{{ $directories->designation_eng ?? '' }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="designationHin"><b>Designation Hindi</b></label>
                                        <input type="text" class="form-control" id="designationHin" name="designation_hin" placeholder="Designation Hindi" value="{{ $directories->designation_hin ?? '' }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="section_eng"><b>Section English</b></label>
                                        <select class="form-control" id="section_eng" name="section_eng" {{ isset($mode) && $mode == 'view' ? 'disabled' : '' }}>
                                            <option value="">Choose Section</option> 
                                            <option value="L&DO Office" {{ (isset($directories) && $directories->section_eng == 'L&DO Office') ? 'selected' : '' }}>L&DO Office</option>
                                            <option value="Admin Section" {{ (isset($directories) && $directories->section_eng == 'Admin Section') ? 'selected' : '' }}>Admin Section</option>
                                            <option value="Property Section-I" {{ (isset($directories) && $directories->section_eng == 'Property Section-I') ? 'selected' : '' }}>Property Section-I</option>
                                            <option value="Property Section-II" {{ (isset($directories) && $directories->section_eng == 'Property Section-II') ? 'selected' : '' }}>Property Section-II</option>
                                            <option value="Property Section-III" {{ (isset($directories) && $directories->section_eng == 'Property Section-III') ? 'selected' : '' }}>Property Section-III</option>
                                            <option value="Lease - I Section" {{ (isset($directories) && $directories->section_eng == 'Lease - I Section') ? 'selected' : '' }}>Lease - I Section</option>
                                            <option value="Lease - II (A) Section" {{ (isset($directories) && $directories->section_eng == 'Lease - II (A) Section') ? 'selected' : '' }}>Lease - II (A) Section</option>
                                            <option value="Lease - II (B) Section" {{ (isset($directories) && $directories->section_eng == 'Lease - II (B) Section') ? 'selected' : '' }}>Lease - II (B) Section</option>
                                            <!-- <option value="Lease - III Section" {{ (isset($directories) && $directories->section_eng == 'Lease - III Section') ? 'selected' : '' }}>Lease - III Section</option> -->
                                            <option value="Lease - IV Section" {{ (isset($directories) && $directories->section_eng == 'Lease - IV Section') ? 'selected' : '' }}>Lease - IV Section</option>
                                            <option value="Lease - V Section" {{ (isset($directories) && $directories->section_eng == 'Lease - V Section') ? 'selected' : '' }}>Lease - V Section</option>
                                            <option value="Vigilance Section" {{ (isset($directories) && $directories->section_eng == 'Vigilance Section') ? 'selected' : '' }}>Vigilance Section</option>
                                            <option value="IAC Section" {{ (isset($directories) && $directories->section_eng == 'IAC Section') ? 'selected' : '' }}>IAC Section</option>
                                            <option value="RP Cell" {{ (isset($directories) && $directories->section_eng == 'RP Cell') ? 'selected' : '' }}>RP Cell</option>
                                            <option value="Technical Section" {{ (isset($directories) && $directories->section_eng == 'RP Cell') ? 'selected' : '' }}>RP Cell</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="section_hin"><b>Section Hindi</b></label>
                                        <select class="form-control" id="section_hin" name="section_hin" {{ isset($mode) && $mode == 'view' ? 'disabled' : '' }}>
                                        <option value="">खंड चुनें</option>
                                            <option value="एल एंड डी ओ कार्यालय" {{ (isset($directories) && $directories->section_hin == 'एल एंड डी ओ कार्यालय') ? 'selected' : '' }}>एल एंड डी ओ कार्यालय</option>
                                            <option value="प्रशासन खंड" {{ (isset($directories) && $directories->section_hin == 'प्रशासन खंड') ? 'selected' : '' }}>प्रशासन खंड</option>
                                            <option value="संपत्ति खंड-I" {{ (isset($directories) && $directories->section_hin == 'संपत्ति खंड-I') ? 'selected' : '' }}>संपत्ति खंड-I</option>
                                            <option value="संपत्ति खंड-II" {{ (isset($directories) && $directories->section_hin == 'संपत्ति खंड-II') ? 'selected' : '' }}>संपत्ति खंड-II</option>
                                            <option value="संपत्ति खंड-III" {{ (isset($directories) && $directories->section_hin == 'संपत्ति खंड-III') ? 'selected' : '' }}>संपत्ति खंड-III</option>
                                            <option value="लीज - I खंड" {{ (isset($directories) && $directories->section_hin == 'लीज - I खंड') ? 'selected' : '' }}>लीज - I खंड</option>
                                            <option value="लीज - II (A) खंड" {{ (isset($directories) && $directories->section_hin == 'लीज - II (A) खंड') ? 'selected' : '' }}>लीज - II (A) खंड</option>
                                            <option value="लीज - II (B) खंड" {{ (isset($directories) && $directories->section_hin == 'लीज - II (B) खंड') ? 'selected' : '' }}>लीज - II (B) खंड</option>
                                            <option value="लीज - IV खंड" {{ (isset($directories) && $directories->section_hin == 'लीज - IV खंड') ? 'selected' : '' }}>लीज - IV खंड</option>
                                            <option value="लीज - V खंड" {{ (isset($directories) && $directories->section_hin == 'लीज - V खंड') ? 'selected' : '' }}>लीज - V खंड</option>
                                            <option value="सतर्कता खंड" {{ (isset($directories) && $directories->section_hin == 'सतर्कता खंड') ? 'selected' : '' }}>सतर्कता खंड</option>
                                            <option value="IAC खंड" {{ (isset($directories) && $directories->section_hin == 'IAC खंड') ? 'selected' : '' }}>IAC खंड</option>
                                            <option value="RP सेल" {{ (isset($directories) && $directories->section_hin == 'RP सेल') ? 'selected' : '' }}>RP सेल</option>
                                            <option value="तकनीकी खंड" {{ (isset($directories) && $directories->section_hin == 'तकनीकी खंड') ? 'selected' : '' }}>तकनीकी खंड</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            @if(Route::currentRouteName() != 'viewDirectory')
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                                    <button type="button" class="btn btn-danger" onclick="window.location.href='{{ route('listDirectory') }}';">Cancel</button>
                                </div>
                            @endif
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
