@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
@haspermission('view dashboard')

<?php
$labels = [];
$barData = [];
$seriesLabels = [];
foreach ($barChartData as $key => $row) {
	$labels[] = $row->land;
	$data = [];
	$series = [];
	$counter = 0;
	foreach ($row as $index => $val) {
		if ($counter > 0) {
			$data[] = $val;
			if (count($seriesLabels) == 0) {
				$series[] = "'" . $index . "'"; // must wrap in quotes as javascript throws error on < and >
			}
		}
		$counter++;
	}
	if (count($seriesLabels) == 0 && count($series) > 0) {
		$seriesLabels = $series;
	}

	$barData[] = $data;
}
?>
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
	<div class="col">
		<div class="card radius-10 border-start border-0">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div class="widgets-icons-2 rounded-circle text-white mr-icons-margin" style="background-color: #FFEBEE;"><img src="{{asset('assets/images/properties-icon-hand.svg')}}" alt="properties">
					</div>
					<div>
						<h4 class="my-1 grid-icons-size text-dark">{{number_format($totalCount)}}</h4>
						<p class="mb-0 text-secondary">Total No. of Properties</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card radius-10 border-start border-0">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div class="widgets-icons-2 rounded-circle text-white mr-icons-margin" style="background-color: #FFF3E0;"><img src="{{asset('assets/images/pageless.svg')}}" alt="Pageless">
					</div>
					<div>
						<h4 class="my-1 grid-icons-size text-dark">{{number_format(round($totalArea))}}</h4>
						<p class="mb-0 text-secondary">Total Area in Sq. Mtr.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card radius-10 border-start border-0">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div class="widgets-icons-2 rounded-circle text-white mr-icons-margin" style="background-color: #F3E5F5;"><img src="{{asset('assets/images/sell.svg')}}" alt="Land Value">
					</div>
					<div>
						<h4 class="my-1 grid-icons-size text-dark">₹0</h4>
						<p class="mb-0 text-secondary">Total Land Value</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card radius-10 border-start border-0">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div class="widgets-icons-2 rounded-circle text-white mr-icons-margin" style="background-color: #E0F2F1;"><img src="{{asset('assets/images/payments.svg')}}" alt="properties">
					</div>
					<div>
						<h4 class="my-1 text-dark grid-icons-size">₹0</h4>
						<p class="mb-0 text-secondary">Revenue: FY 2023-2024</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-6">
	<div class="col">
		<div class="card radius-10 border-start border-0">
			<div class="card-body">
				<div class="text-center">
					<div class="widgets-icons-2 rounded-circle text-white m-auto" style="background-color: #E8F5E9;"><img src="{{asset('assets/images/lease-hold.svg')}}" alt="properties">
					</div>
					<div>
						<h4 class="my-1 text-dark">{{number_format($statusCount['lease_hold'])}}</h4>
						<p class="mb-0 text-secondary">Lease Hold</p>
					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card radius-10 border-start border-0">
			<div class="card-body">
				<div class="text-center">
					<div class="widgets-icons-2 rounded-circle text-white m-auto" style="background-color: #FCE4EC;"><img src="{{asset('assets/images/freeHold.svg')}}" alt="Free Hold">
					</div>
					<div>
						<h4 class="my-1 text-dark">{{number_format($statusCount['free_hold'])}}</h4>
						<p class="mb-0 text-secondary">Free Hold</p>
					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card radius-10 border-start border-0">
			<div class="card-body">
				<div class="text-center">
					<div class="widgets-icons-2 rounded-circle text-white m-auto" style="background-color: #E0F2F1;"><img src="{{asset('assets/images/residential.svg')}}" alt="properties">
					</div>
					<div>
						<h4 class="my-1 text-dark">{{number_format($propertyTypeCount['Residential'])}}</h4>
						<p class="mb-0 text-secondary">Residential</p>
					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card radius-10 border-start border-0">
			<div class="card-body">
				<div class="text-center">
					<div class="widgets-icons-2 rounded-circle text-white m-auto" style="background-color: #FFF3E0;"><img src="{{asset('assets/images/commercial.svg')}}" alt="properties">
					</div>
					<div>
						<h4 class="my-1 text-dark">{{number_format($propertyTypeCount['Commercial'])}}</h4>
						<p class="mb-0 text-secondary">Commercial</p>
					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card radius-10 border-start border-0">
			<div class="card-body">
				<div class="text-center">
					<div class="widgets-icons-2 rounded-circle text-white m-auto" style="background-color: #FFEBEE;"><img src="{{asset('assets/images/factory.svg')}}" alt="Industrial">
					</div>
					<div>
						<h4 class="my-1 text-dark">{{number_format($propertyTypeCount['Industrial'])}}</h4>
						<p class="mb-0 text-secondary">Industrial</p>
					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card radius-10 border-start border-0">
			<div class="card-body">
				<div class="text-center">
					<div class="widgets-icons-2 rounded-circle text-white m-auto" style="background-color: #F3E5F5;"><img src="{{asset('assets/images/institute.svg')}}" alt="properties">
					</div>
					<div>
						<h4 class="my-1 text-dark">{{number_format($propertyTypeCount['Institutional'])}}</h4>
						<p class="mb-0 text-secondary">Institutional</p>
					</div>

				</div>
			</div>
		</div>
	</div>

