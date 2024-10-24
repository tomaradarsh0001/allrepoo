<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\PropertyMaster;
use App\Models\ApplicationStatus;
use App\Models\Document;
use App\Models\DocumentKey;
use App\Models\Application;
use App\Models\User;
use App\Models\Coapplicant;
use App\Models\Section;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ApplicationController as UserApplicationController;

class ApplicationController extends Controller
{
    //get all applications according to the section
    public function index(Request $request){
        $getStatusId = '';
        if ($request->query('status')) {
            $getStatusId = Item::where('item_code', Crypt::decrypt($request->query('status')))->value('id');
        }
        $user = Auth::user();
        $filterPermissionArr = [];
        // $permissionMap = [
        //     'view.registration.new' => 'RS_NEW',
        //     'view.registration.approved' => 'RS_APP',
        //     'view.registration.rejected' => 'RS_REJ',
        //     'view.registration.under_review' => 'RS_UREW',
        //     'view.registration.reviewed' => 'RS_REW',
        //     'view.registration.pending' => 'RS_PEN',
        // ];

        // $allPermissions = $user->getAllPermissions();
        // foreach ($allPermissions as $permission) {
        //     if (isset($permissionMap[$permission->name])) {
        //         $filterPermissionArr[] = $permissionMap[$permission->name];
        //     }
        // }

        // if (!empty($filterPermissionArr)) {
            $items = Item::where('group_id', 1031)->where('is_active',1)->get();
        // }
        return view('admin.applications.index',compact('items', 'getStatusId', 'user'));
        

    }

