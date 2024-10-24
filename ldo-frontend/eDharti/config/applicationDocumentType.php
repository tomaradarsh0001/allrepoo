<?php
return [
    'MUTATION' => [
        'Required' => [
            'Affidavits' => [
                'affidavitsDateAttestation' => [
                    'label' => 'Date of attestation',
                    'type' => 'date'
                ],
                'affidavitsAttestedby' => [
                    'label' => 'Attested by',
                    'type' => 'text'
                ],
            ],
            'Indemnity Bond' => [
                'indemnityBondDateAttestation' =>  [
                    'label' => 'Date of attestation',
                    'type' => 'date'
                ],
                'indemnityBondattestedby' =>  [
                    'label' => 'Attested by',
                    'type' => 'text'
                ],
            ],
            'Lease_Conveyance Deed' => [
                'leaseConvDeedDateOfExecution' => [
                    'label' => 'Date of execution',
                    'type' => 'date'
                ],
                'leaseConvDeedLesseename' => [
                    'label' => 'Lessee Name',
                    'type' => 'text'
                ],
            ],
            'PAN Number' => [
                'panCertificateNo' => [
                    'label' => 'Certificate No.',
                    'type' => 'text'
                ],
                'panDateIssue' => [
                    'label' => 'Date of Issue',
                    'type' => 'date'
                ]
            ],
            'Aadhar Number' => [
                'aadharCertificateNo' => [
                    'label' => 'Certificate No.',
                    'type' => 'text'
                ],
                'aadharDateIssue' => [
                    'label' => 'Date of Issue',
                    'type' => 'date'
                ],
            ],
            'Public Notice' => [
                'newspaperName' => [
                    'label' => 'Name of Newspaper (English & Hindi)',
                    'type' => 'text'
                ],
                'publicNoticeDate' => [
                    'label' => 'Date of Public Notice',
                    'type' => 'date'
                ],
            ],
            'Property Photo' => []
        ],
        'Optional' => [
            'Death Certificate' => [
                'deathCertificateDeceasedName' => [
                    'label' => 'Name of Deceased',
                    'type' => 'text'
                ],
                'deathCertificateDeathdate' => [
                    'label' => 'Date of Death',
                    'type' => 'date'
                ],
                'deathCertificateIssuedate' => [
                    'label' => 'Date of Issue',
                    'type' => 'date'
                ],
                'deathCertificateDocumentCertificate' => [
                    'label' => 'Document/Certificate No.',
                    'type' => 'text'
                ]
            ],
            'Sale Deed' => [
                'SaleDeedRegno' => [
                    'label' => 'Registration No.',
                    'type' => 'text'
                ],
                'SaleDeedVolume' => [
                    'label' => 'Volume',
                    'type' => 'text'
                ],
                'saleDeedBookNo' => [
                    'label' => 'Book No.',
                    'type' => 'text'
                ],
                'saleDeedPageNo' => [
                    'label' => 'Page No.',
                    'type' => 'text'
                ],
                'saleDeedFrom' => [
                    'label' => 'From',
                    'type' => 'text'
                ],
                'saleDeedTo' => [
                    'label' => 'To',
                    'type' => 'text'
                ],
                'saleDeedRegDate' => [
                    'label' => 'Regn. Date',
                    'type' => 'date'
                ],
                'saleDeedRegOfficeName' => [
                    'label' => 'Registration Office Name',
                    'type' => 'text'
                ]
            ],
            'Regd WILL Deed' => [
                'regWillDeedTestatorName' => [
                    'label' => 'Name of Testator',
                    'type' => 'text'
                ],
                'regWillDeedRegNo' => [
                    'label' => 'Registration No.',
                    'type' => 'text'
                ],
                'regWillDeedVolume' => [
                    'label' => 'Volume',
                    'type' => 'text'
                ],
                'regWillDeedBookNo' => [
                    'label' => 'Book No.',
                    'type' => 'text'
                ],
                'regWillDeedPageNo' => [
                    'label' => 'Page No.',
                    'type' => 'text'
                ],
                'regWillDeedFrom' => [
                    'label' => 'From',
                    'type' => 'text'
                ],
                'regWillDeedTo' => [
                    'label' => 'To',
                    'type' => 'text'
                ],
                'regWillDeedRegDate' => [
                    'label' => 'Regn. Date',
                    'type' => 'date'
                ],
                'regWillDeedRegOfficeName' => [
                    'label' => 'Registration Office Name',
                    'type' => 'text'
                ]
            ],
            'Unregd WILL_CODICIL' => [
                'unregWillCodicilTestatorName' => [
                    'label' => 'Name of Testator',
                    'type' => 'text'
                ],
                'unregWillCodicilDateOfWillCodicil' => [
                    'label' => 'Date of WILL/CODICIL',
                    'type' => 'date'
                ]
            ],
            'Relinquishment Deed' => [
                'relinquishDeedRegNo' => [
                    'label' => 'Registration No.',
                    'type' => 'text'
                ],
                'relinquishDeedVolume' => [
                    'label' => 'Volume',
                    'type' => 'text'
                ],
                'relinquishDeedBookno' => [
                    'label' => 'Book No.',
                    'type' => 'text'
                ],
                'relinquishDeedPageno' => [
                    'label' => 'Page No.',
                    'type' => 'text'
                ],
                'relinquishDeedFrom' => [
                    'label' => 'From',
                    'type' => 'text'
                ],
                'relinquishDeedTo' => [
                    'label' => 'To',
                    'type' => 'text'
                ],
                'relinquishDeedRegdate' => [
                    'label' => 'Regn. Date',
                    'type' => 'date'
                ],
                'relinquishDeedRegname' => [
                    'label' => 'Registration office name',
                    'type' => 'text'
                ]
            ],
            'Gift Deed' => [
                'giftdeedRegno' => [
                    'label' => 'Registration No.',
                    'type' => 'text'
                ],
                'giftdeedVolume' => [
                    'label' => 'Volume',
                    'type' => 'text'
                ],
                'giftdeedBookno' => [
                    'label' => 'Book No.',
                    'type' => 'text'
                ],
                'giftdeedPageno' => [
                    'label' => 'Page No.',
                    'type' => 'text'
                ],
                'giftdeedFrom' => [
                    'label' => 'From',
                    'type' => 'text'
                ],
                'giftdeedTo' => [
                    'label' => 'To',
                    'type' => 'text'
                ],
                'giftdeedRegdate' => [
                    'label' => 'Regn. Date',
                    'type' => 'date'
                ],
                'giftdeedRegOfficeName' => [
                    'label' => 'Registration office name',
                    'type' => 'text'
                ]
            ],
            'Surviving Member Certificate(SMC)' => [
                'smcCertificateNo' => [
                    'label' => 'Certificate No.',
                    'type' => 'text'
                ],
                'smcDateOfIssue' => [
                    'label' => 'Date of Issue',
                    'type' => 'date'
                ]
            ],
            'Sanction Building Plan(SBP)' => [
                'sbpDateOfIssue' => [
                    'label' => 'Date of Issue',
                    'type' => 'date'
                ]
            ],
            'Any other Document' => [
                'otherDocumentRemark' => [
                    'label' => 'Remarks',
                    'type' => 'textarea'
                ]
            ]
        ],
        'TempModelName' => 'TempSubstitutionMutation'
    ],
    'LUC' => [
        'documents' => [
            'PropertyTaxPaymentReceipt' => [
                'id' => 'lucpropertyTaxpayreceipt',
                'label' => 'Property Tax Payment Receipt',
                'rowOrder' => 1,
                'required' => 1
            ],
            'PropertyTaxAssessmentReceipt' => [
                'id' => 'PropertyTaxAssessmentReceipt',
                'label' => 'Property Tax Assessment',
                'rowOrder' => 2,
                'required' => 1
            ],
            'Photo' => [
                'id' => 'lucphoto1',
                'label' => 'Property Photo',
                'rowOrder' => 3,
                'required' => 1
            ],
            'Photo2' => [
                'id' => 'lucphotooptional',
                'label' => 'Property Photo2',
                'rowOrder' => 3,
                'required' => 0
            ],
            'PlanPermittingLUC' => [
                'id' => 'lucmpdzonalpermitting',
                'label' => 'MPD/Zonal Plan Permitting LUC',
                'rowOrder' => 4,
                'required' => 1
            ]
        ],
        'TempModelName' => 'TempLandUseChangeApplication'
    ],
    'DOA' => [
        'Required' => [
            'Builder Buyer Agreement' => [],
            'Sale Deed' => [],
            'Building Plan' => [],
            'Other Document' => []
        ],
        'TempModelName' => 'TempDeedOfApartment'
    ],
    'CONVERSION' => [
        'required' => [
            'inputGroups' => [
                [
                    'multiple' => false,
                    'withSelect' => false,
                    'documentns' => [
                        [
                            'id' => 'indemnityBond',
                            'label' => 'Indemnity Bond (Annexure-F)'
                        ]
                    ]
                ],
                [
                    'multiple' => false,
                    'withSelect' => false,
                    'documentns' => [
                        [
                            'id' => 'undertaking',
                            'label' => 'Undertaking (Annexure-G)'
                        ]
                    ]
                ],
                [
                    'multiple' => false,
                    'withSelect' => false,
                    'documentns' => [
                        [
                            'id' => 'lastSubstitutionMutation',
                            'label' => 'Last Substitution/Mutation Letter',
                            'info' => 'Self- attested copy of Last Substitution/Mutation Letter',
                        ]
                    ]
                ],

                [
                    'multiple' => false,
                    'withSelect' => true,
                    'selectOptions' => [
                        "C/D Form",
                        "Sanctioned Building Plan",
                        "Completion Certificate",
                        "Occupancy Certificate",
                        "Payment Receipt of Property Tax Preceding 2 Years",
                        "Others"
                    ],
                    'documentns' => [
                        [
                            'id' => 'selectedDoc',
                            'label' => 'Upload File',
                        ]
                    ]
                ],
                [
                    'multiple' => false,
                    'withSelect' => false,
                    'documentns' => [
                        [
                            'id' => 'possessionOfPremises',
                            'label' => 'Proof of possession of the premises',
                            'info' => 'Latest Electricity Bill/ IGL Bill/ Telephone Bill',
                        ]
                    ]
                ],
                /**------ multiple groups with input */
                [
                    'multiple' => false,
                    'withSelect' => false,
                    'documentns' => [
                        [
                            'id' => 'aplicantAadhaar',
                            'label' => 'Upload Applicants Aadhar',

                        ],
                        [
                            'id' => 'aplicantPan',
                            'label' => 'Upload Applicants PAN',

                        ]
                    ],
                    'inputGroup' => [
                        'multiple' => true,
                        'withSelect' => false,
                        'documentns' => [
                            [
                                'id' => 'lesseeAadhaar',
                                'label' => 'Upload Lessee Aadhar',

                            ],
                            [
                                'id' => 'lesseePan',
                                'label' => 'Upload lessee PAN',

                            ]
                        ]
                    ],
                ]

            ],


        ],
        'optional' => []
    ]

];