</div>
<!--end row-->

<div class="row">
	<div class="col-12 col-lg-4">
		<div class="card radius-10">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>
						<h6 class="mb-0">No. of Properties</h6>
					</div>
					<div class="dropdown ms-auto">
						<a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="javascript:;">Export PDF</a>
							</li>
							<li><a class="dropdown-item" href="javascript:;">Export CSV</a>
							</li>
						</ul>
					</div>
				</div>

				<div class="chart-container-1" style="height: 390px;">
					<canvas id="chartProperties1"></canvas>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-8">
		<div class="card radius-10">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>
						<h6 class="mb-0">Area of Properties (Sq. Mtr.)</h6>
					</div>
					<ul class="graph-color-code ms-auto">
						<li><span class="ap1"></span> Nazul</li>
						<li><span class="ap2"></span> Rehabilitation</li>
					</ul>
					<div class="dropdown">
						<a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="javascript:;">Export PDF</a>
							</li>
							<li><a class="dropdown-item" href="javascript:;">Export CSV</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="chart-container-2 mt-4" style="height: 365px;">
					<canvas id="chart-area"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>
<!--end row-->

<div class="row height-row-align">
	<div class="col-12 col-lg-8 height-col">
		<div class="card radius-10 same-height-card">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>
						<h6 class="mb-0">Land Value</h6>
					</div>
					<div class="dropdown ms-auto">
						<a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="javascript:;">Export PDF</a>
							</li>
							<li><a class="dropdown-item" href="javascript:;">Export CSV</a>
							</li>
						</ul>
					</div>
				</div>

				<div class="chart-container-1" style="height: 490px;">
					<canvas id="landvalue"></canvas>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-4 height-col">
		<div class="card radius-10 same-height-card">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>
						<h6 class="mb-0">Value of Properties (INR)</h6>
					</div>
					<div class="dropdown ms-auto">
						<a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="javascript:;">Export PDF</a>
							</li>
							<li><a class="dropdown-item" href="javascript:;">Export CSV</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="chart-container-1 mt-5">
					<div class="row align-items-center mb-5">
						<div class="col-lg-6">
							<canvas id="chart2"></canvas>
						</div>
						<div class="col-lg-6">
							<h5 class="doudhnut-title">Year 2014</h5>
							<ul class="doughnut-data-1">
								<li><span class="nazula-circle"></span> ₹800cr - Nazul</li>
								<li><span class="rehabilitation-circle"></span> ₹534cr - Rehabilitation</li>
							</ul>
						</div>
					</div>
					<div class="row align-items-center">
						<div class="col-lg-6">
							<canvas id="chartDoughnut2"></canvas>
						</div>
						<div class="col-lg-6">
							<h5 class="doudhnut-title">Year 2007</h5>
							<ul class="doughnut-data-1">
								<li><span class="nazula-circle"></span> ₹234cr - Nazul</li>
								<li><span class="rehabilitation-circle"></span> ₹1000cr - Rehabilitation</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end row -->