    public function getApplications(Request $request){
        $user = Auth::user();
        $sections = $user->sections->pluck('id');
        $columns = ['id', 'old_property_id'];

         // Query for land use changed applications
        $serviceType2 = getServiceType('LUC');
        $query2 = DB::table('land_use_change_applications as lca')
        ->leftJoin('property_masters', 'lca.property_master_id', '=', 'property_masters.id')
        ->leftJoin('old_colonies', 'property_masters.new_colony_name', '=', 'old_colonies.id')
        ->leftJoin('property_lease_details', 'property_masters.id', '=', 'property_lease_details.property_master_id')
        ->leftJoin('application_statuses', function($join) use ($serviceType2) {
            $join->on('lca.id', '=', 'application_statuses.model_id')
                 ->whereColumn('application_statuses.reg_app_no','lca.application_no' )
                 ->where('application_statuses.service_type', $serviceType2);
        })
        ->select(
            'lca.id',
            'lca.created_at',
            DB::raw('coalesce(lca.application_no,"0") as application_no'),
            'lca.status',
            'application_statuses.is_mis_checked',
            'application_statuses.is_scan_file_checked',
            'application_statuses.is_uploaded_doc_checked',
            'application_statuses.mis_checked_by',
            'application_statuses.scan_file_checked_by',
            'application_statuses.uploaded_doc_checked_by',
            'property_masters.old_propert_id',
            'property_masters.new_colony_name',
            'old_colonies.name as colony_name',
            'property_masters.block_no',
            'property_masters.plot_or_property_no',
            'property_lease_details.presently_known_as',
            DB::raw("'LandUseChangeApplication' as model_name") // Add model_name for the first query
        );

        // dd($query2->toSql(),$query2->getBindings());

    // Query for mutation applications
    $serviceType1 = getServiceType('SUB_MUT');
    $query1 = DB::table('mutation_applications as ma')
    ->leftJoin('property_masters', 'ma.property_master_id', '=', 'property_masters.id')
    ->leftJoin('old_colonies', 'property_masters.new_colony_name', '=', 'old_colonies.id')
    ->leftJoin('property_lease_details', 'property_masters.id', '=', 'property_lease_details.property_master_id')
    ->leftJoin('application_statuses', function($join) use ($serviceType1) {
        $join->on('ma.id', '=', 'application_statuses.model_id')
             ->whereColumn('application_statuses.reg_app_no','ma.application_no' )
             ->where('application_statuses.service_type', $serviceType1);
    })
    ->whereIn('ma.section_id', $sections)
    ->select(
        'ma.id',
        'ma.created_at',
        'ma.application_no',
        'ma.status',
        'application_statuses.is_mis_checked',
        'application_statuses.is_scan_file_checked',
        'application_statuses.is_uploaded_doc_checked',
        'application_statuses.mis_checked_by',
        'application_statuses.scan_file_checked_by',
        'application_statuses.uploaded_doc_checked_by',
        'property_masters.old_propert_id',
        'property_masters.new_colony_name',
        'old_colonies.name as colony_name',
        'property_masters.block_no',
        'property_masters.plot_or_property_no',
        'property_lease_details.presently_known_as',
        DB::raw("'MutationApplication' as model_name") // Add model_name for the first query
    );

    //Query for Deed Of Apartment applications
    $serviceType3 = getServiceType('DOA');
    $query3 = DB::table('deed_of_apartment_applications as doa')
        ->leftJoin('property_masters', 'doa.property_master_id', '=', 'property_masters.id')
        ->leftJoin('old_colonies', 'property_masters.new_colony_name', '=', 'old_colonies.id')
        ->leftJoin('property_lease_details', 'property_masters.id', '=', 'property_lease_details.property_master_id')
        ->leftJoin('application_statuses', function($join) use ($serviceType3) {
            $join->on('doa.id', '=', 'application_statuses.model_id')
                 ->whereColumn('application_statuses.reg_app_no','doa.application_no' )
                 ->where('application_statuses.service_type', $serviceType3);
        })
        ->select(
            'doa.id',
            'doa.created_at',
            'doa.application_no',
            'doa.status',
            'application_statuses.is_mis_checked',
            'application_statuses.is_scan_file_checked',
            'application_statuses.is_uploaded_doc_checked',
            'application_statuses.mis_checked_by',
            'application_statuses.scan_file_checked_by',
            'application_statuses.uploaded_doc_checked_by',
            'property_masters.old_propert_id',
            'property_masters.new_colony_name',
            'old_colonies.name as colony_name',
            'property_masters.block_no',
            'property_masters.plot_or_property_no',
            'property_lease_details.presently_known_as',
            DB::raw("'DeedOfApartmentApplication' as model_name") // Add model_name for the first query
        );

    // Combine all three queries using UNION
    $combinedQuery = $query1->union($query2)->union($query3);
    if ($request->status) {
        // Wrap the combined query in a subquery and apply the where clause
        $combinedQuery = DB::table(DB::raw("({$combinedQuery->toSql()}) as combined"))
            ->mergeBindings($query1) // Merge bindings from the first query
            ->mergeBindings($query2) // Merge bindings from the second query
            ->mergeBindings($query3) // Merge bindings from the third query
            ->where('status', $request->status);
    }

     // Apply search filter for global search
     if (!empty($request->input('search.value'))) {
        $search = $request->input('search.value');
        $combinedQuery->where(function ($q) use ($search) {
            $q->where('old_property_id', 'like', "%{$search}%");
        });
    }

    $totalData = $combinedQuery->count();
    $totalFiltered = $totalData;

    // Pagination parameters
    $limit = $request->input('length');
    $start = $request->input('start');

    // Order by requested column
    $orderColumnIndex = $request->input('order.0.column');

    $applications = $combinedQuery->offset($start)
            ->limit($limit)
            ->get();
        $data = [];
        foreach ($applications as $key => $application) {
            $mis_checked_by = User::find($application->mis_checked_by);
            $scan_file_checked_by = User::find($application->scan_file_checked_by);
            $uploaded_doc_checked_by = User::find($application->uploaded_doc_checked_by);
            $nestedData['id'] = $key + 1;
            $nestedData['application_no'] = $application->application_no;
            $nestedData['old_property_id'] = $application->old_propert_id;
            $nestedData['new_colony_name'] = $application->colony_name;
            $nestedData['block_no'] = $application->block_no;
            $nestedData['plot_or_property_no'] = $application->plot_or_property_no;
            $nestedData['presently_known_as'] = $application->presently_known_as;

            switch ($application->model_name) {
                case 'MutationApplication':
                    $appliedFor = 'Mutation';
                    break;
                case 'LandUseChangeApplication':
                    $appliedFor = 'LUC';
                    break;
                case 'DeedOfApartmentApplication':
                    $appliedFor = 'DOA';
                    break;
                default:
                    // Default action
                    break;
            }
            //for getting status
            $item = getStatusDetailsById($application->status);
            $itemCode = $item->item_code;
            $itemName = $item->item_name;
            $itemColor = $item->color_code;
            $statusClasses = [
                'RS_REJ' => 'text-danger bg-light-danger',
                'APP_NEW' => 'text-primary bg-light-primary',
                'APP_WD' => 'text-warning bg-light-warning',
                'RS_REW' => 'text-white bg-secondary',
                'RS_PEN' => 'text-info bg-light-info',
                'RS_APP' => 'text-success bg-light-success',
            ];
            $class = $statusClasses[$itemCode] ?? 'text-secondary bg-light';
            
            $nestedData['applied_for'] = '<label class="badge bg-info mx-1">' . $appliedFor . '</label>';

            $nestedData['activity'] = [
                'mis' => !empty($application->is_mis_checked) ? $application->is_mis_checked : 'NA',
                'scanned_files' => !empty($application->is_scan_file_checked) ? $application->is_scan_file_checked : 'NA',
                'uploaded_doc' => !empty($application->is_uploaded_doc_checked) ? $application->is_uploaded_doc_checked : 'NA',
                'mis_checked_by' => !empty($application->mis_checked_by) ? $mis_checked_by->name : '',
                'scan_file_checked_by' => !empty($application->scan_file_checked_by) ? $scan_file_checked_by->name : '',
                'uploaded_doc_checked_by' => !empty($application->uploaded_doc_checked_by) ? $uploaded_doc_checked_by->name : '',
                'mis_color_code' => !empty(getServiceTypeColorCode('MIS_CHECK')) ? getServiceTypeColorCode('MIS_CHECK') : '',
                'scan_file_color_code' => !empty(getServiceTypeColorCode('SCAN_CHECK')) ? getServiceTypeColorCode('SCAN_CHECK') : '',
                'uploaded_doc_color_code' => !empty(getServiceTypeColorCode('UP_DOC_CHE')) ? getServiceTypeColorCode('UP_DOC_CHE') : '',
            ];

            $nestedData['status'] = '<div class="badge rounded-pill ' . $class . ' p-2 text-uppercase px-3">' . ucwords($itemName) . '</div>';
            $model = base64_encode($application->model_name);

            // Prepare actions
            $action = '<a href="' . url('applications/' . $application->id) . '?type=' . $model . '"><button type="button" class="btn btn-primary px-5">View Application</button></a>';
            $nestedData['action'] = $action;
            $nestedData['created_at'] = $application->created_at;

            $data[] = $nestedData;
        }

        $json_data = [
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        ];

        return response()->json($json_data);

    }

