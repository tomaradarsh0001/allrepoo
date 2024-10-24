@extends('layout.main')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div style="position: absolute; right: 0;">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="margin-bottom: 20px;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        @if(isset($news) && $news->id)
                            @if(isset($mode) && $mode == 'view')
                                <li class="breadcrumb-item"><a href="{{ route('listNews') }}">News List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">View News</li>
                            @else
                                <li class="breadcrumb-item"><a href="{{ route('listNews') }}">News List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit News</li>
                            @endif
                        @else
                            <li class="breadcrumb-item active" aria-current="page">Add News</li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-newspaper"></i>
                </span>
                @if(isset($mode) && $mode == 'view')
                    View News
                @else
                    Manage News
                @endif
            </h3>
            <a href="{{ route('listNews') }}" class="btn btn-gradient-primary">News List</a>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    @if(isset($news) && $news->id)
                        @if(isset($mode) && $mode == 'view')
                            <form class="forms-sample">
                        @else
                            <form class="forms-sample" method="POST" action="{{ route('updateNews', $news->id) }}" enctype="multipart/form-data">
                            @method('PUT')
                        @endif
                    @else
                        <form class="forms-sample" method="POST" action="{{ route('storeNews') }}" enctype="multipart/form-data">
                    @endif
                        @csrf
                        <div class="card-header">
                            <h4 class="card-title">News Form</h4>
                            <p class="card-description">
                                @if(isset($mode) && $mode == 'view')
                                    View news details
                                @else
                                    Add or edit news
                                @endif
                            </p>
                        </div>
                        <div class="card-body">
                            @include('include.statusAlert')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titleEng"><b>Title English</b></label>
                                        <input type="text" class="form-control" id="titleEng" name="title_eng" placeholder="Title English" value="{{ old('title_eng', $news->title_eng ?? '') }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titleHin"><b>Title Hindi</b></label>
                                        <input type="text" class="form-control" id="titleHin" name="title_hin" placeholder="शीर्षक हिन्दी" value="{{ old('title_hin', $news->title_hin ?? '') }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="descriptionEng"><b>Description English</b></label>
                                        <textarea class="form-control" id="descriptionEng" name="description_eng" placeholder="Description English" rows="5" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>{{ old('description_eng', $news->description_eng ?? '') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="descriptionHin"><b>Description Hindi</b></label>
                                        <textarea class="form-control" id="descriptionHin" name="description_hin" placeholder="विवरण हिंदी" rows="5" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>{{ old('description_hin', $news->description_hin ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_active"><b>Is Active</b></label>
                                        <select class="form-control" id="is_active" name="is_active" {{ isset($mode) && $mode == 'view' ? 'disabled' : '' }}>
                                            <option value="1" {{ old('is_active', $news->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('is_active', $news->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sortOrder"><b>Sort Order</b></label>
                                        <input type="number" class="form-control" name="sort_order" id="sortOrder" placeholder="News Order" min="1" value="{{ old('sort_order', $news->sort_order ?? '') }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="document_path"><b>File Attachment</b></label>
                                        <input type="file" class="form-control" id="document_path" name="document_path" {{ isset($mode) && $mode == 'view' ? 'disabled' : '' }}>
                                        
                                        @if(isset($news) && $news->document_path)
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small>Current file: {{ $news->document_path }}</small>
                                                @if(!(isset($mode) && $mode == 'view'))
                                                    <a href="{{ route('deleteDocument', $news->id) }}" class="btn btn-danger btn-sm mt-1" title="Remove Document">
                                                        <i class="mdi mdi-close"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <input type="hidden" name="current_document_path" value="{{ $news->document_path }}">
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="displayTill"><b>Display Till</b></label>
                                        <input type="date" class="form-control" id="displayTill" name="display_till" placeholder="Set Display Till Date" value="{{ old('display_till', $news->display_till ?? '') }}" {{ isset($mode) && $mode == 'view' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>

                            @if(Route::currentRouteName() != 'viewNews')
                            <div class="card-footer">
                                <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                                <button type="button" class="btn btn-danger" onclick="window.location.href='{{ route('listNews') }}';">Cancel</button>
                            </div>
                            @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
