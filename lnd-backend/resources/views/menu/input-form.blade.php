@extends('layout.main')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div style="position: absolute; right: 0;">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="margin-bottom: 20px;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        @if(isset($menu) && $menu->id)
                        <li class="breadcrumb-item"><a href="{{ route('listMenu') }}">Menu List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ Route::currentRouteName() == 'viewMenu' ? 'View Menu' : 'Edit Menu' }}</li>
                        @else
                        <li class="breadcrumb-item active" aria-current="page">Add Menu</li>
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
                Manage Menu
            </h3>
            <a href="{{ route('listMenu') }}" class="btn btn-gradient-primary">Menu List</a>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <form class="forms-sample" method="post" action="{{route('storeMenu')}}">
                        @csrf
                        <input type="hidden" name="id" value="{{isset($menu) ? $menu->id:''}}">
                        <div class="card-header">
                            <h4 class="card-title">Menu Input Form</h4>
                            <p class="card-description"> {{ Route::currentRouteName() == 'viewMenu' ? 'View' : 'Add/Edit' }} a menu </p>
                        </div>
                        <div class="card-body">
                            @include('include.statusAlert')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="labelEng">Label English</label>
                                        <input type="text" class="form-control" id="labelEng" name="label_eng" placeholder="Label English" value="{{isset($menu) ? $menu->label_eng:''}}" {{ Route::currentRouteName() == 'viewMenu' ? 'disabled' : '' }}>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="labelHin">Label Hindi</label>
                                        <input type="text" class="form-control" id="labelHin" name="label_hin" placeholder="लेबल हिंदी" value="{{isset($menu) ? $menu->label_hin:''}}" {{ Route::currentRouteName() == 'viewMenu' ? 'disabled' : '' }}>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="parentSelct">Parent Menu</label>
                                        <select name="parent_id" class="form-control" id="parentSelct" {{ Route::currentRouteName() == 'viewMenu' ? 'disabled' : '' }}>
                                            <option value="">Leave Blank to display as main menu</option>
                                            @if($parentMenu->count() > 0)
                                            @foreach($parentMenu as $parent)
                                            <option value="{{$parent->id}}" {{(isset($menu) && $menu->parent_id == $parent->id)?'selected':'' }}>{{$parent->label_eng}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sortOrder">Order</label>
                                        <input type="number" class="form-control" name="sort_order" id="sortOrder" placeholder="Menu Order" min="1" value="{{isset($menu) ? $menu->sort_order:1}}" {{ Route::currentRouteName() == 'viewMenu' ? 'disabled' : '' }}>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="is_active"><b>Is Active</b></label>
                                        <select class="form-control" id="is_active" name="is_active" {{ Route::currentRouteName() == 'viewMenu' ? 'disabled' : '' }}>
                                            <option value="1" {{ (isset($menu) && $menu->is_active == 1) ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ (isset($menu) && $menu->is_active == 0) ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="link">Link</label>
                                        <input type="text" class="form-control" name="link" id="link" placeholder="link" value="{{isset($menu) ? $menu->link:''}}" {{ Route::currentRouteName() == 'viewMenu' ? 'disabled' : '' }}>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="location">Open in new tab</label>
                                        <select name="link_in_new_tab" class="form-control" {{ Route::currentRouteName() == 'viewMenu' ? 'disabled' : '' }}>
                                            <option value="0" {{(isset($menu) && $menu->link_in_new_tab == '0')?'selected':'' }}>No</option>
                                            <option value="1" {{(isset($menu) && $menu->link_in_new_tab == '1')?'selected':'' }}>Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <select name="location" class="form-control" {{ Route::currentRouteName() == 'viewMenu' ? 'disabled' : '' }}>
                                            <option value="header" {{(isset($menu) && $menu->location == 'header')?'selected':'' }}>Header</option>
                                            <option value="footer" {{(isset($menu) && $menu->location == 'footer')?'selected':'' }}>Footer</option>
                                            <option value="page" {{(isset($menu) && $menu->location == 'page')?'selected':'' }}>Page</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 {{(isset($menu) &&  $menu->page_id)?'':'d-none'}}" id="div-page-select">
                                    <div class="form-group">
                                        <label for="sortOrder">Page</label>
                                        <select name="page_id" class="form-control" {{ Route::currentRouteName() == 'viewMenu' ? 'disabled' : '' }}>
                                            {{(isset($menu) &&  $menu->page_id)?$menu->page_id:''}}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(Route::currentRouteName() != 'viewMenu')
                        <div class="card-footer">
                            <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                            <button type="button" class="btn btn-danger" onclick="window.location.href='{{ route('listMenu') }}';">Cancel</button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('validation/dist/jquery.validate.min.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let engInput = document.getElementById('labelEng');
        engInput.addEventListener('blur', () => {
            let engLabel = engInput.value
            if (engLabel.length > 0) {
                let defaultLink = engLabel.replace(/[^\w\s&]/gi, '').replace('&', '').toLowerCase().replace(/\s+/g, '-');
                document.querySelector('#link').value = defaultLink
            }
        })

    })
</script>
@endsection