    //for view single application details - SOURAV CHAUHAN (14/Oct/2024)
    public function view(Request $request,$id){
        $requestModel = base64_decode($request->type);
        $model = '\\App\\Models\\' . $requestModel;
        $applicationDetails = $model::find($id);
        $application = Application::where('application_no',$applicationDetails['application_no'])->first();
        if($applicationDetails){
            $data = [];
            switch ($requestModel) {
                case 'MutationApplication':
                    $applicationType = 'Mutation';
                    $serviceType = getServiceType('SUB_MUT');
                    $documents = Document::where('service_type',$serviceType)->where('model_name',$requestModel)->where('model_id',$id)->get();
                    if (!empty($documents)) {

                        //second step douments ***********************************************
                        $stepSecondFilters = config('applicationDocumentType.MUTATION.Required');
                        $topLevelKeys = array_keys($stepSecondFilters);
                        $stepSecondFilteredDocuments = $documents->filter(function ($document) use ($topLevelKeys) {
                            return in_array($document->title, $topLevelKeys);
                        });
                        foreach ($stepSecondFilteredDocuments as $document) {
                            $stepSecondFinalDocuments[$document->document_type]['file_path'] = $document->file_path;
                            $documentKeys = DocumentKey::where('document_id', $document->id)->get();
                            foreach ($documentKeys as $documentKey) {
                                $label = config('applicationDocumentType.MUTATION.Required.' . $document->document_type . '.' . $documentKey->key . '.label');
                                $type  = config('applicationDocumentType.MUTATION.Required.' . $document->document_type . '.' . $documentKey->key . '.type');
                                $stepSecondFinalDocuments[$document->document_type]['value'][$documentKey->key]['value'] = $documentKey->value;
                                $stepSecondFinalDocuments[$document->document_type]['value'][$documentKey->key]['label'] = $label;
                                $stepSecondFinalDocuments[$document->document_type]['value'][$documentKey->key]['type'] = $type;
                            }
                        }
                        

                        foreach ($stepSecondFilters as $documentType => $fields) {
                            if (!isset($stepSecondFinalDocuments[$documentType])) {
                                $stepSecondFinalDocuments[$documentType] = [
                                    'file_path' => null,
                                    'value' => []
                                ];

                                foreach ($fields as $key => $field) {
                                    $stepSecondFinalDocuments[$documentType]['value'][$key] = [
                                        'value' => null,
                                        'label' => $field['label'],
                                        'type' => $field['type']
                                    ];
                                }
                            } else {
                                foreach ($fields as $key => $field) {
                                    if (!isset($stepSecondFinalDocuments[$documentType]['value'][$key])) {
                                        $stepSecondFinalDocuments[$documentType]['value'][$key] = [
                                            'value' => null,
                                            'label' => $field['label'],
                                            'type' => $field['type']
                                        ];
                                    }
                                }
                            }
                        }

                        $data['requiredDoc'] = $stepSecondFinalDocuments;


                        //Third step douments*************************************
                        $stepThirdFilters = config('applicationDocumentType.MUTATION.Optional');
                        $topLevelKeys = array_keys($stepThirdFilters);
                        $stepThirdFilteredDocuments = $documents->filter(function ($document) use ($topLevelKeys) {
                            return in_array($document->title, $topLevelKeys);
                        });
                        // dd($stepThirdFilteredDocuments);
                        foreach ($stepThirdFilteredDocuments as $document) {
                            $stepThirdFinalDocuments[$document->document_type]['file_path'] = $document->file_path;
                            $documentKeys = DocumentKey::where('document_id', $document->id)->get();
                            foreach ($documentKeys as $documentKey) {
                                $label = config("applicationDocumentType.MUTATION.Optional." . $document->document_type . "." . $documentKey->key . ".label");
                                $type  = config("applicationDocumentType.MUTATION.Optional." . $document->document_type . "." . $documentKey->key . ".type");
                                $stepThirdFinalDocuments[$document->document_type]['value'][$documentKey->key]['value'] = $documentKey->value;
                                $stepThirdFinalDocuments[$document->document_type]['value'][$documentKey->key]['label'] = $label;
                                $stepThirdFinalDocuments[$document->document_type]['value'][$documentKey->key]['type'] = $type;
                            }
                        }


                        foreach ($stepThirdFilters as $documentType => $fields) {
                            // Check if the document type exists in the final documents
                            if (!isset($stepThirdFinalDocuments[$documentType])) {
                                // Initialize the document type if it doesn't exist
                                $stepThirdFinalDocuments[$documentType] = [
                                    'file_path' => null, // Set file_path to null
                                    'value' => [] // Initialize value array
                                ];

                                // Populate the 'value' array with fields from $stepSecondFilters
                                foreach ($fields as $key => $field) {
                                    $stepThirdFinalDocuments[$documentType]['value'][$key] = [
                                        'value' => null, // Set to null
                                        'label' => $field['label'],
                                        'type' => $field['type']
                                    ];
                                }
                            } else {
                                // If the document type exists, ensure the 'value' is populated with nulls for missing fields
                                foreach ($fields as $key => $field) {
                                    if (!isset($stepThirdFinalDocuments[$documentType]['value'][$key])) {
                                        $stepThirdFinalDocuments[$documentType]['value'][$key] = [
                                            'value' => null,
                                            'label' => $field['label'],
                                            'type' => $field['type']
                                        ];
                                    }
                                }
                            }
                        }
                        $data['optionalDoc'] = $stepThirdFinalDocuments;
                    }
                    //coapplicants
                    $data['coapplicants'] = Coapplicant::where('service_type', $serviceType)->where('model_name', $requestModel)->where('model_id', $id)->get();
                    
                    break;
                case 'LandUseChangeApplication':
                    $applicationType = 'Land Use Change';
                    $serviceType = getServiceType('LUC');

                    $documentList = config('applicationDocumentType.LUC.documents');
                    $requiredDocuments = collect($documentList)->where('required', 1)->all();
                    $requiredDocumentTypes = array_map(function ($element) {
                        return $element['label'];
                    }, $requiredDocuments);
                    $uploadedDocuments = Document::where('service_type',$serviceType)->where('model_name', $requestModel)->where('model_id', $id)->get();
                
                    $documents = [
                        'required' => [],
                        'optional' => [],
                    ];
                    
                    // Required documents
                    foreach ($requiredDocumentTypes as $requiredDocument) {
                        foreach ($uploadedDocuments as $uploadedDocument) {
                            if ($requiredDocument == $uploadedDocument->title) {
                                $documents['required'][] = [
                                    'title' => $uploadedDocument->title,
                                    'file_path' => $uploadedDocument->file_path
                                ];
                                break;
                            }
                        }
                    }

                    //optional documents
                    $optionalDocuments = collect($documentList)->where('required', 0)->all();
                    $optionalDocumentTypes = array_map(function ($element) {
                        return $element['label'];
                    }, $optionalDocuments);

                    foreach ($optionalDocumentTypes as $optionalDocument) {
                        $found = false;
                        foreach ($uploadedDocuments as $uploadedDocument) {
                            if ($optionalDocument == $uploadedDocument->title) {
                                $documents['optional'][] = [
                                    'title' => $uploadedDocument->title,
                                    'file_path' => $uploadedDocument->file_path
                                ];
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $documents['optional'][] = [
                                'title' => $optionalDocument,
                                'file_path' => null
                            ];
                        }
                    }
                    $data['documents'] = $documents;       
                    break;
                case 'DeedOfApartmentApplication':
                    $applicationType = 'Deed Of Apartment';
                    $serviceType = getServiceType('DOA');
                    $requiredDocuments = config('applicationDocumentType.DOA.Required');
                    $uploadedDocuments = Document::where('service_type',$serviceType)->where('model_name', $requestModel)->where('model_id', $id)->get();
                    $documents = [
                        'required' => [],
                    ];
                    foreach ($requiredDocuments as $key => $requiredDocument) {
                        foreach ($uploadedDocuments as $uploadedDocument) {
                            if ($key == $uploadedDocument->title) {
                                $documents['required'][] = [
                                    'title' => $uploadedDocument->title,
                                    'file_path' => $uploadedDocument->file_path
                                ];
                                break;
                            }
                        }
                    }
                    $data['documents'] = $documents;  
                    break;
                default:
                    $applicationType = '';
                    break;
            }
    
            $data['applicationType'] = $applicationType;
            $data['roles'] = Auth::user()->roles[0]->name;
            $property = PropertyMaster::find($applicationDetails['property_master_id']);
            if (!empty($property['id'])) {

                $response = Http::timeout(10)->get('https://ldo.gov.in/eDhartiAPI/Api/GetValues/PropertyDocList?PropertyID=' . $property['old_propert_id']);
                if ($response->successful()) {
                    $jsonData = $response->json();
                    // Proceed if jsonData is not empty
                    if (!empty($jsonData)) {
                        $scannedFiles['baseUrl'] = $jsonData[0]['Path'];
                        foreach ($jsonData[0]['ListFileName'] as $value) {
                            $scannedFiles['files'][] = $value['PropertyFileName'];
                        }
                    } else {
                        // Handle case where the response is empty or not as expected
                        Log::warning('API response returned empty or invalid data.');
                        $scannedFiles['files'] = [];
                    }
                } else {
                    // Handle non-successful HTTP response
                    Log::error('API request failed with status: ' . $response->status());
                    $scannedFiles['files'] = [];
                }


                $data['scannedFiles'] = $scannedFiles;
                $data['propertyMasterId'] = $property['id'];
                $data['suggestedPropertyId'] = $property['old_propert_id'];
                $data['oldPropertyId'] = $property['old_propert_id'];
                $data['uniquePropertyId'] = $property['unique_propert_id'];
                $data['sectionCode'] = $property['section_code'];
            } else {
                $data['propertyMasterId'] = '';
                $data['suggestedPropertyId'] = '';
                $data['oldPropertyId'] = '';
                $data['uniquePropertyId'] = '';
                $data['sectionCode'] = '';
            }
            $applicationDetails['serviceType'] = $serviceType;
            $section = Section::find($applicationDetails->section_id);
            $applicationDetails['sectionCode'] = $section['section_code'];
            $data['details'] = $applicationDetails;
            $data['applicationMovementId'] = $id;
            $data['checkList'] = ApplicationStatus::where('service_type', $serviceType)->where('model_id', $id)->first();
            $oldPropertyId = (string) $applicationDetails['old_property_id'];
            $UserApplicationController = new UserApplicationController();
            $data['propertyCommonDetails'] = $UserApplicationController->getPropertyCommonDetails($oldPropertyId);

            $data['user'] = User::find($applicationDetails['created_by']);
            // dd($data);
            return view('application.view',$data);
        } else {

        }
    }
}
