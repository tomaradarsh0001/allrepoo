@forelse($dataWithPagination as $index => $registrationDetail)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>
            {{ $registrationDetail->applicant_number }}
        </td>
        <td>
            {{ $registrationDetail->name }}

        </td>
        <td>
            {{ ucfirst($registrationDetail->block) . '/' . ucfirst($registrationDetail->plot) . '/' . ucfirst($registrationDetail->flat) . '/' . ucfirst($registrationDetail->oldColony->name) }}
        </td>
        <td>
            {{ ucwords($registrationDetail->user_type) }}
        </td>
        <td>
            {{ ucwords($registrationDetail->purpose_of_registation) }}
        </td>
        <td>
            @switch($registrationDetail->item_code)
                @case('RS_REJ')
                    <div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">
                        {{ ucwords($registrationDetail->item_name) }}
                    </div>
                @break

                @case('RS_NEW')
                    <div class="badge rounded-pill text-primary bg-light-primary p-2 text-uppercase px-3">
                        {{ ucwords($registrationDetail->item_name) }}
                    </div>
                @break

                @case('RS_UREW')
                    <div class="badge rounded-pill text-warning bg-light-warning  p-2 text-uppercase px-3">
                        {{ ucwords($registrationDetail->item_name) }}
                    </div>
                @break

                @case('RS_REW')
                    <div class="badge rounded-pill text-white bg-secondary p-2 text-uppercase px-3">
                        {{ ucwords($registrationDetail->item_name) }}
                    </div>
                @break

                @case('RS_PEN')
                    <div class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">
                        {{ ucwords($registrationDetail->item_name) }}
                    </div>
                @break

                @case('RS_APP')
                    <div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">
                        {{ ucwords($registrationDetail->item_name) }}
                    </div>
                @break

                @default
                    <div class="badge rounded-pill text-secondary bg-light p-2 text-uppercase px-3">
                        {{ ucwords($registrationDetail->item_name) }}
                    </div>
            @endswitch
        </td>
        <td>
            @php
                $documents = [];
                // Sale Deed Document
                $saleDeedDocPart = explode('/', $registrationDetail->sale_deed_doc);
                $saleDeedDoc = end($saleDeedDocPart);
                if (!empty($saleDeedDoc)) {
                    $documents[] =
                        "<a href='" .
                        htmlspecialchars($registrationDetail->sale_deed_doc) .
                        "' target='_blank' class='link-primary'>" .
                        ucfirst(htmlspecialchars($saleDeedDoc)) .
                        '</a>';
                }

                // Builder Buyer Agreement Document
                $builderBuyerAgreementDocPart = explode('/', $registrationDetail->builder_buyer_agreement_doc);
                $builderBuyerAgreementDoc = end($builderBuyerAgreementDocPart);
                if (!empty($builderBuyerAgreementDoc)) {
                    $documents[] =
                        "<a href='" .
                        htmlspecialchars($registrationDetail->builder_buyer_agreement_doc) .
                        "' target='_blank' class='link-primary'>" .
                        ucfirst(htmlspecialchars($builderBuyerAgreementDoc)) .
                        '</a>';
                }

                // Lease Deed Document
                $leaseDeedDocPart = explode('/', $registrationDetail->lease_deed_doc);
                $leaseDeedDoc = end($leaseDeedDocPart);
                if (!empty($leaseDeedDoc)) {
                    $documents[] =
                        "<a href='" .
                        htmlspecialchars($registrationDetail->lease_deed_doc) .
                        "' target='_blank' class='link-primary'>" .
                        ucfirst(htmlspecialchars($leaseDeedDoc)) .
                        '</a>';
                }

                // Substitution Mutation Letter Document
                $substitutionMutationLetterDocPart = explode(
                    '/',
                    $registrationDetail->substitution_mutation_letter_doc,
                );
                $substitutionMutationLetterDoc = end($substitutionMutationLetterDocPart);
                if (!empty($substitutionMutationLetterDoc)) {
                    $documents[] =
                        "<a href='" .
                        htmlspecialchars($registrationDetail->substitution_mutation_letter_doc) .
                        "' target='_blank' class='link-primary'>" .
                        ucfirst(htmlspecialchars($substitutionMutationLetterDoc)) .
                        '</a>';
                }

                // Owner Lessee Document
                $ownerLesseeDocPart = explode('/', $registrationDetail->owner_lessee_doc);
                $ownerLesseeDoc = end($ownerLesseeDocPart);
                if (!empty($ownerLesseeDoc)) {
                    $documents[] =
                        "<a href='" .
                        htmlspecialchars($registrationDetail->owner_lessee_doc) .
                        "' target='_blank' class='link-primary'>" .
                        ucfirst(htmlspecialchars($ownerLesseeDoc)) .
                        '</a>';
                }

                // Authorised Signatory Document
                $authorisedSignatoryDocPart = explode('/', $registrationDetail->authorised_signatory_doc);
                $authorisedSignatoryDoc = end($authorisedSignatoryDocPart);
                if (!empty($authorisedSignatoryDoc)) {
                    $documents[] =
                        "<a href='" .
                        htmlspecialchars($registrationDetail->authorised_signatory_doc) .
                        "' target='_blank' class='link-primary'>" .
                        ucfirst(htmlspecialchars($authorisedSignatoryDoc)) .
                        '</a>';
                }

                // Chain of Ownership Document
                $chainOfOwnershipDocPart = explode('/', $registrationDetail->chain_of_ownership_doc);
                $chainOfOwnershipDoc = end($chainOfOwnershipDocPart);
                if (!empty($chainOfOwnershipDoc)) {
                    $documents[] =
                        "<a href='" .
                        htmlspecialchars($registrationDetail->chain_of_ownership_doc) .
                        "' target='_blank' class='link-primary'>" .
                        ucfirst(htmlspecialchars($chainOfOwnershipDoc)) .
                        '</a>';
                }
                $documents = implode('<br>', $documents);
            @endphp
            {!! $documents !!}
        </td>
        <td>
            {!! $registrationDetail->remarks
                ? $registrationDetail->remarks .
                    ' <span style="
                font-size: 13px;
                color: #7e7e7ea1;
                font-weight: 700;
            ">(' .
                    $registrationDetail->assigned_by_name .
                    ')</span>'
                : 'NA' !!}


        </td>

        <td>
            <!-- View Button -->
            <a href="{{ url('register/user/' . $registrationDetail->id . '/view') }}">
                <button type="button" class="btn btn-success px-5">View</button>
            </a>

        </td>
    </tr>

    @empty
        <tr>
            <td colspan="14">
                <p class="text-danger text-center">No Application Available</p>
            </td>
        </tr>
    @endforelse
    <tr>
        <td colspan="14">
            {!! $dataWithPagination->links('pagination.custom') !!}
        </td>
    </tr>
