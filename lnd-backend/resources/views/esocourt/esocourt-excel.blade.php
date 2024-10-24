@extends('layout.main')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div style="position: absolute; right: 0;">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="margin-bottom: 20px;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">ESO Court Case Multiple Upload</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-gavel"></i>
                </span>
                Manage ESO Court
            </h3>
            <a href="{{route('listCase')}}" class="btn btn-gradient-primary">ESO Court Case</a>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">

                    <form action="{{ route('eso-courts.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header">
                            <h4 class="card-title">ESO Court Case Multiple Upload</h4>
                        </div>
                        <div class="card-body">
                            @include('include.statusAlert')
                            <div class="form-group">
                                <label for="file">Choose Excel file</label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>    
                </div>
            </div>
        </div>
@endsection