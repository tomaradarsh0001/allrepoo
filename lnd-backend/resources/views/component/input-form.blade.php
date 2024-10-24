@extends('layout.main')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <!-- SwatiMishra 30-04-2024, 12:40 PM BreadCrumb Start, replace <div class="page header">....</div> -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div style="position: absolute; right: 0;">
                <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="margin-bottom: 20px;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        @if(isset($component) && $component->id)
                            @if(isset($mode) && $mode == 'view')
                                <li class="breadcrumb-item"><a href="{{ route('listComponent') }}">Components List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">View Component</li>
                            @else
                                <li class="breadcrumb-item"><a href="{{ route('listComponent') }}">Components List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Component</li>
                            @endif
                        @else
                            <li class="breadcrumb-item active" aria-current="page">Add Component</li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-menu"></i>
                </span>
                Manage Components
            </h3>
            <a href="{{route('listComponent')}}" class="btn btn-gradient-primary">Components List</a>
        </div>
        <!-- SwatiMishra 30-04-2024, 12:40 PM BreadCrumb End -->

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <form class="forms-sample" method="post" action="{{route('storeComponent')}}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{isset($component) ? $component->id:''}}">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($viewMode) ? 'View Component' : 'Component Input Form' }}</h4>
                        </div>
                        <div class="card-body">
                            @include('include.statusAlert')
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="name" value="{{isset($component) ? $component->name:''}}" {{ isset($viewMode) && $viewMode ? 'disabled' : '' }}>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="headingEng">Page</label>
                                        <select name="page_id" {{isset($component) ? 'disabled':''}} class="form-control" value="{{isset($component) ? $component->page_id :''}}" {{ isset($viewMode) && $viewMode ? 'disabled' : '' }}>
                                            <option value="">Select a Page</option>
                                            @foreach($pages as $page)
                                            <option value="{{$page->id}}" {{isset($component) && $component->page_id  == $page->id? 'selected':''}}>{{$page->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="is_active"><b>Is Active</b></label>
                                        <select class="form-control" id="is_active" name="is_active" {{ isset($viewMode) && $viewMode ? 'disabled' : '' }}>
                                            <option value="1" {{ (isset($component) && $component->is_active == 1) ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ (isset($component) && $component->is_active == 0) ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="headingEng">Heading English</label>
                                        <input type="text" class="form-control" id="headingEng" name="headingEng" placeholder="English" value="{{isset($component) ? $component->heading_eng:''}}" {{ isset($viewMode) && $viewMode ? 'disabled' : '' }}>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="headingHin">Heading Hindi</label>
                                        <input type="text" class="form-control" id="headingHin" name="headingHin" placeholder="हिंदी शीर्षक" value="{{isset($component) ? $component->heading_hin:''}}" {{ isset($viewMode) && $viewMode ? 'disabled' : '' }}>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4>Sections</h4>
                                    </div>
                                </div>
                            </div>
                            @if(isset($component) && $component->sections->count() > 0)
                            @foreach($component->sections as $index => $section)
                            <div class="sectionContainer" id="section-{{$index}}">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="delete-section btn btn-danger btn-sm" data-parent_id="section-{{$index}}" {{ isset($viewMode) && $viewMode ? 'disabled' : '' }}><i class="mdi mdi-delete"></i> </button>
                                    </div>
                                </div>
                                <input type="hidden" name="sectionId[{{$index}}]" value="{{$section->id}}">
                                <div class="componentInput">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="title_eng">Title (English):</label>
                                                <input type="text" name="title_eng[{{$index}}]" id="title_eng" class="form-control" value="{{$section->title_eng}}" {{ isset($viewMode) && $viewMode ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="title_hin">Title (Hindi):</label>
                                                <input type="text" name="title_hin[{{$index}}]}" id="title_hin" class="form-control" value="{{$section->title_hin}}" {{ isset($viewMode) && $viewMode ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="subtitle_eng">Subtitle (English):</label>
                                                <input type="text" name="subtitle_eng[{{$index}}]" id="subtitle_eng" class="form-control" value="{{$section->subtitle_eng}}" {{ isset($viewMode) && $viewMode ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="subtitle_hin">Subtitle (Hindi):</label>
                                                <input type="text" name="subtitle_hin[{{$index}}]}" id="subtitle_hin" class="form-control" value="{{$section->subtitle_hin}}" {{ isset($viewMode) && $viewMode ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="content_eng">Content (English):</label>
                                                <span class="badge bg-secondary list-start" data-index="{{$index}}" data-language="eng">Add List in Text</span>
                                                <textarea name="content_eng[{{$index}}]" id="content-eng-{{$index}}" rows="4" class="form-control" {{ isset($viewMode) && $viewMode ? 'disabled' : '' }}>{{$section->content_eng}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="content_hin">Content (Hindi):</label>
                                                <span class="badge bg-secondary list-start" data-index="{{$index}}" data-language="hin">Add List in Text</span>
                                                <textarea name="content_hin[{{$index}}]" id="content-hin-{{$index}}" rows="4" class="form-control" {{ isset($viewMode) && $viewMode ? 'disabled' : '' }}>{{$section->content_hin}}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="image">Image</label>
                                                <input type="file" name="image[{{$index}}]" id="image" class="form-control" {{ isset($viewMode) && $viewMode ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="link">Link URL:</label>
                                                <input type="text" name="link[{{$index}}]" id="link" class="form-control" value="{{$section->link}}" {{ isset($viewMode) && $viewMode ? 'disabled' : '' }}>
                                            </div>
                                        </div> 
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="link_type">Link Type:</label>
                                                <select class="form-control" id="link_type" name="link_type[{{$index}}]" {{ isset($viewMode) && $viewMode ? 'disabled' : '' }}>
                                                    <option value="">Choose Section</option>
                                                    <option value="1" {{ (isset($section) && $section->link_type == 1) ? 'selected' : '' }}>Frontend</option>
                                                    <option value="2" {{ (isset($section) && $section->link_type == 2) ? 'selected' : '' }}>External</option>
                                                    <option value="3" {{ (isset($section) && $section->link_type == 3) ? 'selected' : '' }}>Backend</option>
                                                </select>
                                            </div>
                                        </div>                                       
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            @else
                            <div class="noSection">
                                <h3>This component doesn't have sections</h3>
                            </div>
                            @endif
                        </div>
                        @if(!isset($viewMode))
                        <div class="row btn-row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="btn btn-gradient-info mr-2" onclick="appendSectionInput()">Add Section</button>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                            <button type="button" class="btn btn-danger" onclick="window.location.href='{{ route('listComponent') }}';">Cancel</button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="list-input d-none">
    <label>What to add in list</label>
    <span class="badge bg-secondary list-end">Close</span>
    <input type="text" name="" id="list-input" class="form-control">
    <button class="btn btn-gradient-info mt-2" type="button" id="list-enter">Add</button>
</div>
<div class="d-none sectionContainer" id="sectionInput">
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="delete-section btn btn-danger btn-sm" data-parent_id=""><i class="mdi mdi-delete"></i> </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="title_eng">Title (English):</label>
                <input type="text" name="title_eng[]" id="title_eng" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="title_hin">Title (Hindi):</label>
                <input type="text" name="title_hin[]" id="title_hin" class="form-control">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="subtitle_eng">Subtitle (English):</label>
                <input type="text" name="subtitle_eng[]" id="subtitle_eng" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="subtitle_hin">Subtitle (Hindi):</label>
                <input type="text" name="subtitle_hin[]" id="subtitle_hin" class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="content_eng">Content (English):</label>
                <span class="badge bg-secondary list-start" data-index="" data-language="eng">Add List in Text</span>
                <textarea name="content_eng[]" id="content-eng" rows="4" class="form-control"></textarea>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="content_hin">Content (Hindi):</label>
                <span class="badge bg-secondary list-start" data-index="" data-language="hin">Add List in Text</span>
                <textarea name="content_hin[]" id="content-hin" rows="4" class="form-control"></textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label for="image">Image</label>
            <input type="file" name="image[]" id="image" class="form-control">
        </div>
        <div class="col-md-6">
            <label for="link">Link URL:</label>
            <input type="text" name="link[]" id="link" class="form-control">
        </div>
    </div>
</div>

<script>
    let sectionLength = '<?= isset($component) ? $component->sections->count() : 0 ?>';
    let targetTextArea = null;
    const appendSectionInput = () => {
        const sectionInput = $('#sectionInput').clone();
        sectionInput.removeClass('d-none');
        sectionInput.attr('id', `section-${sectionLength}`)
        const deleteBtn = sectionInput.find('.delete-section');
        const spanList = sectionInput.find('.badge');
        console.log(spanList);
        if (spanList.length > 0) {
            spanList.each((ind, span) => {
                $(span).attr('data-index', sectionLength);
            })
        }
        const textareas = sectionInput.find('textarea');
        if (textareas.length > 0) {
            textareas.each((ind, area) => {
                $(area).attr('id', $(area).attr('id') + '-' + sectionLength);
            })
        }
        deleteBtn.attr('data-parent-id', `section-${sectionLength}`);
        if ($('.card-body').find('.noSection').length > 0) {
            $('.noSection').remove();
        }
        $('.card-body').append(sectionInput);
        sectionLength++;
    }
    $(document).on('click', '.delete-section', (e) => {
        const isBtn = (e.target.tagName.toLocaleLowerCase() == 'button');
        const btn = isBtn ? e.target : e.target.parentElement;
        const parentId = btn.getAttribute('data-parent-id');
        $(`#${parentId}`).slideUp(500, function() {
            $(this).remove()
        });

    });
    $(document).on('click', '.list-start', (e) => {
        console.log();
        const sectionNum = $(e.target).data('index');
        const language = $(e.target).data('language');
        targetTextArea = $(`#content-${language}-${sectionNum}`)[0];
        console.log(targetTextArea);
        $('.list-input').removeClass('d-none');
        $('#list-input').focus();
    });
    $('#list-enter').click((e) => {
        let inputstr = $('#list-input').val().trim();
        if (inputstr.length > 0) {
            $(targetTextArea).val($(targetTextArea).val() + '\n<li>' + inputstr + '</li>');
            $('#list-input').val('')
        }
    });
    $(document).on('click', '.list-end', (e) => {
        console.log(e)
        $('#list-input').blur();
        $('.list-input').addClass('d-none');
        targetTextArea.focus();
        targetTextArea = null;
    })
</script>

@endsection
