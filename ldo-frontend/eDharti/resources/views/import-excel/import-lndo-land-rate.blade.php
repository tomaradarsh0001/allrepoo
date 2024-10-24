@extends('layouts.app')

@section('title', 'Import L&DO Land Rates')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">RGR</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">RGR</li>
            </ol>
        </nav>
    </div>
    <!-- <div class="ms-auto"><a href="#" class="btn btn-primary">Button</a></div> -->
</div>
<!--end breadcrumb-->
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Import L&DO Land Rates</h5>
        <form action="{{ route('lndoLandRates.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="orm-group mb-3">
                <label for="file" class="form-label">Select Excel File</label>
                <input type="file" class="form-control" id="file" name="file" required>
            </div>
            <button type="submit" class="btn btn-primary">Import</button>
        </form>
    </div>
</div>
@endsection
