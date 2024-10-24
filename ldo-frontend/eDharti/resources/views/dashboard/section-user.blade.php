@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<style>
    .subtypes {
        display: flex;
        flex-direction: row;
        justify-content: space-around;
    }

    .typeName {
        text-align: center;
    }

    .custom-col {
        flex: 1;
        margin: 0 5px;
    }

    .custom-col:first-child {
        margin-left: 0;
    }

    .custom-col:last-child {
        margin-right: 0;
    }

    h6 {
        font-size: 11px !important;
    }

    .status_name {
        color: #101010;
        font-size: 16px;
        font-weight: 500;
    }

    .status_name:after {
        content: ':';
        display: inline
    }

    .status_value {
        color: #101010;
        font-size: 16px;
        font-weight: 500;
    }
</style>
<div class="container-fluid">
    <div class="row justify-content-between mb-3">
        <div class="col-lg-6">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Dashboard</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="colony-dropdown ms-auto">
                <div>
                    <select id="select-filter" class="form-select">
                        <option value=""> Filter by section</option>
                        @foreach($sections as $section)
                        <option value="{{$section->section_code}}">{{$section->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid dashboardcards">
        <div class="row">
            <div class="col-lg-6 col-12">
                <div class="card skybluecard">
                    <div class="card-body">
                        <div class="dashboard-card-view">
                            <h4><a href="{{ route('regiserUserListings') }}" style="color: inherit">Total Registrations
                                    {{ $registrations['totalCount'] }}</a></h4>
                            <table class="table table-bordered property-table-infos skybluetable mb-0 mt-3">
                                <tr>
                                    <td><a
                                            href="{{ route('regiserUserListings', ['status' => Crypt::encrypt('RS_NEW')]) }}">Pending:
                                            {{ $registrations['newCount'] }}</a></td>
                                    <td><a
                                            href="{{ route('regiserUserListings', ['status' => Crypt::encrypt('RS_REJ')]) }}">Rejected:
                                            {{ $registrations['rejCount'] }}</a></td>
                                </tr>
                                <tr>
                                    <td><a
                                            href="{{ route('regiserUserListings', ['status' => Crypt::encrypt('RS_UREW')]) }}">Under
                                            Review:
                                            {{ $registrations['urewCount'] }}</a></td>
                                    <td><a
                                            href="{{ route('regiserUserListings', ['status' => Crypt::encrypt('RS_APP')]) }}">Approved:
                                            {{ $registrations['appCount'] }}</a></td>
                                </tr>
                                <tr>
                                    <td><a
                                            href="{{ route('regiserUserListings', ['status' => Crypt::encrypt('RS_REW')]) }}">Review:
                                            {{ $registrations['rewCount'] }}</a></td>
                                    <td><a href="#"></a></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="card pinkcard">
                    <div class="card-body">
                        <div class="dashboard-card-view">
                            <h4><a href="{{ route('applicantNewProperties') }}" style="color: inherit">Added Properties
                                    {{ $newProperty['totalCount'] }}</a> </h4>
                            <table class="table table-bordered property-table-infos pinktable mb-0 mt-3">
                                <tr>
                                    <td><a
                                            href="{{ route('applicantNewProperties', ['status' => Crypt::encrypt('RS_PEN')]) }}">Pending:
                                            {{ $newProperty['penCount'] }}</a></td>
                                    <td><a
                                            href="{{ route('applicantNewProperties', ['status' => Crypt::encrypt('RS_REJ')]) }}">Rejected:
                                            {{ $newProperty['rejCount'] }}</a></td>
                                </tr>
                                <tr>
                                    <td><a
                                            href="{{ route('applicantNewProperties', ['status' => Crypt::encrypt('RS_UREW')]) }}">Under
                                            Review: {{ $newProperty['urewCount'] }}</a></td>
                                    <td><a
                                            href="{{ route('applicantNewProperties', ['status' => Crypt::encrypt('RS_APP')]) }}">Approved:
                                            {{ $newProperty['appCount'] }}</a></td>
                                </tr>
                                <tr>
                                    <td><a
                                            href="{{ route('applicantNewProperties', ['status' => Crypt::encrypt('RS_REW')]) }}">Review:
                                            {{ $newProperty['rewCount'] }}</a></td>
                                    <td><a href="#"></a></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="card offorangecard">
                    <div class="card-body">
                        <div class="dashboard-card-view">
                            <h4><a href="{{ route('admin.applications') }}" style="color: inherit">Total Applications <span id="totalAppCount">{{$totalAppCount}}</span></a></h4>
                            <table class="table table-bordered property-table-infos offorangetable mb-0 mt-3">
                                @foreach ($statusList as $i=>$status)
                                @if($i%3 == 0) <tr> @endif
                                    <td><a href="#">{{$status->item_name}}: <span id="total-{{$status->item_code}}">{{isset($statusWiseCounts[$status->item_code]) ? $statusWiseCounts[$status->item_code] : 0}}</span></a></td>
                                    @if($i%3 == 2)
                                </tr> @endif
                                @endforeach
                            </table>
                            {{--<div class="row mt-4">
                            @foreach ($statusList as $status)
                            <div class="col">
                                <span class="status_name">{{$status->item_name}}</span> <span class="status_value">{{isset($statusWiseCounts[$status->item_code]) ? $statusWiseCounts[$status->item_code] : 0}}</span>
                        </div>
                        @endforeach
                    </div>--}}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card purplecard">
            <div class="card-body">
                <div class="dashboard-card-view">
                    <h4>Public Services 100</h4>
                    <table class="table table-bordered property-table-infos purpletable mb-0 mt-3">
                        <tr>
                            <td><a href="{{ route('grievance.index') }}">Greviances: 67</a></td>
                            <td><a href="{{ route('appointments.index') }}">Appointments: 33</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row smallcards">
    <div class="col-lg-3 col-12">
        <div class="card greycard">
            <div class="card-body">
                <h5>Substituion Mutation</h5>
                <table class="table table-bordered property-table-infos greytable mb-0 mt-3">
                    @foreach ($statusList as $i=>$status)

                    @if($i%2 == 0) <tr> @endif
                        <td><a href="#">{{$status->item_name}}: <span id="mutation-{{$status->item_code}}">{{isset($mutataionData[$status->item_code]) ? $mutataionData[$status->item_code] : 0}}</span></a></td>
                        {{--<td><a href="#">Disposed: {{$mutataionData['APP_WD']}}</a></td>--}}
                        @if($i%2 == 1)
                    </tr> @endif
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-12">
        <div class="card darkbluecard">
            <div class="card-body">
                <h5>Land Use Change</h5>
                <table class="table table-bordered property-table-infos darkbluecardtable mb-0 mt-3">
                    @foreach ($statusList as $i=>$status)

                    @if($i%2 == 0) <tr> @endif
                        <td><a href="#">{{$status->item_name}}: <span id="luc-{{$status->item_code}}">{{isset($lucData[$status->item_code]) ? $lucData[$status->item_code] : 0}}</span></a></td>
                        {{--<td><a href="#">Disposed: {{$mutataionData['APP_WD']}}</a></td>--}}
                        @if($i%2 == 1)
                    </tr> @endif
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <!-- <div class="col-lg-3 col-12">
            <div class="card darkbluecard">
                <div class="card-body">
                    <h5>Sale Permission</h5>
                    <table class="table table-bordered property-table-infos darkbluetable mb-0 mt-3">
                        <tr>
                            <td><a href="#">In Process: 50</a></td>
                            <td><a href="#">Disposed: 50</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div> -->
    <div class="col-lg-3 col-12">
        <div class="card bluecard conversioncard">
            <div class="card-body">
                <h5>Conversion</h5>
                <table class="table table-bordered property-table-infos conversiontable mb-0 mt-3">
                    <tr>
                        <td><a href="#">In Process: 60</a></td>
                        <td><a href="#">Disposed: 40</a></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-12">
        <div class="card redcard">
            <div class="card-body">
                <h5>NOC</h5>
                <table class="table table-bordered property-table-infos noctable mb-0 mt-3">
                    <tr>
                        <td><a href="#">In Process: 85</a></td>
                        <td><a href="#">Disposed: 15</a></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@include('include.alerts.ajax-alert')
@endsection

@section('footerScript')
<script>
    $('#select-filter').change(function() {
        let selectedOption = $(this).val();
        if (selectedOption != "") {
            getFilterDataforSelectedOption(selectedOption);
            $('#select-filter option:first').text('Remove Filter').val('');
        } else {
            let allValues = $('#select-filter option').map(function() {
                if ($(this).val() != "")
                    return $(this).val();
            }).get();
            getFilterDataforSelectedOption(allValues);
            $('#select-filter option:first').text('Filter by section').val('');
        }
    })

    function getFilterDataforSelectedOption(values) {
        $.ajax({
            type: "POST",
            url: "{{route('dashbordSectionFilter')}}",
            data: {
                filter: values,
                _token: "{{csrf_token()}}"
            },
            success: function(response) {
                if (response.status == 'success') {
                    $('#totalAppCount').html(response.totalAppCount);
                    let totalKeys = Object.keys(response.statusWiseCounts);
                    totalKeys.forEach(tk => {
                        $('#total-' + tk).html(response.statusWiseCounts[tk]);
                    })
                    let mutationKeys = Object.keys(response.mutataionData);
                    mutationKeys.forEach(mk => {
                        $('#mutation-' + mk).html(response.mutataionData[mk]);
                    })
                    let lucKeys = Object.keys(response.lucData);
                    lucKeys.forEach(lk => {
                        $('#luc-' + lk).html(response.lucData[lk]);
                    })
                } else {
                    showError(response.details);
                }
            },
            error: function(response) {
                if (response.responseJSON && response.responseJSON.message) {
                    showError(response.responseJSON.message)
                }
            }
        })
    }
</script>
@endsection