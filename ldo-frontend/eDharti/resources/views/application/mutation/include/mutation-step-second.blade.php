<div class="mt-3">
    <div class="container-fluid">
        <div class="row g-2">
            <div class="col-lg-12">
                @if(isset($application))
                @foreach($stepSecondFinalDocuments as $documentType => $finalDocument)
                <div class="row row-mb-2">
                    <div class="col-lg-1 icons-flex"></div>
                    <div class="col-lg-11 selected-docs-field">
                        <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group form-box">
                                    <label for="{{ strtolower(str_replace(' ', '_', $documentType)) }}"
                                        class="quesLabel">{{ $documentType }}<span class="text-danger">*</span></label>
                                    <input type="file" name="{{ strtolower(str_replace(' ', '_', $documentType)) }}"
                                        class="form-control" accept="application/pdf"
                                        id="{{ strtolower(str_replace(' ', '_', $documentType)) }}"
                                        onchange="handleFileUpload(this.files[0], '{{ $documentType }}', 'mutation', 'SUB_MUT',)">
                                    <div id="{{ strtolower(str_replace(' ', '_', $documentType)) }}Error"
                                        class="text-danger text-left"></div>
                                </div>
                                @if(isset($finalDocument['file_path']))
                                <a href="{{asset('storage/' .$finalDocument['file_path'] ?? '')}}" target="_blank"
                                    data-document-type="{{$documentType}}" class="fs-6">View saved document</a>
                                @endif
                            </div>
                            @if(isset($finalDocument['value']) && is_array($finalDocument['value']) &&
                            count($finalDocument['value']) > 0)
                            @foreach($finalDocument['value'] as $key => $data)
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="{{ $key }}">{{ $data['label'] }}<span
                                            class="text-danger">*</span></label>
                                    <input type="{{ $data['type'] }}" name="{{ $key }}" class="form-control"
                                        id="{{ $key }}" value="{{ $data['value'] }}">
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="col-lg-12">

                            </div>
                            @endif
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
                                    <label for="Affidavits" class="quesLabel">Affidavits<span
                                            class="text-danger">*</span></label>
                                    <input type="file" name="affidavits" class="form-control" accept="application/pdf"
                                        id="affidavits"
                                        onchange="handleFileUpload(this.files[0],'Affidavits','mutation','SUB_MUT')">
                                    <!-- <label class="note text-dark"><strong>Note:</strong> Upload
                                                                    documents (pdf file, up to 5 MB)</label> -->
                                    <div id="affidavitsError" class="text-danger text-left">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="dateattestation">Date of attestation<span class="text-danger">*</span></label>
                                    <input type="date" name="affidavitsDateAttestation" class="form-control" id="dateattestation">
                                    <div id="dateattestationError" class="text-danger text-left"></div>
                                </div>
                            </div> -->
                            <!-- <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="attestedby">Attested by<span class="text-danger">*</span></label>
                                    <input type="text" name="affidavitsAttestedby" class="form-control alpha-only" id="attestedby" placeholder="Attested By">
                                    <div id="attestedbyError" class="text-danger text-left"></div>
                                </div>
                            </div> -->
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
                                    <label for="indemnityBond" class="quesLabel">Indemnity Bond<span class="text-danger">*</span></label>
                                    <input type="file" name="indemnityBond" class="form-control" accept="application/pdf" id="indemnityBond" onchange="handleFileUpload(this.files[0],'Indemnity Bond','mutation','SUB_MUT')">
                                    <div id="indemnityBondError" class="text-danger text-left">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="indemnityBondDateAttestation">Date of
                                        attestation<span class="text-danger">*</span></label>
                                    <input type="date" name="indemnityBondDateAttestation" class="form-control" id="indemnityBonddateattestation">
                                    <div id="indemnityBonddateattestationError" class="text-danger text-left"></div>
                                </div>
                            </div> -->
                            <!-- <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="indemnityBondattestedby">Attested by<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="indemnityBondattestedby" class="form-control alpha-only"
                                        id="indemnityBondattestedby" placeholder="Attested By">
                                    <div id="indemnityBondattestedbyError" class="text-danger text-left"></div>
                                </div>
                            </div> -->
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
                                    <label for="leaseconyedeed" class="quesLabel">Lease Deed/Conveyance Deed<span class="text-danger">*</span></label>
                                    <input type="file" name="leaseconyedeed" class="form-control"
                                        accept="application/pdf" id="leaseconyedeed"
                                        onchange="handleFileUpload(this.files[0],'Lease_Conveyance Deed','mutation','SUB_MUT')">
                                    <div id="leaseconyedeedError" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <!-- <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="dateofexecution">Date of execution<span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="leaseConvDeedDateOfExecution" class="form-control"
                                        id="dateofexecution">
                                    <div id="dateofexecutionError" class="text-danger text-left"></div>
                                </div>
                            </div> -->
                            <!-- <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="lesseename">Lessee Name<span class="text-danger">*</span></label>
                                    <input type="text" name="leaseConvDeedLesseename" class="form-control alpha-only"
                                        id="lesseename" placeholder="Lessee Name">
                                    <div id="lesseenameError" class="text-danger text-left"></div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>

                <div class="row row-mb-2">
                    <div class="col-lg-1 icons-flex"></div>
                    <div class="col-lg-11 selected-docs-field">
                        <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="pannumber">PAN Number<span class="text-danger">*</span></label>
                                    <input type="file" accept="application/pdf" name="pannumber"
                                        class="form-control pan_number_format text-uppercase" id="pannumber"
                                        maxlength="10" placeholder="PAN Number"
                                        onchange="handleFileUpload(this.files[0],'PAN Number','mutation','SUB_MUT')">
                                        <div id="pannumberError" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <!-- <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="pancertificateno">Certificate No.<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="panCertificateNo" class="form-control"
                                        id="pancertificateno" placeholder="Certificate No.">
                                        <div id="pancertificatenoError" class="text-danger text-left"></div>
                                </div>
                            </div> -->
                            <!-- <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="pandateissue">Date of Issue<span class="text-danger">*</span></label>
                                    <input type="date" name="panDateIssue" class="form-control" id="pandateissue">
                                    <div id="pandateissueError" class="text-danger text-left"></div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>

                <div class="row row-mb-2">
                    <div class="col-lg-1 icons-flex"></div>
                    <div class="col-lg-11 selected-docs-field">
                        <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="aadharnumber">Aadhaar Number<span class="text-danger">*</span></label>
                                    <input type="file" accept="application/pdf" name="aadharnumber"
                                        class="form-control pan_number_format text-uppercase" id="aadharnumber"
                                        maxlength="10" placeholder="Aadhaar Number"
                                        onchange="handleFileUpload(this.files[0],'Aadhar Number','mutation','SUB_MUT')">
                                        <div id="aadharnumberError" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <!-- <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="aadharcertificateno">Certificate No.<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="aadharCertificateNo" class="form-control"
                                        id="aadharcertificateno" placeholder="Certificate No.">
                                        <div id="aadharcertificatenoError" class="text-danger text-left"></div>
                                </div>
                            </div> -->
                            <!-- <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="aadhardateissue">Date of Issue<span class="text-danger">*</span></label>
                                    <input type="date" name="aadharDateIssue" class="form-control" id="aadhardateissue">
                                    <div id="aadhardateissueError" class="text-danger text-left"></div>
                                </div>
                            </div> -->
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
                                    <label for="publicnoticeenhin" class="quesLabel">Public
                                        Notice in National Daily (English & Hindi)<span
                                            class="text-danger">*</span></label>
                                    <input type="file" name="publicnoticeenhin" class="form-control"
                                        accept="application/pdf" id="publicnoticeenhin"
                                        onchange="handleFileUpload(this.files[0],'Public Notice','mutation','SUB_MUT')">
                                    <div id="publicnoticeenhinError" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <!-- <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="newspapernameengligh">Name of Newspaper(English & Hindi)<span class="text-danger">*</span></label>
                                    <input type="text" name="newspaperName" class="form-control alpha-only" id="newspapernameengligh" placeholder="Name of Newspaper (English)">
                                    <div id="newspapernameenglighError" class="text-danger text-left"></div>
                                </div>
                            </div> -->
                            <!-- <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="publicnoticedate">Date of Public Notice<span class="text-danger">*</span></label>
                                    <input type="date" name="publicNoticeDate" class="form-control" id="publicnoticedate">
                                    <div id="publicnoticedateError" class="text-danger text-left"></div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>

                <div class="row row-mb-2">
                    <div class="col-lg-1 icons-flex"></div>
                    <div class="col-lg-11 selected-docs-field">
                        <div class="files-sorting-abs"><i class="bx bxs-file"></i></div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group form-box">
                                    <label for="property_photo" class="quesLabel">Property Photo<span
                                            class="text-danger">*</span></label>
                                    <input type="file" name="property_photo" class="form-control"
                                        accept="application/pdf" id="property_photo"
                                        onchange="handleFileUpload(this.files[0], 'Property Photo', 'mutation', 'SUB_MUT',)">
                                    <div id="property_photoError" class="text-danger text-left"></div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                
            </div>
        </div>
    </div>
</div>