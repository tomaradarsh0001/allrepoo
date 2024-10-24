@extends('layouts.app')

@section('title', 'MIS Form Details')

@section('content')
<style>
	.pagination .active a{
		color:#ffffff !important;

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
                        <li class="breadcrumb-item active" aria-current="page">MIS</li>
                    </ol>
                </nav>
            </div>
            <!-- <div class="ms-auto"><a href="#" class="btn btn-primary">Button</a></div> -->
        </div>
        
        <hr>

        <div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example2" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>S.No.</th>
										<th>Property Id</th>
										<th>File Number</th>
										<th>Property Type</th>
										<th>Property SubType</th>
										<th>Property Status</th>
										<th>Section</th>
										<th>Address</th>
										<th>Premium (₹)</th>
										<th>Ground Rent (₹)</th>
										<th>Area</th>
										<th>Username</th>
										<th>Created At</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
                                    @foreach($propertyDetails as $index => $propertyDetail)
									<tr>
										<td>{{$index+1}}</td>
										<td>
                                            <div class="cursor-pointer text-primary" data-bs-toggle="modal" data-bs-target="#exampleScrollableModal{{$propertyDetail->id}}">{{$propertyDetail->unique_propert_id}}</div> <span class="text-secondary">({{$propertyDetail->old_propert_id}})</span>
                                            <div class="modal fade" id="exampleScrollableModal{{$propertyDetail->id}}" tabindex="-1" aria-hidden="true">
											<div class="modal-dialog modal-dialog-scrollable modal-xl">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title">Modal title</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
                                                    <table class="table mb-0">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col">#</th>
                                                                                <th scope="col">Process Of Transfer</th>
                                                                                <th scope="col">Transfer Date</th>
                                                                                <th scope="col">Lessee Name</th>
                                                                                <th scope="col">Lessee Age</th>
                                                                                <th scope="col">Property Share</th>
                                                                                <th scope="col">Lessee pan No.</th>
                                                                                <th scope="col">Lessee aadhar No.</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($propertyDetail->propertyTransferredLesseeDetails as $key => $lesseeDetails)
                                                                                <tr>
                                                                                    <th scope="row">{{$key+1}}</th>
                                                                                    <td>{{$lesseeDetails->process_of_transfer}}</td>
                                                                                    <td>{{$lesseeDetails->transferDate}}</td>
                                                                                    <td>{{$lesseeDetails->lessee_name}}</td>
                                                                                    <td>{{$lesseeDetails->lessee_age}}</td>
                                                                                    <td>{{$lesseeDetails->property_share}}</td>
                                                                                    <td>{{$lesseeDetails->lessee_pan_no}}</td>
                                                                                    <td>{{$lesseeDetails->lessee_aadhar_no}}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
													</div>
												</div>
											</div>
										</div>
                                        </td>
										<td>
											<div>{{$propertyDetail->unique_file_no}}</div>
											<span class="text-secondary">({{$propertyDetail->file_no}})</span>
										</td>
										<td>{{$item->itemNameById($propertyDetail->property_type)}}</td>
										<td>{{$item->itemNameById($propertyDetail->property_sub_type)}}</td>
										<td>{{$item->itemNameById($propertyDetail->status)}}</td>
										<td>{{$propertyDetail->section_code}}</td>
										<td>@if($propertyDetail->block_no)
											{{$propertyDetail->block_no}}/
											@endif
											{{$propertyDetail->plot_or_property_no}}/ 
											{{$propertyDetail->oldColony->name}}</td>

										<td>
										@if ($propertyDetail->propertyLeaseDetail)
											{{$propertyDetail->propertyLeaseDetail->premium}}.{{$propertyDetail->propertyLeaseDetail->premium_in_paisa}}{{$propertyDetail->propertyLeaseDetail->premium_in_aana}}
										@endif
										</td>

										<td>
										@if ($propertyDetail->propertyLeaseDetail)
											{{$propertyDetail->propertyLeaseDetail->gr_in_re_rs}}.{{$propertyDetail->propertyLeaseDetail->gr_in_paisa}}
										@endif
										</td>

										<td>
										@if ($propertyDetail->propertyLeaseDetail)
											{{$propertyDetail->propertyLeaseDetail->plot_area}} {{$item->itemNameById($propertyDetail->propertyLeaseDetail->unit)}}
										@endif
										</td>
										<td>{{$user->userNameById($propertyDetail->created_by)}}</td>
										<td>
											
											@php
												// Convert UTC time to IST using Carbon
												$utcTime = $propertyDetail->created_at; // Assuming $propertyDetail contains your UTC timestamp
												$istTime = $utcTime->setTimezone('Asia/Kolkata');
											@endphp

											{{ $istTime->format('Y-m-d H:i:s') }}
										</td>
										<td>
											<div class="d-flex gap-3">
												<a href="{{ url('property-details/'.$propertyDetail->id.'/view') }}""> <button type="button" class="btn btn-danger px-5">View</button></a>
												@haspermission('edit.property.details')
												<a href="{{ url('property-details/'.$propertyDetail->id.'/edit') }}"><button type="button" class="btn btn-primary px-5">Edit</button></a>
												@endhaspermission
											</div>
										</td>
                                        <!-- <td>
                                            <a href="#">
                                                <div class="col">
                                                    <button type="button" class="btn btn-info px-5 radius-30">View Detail</button>
                                                </div>
                                            </a>
                                        </td> -->
									</tr>
                                    
                                    @endforeach
								</tbody>
								<!-- <tfoot>
									<tr>
                                        <th>S.No.</th>
										<th>Property Id</th>
										<th>File Number</th>
										<th>Colony Name Old</th>
										<th>Colony Name New</th>
										<th>Colony Name New</th>
										<th>Block No</th>
										<th>Plot No.</th>
									</tr>
								</tfoot> -->
							</table>
						</div>
					</div>
				</div>
        
@endsection


@section('footerScript')
<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>
	<script>
		$(document).ready(function() {
			var table = $('#example2').DataTable( {
				lengthChange: false,
				buttons: [ 'copy', 'excel', 'pdf', 'print']
			} );
		 
			table.buttons().container()
				.appendTo( '#example2_wrapper .col-md-6:eq(0)' );
		} );
	</script>
@endsection