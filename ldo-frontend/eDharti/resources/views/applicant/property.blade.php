@extends('layouts.app')

@section('title', 'Applicant Property History')

@section('content')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">ACCOUNT DETAILS</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Property Details</li>
                </ol>
            </nav>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="part-title">
                <h5>PROPERTY DETAILS</h5>
            </div>
            <div class="part-details">
                <div class="container-fluid">
                    @if ($user->userProperties)
                        @foreach ($user->userProperties as $property)
                            <div class="row">
                                <div class="col-lg-12 col-12">
                                    <h5 class="text-primary">PROPERTY ID : {{ $property->new_property_id ?? '' }}</h5>
                                    {{-- <span class="badge bg-primary rounded-pill"
                                        style="height: 29px; width: 29px; padding-top: 7px; font-size: 14px; margin-bottom: 20px;">{{ $i }}</span> --}}
                                    <table class="table table-bordered property-table-info">
                                        <tbody>
                                            <tr>
                                                <th>Property ID :</th>
                                                <td>{{ $property->new_property_id ?? '' }}</td>
                                                <th>Current Status:</th>
                                                <td>
                                                    <div
                                                        class="ml-2 badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">
                                                        Approved
                                                    </div>
                                                </td>

                                            </tr>
                                            <tr>
                                                <th>Locality :</th>
                                                <td>
                                                    {{ $property->oldColony->name ?? '' }}
                                                </td>
                                                <th>Block :</th>
                                                <td>{{ $property->block ?? '' }}</td>


                                            </tr>
                                            <tr>
                                                <th>Plot :</th>
                                                <td>{{ $property->plot ?? '' }}</td>
                                                <th>Known As :</th>
                                                <td>{{ $property->known_as ?? '' }}</td>

                                            </tr>
                                            <tr>
                                                <th>Documents :</th>
                                                <td colspan="3">
                                                    @if ($property->documents->isNotEmpty())
                                                        <span class="view-hover-data show-toggle-data ms-2"
                                                            style="text-align: left;">
                                                            <a href="javascript:void(0);" class="text-danger pdf-icons"><i
                                                                    class='bx bxs-file-pdf fs-4'></i></a>
                                                            <div class="tooltip-data" style="left: 15px; width:250px;">
                                                                @foreach ($property->documents as $document)
                                                                    @php
                                                                        // Default fileName to an empty string
                                                                        $fileName = '';

                                                                        // Check if title is not empty and split it to determine the file type
                                                                        if (!empty($document->title)) {
                                                                            $obj = explode('_', $document->title);

                                                                            if (isset($obj[0])) {
                                                                                switch ($obj[0]) {
                                                                                    case 'saledeed':
                                                                                        $fileName = 'Sale Deed';
                                                                                        break;
                                                                                    case 'BuilderAgreement':
                                                                                        $fileName = 'Builder Agreement';
                                                                                        break;
                                                                                    case 'leaseDeed':
                                                                                        $fileName = 'Lease Deed';
                                                                                        break;
                                                                                    case 'subsMutLetter':
                                                                                        $fileName =
                                                                                            'Substitution / Mutation Letter';
                                                                                        break;
                                                                                    case 'ownerLessee':
                                                                                        $fileName = 'Owner Lessee';
                                                                                        break;
                                                                                    case 'otherDocuments':
                                                                                        $fileName = 'Other Document';
                                                                                        break;
                                                                                    case 'other':
                                                                                        $fileName = 'Other Document';
                                                                                        break;
                                                                                }
                                                                            }
                                                                        }
                                                                    @endphp

                                                                    <span class='td-data-link'><i
                                                                            class='bx bx-chevron-right'></i> <a
                                                                            href='{{ asset('storage/' . $document->file_path ?? '#') }}'
                                                                            target='_blank'
                                                                            class='link-primary'>{{ $fileName }}</a></span>
                                                                @endforeach
                                                            </div>
                                                        </span>
                                                    @else
                                                        {{-- <p>No documents available.</p> --}}
                                                        <span class="view-hover-data show-toggle-data ms-2"
                                                            style="text-align: left;">
                                                            No documents available.
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                            <hr style="border: 2px solid #116d6e;">
                        @endforeach
                    @endif
                    @if ($newProperties)
                        @foreach ($newProperties as $newProperty)
                            <div class="row">
                                <div class="col-lg-12 col-12">
                                    <h5 class="text-primary">PROPERTY ID : {{ $newProperty->suggested_property_id ?? '' }}
                                    </h5>
                                    <table class="table table-bordered property-table-info">
                                        <tbody>
                                            <tr>
                                                <th>Property ID :</th>
                                                <td>{{ $newProperty->suggested_property_id ?? '' }}</td>
                                                <th>Current Status:</th>
                                                <td>
                                                    @switch(getStatusDetailsById( $newProperty->status ?? '' )->item_code)
                                                        @case('RS_REJ')
                                                            <div
                                                                class="ml-2 badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">
                                                                {{ getStatusDetailsById($newProperty->status ?? '')->item_name }}
                                                            </div>
                                                        @break

                                                        @case('RS_NEW')
                                                            <div
                                                                class="ml-2 badge rounded-pill text-primary bg-light-primary p-2 text-uppercase px-3">
                                                                {{ getStatusDetailsById($newProperty->status ?? '')->item_name }}
                                                            </div>
                                                        @break

                                                        @case('RS_UREW')
                                                            <div
                                                                class="ml-2 badge rounded-pill text-white bg-secondary p-2 text-uppercase px-3">
                                                                {{ getStatusDetailsById($newProperty->status ?? '')->item_name }}
                                                            </div>
                                                        @break

                                                        @case('RS_REW')
                                                            <div
                                                                class="ml-2 badge rounded-pill text-warning bg-light-warning p-2 text-uppercase px-3">
                                                                {{ getStatusDetailsById($newProperty->status ?? '')->item_name }}
                                                            </div>
                                                        @break

                                                        @case('RS_PEN')
                                                            <div
                                                                class="ml-2 badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">
                                                                {{ getStatusDetailsById($newProperty->status ?? '')->item_name }}
                                                            </div>
                                                        @break

                                                        @case('RS_APP')
                                                            <div
                                                                class="ml-2 badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">
                                                                {{ getStatusDetailsById($newProperty->status ?? '')->item_name }}
                                                            </div>
                                                        @break

                                                        @default
                                                            <div
                                                                class="ml-2 badge rounded-pill text-secondary bg-light p-2 text-uppercase px-3">
                                                                {{ getStatusDetailsById($newProperty->status ?? '')->item_name }}
                                                            </div>
                                                    @endswitch
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Locality :</th>
                                                <td>
                                                    {{ $newProperty->oldColony->name ?? '' }}
                                                </td>
                                                <th>Block :</th>
                                                <td>{{ $newProperty->block ?? '' }}</td>


                                            </tr>
                                            <tr>
                                                <th>Plot :</th>
                                                <td>{{ $newProperty->plot ?? '' }}</td>
                                                <th>Known As :</th>
                                                <td>{{ $newProperty->known_as ?? $newProperty->block . '/' . $newProperty->plot . '/' . $newProperty->oldColony->name }}
                                                </td>

                                            </tr>
                                            <tr>
                                                <th>Documents :</th>
                                                <td colspan="3">
                                                    <span class="view-hover-data show-toggle-data ms-2"
                                                        style="text-align: left;">
                                                        <a href="javascript:void(0);" class="text-danger pdf-icons">
                                                            <i class='bx bxs-file-pdf fs-4'></i>
                                                        </a>
                                                        <div class="tooltip-data" style="left: 15px; width: 250px;">
                                                            @php
                                                                $documents = [];

                                                                // Anonymous function to generate document link
                                                                $generateDocumentLink = function (
                                                                    $docPath,
                                                                    $displayName,
                                                                ) {
                                                                    if (!empty($docPath)) {
                                                                        $docUrl = asset('storage/' . $docPath);
                                                                        return "<span class='td-data-link'><i class='bx bx-chevron-right'></i> 
                                                                                    <a href='" .
                                                                            htmlspecialchars($docUrl) .
                                                                            "' target='_blank' class='link-primary'>" .
                                                                            ucfirst(htmlspecialchars($displayName)) .
                                                                            '</a></span>';
                                                                    }
                                                                    return '';
                                                                };

                                                                // Sale Deed Document
                                                                if (!empty($newProperty->sale_deed_doc)) {
                                                                    $saleDeedDoc = explode(
                                                                        '/',
                                                                        $newProperty->sale_deed_doc,
                                                                    );
                                                                    $saleDeedDocDisplay = explode(
                                                                        '_',
                                                                        end($saleDeedDoc),
                                                                    )[0];
                                                                    $documents[] = $generateDocumentLink(
                                                                        $newProperty->sale_deed_doc,
                                                                        $saleDeedDocDisplay,
                                                                    );
                                                                }

                                                                // Builder Buyer Agreement Document
                                                                if (!empty($newProperty->builder_buyer_agreement_doc)) {
                                                                    $builderBuyerAgreementDoc = explode(
                                                                        '/',
                                                                        $newProperty->builder_buyer_agreement_doc,
                                                                    );
                                                                    $builderBuyerAgreementDocDisplay = explode(
                                                                        '_',
                                                                        end($builderBuyerAgreementDoc),
                                                                    )[0];
                                                                    $documents[] = $generateDocumentLink(
                                                                        $newProperty->builder_buyer_agreement_doc,
                                                                        $builderBuyerAgreementDocDisplay,
                                                                    );
                                                                }

                                                                // Lease Deed Document
                                                                if (!empty($newProperty->lease_deed_doc)) {
                                                                    $leaseDeedDoc = explode(
                                                                        '/',
                                                                        $newProperty->lease_deed_doc,
                                                                    );
                                                                    $leaseDeedDocDisplay = explode(
                                                                        '_',
                                                                        end($leaseDeedDoc),
                                                                    )[0];
                                                                    $documents[] = $generateDocumentLink(
                                                                        $newProperty->lease_deed_doc,
                                                                        $leaseDeedDocDisplay,
                                                                    );
                                                                }

                                                                // Substitution Mutation Letter Document
                                                                if (
                                                                    !empty(
                                                                        $newProperty->substitution_mutation_letter_doc
                                                                    )
                                                                ) {
                                                                    $substitutionMutationLetterDoc = explode(
                                                                        '/',
                                                                        $newProperty->substitution_mutation_letter_doc,
                                                                    );
                                                                    $substitutionMutationLetterDocDisplay = explode(
                                                                        '_',
                                                                        end($substitutionMutationLetterDoc),
                                                                    )[0];
                                                                    $documents[] = $generateDocumentLink(
                                                                        $newProperty->substitution_mutation_letter_doc,
                                                                        $substitutionMutationLetterDocDisplay,
                                                                    );
                                                                }

                                                                // Owner Lessee Document
                                                                if (!empty($newProperty->owner_lessee_doc)) {
                                                                    $ownerLesseeDoc = explode(
                                                                        '/',
                                                                        $newProperty->owner_lessee_doc,
                                                                    );
                                                                    $ownerLesseeDocDisplay = explode(
                                                                        '_',
                                                                        end($ownerLesseeDoc),
                                                                    )[0];
                                                                    $documents[] = $generateDocumentLink(
                                                                        $newProperty->owner_lessee_doc,
                                                                        $ownerLesseeDocDisplay,
                                                                    );
                                                                }

                                                                // Other Document
                                                                if (!empty($newProperty->other_doc)) {
                                                                    $otherDoc = explode('/', $newProperty->other_doc);
                                                                    $otherDocDisplay = explode('_', end($otherDoc))[0];
                                                                    $documents[] = $generateDocumentLink(
                                                                        $newProperty->other_doc,
                                                                        $otherDocDisplay,
                                                                    );
                                                                }

                                                                // Authorised Signatory Document
                                                                if (!empty($newProperty->authorised_signatory_doc)) {
                                                                    $authorisedSignatoryDoc = explode(
                                                                        '/',
                                                                        $newProperty->authorised_signatory_doc,
                                                                    );
                                                                    $documents[] = $generateDocumentLink(
                                                                        $newProperty->authorised_signatory_doc,
                                                                        end($authorisedSignatoryDoc),
                                                                    );
                                                                }

                                                                // Chain of Ownership Document
                                                                if (!empty($newProperty->chain_of_ownership_doc)) {
                                                                    $chainOfOwnershipDoc = explode(
                                                                        '/',
                                                                        $newProperty->chain_of_ownership_doc,
                                                                    );
                                                                    $documents[] = $generateDocumentLink(
                                                                        $newProperty->chain_of_ownership_doc,
                                                                        end($chainOfOwnershipDoc),
                                                                    );
                                                                }

                                                                echo implode('', $documents);
                                                            @endphp
                                                        </div>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                            <hr style="border: 2px solid #116d6e;">
                        @endforeach
                    @endif

                </div>
            </div>

            <div class="part-details">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-12 text-end">
                            <button type="button" id="addNewProperty" class="btn btn-primary btn-theme">Add New
                                Property</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="part-title" id="newPropertyFormHeading" style="display: none;">
                <h5>Fill New Property Details</h5>
            </div>
            <div class="part-details" id="newPropertyForm" style="display: {{ $errors->any() ? 'block' : 'none' }};">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('applicant.properties.store') }}" method="POST"
                                enctype="multipart/form-data" id="addNewPropertyForm">
                                @csrf
                                <div id="propertyownerDiv">
                                    {{-- <h5 class="mb-3 form_section_title">Fill Property Details</h5> --}}
                                    <div class="internal_container mt-3" id="default">
                                        <div class="row g-3">

                                            <div class="col-md-4">
                                                <select name="localityInv" id="locality" class="form-select">
                                                    <option value="">Select Locality</option>
                                                    @foreach ($colonyList as $colony)
                                                        <option value="{{ $colony->id }}"
                                                            {{ old('localityInv') == $colony->id ? 'selected' : '' }}>
                                                            {{ $colony->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="text-danger" id="localityError"></div>
                                            </div>

                                            <div class="col-md-4">
                                                <select name="blockInv" id="block" class="form-select">
                                                    <option value="">Select Block</option>
                                                </select>
                                                <div class="text-danger" id="blockError"></div>
                                            </div>

                                            <div class="col-md-4">
                                                <select name="plotInv" id="plot" class="form-select">
                                                    <option value="">Select Plot</option>
                                                </select>
                                                <div class="text-danger" id="plotError"></div>
                                            </div>

                                            <div class="col-md-4">
                                                <select name="knownasInv" id="knownas" class="form-select">
                                                    <option value="">Known As</option>
                                                </select>
                                                <div class="text-danger" id="knownasError"></div>
                                            </div>

                                            <div class="col-md-4">
                                                <select name="landUseInv" id="landUse"
                                                    onchange="getSubTypesByType('landUse','landUseSubtype')"
                                                    class="form-select">
                                                    <option value="">Select Land Use</option>
                                                    @foreach ($propertyTypes[0]->items as $propertyType)
                                                        <option value="{{ $propertyType->id }}"
                                                            {{ old('landUseInv') == $propertyType->id ? 'selected' : '' }}>
                                                            {{ $propertyType->item_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="text-danger" id="landUseError"></div>
                                            </div>

                                            <div class="col-md-4">
                                                <select name="landUseSubtypeInv" id="landUseSubtype" class="form-select">
                                                    <option value="">Select Land Use Subtype</option>
                                                </select>
                                                <div class="text-danger" id="landUseSubtypeError"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mt-4">
                                        <div class="form-check">
                                            <input type="checkbox" name="propertyId" value="1"
                                                class="form-check-input" id="toggleCheckbox"
                                                {{ old('propertyId') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="toggleCheckbox">Is property details not
                                                found in the above list?</label>
                                        </div>
                                    </div>

                                    <div class="internal_container mt-3" id="afterChecked" style="display: none;">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <select name="localityInvFill" id="localityFill" class="form-select">
                                                    <option value="">Select Locality</option>
                                                    @foreach ($colonyList as $colony)
                                                        <option value="{{ $colony->id }}"
                                                            {{ old('localityInvFill') == $colony->id ? 'selected' : '' }}>
                                                            {{ $colony->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="text-danger" id="localityFillError"></div>
                                            </div>

                                            <div class="col-md-4">
                                                <input type="text" name="blocknoInvFill" id="blocknoInvFill"
                                                    class="form-control" placeholder="Block No."
                                                    value="{{ old('blocknoInvFill') }}">
                                                <div class="text-danger" id="blocknoInvFillError"></div>
                                            </div>

                                            <div class="col-md-4">
                                                <input type="text" name="plotnoInvFill" id="plotnoInvFill"
                                                    class="form-control" placeholder="Property/Plot No."
                                                    value="{{ old('plotnoInvFill') }}">
                                                <div class="text-danger" id="plotnoInvFillError"></div>
                                            </div>

                                            <div class="col-md-4">
                                                <input type="text" name="knownasInvFill" id="knownasInvFill"
                                                    class="form-control" placeholder="Known As (Optional)"
                                                    value="{{ old('knownasInvFill') }}">
                                                <div class="text-danger" id="knownasInvFillError"></div>
                                            </div>

                                            <div class="col-md-4">
                                                <select name="landUseInvFill" id="landUseInvFill"
                                                    onchange="getSubTypesByType('landUseInvFill','landUseSubtypeInvFill')"
                                                    class="form-select">
                                                    <option value="">Select Land Use</option>
                                                    @foreach ($propertyTypes[0]->items as $propertyType)
                                                        <option value="{{ $propertyType->id }}"
                                                            {{ old('landUseInvFill') == $propertyType->id ? 'selected' : '' }}>
                                                            {{ $propertyType->item_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="text-danger" id="landUseInvFillError"></div>
                                            </div>

                                            <div class="col-md-4">
                                                <select name="landUseSubtypeInvFill" id="landUseSubtypeInvFill"
                                                    class="form-select">
                                                    <option value="">Select Land Use Subtype</option>
                                                </select>
                                                <div class="text-danger" id="landUseSubtypeInvFillError"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 class="mt-4 mb-3">Ownership documents</h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="IndSaleDeed" class="form-label">Sale Deed</label>
                                                <input type="file" name="saleDeedDocInv" class="form-control"
                                                    accept="application/pdf" id="IndSaleDeed">
                                                <small class="text-muted">Upload documents (pdf file, up to 50 MB)</small>
                                                <div class="text-danger" id="IndSaleDeedError"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="IndBuildAgree" class="form-label">Builder & Buyer
                                                    Agreement</label>
                                                <input type="file" name="BuilAgreeDocInv" class="form-control"
                                                    accept="application/pdf" id="IndBuildAgree">
                                                <small class="text-muted">Upload documents (pdf file, up to 50 MB)</small>
                                                <div class="text-danger" id="IndBuildAgreeError"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="IndLeaseDeed" class="form-label">Lease Deed</label>
                                                <input type="file" name="leaseDeedDocInv" class="form-control"
                                                    accept="application/pdf" id="IndLeaseDeed">
                                                <small class="text-muted">Upload documents (pdf file, up to 50 MB)</small>
                                                <div class="text-danger" id="IndLeaseDeedError"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="IndSubMut" class="form-label">Substitution/Mutation
                                                    Letter</label>
                                                <input type="file" name="subMutLtrDocInv" class="form-control"
                                                    accept="application/pdf" id="IndSubMut">
                                                <small class="text-muted">Upload documents (pdf file, up to 50 MB)</small>
                                                <div class="text-danger" id="IndSubMutError"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="IndOwnerLess" class="form-label">Owner/Lessee
                                                    Relationship</label>
                                                <input type="file" name="ownerLessDocInv" class="form-control"
                                                    accept="application/pdf" id="IndOwnerLess">
                                                <small class="text-muted">Upload documents (pdf file, up to 50 MB)</small>
                                                <div class="text-danger" id="IndOwnerLessError"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="otherDocInv" class="quesLabel">Other Documents</label>
                                                <input type="file" name="otherDocInv" class="form-control"
                                                    accept="application/pdf" id="IndOther">
                                                <label class="note text-dark"><strong>Note:</strong> Upload documents (pdf
                                                    file, up to 5 MB)</label>
                                                <div id="IndOtherError" class="text-danger text-left"></div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="text-end mt-4">
                                    {{-- <button type="submit" class="btn btn-primary btn-theme">Submit</button> --}}
                                    <button type="button" id="submitNewPropertyBtn"
                                        class="btn btn-primary btn-theme">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Dynamic Element --}}
@endsection
@section('footerScript')
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        // Tooltip for View Docs

        $(document).ready(function() {
            var tooltip = $('#tooltip');

            $('.view-more').hover(function(e) {
                var tooltipText = $(this).attr('data-tooltip');
                tooltip.text(tooltipText);
                tooltip.css({
                    top: e.pageY + 10 + 'px',
                    left: e.pageX + 10 + 'px',
                    display: 'block'
                });
            }, function() {
                tooltip.hide();
            });

            $('.view-more').mousemove(function(e) {
                tooltip.css({
                    top: e.pageY + 10 + 'px',
                    left: e.pageX + 10 + 'px'
                });
            });

            $('#addNewProperty').click(function(e) {
                $('#newPropertyForm').show();
                $('#newPropertyFormHeading').show();
                $('html, body').animate({
                    scrollTop: $('#toggleCheckbox').offset().top
                }, 100); // 1000 milliseconds (1 second) for the scroll duration

            })

            document.getElementById('toggleCheckbox').addEventListener('change', function() {
                const firstDiv = document.getElementById('default');
                const secondDiv = document.getElementById('afterChecked');

                if (this.checked) {
                    firstDiv.style.display = 'none';
                    secondDiv.style.display = 'block';
                } else {
                    firstDiv.style.display = 'block';
                    secondDiv.style.display = 'none';
                }
            });


        });

        // Yes/No Do you Know Property ID?
        $(document).ready(function() {
            $('#Yes').change(function() {
                if ($(this).is(':checked')) {
                    $('#ifyes').show();
                    $('#locality').val('')
                    $('#block').val('')
                    $('#plot').val('')
                    $('#knownas').val('')
                    $('#landUse').val('');
                    $('#landUseSubtype').val('');
                    $('#ifYesNotChecked').hide();
                } else {
                    $('#localityFill').val('')
                    $('#blocknoInvFill').val('')
                    $('#plotnoInvFill').val('')
                    $('#knownasInvFill').val('')
                    $('#landUseInvFill').val('')
                    $('#landUseSubtypeInvFill').val('')
                    $('#ifyes').hide();
                    $('#ifYesNotChecked').show();
                }
            });

            $('#YesOrg').change(function() {
                if ($(this).is(':checked')) {
                    $('#ifyesOrg').show();
                    $('#locality_org').val('')
                    $('#block_org').val('')
                    $('#plot_org').val('')
                    $('#knownas_org').val('')
                    $('#landUseOrg').val('')
                    $('#landUseSubtypeOrg').val('')
                    $('#ifYesNotCheckedOrg').hide();
                } else {
                    $('#localityOrgFill').val('')
                    $('#blocknoOrgFill').val('')
                    $('#plotnoOrgFill').val('')
                    $('#knownasOrgFill').val('')
                    $('#landUseOrgFill').val('')
                    $('#landUseSubtypeOrgFill').val('')
                    $('#ifyesOrg').hide();
                    $('#ifYesNotCheckedOrg').show();
                }
            });
        });

        //get all blocks of selected locality
        $('#locality').on('change', function() {
            var locality = this.value;
            $("#block").html('');
            $.ajax({
                url: "{{ route('localityBlocks') }}",
                type: "POST",
                data: {
                    locality: locality,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#block').html('<option value="">Select</option>');
                    $.each(result, function(key, value) {
                        $("#block").append('<option value="' + value.block_no + '">' + value
                            .block_no + '</option>');
                    });
                }
            });
        });

        //get all plots of selected block
        $('#block').on('change', function() {
            var locality = $('#locality').val();
            var block = this.value;
            $("#plot").html('');
            $.ajax({
                url: "{{ route('blockPlots') }}",
                type: "POST",
                data: {
                    locality: locality,
                    block: block,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    // console.log(result);
                    $('#plot').html('<option value="">Select</option>');
                    $.each(result, function(key, value) {

                        // $("#plot").append('<option value="' + value.plot_or_property_no + '">' +
                        //     value.plot_or_property_no + '</option>');
                        $("#plot").append('<option value="' + value + '">' +
                            value + '</option>');
                    });
                }
            });
        });


        //get known as of selected plot
        $('#plot').on('change', function() {
            var locality = $('#locality').val();
            var block = $('#block').val();
            var plot = this.value;
            $("#knownas").html('');
            $.ajax({
                url: "{{ route('plotKnownas') }}",
                type: "POST",
                data: {
                    locality: locality,
                    block: block,
                    plot: plot,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    // console.log(result);
                    $('#knownas').html('<option value="">Select</option>');
                    $.each(result, function(key, value) {

                        $("#knownas").append('<option value="' + value + '">' + value +
                            '</option>');
                    });
                }
            });
        });

        //for organization 
        //get all blocks of selected locality
        $('#locality_org').on('change', function() {
            var locality = this.value;
            $("#block_org").html('');
            $.ajax({
                url: "{{ route('localityBlocks') }}",
                type: "POST",
                data: {
                    locality: locality,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#block_org').html('<option value="">Select</option>');
                    $.each(result, function(key, value) {
                        $("#block_org").append('<option value="' + value.block_no + '">' + value
                            .block_no + '</option>');
                    });
                }
            });
        });

        //get all plots of selected block
        $('#block_org').on('change', function() {
            var locality = $('#locality_org').val();
            var block = this.value;
            $("#plot_org").html('');
            $.ajax({
                url: "{{ route('blockPlots') }}",
                type: "POST",
                data: {
                    locality: locality,
                    block: block,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    // console.log(result);
                    $('#plot_org').html('<option value="">Select Plot</option>');
                    $.each(result, function(key, value) {

                        $("#plot_org").append('<option value="' + value.plot_or_property_no +
                            '">' + value.plot_or_property_no + '</option>');
                    });
                }
            });
        });


        //get known as of selected plot
        $('#plot_org').on('change', function() {
            var locality = $('#locality_org').val();
            var block = $('#block_org').val();
            var plot = this.value;
            $("#knownas_org").html('');
            $.ajax({
                url: "{{ route('plotKnownas') }}",
                type: "POST",
                data: {
                    locality: locality,
                    block: block,
                    plot: plot,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    // console.log(result);
                    $('#knownas_org').html('<option value="">Select</option>');
                    $.each(result, function(key, value) {

                        $("#knownas_org").append('<option value="' + value + '">' + value +
                            '</option>');
                    });
                }
            });
        });

        function getSubTypesByType(type, subtype) {
            var idPropertyType = $(`#${type}`).val();
            $(`#${subtype}`).html('');
            $.ajax({
                url: "{{ route('prpertySubTypes') }}",
                type: "POST",
                data: {
                    property_type_id: idPropertyType,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {

                    $(`#${subtype}`).html('<option value="">Select land use subtype</option>');
                    $.each(result, function(key, value) {
                        $(`#${subtype}`).append('<option value="' + value
                            .id + '">' + value.item_name + '</option>');
                    });
                }
            });
        }

        $(document).ready(function() {
            // Toggle form visibility based on checkbox
            $('#toggleCheckbox').change(function() {
                if ($(this).is(':checked')) {
                    $('#afterChecked').show();
                } else {
                    $('#afterChecked').hide();
                }
            });

            if ($('#toggleCheckbox').is(':checked')) {
                $('#afterChecked').show();
            }

            @if ($errors->any())
                @if (old('propertyId'))
                    $('#afterChecked').show();
                @else
                    $('#afterChecked').hide();
                @endif
            @endif

            $('#submitNewPropertyBtn').click(function(e) {
                // Clear previous error messages
                $('.text-danger').text('');

                let isValid = true;

                // Validate additional form if checkbox is checked
                if ($('#toggleCheckbox').is(':checked')) {
                    if (!$('#localityFill').val()) {
                        $('#localityFillError').text('Please select a locality.');
                        isValid = false;
                    }

                    if (!$('#blocknoInvFill').val()) {
                        $('#blocknoInvFillError').text('Please enter the block number.');
                        isValid = false;
                    }

                    if (!$('#plotnoInvFill').val()) {
                        $('#plotnoInvFillError').text('Please enter the plot number.');
                        isValid = false;
                    }

                    /*if (!$('#knownasInvFill').val()) {
                        $('#knownasInvFillError').text('Please enter known as.');
                        isValid = false;
                    }*/

                    if (!$('#landUseInvFill').val()) {
                        $('#landUseInvFillError').text('Please select land use.');
                        isValid = false;
                    }

                    if (!$('#landUseSubtypeInvFill').val()) {
                        $('#landUseSubtypeInvFillError').text('Please select land use subtype.');
                        isValid = false;
                    }
                } else {
                    // Validate primary form
                    if (!$('#locality').val()) {
                        $('#localityError').text('Please select a locality.');
                        isValid = false;
                    }

                    if (!$('#block').val()) {
                        $('#blockError').text('Please select a block.');
                        isValid = false;
                    }

                    if (!$('#plot').val()) {
                        $('#plotError').text('Please select a plot.');
                        isValid = false;
                    }

                    /*if (!$('#knownas').val()) {
                        $('#knownasError').text('Please select known as.');
                        isValid = false;
                    }*/

                    if (!$('#landUse').val()) {
                        $('#landUseError').text('Please select land use.');
                        isValid = false;
                    }

                    if (!$('#landUseSubtype').val()) {
                        $('#landUseSubtypeError').text('Please select land use subtype.');
                        isValid = false;
                    }
                }

                // Validate file inputs
                /*const fileInputs = [{
                        id: 'IndSaleDeed',
                        errorId: 'saleDeedDocInvError',
                        label: 'Sale Deed'
                    },
                    {
                        id: 'IndBuildAgree',
                        errorId: 'BuilAgreeDocInvError',
                        label: 'Builder & Buyer Agreement'
                    },
                    {
                        id: 'IndLeaseDeed',
                        errorId: 'leaseDeedDocInvError',
                        label: 'Lease Deed'
                    },
                    {
                        id: 'IndSubMut',
                        errorId: 'subMutLtrDocInvError',
                        label: 'Substitution/Mutation Letter'
                    },
                    {
                        id: 'IndOwnerLess',
                        errorId: 'ownerLessDocInvError',
                        label: 'Owner/Lessee Relationship'
                    },
                ];

                fileInputs.forEach(input => {
                    const fileInput = document.getElementById(input.id);
                    if (fileInput.files.length === 0) {
                        $('#' + input.errorId).text('Please upload the ' + input.label + '.');
                        isValid = false;
                    } else if (fileInput.files[0].size > 50 * 1024 * 1024) { // 50 MB limit
                        $('#' + input.errorId).text(input.label + ' file must be less than 50 MB.');
                        isValid = false;
                    }
                });*/

                const fileFields = [{
                        id: '#IndSaleDeed',
                        errorId: '#IndSaleDeedError'
                    },
                    {
                        id: '#IndBuildAgree',
                        errorId: '#IndBuildAgreeError'
                    },
                    {
                        id: '#IndLeaseDeed',
                        errorId: '#IndLeaseDeedError'
                    },
                    {
                        id: '#IndSubMut',
                        errorId: '#IndSubMutError'
                    },
                    {
                        id: '#IndOwnerLess',
                        errorId: '#IndOwnerLessError'
                    },
                    {
                        id: '#IndOther',
                        errorId: '#IndOtherError'
                    }
                ];

                let isValidFiles = true;
                let firstInvalidFieldFiles = null;
                let atLeastOneFile = false;

                // Resetting error states
                fileFields.forEach(function(field) {
                    const inputField = $(field.id);
                    const errorField = $(field.errorId);
                    inputField.removeClass('required');
                    errorField.hide();
                });

                fileFields.forEach(function(field) {
                    const inputField = $(field.id);
                    const errorField = $(field.errorId);
                    const files = inputField[0].files;

                    if (files.length > 0) {
                        atLeastOneFile = true;

                        let allFilesArePDF = true;
                        for (let i = 0; i < files.length; i++) {
                            const fileName = files[i].name;
                            // Validate the file extension
                            if (!fileName.endsWith('.pdf')) {
                                allFilesArePDF = false;
                                break;
                            }
                        }

                        if (!allFilesArePDF) {
                            inputField.addClass('required');
                            errorField.text("Only PDF files are allowed").show();
                            isValidFiles = false;
                            isValid = false;
                            if (firstInvalidFieldFiles === null) {
                                firstInvalidFieldFiles = inputField;
                            }
                        }
                    }
                });

                // Check if at least one file is selected
                if (!atLeastOneFile) {
                    fileFields.forEach(function(field) {
                        const inputField = $(field.id);
                        const errorField = $(field.errorId);
                        inputField.addClass('required');
                        errorField.text("At least one file is required").show();
                        isValidFiles = false;
                        isValid = false;
                        if (firstInvalidFieldFiles === null) {
                            firstInvalidFieldFiles = inputField;
                        }
                    });
                }

                if (!isValid) {
                    event.preventDefault(); // Prevent form submission if validation fails
                } else {
                    $('#submitNewPropertyBtn').prop('disabled', true);
                    $('#submitNewPropertyBtn').html('Submitting...');
                    $('#addNewPropertyForm').submit();
                }
            })
        });
    </script>
@endsection