<div class="row">
	<div class="col-12 col-lg-8">
		<div class="card radius-10">
			<div class="card-body no-padding">
				<div class="d-flex">
					<div class="nav-tabs-left-aside">
						<ul class="nav nav-tabs nav-primary" role="tablist" style="display: block !important;">

							@if(count($tabHeader) > 0)
							@foreach($tabHeader as $i=>$th)
							<li class="nav-item" role="presentation">
								<a class="nav-link {{$i == 0 ? 'active': ''}}" data-bs-toggle="tab" href="#" role="tab" aria-selected="true" onclick="getTabData('{{$th->id}}')">
									<div class="text-center">
										<div class="tab-title">{{$th->property_type_name}}</div>
										<span class="tab-total-no">{{$th->counter}}</span>
									</div>
								</a>
							</li>
							@endforeach
							@endif
							<!-- <li class="nav-item" role="presentation">
								<a class="nav-link active" data-bs-toggle="tab" href="#residential" role="tab" aria-selected="true">
									<div class="text-center">
										<div class="tab-title">Residential</div>
										<span class="tab-total-no">20K</span>
									</div>
								</a>
							</li>
							<li class="nav-item" role="presentation">
								<a class="nav-link" data-bs-toggle="tab" href="#commercial" role="tab" aria-selected="false">
									<div class="text-center">
										<div class="tab-title">Commercial</div>
										<span class="tab-total-no">10K</span>
									</div>
								</a>
							</li>
							<li class="nav-item" role="presentation">
								<a class="nav-link" data-bs-toggle="tab" href="#industrial" role="tab" aria-selected="false">
									<div class="text-center">
										<div class="tab-title">Industrial</div>
										<span class="tab-total-no">7K</span>
									</div>
								</a>
							</li>
							<li class="nav-item" role="presentation">
								<a class="nav-link" data-bs-toggle="tab" href="#institutional" role="tab" aria-selected="false">
									<div class="text-center">
										<div class="tab-title">Institutional</div>
										<span class="tab-total-no">5K</span>
									</div>
								</a>
							</li>
							<li class="nav-item" role="presentation">
								<a class="nav-link" data-bs-toggle="tab" href="#residentialCommercial" role="tab" aria-selected="false">
									<div class="text-center">
										<div class="tab-title">Residential & Commercial</div>
										<span class="tab-total-no">8K</span>
									</div>
								</a>
							</li>
							<li class="nav-item" role="presentation">
								<a class="nav-link" data-bs-toggle="tab" href="#others" role="tab" aria-selected="false">
									<div class="text-center">
										<div class="tab-title">Others</div>
										<span class="tab-total-no">10K</span>
									</div>
								</a>
							</li> -->
						</ul>
					</div>
					<div class="nav-tabs-right-aside">
						<div class="tab-content py-3">
							<div class="tab-pane fade show active" id="" role="tabpanel">
								<ul class="progress-report">
									<!-- new code -->
									<?php
									$max = 0;
									foreach ($tab1Details as $row) {
										$max = $row->counter > $max ? $row->counter : $max;
									}
									?>

									@foreach($tab1Details as $detail)
									<li>
										<?php
										$width = $max > 0 ? ($detail->counter / $max) * 100 : 0;
										?>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">{{$detail->PropSubType}}</span>
											<span class="progress-result">{{$detail->counter}}</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: {{$width}}%" aria-valuenow="{{$detail->counter}}" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									@endforeach

								</ul>

								<!--- new code -->
							</div>
							<!-- <div class="tab-pane fade" id="commercial" role="tabpanel">
								<ul class="progress-report">
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Central Government / PSU</span>
											<span class="progress-result">1.5K</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">State Government / PSU</span>
											<span class="progress-result">1K</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">International Govt. / Organisation</span>
											<span class="progress-result">700</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Political Parties</span>
											<span class="progress-result">850</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Socio-Cultural Institute</span>
											<span class="progress-result">100</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Educational Institutes</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Medical Facilities</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Clubs</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Public Infrastructure (Public Convenience, Fire, Water & Electricity Supply, Fire & Police)</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Others (NGO, etc.)</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
								</ul>
							</div>
							<div class="tab-pane fade" id="industrial" role="tabpanel">
								<ul class="progress-report">
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Central Government / PSU</span>
											<span class="progress-result">1.5K</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">State Government / PSU</span>
											<span class="progress-result">1K</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">International Govt. / Organisation</span>
											<span class="progress-result">700</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Political Parties</span>
											<span class="progress-result">850</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Socio-Cultural Institute</span>
											<span class="progress-result">100</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Educational Institutes</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Medical Facilities</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Clubs</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Public Infrastructure (Public Convenience, Fire, Water & Electricity Supply, Fire & Police)</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Others (NGO, etc.)</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
								</ul>
							</div>
							<div class="tab-pane fade" id="institutional" role="tabpanel">
								<ul class="progress-report">
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Central Government / PSU</span>
											<span class="progress-result">1.5K</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">State Government / PSU</span>
											<span class="progress-result">1K</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">International Govt. / Organisation</span>
											<span class="progress-result">700</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Political Parties</span>
											<span class="progress-result">850</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Socio-Cultural Institute</span>
											<span class="progress-result">100</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Educational Institutes</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Medical Facilities</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Clubs</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Public Infrastructure (Public Convenience, Fire, Water & Electricity Supply, Fire & Police)</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Others (NGO, etc.)</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
								</ul>
							</div>
							<div class="tab-pane fade" id="residentialCommercial" role="tabpanel">
								<ul class="progress-report">
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Central Government / PSU</span>
											<span class="progress-result">1.5K</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">State Government / PSU</span>
											<span class="progress-result">1K</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">International Govt. / Organisation</span>
											<span class="progress-result">700</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Political Parties</span>
											<span class="progress-result">850</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Socio-Cultural Institute</span>
											<span class="progress-result">100</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Educational Institutes</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Medical Facilities</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Clubs</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Public Infrastructure (Public Convenience, Fire, Water & Electricity Supply, Fire & Police)</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Others (NGO, etc.)</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
								</ul>
							</div>
							<div class="tab-pane fade" id="others" role="tabpanel">
								<ul class="progress-report">
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Central Government / PSU</span>
											<span class="progress-result">1.5K</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">State Government / PSU</span>
											<span class="progress-result">1K</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">International Govt. / Organisation</span>
											<span class="progress-result">700</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Political Parties</span>
											<span class="progress-result">850</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Socio-Cultural Institute</span>
											<span class="progress-result">100</span>
										</div>
										<div class="progress mb-4" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Educational Institutes</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Medical Facilities</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Clubs</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Public Infrastructure (Public Convenience, Fire, Water & Electricity Supply, Fire & Police)</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
									<li>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="progress-title">Others (NGO, etc.)</span>
											<span class="progress-result">150</span>
										</div>
										<div class="progress" style="height:7px;">
											<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</li>
								</ul>
							</div> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-4">
		<div class="card radius-10">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>
						<h6 class="mb-0">Revenue (INR in CR)</h6>
					</div>
					<div class="dropdown ms-auto">
						<a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="javascript:;">Export PDF</a>
							</li>
							<li><a class="dropdown-item" href="javascript:;">Export CSV</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="chart-container-2" style="height: 564px;">
					<canvas id="revenueINR"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end row -->
