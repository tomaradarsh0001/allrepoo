<div class="mt-3">
    <div class="container-fluid">
        <div class="row g-2">
            <div class="col-lg-12">
                @if(isset($application))
                    @foreach($stepThirdFinalDocuments as $documentType => $finalDocument)
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
                                                onchange="handleFileUpload(this.files[0], '{{ $documentType }}', 'mutation','SUB_MUT')">
                                            <div id="{{ strtolower(str_replace(' ', '_', $documentType)) }}Error"
                                                class="text-danger text-left"></div>
                                        </div>
                                        @if(isset($finalDocument['file_path']))
                                        <a href="{{asset('storage/' .$finalDocument['file_path'] ?? '')}}" data-document-type="{{$documentType}}" target="_blank"
                                            class="fs-6">View saved document</a>
                                        @endif
                                    </div>
                                    @if(isset($finalDocument['value']) && is_array($finalDocument['value']) &&
                                    count($finalDocument['value']) > 0)
                                        @foreach($finalDocument['value'] as $key => $data)
                                            @if( $key == 'otherDocumentRemark')
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="{{ $key }}">{{ $data['label'] }}<span
                                                                class="text-danger">*</span></label>
                                                        <textarea name="{{ $key }}" class="form-control" id="{{ $key }}"
                                                        placeholder="Remarks" rows="3">{{ $data['value'] }}</textarea>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="{{ $key }}">{{ $data['label'] }}<span
                                                                class="text-danger">*</span></label>
                                                        <input type="{{ $data['type'] }}" name="{{ $key }}" class="form-control"
                                                            id="{{ $key }}" value="{{ $data['value'] }}">
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                    <div class="col-lg-12">
                                        <p class="text-danger">No values available for this document type.</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                @foreach($documentTypes as $document)
                    <div class="row row-mb-2" style="display:none" id="{{$document->item_code}}">
                        <div class="col-lg-1 icons-flex"></div>
                        <div class="col-lg-11 selected-docs-field">
                            <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group form-box">
                                        <label for="{{$document->item_code}}" class="quesLabel">{{$document->item_name}}</label>
                                        <input type="file" name="{{$document->item_code}}" class="form-control"
                                            accept="application/pdf"
                                            onchange="handleFileUpload(this.files[0],'{{$document->item_name}}','mutation','SUB_MUT')">
                                        <div id="{{$document->item_code}}Error" class="text-danger text-left"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach


                <!-- <div class="row row-mb-2">
                    <div class="col-lg-1 icons-flex"></div>
                    <div class="col-lg-11 selected-docs-field">
                        <div class="files-sorting-abs"><i class='bx bxs-file'></i></div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group form-box">
                                    <label for="deathCertificate" class="quesLabel">Death
                                        Certificate</label>
                                    <input type="file" name="deathCertificate" class="form-control"
                                        accept="application/pdf" id="deathCertificate"
                                        onchange="handleFileUpload(this.files[0],'Death Certificate','mutation','SUB_MUT')">
                                    <div id="deathCertificateError" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="deathCertificateDeceasedName">Name of Deceased</label>
                                    <input type="text" name="deathCertificateDeceasedName"
                                        class="form-control alpha-only" id="deceasedName"
                                        placeholder="Name of Deceased">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="deathCertificateDeathdate">Date of Death</label>
                                    <input type="date" name="deathCertificateDeathdate" class="form-control"
                                        id="deathdate">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="deathCertificateIssuedate">Date of Issue</label>
                                    <input type="date" name="deathCertificateIssuedate" class="form-control"
                                        id="issuedate" placeholder="Date of Issue">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="deathCertificateDocumentCertificate">Document/Certificate No.</label>
                                    <input type="text" name="deathCertificateDocumentCertificate" class="form-control"
                                        id="document_certificate" placeholder="Document/Certificate No.">
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
                                    <label for="saledeeddoc" class="quesLabel">Sale Deed</label>
                                    <input type="file" name="saledeeddoc" class="form-control" accept="application/pdf"
                                        id="saledeeddoc"
                                        onchange="handleFileUpload(this.files[0],'Sale Deed','mutation','SUB_MUT')">
                                    <div id="saledeeddocError" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="SaleDeedRegno">Registration No.</label>
                                    <input type="text" name="SaleDeedRegno" class="form-control numericOnly"
                                        id="saledeedregno" placeholder="Registration No.">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="SaleDeedVolume">Volume</label>
                                    <input type="text" min="0" name="SaleDeedVolume" class="form-control numericOnly"
                                        id="saledeedvolume" placeholder="Volume">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="saleDeedBookNo">Book No.</label>
                                    <input type="text" name="saleDeedBookNo" class="form-control numericOnly"
                                        id="saledeedbookno" placeholder="Book No.">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="saleDeedPageNo">Page No.</label>
                                    <input type="text" name="saleDeedPageNo" class="form-control numericOnly"
                                        id="saledeedpageno" placeholder="Page No.">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="saleDeedFrom">From</label>
                                    <input type="date" name="saleDeedFrom" class="form-control" id="saledeedfrom">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="saleDeedTo">To</label>
                                    <input type="date" name="saleDeedTo" class="form-control" id="saledeedto">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="saleDeedRegDate">Regn. Date</label>
                                    <input type="date" name="saleDeedRegDate" class="form-control" id="saledeedregdate">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="saleDeedRegOfficeName">Registration Office Name</label>
                                    <input type="text" name="saleDeedRegOfficeName" class="form-control alpha-only"
                                        id="saledeedregname" placeholder="Registration Office Name">
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
                                    <label for="regWillDeed" class="quesLabel">Regd. WILL Deed</label>
                                    <input type="file" name="regWillDeed" class="form-control" accept="application/pdf"
                                        id="regWillDeed"
                                        onchange="handleFileUpload(this.files[0],'Regd. WILL Deed','mutation','SUB_MUT')">
                                    <div id="regWillDeedError" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="regWillDeedTestatorName">Name of Testator</label>
                                    <input type="text" name="regWillDeedTestatorName" class="form-control alpha-only"
                                        id="testatorname" placeholder="Name of Testator">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="regWillDeedRegNo">Registration No.</label>
                                    <input type="text" name="regWillDeedRegNo" class="form-control numericOnly"
                                        id="WillRegNo" placeholder="Registration No.">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="regWillDeedVolume">Volume</label>
                                    <input type="text" min="0" name="regWillDeedVolume" class="form-control numericOnly"
                                        id="Willvolume" placeholder="Volume">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="regWillDeedBookNo">Book No.</label>
                                    <input type="text" name="regWillDeedBookNo" class="form-control numericOnly"
                                        id="Willbookno" placeholder="Book No.">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="regWillDeedPageNo">Page No.</label>
                                    <input type="text" name="regWillDeedPageNo" class="form-control numericOnly"
                                        id="Willpageno" placeholder="Page No.">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="regWillDeedFrom">From</label>
                                    <input type="date" name="regWillDeedFrom" class="form-control" id="Willfrom">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="regWillDeedTo">To</label>
                                    <input type="date" name="regWillDeedTo" class="form-control" id="Willto">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="regWillDeedRegDate">Regn. Date</label>
                                    <input type="date" name="regWillDeedRegDate" class="form-control" id="WillRegdate">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="regWillDeedRegOfficeName">Registration Office Name</label>
                                    <input type="text" name="regWillDeedRegOfficeName" class="form-control alpha-only"
                                        id="Willregname" placeholder="Registration Office Name">
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
                                    <label for="unregWillCodicil" class="quesLabel">Unregd.
                                        WILL/CODICIL</label>
                                    <input type="file" name="unregWillCodicil" class="form-control"
                                        accept="application/pdf" id="unregWillCodicil"
                                        onchange="handleFileUpload(this.files[0],'Unregd. WILL_CODICIL','mutation','SUB_MUT')">
                                    <div id="unregWillCodicilError" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="unregWillCodicilTestatorName">Name of Testator</label>
                                    <input type="text" name="unregWillCodicilTestatorName"
                                        class="form-control alpha-only" id="UnWilltestatorname"
                                        placeholder="Name of Testator">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="unregWillCodicilDateOfWillCodicil">Date of WILL/CODICIL</label>
                                    <input type="date" name="unregWillCodicilDateOfWillCodicil" class="form-control"
                                        id="UnWillRegdate">
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
                                    <label for="relinquishDeed" class="quesLabel">Relinquishment
                                        Deed</label>
                                    <input type="file" name="relinquishDeed" class="form-control"
                                        accept="application/pdf" id="relinquishDeed"
                                        onchange="handleFileUpload(this.files[0],'Relinquishment Deed','mutation','SUB_MUT')">
                                    <div id="relinquishDeedError" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="relinquishDeedRegNo">Registration No.</label>
                                    <input type="text" name="relinquishDeedRegNo" class="form-control numericOnly"
                                        id="relinqdeedregno" placeholder="Registration No.">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="relinquishDeedVolume">Volume</label>
                                    <input type="text" min="0" name="relinquishDeedVolume"
                                        class="form-control numericOnly" id="relinqvolume">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="relinquishDeedBookno">Book No.</label>
                                    <input type="text" name="relinquishDeedBookno" class="form-control numericOnly"
                                        id="relinqdeedbookno">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="relinquishDeedPageno">Page No.</label>
                                    <input type="text" name="relinquishDeedPageno" class="form-control numericOnly"
                                        id="relinqdeedpageno">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="relinquishDeedFrom">From</label>
                                    <input type="date" name="relinquishDeedFrom" class="form-control"
                                        id="relinqdeedfrom">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="relinquishDeedTo">To</label>
                                    <input type="date" name="relinquishDeedTo" class="form-control" id="relinqdeedto">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="relinquishDeedRegdate">Regn. Date</label>
                                    <input type="date" name="relinquishDeedRegdate" class="form-control"
                                        id="relinqdeedregdate">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="relinquishDeedRegname">Registration office name</label>
                                    <input type="text" name="relinquishDeedRegname" class="form-control alpha-only"
                                        id="relinqdeedregname">
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
                                    <label for="giftDeed" class="quesLabel">Gift Deed</label>
                                    <input type="file" name="giftDeed" class="form-control" accept="application/pdf"
                                        id="giftDeed" onchange="handleFileUpload(this.files[0],'Gift Deed','mutation','SUB_MUT')">
                                    <div id="giftDeedError" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="giftdeedRegno">Registration No.</label>
                                    <input type="text" name="giftdeedRegno" class="form-control numericOnly"
                                        id="giftdeedregno">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="giftdeedVolume">Volume</label>
                                    <input type="text" min="0" name="giftdeedVolume" class="form-control numericOnly"
                                        id="giftvolume">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="giftdeedBookno">Book No.</label>
                                    <input type="text" name="giftdeedBookno" class="form-control numericOnly"
                                        id="giftdeedbookno">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="giftdeedPageno">Page No.</label>
                                    <input type="text" name="giftdeedPageno" class="form-control numericOnly"
                                        id="giftdeedpageno">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="giftdeedFrom">From</label>
                                    <input type="date" name="giftdeedFrom" class="form-control" id="giftdeedfrom">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="giftdeedTo">To</label>
                                    <input type="date" name="giftdeedTo" class="form-control" id="giftdeedto">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="giftdeedRegdate">Regn. Date</label>
                                    <input type="date" name="giftdeedRegdate" class="form-control" id="giftdeedregdate">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="giftdeedRegOfficeName">Registration office name</label>
                                    <input type="text" name="giftdeedRegOfficeName" class="form-control alpha-only"
                                        id="giftdeedregname">
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
                                    <label for="smc" class="quesLabel">Surviving Member
                                        Certificate(SMC)</label>
                                    <input type="file" name="smc" class="form-control" accept="application/pdf" id="smc"
                                        onchange="handleFileUpload(this.files[0],'Surviving Member Certificate(SMC)','mutation','SUB_MUT')">
                                    <div id="smcError" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="smcCertificateNo">Certificate No.</label>
                                    <input type="text" name="smcCertificateNo" class="form-control"
                                        id="smccertificateno">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="smcDateOfIssue">Date of Issue</label>
                                    <input type="date" name="smcDateOfIssue" class="form-control" id="smcdateissue">
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
                                    <label for="sbp" class="quesLabel">Sanction Building
                                        Plan(SBP)</label>
                                    <input type="file" name="sbp" class="form-control" accept="application/pdf" id="sbp"
                                        onchange="handleFileUpload(this.files[0],'Sanction Building Plan(SBP)','mutation','SUB_MUT')">
                                    <div id="sbpError" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="sbpDateOfIssue">Date of Issue</label>
                                    <input type="date" name="sbpDateOfIssue" class="form-control" id="sbpdateissue">
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
                                    <label for="otherDocument" class="quesLabel">Any other Document</label>
                                    <input type="file" name="otherDocument" class="form-control"
                                        accept="application/pdf" id="otherDocument"
                                        onchange="handleFileUpload(this.files[0],'Any other Document','mutation','SUB_MUT')">
                                    <div id="sbpError" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="otherDocumentRemark">Remarks</label>
                                    <textarea name="otherDocumentRemark" class="form-control" id="remarksotherdoc"
                                        placeholder="Remarks" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                @endif
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-12">
                <h6 class="mt-3 mb-0">Terms & Conditions</h6>
                <ul class="consent-agree">
                    <li>Declaration is given by applicant(s) that all facts details given by
                        him/her are correct and true to his knowledge otherwise his application
                        will be liable to be rejected. and,</li>
                    <li>Undertaking that applicant is agreeing with the terms and conditions as
                        mentioned in substitution/Mutation brochure/manual.</li>
                    <li>Payment of Non-Refundable Processing Fee</li>
                </ul>
                <div class="form-check form-group">
                    @if(isset($application))
                        <input class="form-check-input" name="agreeConsent" type="checkbox" id="agreeconsent">
                    @else
                        <input class="form-check-input" name="agreeConsent" type="checkbox" id="agreeconsent">
                    @endif

                    <label class="form-check-label" for="agreeconsent">I agree, all the
                        information provided by me is accurate to the best of my knowledge. I
                        take full responsibility for any issues or failures that may arise from
                        its use.</label>

                        <div id="MutAgreeconsentError" class="text-danger text-left"></div>
                </div>
            </div>
        </div>
    </div>
</div>