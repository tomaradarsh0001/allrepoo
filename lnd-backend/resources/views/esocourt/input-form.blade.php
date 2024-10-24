@extends('layout.main')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div style="position: absolute; right: 0;">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="margin-bottom: 20px;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        @if(isset($cases) && $cases->id)
                            @if(isset($mode) && $mode == 'view')
                                <li class="breadcrumb-item"><a href="{{ route('listCase') }}">ESO Court List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">View Case Detail</li>
                            @else
                                <li class="breadcrumb-item"><a href="{{ route('listCase') }}">ESO Court List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Case Detail</li>
                            @endif
                        @else
                            <li class="breadcrumb-item active" aria-current="page">Add Case Detail</li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-gavel"></i>
                </span>
                @if(isset($mode) && $mode == 'view')
                    View Case Detail
                @else
                    Manage ESO Court
                @endif
            </h3>
            <a href="{{ route('listCase') }}" class="btn btn-gradient-primary">ESO Court List</a>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    @if(isset($cases) && $cases->id)
                        @if(isset($mode) && $mode == 'view')
                            <form class="forms-sample">
                        @else
                            <form class="forms-sample" method="POST" action="{{ route('updateCase', $cases->id) }}" enctype="multipart/form-data">
                            @method('PUT')
                        @endif
                    @else
                        <form class="forms-sample" method="POST" action="{{ route('storeCase') }}" enctype="multipart/form-data">
                    @endif
                        @csrf
                        <div class="card-header">
                            <h4 class="card-title">ESO Court Case Form</h4>
                            <p class="card-description"> 
                                @if(isset($mode) && $mode == 'view')
                                    View Case Detail
                                @else
                                    Add or edit case
                                @endif
                            </p>
                        </div>
                        <div class="card-body">
                            @include('include.statusAlert')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="section"><b>Section</b></label>
                                        <select class="form-control" id="section_id" name="section_id" required {{ isset($mode) && $mode == 'view' ? 'disabled' : '' }}>
                                            <option value="">Select a Section</option>
                                            @foreach($sections as $section)
                                                <option value="{{ $section->id }}" {{ (isset($cases) && $cases->section_id == $section->id) ? 'selected' : '' }}>
                                                    {{ $section->short_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="file_no"><b>File No.</b></label>
                                        <input type="text" class="form-control" id="file_no" name="file_no" placeholder="File No." value="{{ $cases->file_no ?? '' }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="active_in_court"><b>Active In Court</b></label>
                                        <select class="form-control" id="active_in_court" name="active_in_court" {{ isset($mode) && $mode == 'view' ? 'disabled' : '' }}>
                                        <option value="">Select Options</option>
                                            <option value="Supreme Court" {{ (isset($cases) && $cases->active_in_court == 'Supreme Court') ? 'selected' : '' }}>Supreme Court</option>
                                            <option value="High Court" {{ (isset($cases) && $cases->active_in_court == 'High Court') ? 'selected' : '' }}>High Court</option>
                                            <option value="District Court" {{ (isset($cases) && $cases->active_in_court == 'District Court') ? 'selected' : '' }}>District Court</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="case_no"><b>Case No.</b></label>
                                        <input type="text" class="form-control" id="case_no" name="case_no" placeholder="Case No." value="{{ $cases->case_no ?? '' }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="commencement_date"><b>Commencement Date</b></label>
                                        <input type="date" class="form-control" id="commencement_date" name="commencement_date" placeholder="Set Commencement Date" value="{{ old('commencement_date', $cases->commencement_date ?? '') }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subject"><b>Subject</b></label>
                                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" value="{{ $cases->subject ?? '' }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="status"><b>Status</b></label>
                                        <textarea class="form-control" id="status" name="status" placeholder="Status" rows="5" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>{{ old('status', $cases->status ?? '') }}</textarea>
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="closing_date"><b>Closing Date</b></label>
                                        <input type="date" class="form-control" id="closing_date" name="closing_date" placeholder="Set Closing Date" value="{{ old('closing_date', $cases->closing_date ?? '') }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ldoh"><b>Last Date of Hearing</b></label>
                                        <input type="date" class="form-control" id="ldoh" name="ldoh" placeholder="Set Last Date of Hearing" value="{{ old('ldoh', $cases->ldoh ?? '') }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ndoh"><b>Next Date of Hearing</b></label>
                                        <input type="date" class="form-control" id="ndoh" name="ndoh" placeholder="Set Next Date of Hearing" value="{{ old('ndoh', $cases->ndoh ?? '') }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="additional_info"><b>Additional Information</b></label>
                                        <input type="text" class="form-control" id="additional_info" name="additional_info" placeholder="Additional Information" value="{{ $cases->additional_info ?? '' }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="remarks"><b>Remarks</b></label>
                                        <input type="text" class="form-control" id="remarks" name="remarks" placeholder="Remarks" value="{{ $cases->remarks ?? '' }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="judgement_link"><b>Judgement Link</b></label>
                                        <input type="file" class="form-control" id="judgement_link" name="judgement_link" {{ isset($mode) && $mode == 'view' ? 'disabled' : '' }}>
                                        
                                        @if(isset($cases) && $cases->judgement_link)
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small>Current file: {{ $cases->judgement_link }}</small>
                                                @if(!(isset($mode) && $mode == 'view'))
                                                    <a href="{{ route('deleteJudgement', $cases->id) }}" class="btn btn-danger btn-sm mt-1" title="Remove Document">
                                                        <i class="mdi mdi-close"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <input type="hidden" name="current_judgement_link" value="{{ $cases->judgement_link }}">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_active"><b>Is Active</b></label>
                                        <select class="form-control" id="is_active" name="is_active" {{ isset($mode) && $mode == 'view' ? 'disabled' : '' }}>
                                            <option value="1" {{ old('is_active', $cases->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('is_active', $cases->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sortOrder"><b>Sort Order</b></label>
                                        <input type="number" class="form-control" name="sort_order" id="sortOrder" placeholder="Case Sort Order" min="1" value="{{ old('sort_order', $cases->sort_order ?? '') }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>


                            <!-- Add more fields as necessary -->
                            @if(Route::currentRouteName() != 'viewCase')
                        <div class="card-footer">
                            <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                            <button type="button" class="btn btn-danger" onclick="window.location.href='{{ route('listCase') }}';">Cancel</button>
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