<div class="row">
	<div class="col-12 col-lg-6">
		<div class="card radius-10">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>
						<h6 class="mb-0">Number of Properties</h6>
					</div>
					<ul class="graph-color-code ms-auto">
						<li><span class="ap1"></span> Nazul</li>
						<li><span class="ap2" style="background-color: #FFB74D;"></span> Rehabilitation</li>
					</ul>
					<div class="dropdown">
						<a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="javascript:;">Export PDF</a>
							</li>
							<li><a class="dropdown-item" href="javascript:;">Export CSV</a>
							</li>
						</ul>
					</div>
				</div>

				<div class="chart-container-1" style="height: 480px;">
					<canvas id="noofproperties"></canvas>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-6">
		<div class="card radius-10">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>
						<h6 class="mb-0">Property Area</h6>
					</div>
					<!-- <ul class="graph-color-code ms-auto">
						<li><span class="ap1" style="border-radius: 50px;"></span> New Visitors</li>
						<li><span class="ap2" style="background-color: #81C784;border-radius: 50px;"></span> Returning Visitors</li>
						<li><span class="date-now">Mar 2024</span></li>
					</ul>
					<div class="dropdown">
						<a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="javascript:;">Export PDF</a>
							</li>
							<li><a class="dropdown-item" href="javascript:;">Export CSV</a>
							</li>
						</ul>
					</div> -->
				</div>

				<div class="chart-container"><!--style="height: 480px;"-->
					<table class="table table-striped text-center">
						<thead>
							<tr>
								<th>Area of Property(Sqm)</th>
								<th>Number of Properties</th>
								<th>Area of Propoerties</th>
							</tr>
						</thead>
						@php
						$labels = $propertyAreaDetails['labels'];
						$counts = $propertyAreaDetails['counts'];
						$areas = $propertyAreaDetails['areas'];
						$totalAreaInSqm = array_sum($areas);
						$totalCount = array_sum($counts);
						$totalAreaInAcre = $totalAreaInSqm * 0.0002471053815;
						@endphp
						<tbody>
							@foreach($labels as $i=>$label)
							<tr>
								<td>{{$label}}</td>
								<td>{{$counts[$i].' ('. round(($counts[$i]/$totalCount)*100).'%)'}}</td>
								<td>{{$areas[$i].' ('. round(($areas[$i]/$totalAreaInSqm)*100). '%)'}}</td>
							</tr>
							@endforeach
							<tr>
								<th>Grand Total</th>
								<th>{{$totalCount}}</th>
								<th>{{round($totalAreaInSqm,3)}} ({{round($totalAreaInAcre,3)}} Acres)</th>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@else
		<div class="card radius-10">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<img src="assets/images/avatars/avatar-1.png" class="rounded-circle p-1 border" width="90" height="90" alt="...">
					<div class="flex-grow-1 ms-3">
						<h5 class="mt-0">Hello {{auth()->user()->name}}</h5>
						<p class="mb-0">Welcome to EDharti MIS Form</p>
					</div>
				</div>
			</div>
		</div>
	@endhaspermission
	@endsection
