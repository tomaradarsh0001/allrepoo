@extends('layout.main')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div style="position: absolute; right: 0;">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="margin-bottom: 20px;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        @if(isset($officedocs) && $officedocs->id)
                            @if(isset($mode) && $mode == 'view')
                                <li class="breadcrumb-item"><a href="{{ route('listDocs') }}">Document List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">View Document</li>
                            @else
                                <li class="breadcrumb-item"><a href="{{ route('listDocs') }}">Document List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Document</li>
                            @endif
                        @else
                            <li class="breadcrumb-item active" aria-current="page">Add Document</li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-file-document"></i>
                </span>
                @if(isset($mode) && $mode == 'view')
                    View Office Documents
                @else
                    Manage Office Documents
                @endif
            </h3>
            <a href="{{ route('listDocs') }}" class="btn btn-gradient-primary">Document List</a>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    @if(isset($officedocs) && $officedocs->id)
                        @if(isset($mode) && $mode == 'view')
                            <form class="forms-sample">
                        @else
                            <form class="forms-sample" method="POST" action="{{ route('updateDocs', $officedocs->id) }}" enctype="multipart/form-data">
                            @method('PUT')
                        @endif
                    @else
                        <form class="forms-sample" method="POST" action="{{ route('storeDocs') }}" enctype="multipart/form-data">
                    @endif
                        @csrf
                        <div class="card-header">
                            <h4 class="card-title">Office Documents Form</h4>
                            <p class="card-description"> 
                                @if(isset($mode) && $mode == 'view')
                                    View documents
                                @else
                                    Add or edit documents
                                @endif
                            </p>
                        </div>
                        <div class="card-body">
                            @include('include.statusAlert')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category"><b>Category</b></label>
                                        <select class="form-control" id="category_id" name="category_id" required {{ isset($mode) && $mode == 'view' ? 'disabled' : '' }}>
                                            <option value="" disabled>Select a Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ (isset($officedocs) && $officedocs->category_id == $category->id) ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="displayTill"><b>Display Till</b></label>
                                        <input type="date" class="form-control" id="displayTill" name="display_till" placeholder="Set Display Till Date" value="{{ old('display_till', $officedocs->display_till ?? '') }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>    
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titleEng"><b>Title English</b></label>
                                        <input type="text" class="form-control" id="titleEng" name="title_eng" placeholder="Title English" value="{{ old('title_eng', $officedocs->title_eng ?? '') }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titleHin"><b>Title Hindi</b></label>
                                        <input type="text" class="form-control" id="titleHin" name="title_hin" placeholder="शीर्षक हिन्दी" value="{{ old('title_hin', $officedocs->title_hin ?? '') }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="descriptionEng"><b>Description English</b></label>
                                        <textarea class="form-control" id="descriptionEng" name="description_eng" placeholder="Description English" rows="5" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>{{ old('description_eng', $officedocs->description_eng ?? '') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="descriptionHin"><b>Description Hindi</b></label>
                                        <textarea class="form-control" id="descriptionHin" name="description_hin" placeholder="विवरण हिंदी" rows="5" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>{{ old('description_hin', $officedocs->description_hin ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_active"><b>Is Active</b></label>
                                        <select class="form-control" id="is_active" name="is_active" {{ isset($mode) && $mode == 'view' ? 'disabled' : '' }}>
                                            <option value="1" {{ old('is_active', $officedocs->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('is_active', $officedocs->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sortOrder"><b>Sort Order</b></label>
                                        <input type="number" class="form-control" name="sort_order" id="sortOrder" placeholder="Document Order" min="1" value="{{ old('sort_order', $officedocs->sort_order ?? '') }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="attachment_eng"><b>File Attachment English</b></label>
                                        <input type="file" class="form-control" id="attachment_eng" name="attachment_eng" {{ isset($mode) && $mode == 'view' ? 'disabled' : '' }}>
                                        @if(isset($officedocs) && $officedocs->attachment_eng)
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small>Current file: {{ $officedocs->attachment_eng }}</small>
                                                @if(!(isset($mode) && $mode == 'view'))
                                                    <a href="{{ route('deleteAttachment', ['id' => $officedocs->id, 'lang' => 'eng']) }}" class="btn btn-danger btn-sm mt-1" title="Remove Document">
                                                        <i class="mdi mdi-close"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <input type="hidden" name="current_attachment_eng" value="{{ $officedocs->attachment_eng }}">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="attachment_hin"><b>File Attachment Hindi</b></label>
                                        <input type="file" class="form-control" id="attachment_hin" name="attachment_hin" {{ isset($mode) && $mode == 'view' ? 'disabled' : '' }}>
                                        @if(isset($officedocs) && $officedocs->attachment_hin)
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small>मौजूदा फ़ाइल: {{ $officedocs->attachment_hin }}</small>
                                                @if(!(isset($mode) && $mode == 'view'))
                                                    <a href="{{ route('deleteAttachment', ['id' => $officedocs->id, 'lang' => 'hin']) }}" class="btn btn-danger btn-sm mt-1" title="Remove Document">
                                                        <i class="mdi mdi-close"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <input type="hidden" name="current_attachment_hin" value="{{ $officedocs->attachment_hin }}">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if(Route::currentRouteName() != 'viewDocs')
                            <div class="card-footer">
                                <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                                <button type="button" class="btn btn-danger" onclick="window.location.href='{{ route('listDocs') }}';">Cancel</button>
                            </div>
                            @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
