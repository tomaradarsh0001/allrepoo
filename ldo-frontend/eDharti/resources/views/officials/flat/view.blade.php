@extends('layouts.app')

@section('title', 'MIS Form Details')

@section('content')
    <style>
        .pagination .active a {
            color: #ffffff !important;

        }
    </style>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">MIS</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Flat Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- <div class="ms-auto"><a href="#" class="btn btn-primary">Button</a></div> -->

    <hr>

    <div class="card">
        <div class="card-body">
            <div class="container">
                <h5 class="mb-4 pt-3 text-decoration-underline">BASIC DETAILS</h5>
                <div class="container pb-3">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td><b>Flat Id: </b> {{ $flatData->unique_flat_id }}</td>
                                <td><b>Flat No.: </b> {{ $flatData->flat_number }}</td>
                            </tr>
                            <tr>
                                <td><b>Property Id: </b> {{ $flatData->unique_property_id }}</td>
                                <td><b>Old Property Id: </b> {{ $flatData->old_property_id }}</td>
                                
                            </tr>
                            <tr>
                                <td><b>Main File No.: </b> {{ $flatData->main_file_no }}</td>
                                <td><b>Computer generated flat file no. : </b> {{ $flatData->unique_file_no }} </td>
                            </tr>
                            <tr>
                                <td><b>Colony Name(Present): </b> {{ $flatData->colony_name }} </td>
                                <td><b>Property Status: </b> {{ $flatData->property_status }} </td>
                            </tr>
                            <tr>
                                <td><b>Builder Name : </b> {{ $flatData->builder_developer_name }} </td>
                                <td><b>Buyer Name : </b> {{ $flatData->original_buyer_name }} </td>
                                
                            </tr>
                            <tr>
                                <td><b>Purchase Date : </b> {{ \Carbon\Carbon::parse($flatData->purchase_date)->format('d/m/Y')  }} </td>
                                <td><b>Present Occupent Name: </b> {{ $flatData->present_occupant_name }} </td>
                            </tr>
                            <tr>
                                <td><b>Block No.: </b> {{ $flatData->block }}</td>
                                <td><b>Plot No.: </b> {{ $flatData->plot }} </td>
                            </tr>
                            <tr>
                                <td><b>Presently Known As: </b>{{ $flatData->property_known_as }}
                                </td>
                                <td><b>Area: <span class="text-secondary">({{ $flatData->area_in_sqm }}
                                        Sq
                                        Meter)</span>
                                </td>
                            </tr> 
                        </tbody>
                    </table>
                </div>
                <hr>

                {{-- <h5 class="mb-4 pt-3 text-decoration-underline">LEASE DETAILS</h5>
                <div class="container pb-3">
                    <table class="table table-bordered">
                        <tbody>
                           
                            <tr>
                                <td><b>Block No.: </b> {{ $flatData->block }}</td>
                                <td><b>Plot No.: </b> {{ $flatData->plot }} </td>
                            </tr>
                            <tr>
                                <td><b>Presently Known As: </b>{{ $flatData->property_known_as }}
                                </td>
                                <td><b>Area: <span class="text-secondary">({{ $flatData->area_in_sqm }}
                                        Sq
                                        Meter)</span>
                                </td>
                            </tr> 
                             <tr>
                                <td><b>Premium (Re/ Rs): </b>₹
                                    2.2
                                </td>
                                <td><b>Ground Rent (Re/ Rs):
                                    </b>₹
                                    2.2
                                </td>
                            </tr>
                            <tr>
                                <td><b>Start Date of Ground Rent:
                                    </b>2024-08-03 </td>
                                <td><b>RGR Duration (Yrs): </b>
                                    2
                                </td>
                            </tr>
                            <tr>
                                <td><b>First Revision of GR due on:
                                    </b>2026-08-03 </td>
                                <td><b>Purpose for which leased/<br> allotted (As per lease):
                                    </b>Residential
                                </td>
                            </tr>

                            <tr>
                                <td><b>Sub-Type (Purpose , at present):
                                    </b>Multistorey Building
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>Land Use Change:
                                    </b>No </td>

                            </tr>
                            <tr>
                                <td><b>If yes,<br>Purpose for which leased/<br> allotted (As per lease):
                                    </b>NA
                                </td>
                                <td><b>Sub-Type (Purpose , at present):
                                    </b>NA
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr> --}}

                {{-- <h5 class="mb-4 pt-3 text-decoration-underline">LAND TRANSFER DETAILS</h5> --}}
                {{-- <div class="container pb-3"> --}}
                                                                        <!-- Added by Nitin to group land transfer by date ---->
                                                            <!-- Modified By Nitin--->
                                {{-- <div class="border border-primary p-3 mt-3">
                                    <p><b>Process Of Transfer: </b>Substitution</p>
                                                                            <p><b>Date: </b>2024-07-03</p>
                                                                        <table class="table table-bordered">
                                        <tbody><tr>
                                            <th>Lessee Name</th>
                                            <th>Lessee Age (in Years)</th>
                                            <th>Lessee Share</th>
                                            <th>Lessee PAN Number</th>
                                            <th>Lessee Aadhar Number</th>
                                        </tr>
                                                                                    <tr>
                                                <td>Test One</td>
                                                <td></td>
                                                <td>45</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                                                            </tbody></table>
                                </div> --}}
                                                                                <!-- Added by Nitin to group land transfer by date ---->
                                                            <!-- Modified By Nitin--->
                                {{-- <div class="border border-primary p-3 mt-3">
                                    <p><b>Process Of Transfer: </b>Original</p>
                                                                            <p><b>Date: </b>2024-07-14</p>
                                                                        <table class="table table-bordered">
                                        <tbody><tr>
                                            <th>Lessee Name</th>
                                            <th>Lessee Age (in Years)</th>
                                            <th>Lessee Share</th>
                                            <th>Lessee PAN Number</th>
                                            <th>Lessee Aadhar Number</th>
                                        </tr>
                                                                                    <tr>
                                                <td>Sourav Chauhan</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                                                            </tbody></table>
                                </div> --}}
                                                                                <!-- Added by Nitin to group land transfer by date ---->
                                                            <!-- Modified By Nitin--->
                                {{-- <div class="border border-primary p-3 mt-3">
                                    <p><b>Process Of Transfer: </b>Substitution</p>
                                                                            <p><b>Date: </b>2024-07-17</p>
                                                                        <table class="table table-bordered">
                                        <tbody><tr>
                                            <th>Lessee Name</th>
                                            <th>Lessee Age (in Years)</th>
                                            <th>Lessee Share</th>
                                            <th>Lessee PAN Number</th>
                                            <th>Lessee Aadhar Number</th>
                                        </tr>
                                                                                    <tr>
                                                <td>Test Two</td>
                                                <td></td>
                                                <td>34</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                                                            </tbody></table>
                                </div> --}}
                                                                                        </div>
                {{-- <hr> --}}

                {{-- <h5 class="mb-4 pt-3 text-decoration-underline">PROPERTY STATUS DETAILS</h5>
                <div class="container pb-3">
                    <table class="table table-bordered">
                        <tbody>
                                                                                            <tr>
                                    <td><b>Free Hold (F/H): </b>Yes</td>
                                    <td><b>Date of Conveyance Deed:
                                        </b>NA</td>
                                    <td>
                                        <b>In Favour of, Name: </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Vaccant: </b>No</td>
                                    <td><b>In Possession Of:
                                        </b>NA
                                    </td>
                                    <td><b>Date Of Transfer:
                                        </b>NA
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Others: </b>No</td>
                                    <td><b>Remark: </b>NA</td>
                                </tr>
                                                    </tbody>
                    </table>
                </div>
                <hr>

                <h5 class="mb-4 pt-3 text-decoration-underline">INSPECTION &amp; DEMAND DETAILS</h5>
                <div class="container pb-3">
                    <p class="font-weight-bold">No Records Available</p><table class="table table-bordered">
                        <tbody>
                                                            
                                                    </tbody>
                    </table>
                </div>
                <hr>

                <h5 class="mb-4 pt-3 text-decoration-underline">MISCELLANEOUS DETAILS</h5>
                <div class="container pb-3">
                    <p class="font-weight-bold">No Records Available</p><table class="table table-bordered">
                        <tbody>
                                                            
                                                    </tbody>
                    </table>
                </div>
                <hr>

                <h5 class="mb-4 pt-3 text-decoration-underline">Latest Contact Details</h5>
                <div class="container">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td><b>Address: </b>Test</td>
                                <td><b>Phone No.: </b>NA</td>
                            </tr>
                            <tr>
                                <td><b>Email: </b>NA</td>
                                <td><b>As on Date: </b>
                                                                            2024-07-19
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> --}}


                            {{-- </div> --}}
        </div>
    </div>
  


@endsection


@section('footerScript')
    
@endsection