<!-- end row -->

@section('footerScript')
@haspermission('view dashboard')
	<script src="{{ asset('assets/js/Chart.min.js') }}"></script>
	<script src="{{ asset('assets/js/chartjs-plugin-datalabels.min.js') }}"></script>
	<script src="{{ asset('assets/js/index.js') }}"></script>
<script>
	$(document).ready(() => {

		//chart no. (nazul rehabilitation)
		var ctx2 = document.getElementById("chartProperties1").getContext('2d');

		var gradientStroke1 = ctx2.createLinearGradient(0, 0, 0, 300);
		gradientStroke1.addColorStop(0, '#64B5F6');
		// gradientStroke1.addColorStop(1, '#17c5ea');

		var gradientStroke2 = ctx2.createLinearGradient(0, 0, 0, 300);
		gradientStroke2.addColorStop(0, '#81C784');
		// gradientStroke2.addColorStop(1, '#ffdf40');

		var myChart = new Chart(ctx2, {
			type: 'bar',
			data: {
				labels: ['No. of Properties'],
				datasets: [{
						label: 'Nazul',
						data: ["{{$landTypeCount['Nazul']}}"],
						borderColor: gradientStroke1,
						backgroundColor: gradientStroke1,
						hoverBackgroundColor: gradientStroke1,
						pointRadius: 0,
						fill: false,
						borderWidth: 0
					},
					{
						label: 'Rehabilitation',
						data: ["{{$landTypeCount['Rehabilitation']}}"],
						borderColor: gradientStroke2,
						backgroundColor: gradientStroke2,
						hoverBackgroundColor: gradientStroke2,
						pointRadius: 0,
						fill: false,
						borderWidth: 0
					}
				]
			},

			options: {
				plugins: {
					datalabels: {
						color: '#fff',
						font: {
							size: 18
						}
					}
				},
				maintainAspectRatio: false,
				legend: {
					position: 'bottom',
					display: true,
					labels: {
						boxWidth: 8
					}
				},
				tooltips: {
					displayColors: true,
					display: true
				},
				scales: {
					xAxes: [{
						barPercentage: .5
					}]
				}
			}
		});

		var ctx3 = document.getElementById("chart-area").getContext('2d');

		var gradientStroke1 = ctx3.createLinearGradient(0, 0, 0, 300);
		gradientStroke1.addColorStop(0, '#64B5F6');
		// gradientStroke1.addColorStop(1, '#17c5ea'); 

		var gradientStroke2 = ctx3.createLinearGradient(0, 0, 0, 300);
		gradientStroke2.addColorStop(0, '#E57373');
		// gradientStroke2.addColorStop(1, '#ffdf40');

		var myChart = new Chart(ctx3, {
			type: 'bar',
			data: {
				labels: [<?= implode(", ", $seriesLabels) ?>],
				datasets: [{
						label: '{{$labels[0]}}',
						data: [<?= implode(", ", $barData[0]) ?>],
						borderColor: gradientStroke1,
						backgroundColor: gradientStroke1,
						hoverBackgroundColor: gradientStroke1,
						pointRadius: 0,
						fill: false,
						borderWidth: 0
					},
					{
						label: '{{$labels[1]}}',
						data: [<?= implode(", ", $barData[1]) ?>],
						borderColor: gradientStroke2,
						backgroundColor: gradientStroke2,
						hoverBackgroundColor: gradientStroke2,
						pointRadius: 1,
						fill: false,
						borderWidth: 1
					}
				]
			},

			options: {
				plugins: {
					datalabels: {
						color: '#fff',
						font: {
							size: 18
						}
					}
				},
				maintainAspectRatio: false,
				legend: {
					position: 'top',
					display: false,
					labels: {
						boxWidth: 0
					}
				},
				tooltips: {
					displayColors: false,
				},
				scales: {
					XAxes: [{
						barPercentage: .5
					}]
				}
			}
		});
	});

	/*** new code by Nitin ---  */
	const getTabData = (tabId) => {
		console.log(tabId);
		$.ajax({
			type: 'get',
			url: "{{ url('dashboard/property-type-data')}}" + '/' + tabId,
			success: response => {
				let updatedHTML = '';
				//get max counter 
				let max = 0;
				response.map(row => {
					max = row.counter > max ? row.counter : max;
				});
				//get progressbar html for each row
				response.map(row => {
					let width = (max > 0) ? (row.counter / max) * 100 : 0;
					updatedHTML += `<li>
						<div class = "d-flex justify-content-between align-items-center mb-2">
							<span class = "progress-title">${row.PropSubType}</span>
							<span class="progress-result">${row.counter}</span>
						</div>
						<div class = "progress mb-4" style = "height:7px;">
						<div class="progress-bar" role="progressbar" style="width: ${width}%" aria-valuenow="${row.counter}" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</li>`

				})
				$('ul.progress-report').html(updatedHTML);
			},
			error: errorResponse => {
				console.log(errorResponse);
			}
		})
	}
</script>
@endhaspermission
@endsection