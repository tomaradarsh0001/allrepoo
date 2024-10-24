<div class="mt-3">
    <div class="container-fluid">
        <div class="row g-2">
            <div class="col-lg-12">
                <div class="part-title mb-2">
                    <h5>Fill Apartment Details</h5>
                </div>
            </div>
            <input type="hidden" id="old_property_id" name="old_property_id"
                value="{{ isset($application) ? $application->old_property_id : old('old_property_id') }}">
            <input type="hidden" id="property_master_id" name="property_master_id"
                value="{{ isset($application) ? $application->property_master_id : old('property_master_id') }}">
            <input type="hidden" id="new_property_id" name="new_property_id"
                value="{{ isset($application) ? $application->new_property_id : old('new_property_id') }}">
            <input type="hidden" id="splited_property_detail_id" name="splited_property_detail_id"
                value="{{ isset($application) ? $application->splited_property_detail_id : old('splited_property_detail_id') }}">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="applicantName" class="form-label">Name<span
                        class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="applicantName" id="applicantName"
                        placeholder="Enter Name"
                        value="{{ isset($application) ? $application->applicant_name : old('applicantName') }}">
                    @error('applicantName')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="text-danger" id="applicantNameError"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="applicantAddress" class="form-label">Communication Address<span
                        class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="applicantAddress" id="applicantAddress"
                        placeholder="Enter Communication Address"
                        value="{{ isset($application) ? $application->applicant_address : old('applicantAddress') }}">
                    @error('applicantAddress')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="text-danger" id="applicantAddressError"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="buildingName" class="form-label">Building Name<span
                        class="text-danger">*</span> <small class="form-text text-muted">(In
                            which the apartment exists.)</small></label>
                    <input type="text" class="form-control" name="buildingName" id="buildingName"
                        placeholder="Building name"
                        value="{{ isset($application) ? $application->building_name : old('buildingName') }}">
                    @error('buildingName')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="text-danger" id="buildingNameError"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="locality" class="form-label">Locality<span
                        class="text-danger">*</span></label>
                    <select name="locality" id="locality" class="form-select">
                        <option value="">Select</option>
                        @if(isset($colonyList))
                        @foreach ($colonyList as $colony)
                            <option value="{{ $colony->id }}"
                                {{ isset($application) && $application->locality == $colony->id ? 'selected' : '' }}>
                                {{ $colony->name }}</option>
                        <option value="{{ $colony->id }}">{{ $colony->name }}</option>
                        @endforeach
                        @endif
                    </select>
                    @error('locality')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="text-danger" id="localityError"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="block" class="form-label">Block<span
                        class="text-danger">*</span></label>
                    <select name="block" id="block" class="form-select">
                        <option value="">Select</option>
                    </select>
                    @error('block')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="text-danger" id="blockError"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="plot" class="form-label">Plot No.<span
                        class="text-danger">*</span> <small class="form-text text-muted">(Where
                            the
                            building/property exists.)</small></label>
                    <select name="plot" id="plot" class="form-select">
                        <option value="">Select</option>
                    </select>
                    @error('plot')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="text-danger" id="plotError"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="knownas" class="form-label">Prasently Known As<span
                        class="text-danger">*</span></label>
                    <select name="knownas" id="knownas" class="form-select">
                        <option value="">Select</option>
                    </select>
                    @error('knownas')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="text-danger" id="knownasError"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="flatId" class="form-label">Flat</label>
                    <select name="flatId" id="flatId" class="form-select">
                        <option value="">Select</option>
                    </select>
                    @error('flatId')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="text-danger" id="flatIdError"></div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <div class="checkbox-options" style="padding-top:36px !important;">
                        <div class="form-check form-check-success">
                            <label class="form-check-label" for="isFlatNotInList">
                                is Flat not listed?
                            </label>
                            <input class="form-check-input required-for-approve" name="isFlatNotInList"
                                type="checkbox" id="isFlatNotInList" @if (isset($application) && $application->isFlatNotListed) checked @endif>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <label for="flatNumber" class="form-label">Flat No.<span
                        class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="flatNumber" id="flatNumber"
                        placeholder="Flat Number"
                        value="{{ isset($application) ? $application->flat_number : old('flatNumber') }}" readonly>
                    @error('flatNumber')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="text-danger" id="flatNumberError"></div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <label for="builderName" class="form-label">Name of Builder / Developer<span
                        class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="builderName" id="builderName"
                        placeholder="Name of Builder / Developer"
                        value="{{ isset($application) ? $application->builder_developer_name : old('builderName') }}"
                        readonly>
                    @error('builderName')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="text-danger" id="builderNameError"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="originalBuyerName" class="form-label">Name Of Original Buyer<span
                        class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="originalBuyerName" id="originalBuyerName"
                        placeholder="Enter Name Of Original Buyer"
                        value="{{ isset($application) ? $application->original_buyer_name : old('originalBuyerName') }}">
                </div>
                @error('originalBuyerName')
                <div class="text-danger">{{ $message }}</div>
                @enderror
                <div class="text-danger" id="originalBuyerNameError"></div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="presentOccupantName" class="form-label">Name Of Present Occupant<span
                        class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="presentOccupantName" id="presentOccupantName"
                        placeholder="Enter Name Of Present Occupant"
                        value="{{ isset($application) ? $application->present_occupant_name : old('presentOccupantName') }}">
                </div>
                @error('presentOccupantName')
                <div class="text-danger">{{ $message }}</div>
                @enderror
                <div class="text-danger" id="presentOccupantNameError"></div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="purchasedFrom" class="form-label">Purchased From<span
                        class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="purchasedFrom" id="purchasedFrom"
                        placeholder="Enter Purchased From"
                        value="{{ isset($application) ? $application->purchased_from : old('purchasedFrom') }}">
                </div>
                @error('purchasedFrom')
                <div class="text-danger">{{ $message }}</div>
                @enderror
                <div class="text-danger" id="purchasedFromError"></div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="purchaseDate" class="form-label">Date of Purchase<span
                        class="text-danger">*</span></label>
                    <input type="date" name="purchaseDate" class="form-control" id="purchaseDate"
                        pattern="\d{2} \d{2} \d{4}"
                        value="{{ isset($application) ? $application->purchased_date : old('purchaseDate') }}">
                </div>
                @error('purchaseDate')
                <span class="errorMsg">{{ $message }}</span>
                @enderror
                <div id="purchaseDateError" class="text-danger"></div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="apartmentArea" class="form-label">Flat Area<span
                        class="text-danger">*</span> <small class="form-text text-muted">(In
                            Sq.
                            Mtr. including common area.)</small></label>
                    <input type="text" class="form-control" name="apartmentArea" id="apartmentArea"
                        placeholder="Enter Total Flat Area"
                        value="{{ isset($application) ? $application->flat_area : old('apartmentArea') }}">
                </div>
                @error('apartmentArea')
                <div class="text-danger">{{ $message }}</div>
                @enderror
                <div class="text-danger" id="apartmentAreaError"></div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="plotArea" class="form-label">Plot Area<span
                        class="text-danger">*</span> <small class="form-text text-muted">(Leased
                            From L&DO in Sq. Mtr.)</small> </label>
                    <input type="text" class="form-control" name="plotArea" id="plotArea"
                        placeholder="Enter Total Plot Area"
                        value="{{ isset($application) ? $application->plot_area : old('plotArea') }}">
                </div>
                @error('plotArea')
                <div class="text-danger">{{ $message }}</div>
                @enderror
                <div class="text-danger" id="plotAreaError"></div>
            </div>
        </div>
    </div>
</div>