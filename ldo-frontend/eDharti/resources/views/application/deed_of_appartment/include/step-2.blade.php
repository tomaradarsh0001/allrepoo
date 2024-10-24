<div class="mt-3">
    <div class="container-fluid">
        <div class="row g-2">
            <div class="col-lg-12">
                @if (isset($application))
                    @foreach ($stepSecondFinalDocuments as $documentType => $finalDocument)
                        <div class="row row-mb-2">
                            <div class="col-lg-1 icons-flex"></div>
                            <div class="col-lg-11 selected-docs-field">
                                <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group form-box">
                                            <label for="{{ strtolower(str_replace(' ', '_', $documentType)) }}"
                                                class="quesLabel">{{ $documentType }}<span
                                                    class="text-danger">*</span></label>
                                            <input type="file"
                                                name="{{ strtolower(str_replace(' ', '_', $documentType)) }}"
                                                class="form-control" accept="application/pdf"
                                                id="{{ strtolower(str_replace(' ', '_', $documentType)) }}"
                                                onchange="handleFileUpload(this.files[0], '{{ $documentType }}', 'deed_of_apartment', 'DOA')">
                                            <div id="{{ strtolower(str_replace(' ', '_', $documentType)) }}Error"
                                                class="text-danger text-left"></div>
                                        </div>
                                        @if (isset($finalDocument['file_path']))
                                            <a href="{{ asset('storage/' . $finalDocument['file_path'] ?? '') }}"
                                                target="_blank" data-document-type="{{ $documentType }}"
                                                class="fs-6">View saved document</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row row-mb-2">
                        <div class="col-lg-1 icons-flex"></div>
                        <div class="col-lg-11 selected-docs-field">
                            <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group form-box">
                                        <!-- Begin -->
                                        <div class="form-group form-box">
                                            <label for="builderBuyerAgreement" class="quesLabel">Builder Buyer
                                                Agreement<span class="text-danger">*</span></label>
                                            <input type="file" name="builderBuyerAgreement" class="form-control"
                                                accept="application/pdf" id="builderBuyerAgreement"
                                                onchange="handleFileUpload(this.files[0],'Builder Buyer Agreement','deed_of_apartment','DOA')">
                                            <div id="builderBuyerAgreementError" class="text-danger text-left">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-mb-2">
                        <div class="col-lg-1 icons-flex"></div>
                        <div class="col-lg-11 selected-docs-field">
                            <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group form-box">
                                        <div class="form-group form-box">
                                            <label for="saleDeed" class="quesLabel">Sale Deed<span
                                                    class="text-danger">*</span></label>
                                            <input type="file" name="saleDeed" class="form-control"
                                                accept="application/pdf" id="saleDeed"
                                                onchange="handleFileUpload(this.files[0],'Sale Deed','deed_of_apartment','DOA')">
                                            <div id="saleDeedError" class="text-danger text-left">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-mb-2">
                        <div class="col-lg-1 icons-flex"></div>
                        <div class="col-lg-11 selected-docs-field">
                            <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group form-box">
                                        <label for="buildingPlan" class="quesLabel">Building Plan<span
                                                class="text-danger">*</span></label>
                                        <input type="file" name="buildingPlan" class="form-control"
                                            accept="application/pdf" id="buildingPlan"
                                            onchange="handleFileUpload(this.files[0],'Building Plan','deed_of_apartment','DOA')">
                                        <div id="buildingPlanError" class="text-danger text-left">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-mb-2">
                        <div class="col-lg-1 icons-flex"></div>
                        <div class="col-lg-11 selected-docs-field">
                            <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group form-box">
                                        <label for="otherDocument" class="quesLabel">Other Document<span
                                                class="text-danger">*</span></label>
                                        <input type="file" name="otherDocument" class="form-control"
                                            accept="application/pdf" id="otherDocument"
                                            onchange="handleFileUpload(this.files[0],'Other Document','deed_of_apartment','DOA')">
                                        <div id="otherDocumentError" class="text-danger text-left">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row row-mb-2">
                    <div class="col-lg-12">
                        <h6 class="mt-3 mb-0" id="LUCHideTitle">Terms & Conditions</h6>
                        <ul class="consent-agree">
                            <li>Declaration is given by applicant(s) that all facts details given by
                                him/her are correct and true to his knowledge otherwise his application
                                will be liable to be rejected. and,</li>
                            <li>Undertaking that applicant is agreeing with the terms and conditions as
                                mentioned in substitution/Mutation brochure/manual.</li>
                            <li>Payment of Non-Refundable Processing Fee</li>
                        </ul>
                        <div class="form-check form-group">
                            @if (isset($application))
                                <input class="form-check-input" name="agreeConsent" type="checkbox"
                                    id="agreeDOAConsent">
                            @else
                                <input class="form-check-input" name="agreeConsent" type="checkbox"
                                    id="agreeDOAConsent">
                            @endif

                            <label class="form-check-label" for="doaagreeconsent">I agree, all the
                                information provided by me is accurate to the best of my knowledge. I
                                take full responsibility for any issues or failures that may arise from
                                its use.</label>
                        </div>

                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
