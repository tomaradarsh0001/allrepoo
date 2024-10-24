<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralFunctions;
use App\Http\Controllers\Controller;
use App\Services\ColonyService;
use App\Services\MisService;
use Illuminate\Http\Request;
use App\Models\OldColony;
use App\Models\UserProperty;
use App\Models\TempDeedOfApartment;
use App\Models\User;
use App\Models\Item;
use App\Models\Payment;
use App\Models\PropertyLeaseDetail;
use App\Models\PropertyMaster;
use App\Models\SplitedPropertyDetail;
use App\Models\TempDocument;
use App\Models\TempSubstitutionMutation;
use App\Models\TempCoapplicant;
use App\Models\TempDocumentKey;
use Carbon\Carbon;
use App\Services\LandRateService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\MutationApplication;
use App\Models\Application;
use App\Models\ApplicationMovement;
use App\Models\DeedOfApartmentApplication;
use App\Models\LandUseChangeApplication;
use App\Models\TempLandUseChangeApplication;

class ApplicationController extends Controller
{
    // for checking is any application available for this property id - SOURAV CHAUHAN (7/oct/2024)
    public function isPropertyFree(Request $request)
    {
        $modelsToCheck = [MutationApplication::class, TempSubstitutionMutation::class, TempDeedOfApartment::class, DeedOfApartmentApplication::class, LandUseChangeApplication::class, TempLandUseChangeApplication::class];

        // foreach ($modelsToCheck as $model) {
        //     $isPropertyIdAvailable = $model::where('old_property_id', $request->propertyId)->first();
        //     if ($isPropertyIdAvailable) {
        //         return response()->json(['status' => false, 'message' => 'Application already available for the selected Property ID:- ' . $request->propertyId]);
        //     }
        // }
        return response()->json(['status' => true, 'message' => 'Property ID:- ' . $request->propertyId . ' is available']);
    }


    public function deedOfApartmentCreateForm(ColonyService $colonyService, MisService $misService)
    {
        $colonyList = $colonyService->getColonyList();
        $propertyTypes = $misService->getItemsByGroupId(1052);
        return view('apartment.create', compact(['colonyList', 'propertyTypes']));
    }

    //Commented on 10/01/2024 By Lalit for Newly common functionality
    /*public function store(Request $request)
    {
        // Validation rules
        $validated = $request->validate([
            'applicantName' => 'required|string|max:255',
            'applicantAddress' => 'required|string',
            'buildingName' => 'required|string|max:255',
            'locality' => 'required|numeric',
            'block' => 'required|numeric',
            'plot' => 'required|numeric',
            'knownas' => 'required|string|max:255',
            'originalBuyerName' => 'required|string|max:255',
            'presentOccupantName' => 'required|string|max:255',
            'purchasedFrom' => 'required|string|max:255',
            'plotArea' => 'required|numeric',
            'flatArea' => 'required|numeric',
            'flatNumber' => 'required|string|max:255',
            'builderName' => 'required|string|max:255',
            'builderAgreementDoc' => 'required|file|mimes:pdf|max:51200', // Max 50MB
            'saleDeedDoc' => 'required|file|mimes:pdf|max:5120', // Max 5MB
            'otherDoc' => 'required|file|mimes:pdf|max:5120', // Max 5MB
            'buildingPlanDoc' => 'required|file|mimes:pdf|max:5120', // Max 5MB
        ]);

        //Generate unique application number for that need to pass table_name & prefeix to generate applicaiton number
        $applicationNumber = GeneralFunctions::generateUniqueApplicationNumber(TempDeedOfApartment::class, 'application_number');

        // Handle file uploads
        if ($request->locality) {
            $colony = OldColony::find($request->locality);
            $colonyCode = $colony->code;
            if (isset($request->builderAgreementDoc)) {
                $builderAgreementDoc = GeneralFunctions::uploadFile($request->builderAgreementDoc, $colonyCode . '/' . $applicationNumber, 'builderAgreement');
            }
            if (isset($request->saleDeedDoc)) {
                $saleDeedDoc = GeneralFunctions::uploadFile($request->saleDeedDoc, $colonyCode . '/' . $applicationNumber, 'saleDeed');
            }
            if (isset($request->otherDoc)) {
                $otherDoc = GeneralFunctions::uploadFile($request->otherDoc, $colonyCode . '/' . $applicationNumber, 'otherDocument');
            }
            if (isset($request->buildingPlanDoc)) {
                $buildingPlanDoc = GeneralFunctions::uploadFile($request->buildingPlanDoc, $colonyCode . '/' . $applicationNumber, 'buildingPlan');
            }
        }

        // Save to database
        TempDeedOfApartment::create([
            'user_id'   => Auth::id(),
            'application_number'  => !empty($applicationNumber) ? $applicationNumber : '',
            'application_type'  => !empty(getServiceType('APP_DOA')) ? getServiceType('APP_DOA') : '',
            'applicant_name' => !empty($request->applicantName) ? $request->applicantName : '',
            'applicant_address' => !empty($request->applicantAddress) ? $request->applicantAddress : '',
            'building_name' => !empty($request->buildingName) ? $request->buildingName : '',
            'locality' => !empty($request->locality) ? $request->locality : '',
            'block' => !empty($request->block) ? $request->block : '',
            'plot' => !empty($request->plot) ? $request->plot : '',
            'known_as' => !empty($request->knownas) ? $request->knownas : '',
            'original_buyer_name' => !empty($request->originalBuyerName) ? $request->originalBuyerName : '',
            'present_occupant_name' => !empty($request->presentOccupantName) ? $request->presentOccupantName : '',
            'purchased_from' => !empty($request->purchasedFrom) ? $request->purchasedFrom : '',
            'plot_area' => !empty($request->plotArea) ? $request->plotArea : 0.00,
            'flat_id' => !empty($request->flatId) ? $request->flatId : null,
            'flat_number' => !empty($request->flatNumber) ? $request->flatNumber : '',
            'builder_developer_name' => !empty($request->builderName) ? $request->builderName : '',
            'flat_area' => !empty($request->apartmentArea) ? $request->apartmentArea : 0.00,
            'builder_agreement_doc' => !empty($builderAgreementDoc) ? $builderAgreementDoc : '',
            'sale_deed_doc' => !empty($saleDeedDoc) ? $saleDeedDoc : '',
            'other_doc' => !empty($otherDoc) ? $otherDoc : '',
            'building_plan_doc' => !empty($buildingPlanDoc) ? $buildingPlanDoc : '',
        ]);

        return redirect()->back()->with('success', 'Application successfully accepted for Deed Of Apartment.');
    }*/

    public function getProperty(Request $request)
    {
        try {
            // Initialize variables with default empty values
            $id = $oldPropertyId = $uniquePropertyId = $splittedPropertyId = '';
            // Validate request inputs
            if (!empty($request->locality) && !empty($request->block) && !empty($request->plot)) {
                // Fetch property details based on locality, block, and plot
                $property = PropertyMaster::where('new_colony_name', $request->locality)
                    ->where('block_no', $request->block)
                    ->where('plot_or_property_no', $request->plot)
                    ->first();

                // Check if the property exists
                if ($property) {
                    // Assign values to variables
                    $id = $property->id;
                    $oldPropertyId = $property->old_propert_id;
                    $uniquePropertyId = $property->unique_propert_id;
                } else {
                    // Handle split property details
                    $getSplittedDetails = SplitedPropertyDetail::where('plot_flat_no', $request->plot)
                        ->where('presently_known_as', $request->knownas)
                        ->first();

                    if ($getSplittedDetails) {
                        $property = PropertyMaster::where('new_colony_name', $request->locality)
                            ->where('block_no', $request->block)
                            ->where('id', $getSplittedDetails->property_master_id)
                            ->first();

                        if ($property) {
                            $id = $property->id;
                            $oldPropertyId = $getSplittedDetails->old_property_id;
                            $uniquePropertyId = $property->unique_propert_id;
                            $splittedPropertyId = $getSplittedDetails->id;
                        }
                    }
                }

                if ($id) {
                    return [
                        'property_master_id'    => $id,
                        'old_propert_id'        => $oldPropertyId,
                        'new_property_id'       => $uniquePropertyId,
                        'splited_property_detail_id'    => $splittedPropertyId,
                    ];
                } else {
                    // Return custom error when no record is found
                    return response()->json(['error' => 'Property details not found.'], 404);
                }
            } else {
                return response()->json(['error' => 'Invalid input. Please provide valid locality, block, and plot details.'], 400);
            }
        } catch (\Exception $e) {
            // Return custom error for exception
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $transactionSuccess = false;
            $tempDOA = null;
            $oldPropertyId = $newPropertyId = $masterPropertyId = $splittedPropertyId = '';
            if ($request->isFlatNotInList) {
                $getPropertyDataObj = self::getProperty($request);
                $oldPropertyId = $getPropertyDataObj['old_propert_id'];
                $newPropertyId = $getPropertyDataObj['new_property_id'];
                $masterPropertyId = $getPropertyDataObj['property_master_id'];
                $splittedPropertyId = $getPropertyDataObj['splited_property_detail_id'];
            } else {
                if ($request->oldPropertyId) {
                    $oldPropertyId = $request->oldPropertyId;
                }
                if ($request->newPropertyId) {
                    $newPropertyId = $request->newPropertyId;
                }
                if ($request->propertyMasterId) {
                    $masterPropertyId = $request->propertyMasterId;
                }
                if ($request->splittedPropertyId) {
                    $splittedPropertyId = $request->splittedPropertyId;
                }
            }
            $updateId = $request->updateId;
            if ($request->propertyStatus == 'Free Hold') {
                $propertyStatus = 952;
            } else {
                $propertyStatus = 951;
            }
            if ($updateId != '0') {
                DB::transaction(function () use ($request, &$transactionSuccess, &$updateId, &$tempDOA, &$propertyStatus) {
                    $tempDOA = TempDeedOfApartment::find($updateId);
                    if (isset($tempDOA)) {
                        $tempDOA->property_status = !empty($propertyStatus) ? $propertyStatus : $tempDOA->property_status;
                        $tempDOA->status_of_applicant = !empty($request->statusofapplicant) ? $request->statusofapplicant : $tempDOA->status_of_applicant;
                        $tempDOA->applicant_name = !empty($request->applicantName) ? $request->applicantName : $tempDOA->applicant_name;
                        $tempDOA->applicant_address = !empty($request->applicantAddress) ? $request->applicantAddress : $tempDOA->applicant_address;
                        $tempDOA->building_name = !empty($request->buildingName) ? $request->buildingName : $tempDOA->building_name;
                        $tempDOA->locality = !empty($request->locality) ? $request->locality : $tempDOA->locality;
                        $tempDOA->block = !empty($request->block) ? $request->block : $tempDOA->block;
                        $tempDOA->plot = !empty($request->plot) ? $request->plot : $tempDOA->plot;
                        $tempDOA->known_as = !empty($request->knownas) ? $request->knownas : $tempDOA->known_as;
                        $tempDOA->flat_id = !empty($request->flatId) ? $request->flatId : $tempDOA->flat_id;
                        $tempDOA->flat_number = !empty($request->flatNumber) ? $request->flatNumber : $tempDOA->flat_number;
                        $tempDOA->builder_developer_name = !empty($request->builderName) ? $request->builderName : $tempDOA->builder_developer_name;
                        $tempDOA->original_buyer_name = !empty($request->originalBuyerName) ? $request->originalBuyerName : $tempDOA->original_buyer_name;
                        $tempDOA->present_occupant_name = !empty($request->presentOccupantName) ? $request->presentOccupantName : $tempDOA->present_occupant_name;
                        $tempDOA->purchased_from = !empty($request->purchasedFrom) ? $request->purchasedFrom : $tempDOA->purchased_from;
                        $tempDOA->purchased_date = !empty($request->purchaseDate) ? $request->purchaseDate : $tempDOA->purchased_date;
                        $tempDOA->flat_area = !empty($request->flatArea) ? $request->flatArea : $tempDOA->flat_area;
                        $tempDOA->plot_area = !empty($request->plotArea) ? $request->plotArea : $tempDOA->plot_area;
                        $tempDOA->updated_by = Auth::user()->id;
                        if ($tempDOA->save()) {
                            $transactionSuccess = true;
                        }
                    }
                });
            } else {
                $propertyDetails = PropertyMaster::where('old_propert_id', $request->propertyid)->first();
                DB::transaction(function () use ($request, &$transactionSuccess, &$propertyDetails, &$tempDOA, &$propertyStatus, &$oldPropertyId, &$newPropertyId, &$masterPropertyId, &$splittedPropertyId) {
                    $tempDOA = TempDeedOfApartment::create([
                        'old_property_id'   => !empty($oldPropertyId) ? $oldPropertyId : $request->propertyid,
                        'new_property_id'   => !empty($newPropertyId) ? $newPropertyId : $propertyDetails['unique_propert_id'],
                        'property_master_id'   => !empty($masterPropertyId) ? $masterPropertyId : $propertyDetails['id'],
                        'splited_property_detail_id'   => !empty($splittedPropertyId) ? $splittedPropertyId : null,
                        'property_status'   => !empty($propertyStatus) ? $propertyStatus : null,
                        'status_of_applicant'   => !empty($request->statusofapplicant) ? $request->statusofapplicant : null,
                        'service_type'   => !empty(getServiceType('DOA')) ? getServiceType('DOA') : null,
                        'applicant_name'   => !empty($request->applicantName) ? $request->applicantName : null,
                        'applicant_address'   => !empty($request->applicantAddress) ? $request->applicantAddress : null,
                        'building_name'   => !empty($request->buildingName) ? $request->buildingName : null,
                        'locality'   => !empty($request->locality) ? $request->locality : null,
                        'block'   => !empty($request->block) ? $request->block : null,
                        'plot'   => !empty($request->plot) ? $request->plot : null,
                        'known_as'   => !empty($request->knownas) ? $request->knownas : null,
                        'flat_id'   => !empty($request->flatId) ? $request->flatId : null,
                        'flat_number'   => !empty($request->flatNumber) ? $request->flatNumber : null,
                        'builder_developer_name'   => !empty($request->builderName) ? $request->builderName : null,
                        'original_buyer_name'   => !empty($request->originalBuyerName) ? $request->originalBuyerName : null,
                        'present_occupant_name'   => !empty($request->presentOccupantName) ? $request->presentOccupantName : null,
                        'purchased_from'   => !empty($request->purchasedFrom) ? $request->purchasedFrom : null,
                        'purchased_date'   => !empty($request->purchaseDate) ? $request->purchaseDate : null,
                        'flat_area'   => !empty($request->flatArea) ? $request->flatArea : null,
                        'plot_area'   => !empty($request->plotArea) ? $request->plotArea : null,
                        'created_by'   => Auth::id(),
                        'updated_by'   => Auth::id(),
                    ]);
                    if ($tempDOA) {
                        $transactionSuccess = true;
                    }
                });
            }

            if ($transactionSuccess) {
                $response = ['status' => true, 'message' => 'Deed of Apartment Details Saved Successfully', 'data' => $tempDOA];
            } else {
                $response = ['status' => false, 'message' => 'Something went wrong!', 'data' => 0];
            }
            return json_encode($response);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            $response = ['status' => false, 'message' => $e->getMessage(), 'data' => 0];
            return json_encode($response);
        }
    }

    /*public function store(Request $request)
    {
        $oldPropertyId = $newPropertyId = $masterPropertyId = $splittedPropertyId = '';
        // Check if the checkbox is checked
        //Check if flat not in list then we need to get oldPropertyId,newPropertyId,masterPropertyId,splittedPropertyId through locality,block,plot & property known as
        if ($request->isFlatNotInList) {
            $getPropertyDataObj = self::getProperty($request);
            $oldPropertyId = $getPropertyDataObj['old_propert_id'];
            $newPropertyId = $getPropertyDataObj['new_property_id'];
            $masterPropertyId = $getPropertyDataObj['property_master_id'];
            $splittedPropertyId = $getPropertyDataObj['splited_property_detail_id'];
        } else {
            if ($request->old_property_id) {
                $oldPropertyId = $request->old_property_id;
            }
            if ($request->new_property_id) {
                $newPropertyId = $request->new_property_id;
            }
            if ($request->property_master_id) {
                $masterPropertyId = $request->property_master_id;
            }
            if ($request->splited_property_detail_id) {
                $splittedPropertyId = $request->splited_property_detail_id;
            }
        }

        $tempDeed = TempDeedOfApartment::create([
            'old_property_id'   => !empty($oldPropertyId) ? $oldPropertyId : null,
            'new_property_id'   => !empty($newPropertyId) ? $newPropertyId : null,
            'property_master_id'   => !empty($masterPropertyId) ? $masterPropertyId : null,
            'splited_property_detail_id'   => !empty($splittedPropertyId) ? $splittedPropertyId : null,
            'property_status'   => !empty($request->property_status) ? $request->property_status : null,
            'status_of_applicant'   => !empty($request->status_of_applicant) ? $request->status_of_applicant : null,
            'service_type'   => !empty(getServiceType('DOA')) ? getServiceType('DOA') : null,
            'applicant_name'   => !empty($request->applicantName) ? $request->applicantName : null,
            'applicant_address'   => !empty($request->applicantAddress) ? $request->applicantAddress : null,
            'building_name'   => !empty($request->buildingName) ? $request->buildingName : null,
            'locality'   => !empty($request->locality) ? $request->locality : null,
            'block'   => !empty($request->block) ? $request->block : null,
            'plot'   => !empty($request->plot) ? $request->plot : null,
            'known_as'   => !empty($request->knownas) ? $request->knownas : null,
            'flat_id'   => !empty($request->flatId) ? $request->flatId : null,
            'flat_number'   => !empty($request->flatNumber) ? $request->flatNumber : null,
            'builder_developer_name'   => !empty($request->builderName) ? $request->builderName : null,
            'original_buyer_name'   => !empty($request->originalBuyerName) ? $request->originalBuyerName : null,
            'present_occupant_name'   => !empty($request->presentOccupantName) ? $request->presentOccupantName : null,
            'purchased_from'   => !empty($request->purchasedFrom) ? $request->purchasedFrom : null,
            'purchased_date'   => !empty($request->purchaseDate) ? $request->purchaseDate : null,
            'flat_area'   => !empty($request->flatArea) ? $request->flatArea : null,
            'plot_area'   => !empty($request->plotArea) ? $request->plotArea : null,
            'created_by'   => Auth::id(),
            'updated_by'   => Auth::id(),
        ]);
        if ($tempDeed->id > 0) {
            // Return success response with last inserted ID
            return response()->json([
                'success' => true,
                'last_inserted_id' => $tempDeed->id,
                'propertyid' => $oldPropertyId,
            ]);
        }
    }*/


    //for showing application form - Sourav Chauhan - 12/sep/2024
    public function newApplication(ColonyService $colonyService, MisService $misService)
    {
        // $data['userProperties'] = UserProperty::where('user_id', Auth::id())->pluck('old_property_id');
        $userProperties = UserProperty::where('user_id', Auth::id())->get();
        $fillId = [];
        foreach ($userProperties as $property) {
            $locality = OldColony::find($property->locality);
            $fillId[$property->old_property_id] = $property->block . '/' . $property->plot . '/' . $locality['name'];
        }
        $data['userProperties'] = $fillId;
        $lcm = new LandRateService();
        $data['applicantTypes'] = $lcm->getApplicantTyps();
        $data['colonyList'] = $colonyService->getColonyList();
        $data['propertyTypes'] = $misService->getItemsByGroupId(1052);
        $data['applicantStatus'] = $misService->getItemsByGroupId(1002);
        $data['documentTypes'] = getItemsByGroupId(17005);
        return view('applicant.new_application', $data);
    }

    //for fetching property detals by property id - Sourav Chauhan - 12/sep/2024
    public function getPropertyDetails(Request $request)
    {
        $oldPropertyId = $request->propertyId;
        $updateId = $request->updateId;
        $draftApplicationPropertyId = $request->draftApplicationPropertyId;

        //for edit case
        if ($draftApplicationPropertyId == 'true') {
            $decodedModel = $request->model;

            $model = '\\App\\Models\\' . $decodedModel;
            if (!class_exists($model)) {
                return redirect()->back();
            }
            $instance = new $model();
            $serviceType = $instance->serviceType;
        }

        //$data['applicationTypeOption'] = "<option value='$serviceType->item_code'>$serviceType->item_name</option>";

        // dd($draftApplicationPropertyId,$updateId);
        if ($draftApplicationPropertyId == 'false' && $updateId != 0) {
            $response = ['status' => false, 'message' => 'Data should be deleted', 'data' => 'deleteYes'];
        } else {
            $propertyDetails = PropertyMaster::where('old_propert_id', $oldPropertyId)->first();
            $data = [];
            $data['propertyDetails'] = Self::getPropertyCommonDetails($oldPropertyId);
            $data['items'] = [];
            if ($propertyDetails) {
                $data['status'] = $propertyDetails['status'];
                if ($data['status'] == '952') {
                    //if free hold
                    if ($draftApplicationPropertyId == 'true') {
                        $itemCodes = [$serviceType->item_code];
                    } else {
                        $itemCodes = ['NOC', 'SUB_MUT', 'PRP_CERT'];
                    }
                } else {
                    //if lease hold
                    if ($draftApplicationPropertyId == 'true') {
                        $itemCodes = [$serviceType->item_code];
                    } else {
                        $itemCodes = ['LUC', 'DOA', 'CONVERSION', 'SEL_PERM', 'PRP_CERT', 'SUB_MUT'];
                    }
                }
                $items = Item::whereIn('item_code', $itemCodes)->pluck('item_name', 'item_code');
                if ($items) {
                    $data['items'] = $items;
                }
                $response = ['status' => true, 'message' => 'Provided Property is available.', 'data' => $data];
            } else {
                $response = ['status' => false, 'message' => 'Provided Property ID is not available.', 'data' => NULL];
            }
        }
        return $response;
    }

    //for getting property common details - SOURAV CHAUHAN (10/Oct/2024)
    public function getPropertyCommonDetails($propertyId)
    {
        $propertyMaster = PropertyMaster::where('old_propert_id', $propertyId)->first();

        $data['propertyType'] = getServiceNameById($propertyMaster->property_type);
        $data['propertySubType'] = getServiceNameById($propertyMaster->property_sub_type);

        $propertyLeaseDetail = $propertyMaster->propertyLeaseDetail;
        $data['leaseType'] = getServiceNameById($propertyLeaseDetail->type_of_lease);
        $data['leaseExectionDate'] = $propertyLeaseDetail->doe;
        $data['area'] = $propertyLeaseDetail->plot_area_in_sqm;
        $data['presentlyKnownAs'] = $propertyLeaseDetail->presently_known_as;

        $propertyTransferDetaails = $propertyMaster->propertyTransferredLesseeDetails;
        $originalLessee = $propertyTransferDetaails->where('process_of_transfer', 'Original')->first();
        $data['inFavourOf'] = $originalLessee->lessee_name;
        return $data;
    }

    // for fetching user details - Sourav Chauhan - 13/sep/2024
    public function fetchUserDetails()
    {
        $data = [];
        $data['user'] = $user = User::where('id', Auth::id())->first();
        $data['details'] = $user->applicantUserDetails;
        if ($data) {
            $response = ['status' => true, 'message' => 'User is available.', 'data' => $data];
        } else {
            $response = ['status' => false, 'message' => 'User is not available.', 'data' => NULL];
        }
        return $response;
    }

    public function uploadFile(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:2048',
        ]);
        //initialize variables
        // $dateOfAttestation = $attestedBy = $nameOfDeceased = $dateOfDeath = $dateOfIssue = $documentCertificateNo = $registrationNo = $volume = $bookNo = $pageFromTo = $regnDate = $regnOfficeName = $nameOfTestator = $dateOfWillCodicil = $dateOfExecution = $nameOfCourt = $dateOfCourtOrder = $certificateNo = $nameOfLessee = $nameOfNewspaperEnglish = $nameOfNewspaperHindi = $dateOfPublicNotice = $nameOfExecutor = $otherDetails = null;

        $file = $request->file;
        $name = $request->name;
        $type = $request->type;
        $propertyId = $request->propertyId;
        $updateId = $request->updateId;
        $processType = $request->processType;

        $user = Auth::user();
        $userDetails = $user->applicantUserDetails;
        $registrationNumber = $userDetails->applicant_number;
        $propertyDetails = PropertyMaster::where('old_propert_id', $propertyId)->first();
        $colonyId = $propertyDetails['new_colony_name'];
        $colony = OldColony::find($colonyId);
        $colonyCode = $colony->code;

        if ($file) {
            $service_type = getServiceType($processType);
            if ($type == 'mutation') {
                $processType = strtoupper($type);
            }
            $modelName = config('applicationDocumentType.' . $processType . '.TempModelName');
            $documentUploded = TempDocument::where('service_type', $service_type)->where('model_id', $updateId)->where('document_type', $name)->first();
            $date = now()->format('YmdHis');
            $fileName = $name . '_' . $date . '.' . $file->extension();
            $pathToUpload = $registrationNumber . '/' . $colonyCode . '/' . $type . '/' . $updateId;
            if ($documentUploded) {
                //delete the fie from folder
                $deletedFile = $documentUploded->file_path;
                if ($deletedFile) {
                    if (Storage::disk('public')->exists($deletedFile)) {
                        Storage::disk('public')->delete($deletedFile);
                    }
                    $path = $file->storeAs($pathToUpload, $fileName, 'public');
                    if ($path) {
                        $documentUploded->file_path = $path;
                        $documentUploded->updated_by = Auth::user()->id;
                        if ($documentUploded->save()) {
                            return response()->json(['status' => true, 'path' => $path]);
                        } else {
                            return response()->json(['status' => false, 'message' => 'File update failed.']);
                        }
                    }
                }
            } else {
                $path = $file->storeAs($pathToUpload, $fileName, 'public');
                if ($path) {
                    $documentUploded = TempDocument::create([
                        'service_type' => $service_type,
                        'model_name' => $modelName, //'TempSubstitutionMutation',
                        'model_id' => $updateId,
                        'title' => $name,
                        'document_type' => $name,
                        'file_path' => $path,
                        'created_by' => Auth::user()->id,
                    ]);
                    if ($documentUploded) {
                        return response()->json(['status' => true, 'path' => $path]);
                    } else {
                        return response()->json(['status' => false, 'message' => 'File saving failed.']);
                    }
                }
            }
        }

        return response()->json(['status' => false, 'message' => 'File upload failed.']);
    }

    //Get all incomplete applications
    public function draftApplications()
    {
        return view('application.draft.index');
    }

    public function getDraftApplications(Request $request)
    {
        // Define the columns that can be ordered and searched
        $columns = ['id', 'old_property_id'];

        // Start query
        // $query = DB::table('temp_substitution_mutation as tsm')->query()
        //     ->leftJoin('property_masters', 'tsm.property_master_id', '=', 'property_masters.id')
        //     ->leftJoin('old_colonies', 'property_masters.new_colony_name', '=', 'old_colonies.id')
        //     ->leftJoin('property_lease_details', 'property_masters.id', '=', 'property_lease_details.property_master_id')
        //     ->where('tsm.created_by' ,'=', Auth::id())
        //     ->select(
        //         'tsm.id',
        //         'property_masters.old_propert_id',
        //         'property_masters.new_colony_name',
        //         'old_colonies.name as colony_name',
        //         'property_masters.block_no',
        //         'property_masters.plot_or_property_no',
        //         'property_lease_details.presently_known_as'
        //     );

        // Define the first table query
        $query1 = DB::table('temp_substitution_mutation as tsm')
            ->leftJoin('property_masters', 'tsm.property_master_id', '=', 'property_masters.id')
            ->leftJoin('old_colonies', 'property_masters.new_colony_name', '=', 'old_colonies.id')
            ->leftJoin('property_lease_details', 'property_masters.id', '=', 'property_lease_details.property_master_id')
            ->where('tsm.created_by', '=', Auth::id())
            ->select(
                'tsm.id',
                'tsm.created_at',
                'property_masters.old_propert_id',
                'property_masters.new_colony_name',
                'old_colonies.name as colony_name',
                'property_masters.block_no',
                'property_masters.plot_or_property_no',
                'property_lease_details.presently_known_as',
                DB::raw("'TempSubstitutionMutation' as model_name") // Add model_name for the first query
            );

        //Land use change
        $query2 = DB::table('temp_land_use_change_applications as luc') // Replace 'another_table' with your actual table name
            ->leftJoin('property_masters', 'luc.property_master_id', '=', 'property_masters.id')
            ->leftJoin('old_colonies', 'property_masters.new_colony_name', '=', 'old_colonies.id')
            ->leftJoin('property_lease_details', 'property_masters.id', '=', 'property_lease_details.property_master_id')
            ->where('luc.created_by', '=', Auth::id())
            ->select(
                'luc.id', // Ensure this is compatible with the first query
                'luc.created_at',
                'property_masters.old_propert_id',
                'property_masters.new_colony_name',
                'old_colonies.name as colony_name',
                'property_masters.block_no',
                'property_masters.plot_or_property_no',
                'property_lease_details.presently_known_as',
                DB::raw("'TempLandUseChangeApplication' as model_name") // Add model_name for the second query
            );

        //Deed Of Apartment
        $query3 = DB::table('temp_deed_of_apartments as doa') // Replace 'another_table' with your actual table name
            ->leftJoin('property_masters', 'doa.property_master_id', '=', 'property_masters.id')
            ->leftJoin('old_colonies', 'property_masters.new_colony_name', '=', 'old_colonies.id')
            ->leftJoin('property_lease_details', 'property_masters.id', '=', 'property_lease_details.property_master_id')
            ->where('doa.created_by', '=', Auth::id())
            ->select(
                'doa.id', // Ensure this is compatible with the first query
                'doa.created_at',
                'property_masters.old_propert_id',
                'property_masters.new_colony_name',
                'old_colonies.name as colony_name',
                'property_masters.block_no',
                'property_masters.plot_or_property_no',
                'property_lease_details.presently_known_as',
                DB::raw("'TempDeedOfApartment' as model_name") // Add model_name for the second query
            );

        // Combine all three queries using UNION
        // $combinedQuery = $query1->union($query2);
        $combinedQuery = $query1->union($query2)->union($query3);
        // dd($combinedQuery->get());
        // $combinedQuery = $query1->union($query2);
        // Execute the combined query
        // $query = $combinedQuery->get();

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
        // $order = $columns[$orderColumnIndex] ?? 'id'; // Default order by 'id' if index is invalid
        // $dir = $request->input('order.0.dir');

        // Use raw SQL to sort by concatenated columns
        // $query->orderBy($order, $dir);

        // Apply ordering and limit/offset
        $applications = $combinedQuery->offset($start)
            ->limit($limit)
            ->get();

        $data = [];
        // dd($applications);
        foreach ($applications as $key => $application) {
            $nestedData['id'] = $key + 1;
            $nestedData['old_property_id'] = $application->old_propert_id;
            $nestedData['new_colony_name'] = $application->colony_name;
            $nestedData['block_no'] = $application->block_no;
            $nestedData['plot_or_property_no'] = $application->plot_or_property_no;
            $nestedData['presently_known_as'] = $application->presently_known_as;

            switch ($application->model_name) {
                case 'TempSubstitutionMutation':
                    $appliedFor = 'Mutation';
                    break;
                case 'TempLandUseChangeApplication':
                    $appliedFor = 'LUC';
                    break;
                case 'TempDeedOfApartment':
                    $appliedFor = 'DOA';
                    break;
                default:
                    // Default action
                    break;
            }
            $nestedData['applied_for'] = '<label class="badge bg-info mx-1">' . $appliedFor . '</label>';
            $model = base64_encode($application->model_name);

            // Prepare actions
            $action = '<a href="' . url('applications/draft/' . $application->id) . '?type=' . $model . '"><button type="button" class="btn btn-primary px-5">Complete Application</button></a> <a href="javascript:void(0)" ><button type="button" class="btn btn-danger px-5" onclick="deleteConfirmModal(\'Are you sure to delete ' . $appliedFor . ' application?\',\'' . base64_encode($application->model_name) . '\',\'' . base64_encode($application->id) . '\')">Delete Draft</button></a>';
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

    public function getDraftApplication(Request $request, $id, ColonyService $colonyService, MisService $misService)
    {
        $decodedModel = base64_decode($request->type);
        $data['decodedModel'] = $decodedModel;
        $model = '\\App\\Models\\' . $decodedModel;
        if (!class_exists($model)) {
            return redirect()->back();
        }

        $applicationQuery = $model::where('id', $id);
        // Conditionally add the alias for 'applicant_status' while keeping all other columns
        if ($decodedModel == 'TempLandUseChangeApplication') {
            $applicationQuery = $applicationQuery->addSelect('*', 'applicant_status as status_of_applicant');
        }

        // Fetch the first result and store it in $data['application']
        $application = $applicationQuery->first();
        $data['application'] = $application;

        if (!empty($application)) {
            $documents = TempDocument::where('model_name', $decodedModel)->where('model_id', $id)->get();
            $documentKeys = [];
            $tempCoapplicant = null;
            $stepSecondFinalDocuments = [];
            $stepThirdFinalDocuments = [];
            switch ($decodedModel) {
                case 'TempSubstitutionMutation':
                    $appliedFor = 'Mutation';
                    if (!empty($documents)) {

                        //second step douments ***********************************88
                        $stepSecondFilters = config('applicationDocumentType.MUTATION.Required');
                        $topLevelKeys = array_keys($stepSecondFilters);
                        $stepSecondFilteredDocuments = $documents->filter(function ($document) use ($topLevelKeys) {
                            return in_array($document->title, $topLevelKeys);
                        });
                        foreach ($stepSecondFilteredDocuments as $document) {
                            $stepSecondFinalDocuments[$document->document_type]['file_path'] = $document->file_path;
                            $tempDocumentKeys = TempDocumentKey::where('temp_document_id', $document->id)->get();
                            foreach ($tempDocumentKeys as $tempDocumentKey) {
                                $label = config('applicationDocumentType.MUTATION.Required.' . $document->document_type . '.' . $tempDocumentKey->key . '.label');
                                $type  = config('applicationDocumentType.MUTATION.Required.' . $document->document_type . '.' . $tempDocumentKey->key . '.type');
                                $stepSecondFinalDocuments[$document->document_type]['value'][$tempDocumentKey->key]['value'] = $tempDocumentKey->value;
                                $stepSecondFinalDocuments[$document->document_type]['value'][$tempDocumentKey->key]['label'] = $label;
                                $stepSecondFinalDocuments[$document->document_type]['value'][$tempDocumentKey->key]['type'] = $type;
                            }
                        }
                        // dd($stepSecondFinalDocuments);

                        foreach ($stepSecondFilters as $documentType => $fields) {
                            // Check if the document type exists in the final documents
                            if (!isset($stepSecondFinalDocuments[$documentType])) {
                                // Initialize the document type if it doesn't exist
                                $stepSecondFinalDocuments[$documentType] = [
                                    'file_path' => null, // Set file_path to null
                                    'value' => [] // Initialize value array
                                ];

                                // Populate the 'value' array with fields from $stepSecondFilters
                                foreach ($fields as $key => $field) {
                                    $stepSecondFinalDocuments[$documentType]['value'][$key] = [
                                        'value' => null, // Set to null
                                        'label' => $field['label'],
                                        'type' => $field['type']
                                    ];
                                }
                            } else {
                                // If the document type exists, ensure the 'value' is populated with nulls for missing fields
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


                        //Third step douments*************************************
                        $stepThirdFilters = config('applicationDocumentType.MUTATION.Optional');
                        $topLevelKeys = array_keys($stepThirdFilters);
                        $stepThirdFilteredDocuments = $documents->filter(function ($document) use ($topLevelKeys) {
                            return in_array($document->title, $topLevelKeys);
                        });
                        // dd($stepThirdFilteredDocuments);
                        foreach ($stepThirdFilteredDocuments as $document) {
                            $stepThirdFinalDocuments[$document->document_type]['file_path'] = $document->file_path;
                            $tempDocumentKeys = TempDocumentKey::where('temp_document_id', $document->id)->get();
                            foreach ($tempDocumentKeys as $tempDocumentKey) {
                                $label = config("applicationDocumentType.MUTATION.Optional." . $document->document_type . "." . $tempDocumentKey->key . ".label");
                                $type  = config("applicationDocumentType.MUTATION.Optional." . $document->document_type . "." . $tempDocumentKey->key . ".type");
                                $stepThirdFinalDocuments[$document->document_type]['value'][$tempDocumentKey->key]['value'] = $tempDocumentKey->value;
                                $stepThirdFinalDocuments[$document->document_type]['value'][$tempDocumentKey->key]['label'] = $label;
                                $stepThirdFinalDocuments[$document->document_type]['value'][$tempDocumentKey->key]['type'] = $type;
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
                    }
                    $data['tempCoapplicant'] = TempCoapplicant::where('model_name', $decodedModel)->where('model_id', $id)->get();
                    break;

                    //land use change
                case 'TempLandUseChangeApplication':
                    $data['appliedFor'] = 'LUC';
                    $data['applicationDocumentType'] = config('applicationDocumentType.LUC.Required');
                    if (!empty($documents)) {
                        foreach ($documents as $document) {
                            $stepSecondFinalDocuments[$document->document_type]['file_path'] = $document->file_path;
                            $tempDocumentKeys = TempDocumentKey::where('temp_document_id', $document->id)->get();
                            foreach ($tempDocumentKeys as $tempDocumentKey) {
                                $stepSecondFinalDocuments[$documents->document_type]['value'][$tempDocumentKey->key] = $tempDocumentKey->value;
                            }
                        }
                    }

                    // $tempCoapplicant = TempCoapplicant::where('model_name', $decodedModel)->where('model_id', $id)->get();
                case 'TempDeedOfApartment':
                    // $data['appliedFor'] = 'DOA';
                    // $data['applicationDocumentType'] = config('applicationDocumentType.DOA.Required');
                    // if (!empty($documents)) {
                    //     foreach ($documents as $document) {
                    //         $stepSecondFinalDocuments[$document->document_type]['file_path'] = $document->file_path;
                    //         $tempDocumentKeys = TempDocumentKey::where('temp_document_id', $document->id)->get();
                    //         foreach ($tempDocumentKeys as $tempDocumentKey) {
                    //             $stepSecondFinalDocuments[$documents->document_type]['value'][$tempDocumentKey->key] = $tempDocumentKey->value;
                    //         }
                    //     }
                    // }
                    //second step douments ***********************************88
                    $stepSecondFilters = config('applicationDocumentType.DOA.Required');
                    $topLevelKeys = array_keys($stepSecondFilters);
                    $stepSecondFilteredDocuments = $documents->filter(function ($document) use ($topLevelKeys) {
                        return in_array($document->title, $topLevelKeys);
                    });
                    foreach ($stepSecondFilteredDocuments as $document) {
                        $stepSecondFinalDocuments[$document->document_type]['file_path'] = $document->file_path;
                    }

                    foreach ($stepSecondFilters as $documentType => $fields) {
                        // Check if the document type exists in the final documents
                        if (!isset($stepSecondFinalDocuments[$documentType])) {
                            // Initialize the document type if it doesn't exist
                            $stepSecondFinalDocuments[$documentType] = [
                                'file_path' => null, // Set file_path to null
                            ];
                        }
                    }
                default:
                    // Default action
                    break;
            }
            $data['stepSecondFinalDocuments'] = $stepSecondFinalDocuments;
            $data['stepThirdFinalDocuments'] = $stepThirdFinalDocuments;
            $data['applicantStatus'] = $misService->getItemsByGroupId(1002);
            $data['colonyList'] = $colonyService->getColonyList();
            $data['propertyTypes'] = $misService->getItemsByGroupId(1052);
            $data['documentTypes'] = getItemsByGroupId(17005);
            $lcm = new LandRateService();
            $data['applicantTypes'] = $lcm->getApplicantTyps();

            return view('applicant.new_application', $data);
        } else {
            return redirect()->route('draftApplications')->with('failure', 'Application not available!');
        }
    }


    public function applicationsHistoryDetails()
    {
        return view('application.history.index');
    }

    //for fetching the applications which are submitted successfully - - SOURAV CHAUHAN (4/oct/2024)
    public function getHistoryApplications(Request $request)
    {
        // Define the columns that can be ordered and searched
        $columns = ['id', 'old_property_id'];

        // Start query
        $query2 = DB::table('land_use_change_applications as lca')
            ->leftJoin('property_masters', 'lca.property_master_id', '=', 'property_masters.id')
            ->leftJoin('old_colonies', 'property_masters.new_colony_name', '=', 'old_colonies.id')
            ->leftJoin('property_lease_details', 'property_masters.id', '=', 'property_lease_details.property_master_id')
            ->where('lca.created_by', '=', Auth::id())
            ->where('lca.status', '!=', getServiceType('APP_WD'))
            ->select(
                'lca.id',
                'lca.created_at',
                DB::raw('coalesce(lca.application_no,"0") as application_no'),
                'lca.status',
                'property_masters.old_propert_id',
                'property_masters.new_colony_name',
                'old_colonies.name as colony_name',
                'property_masters.block_no',
                'property_masters.plot_or_property_no',
                'property_lease_details.presently_known_as',
                DB::raw("'LandUseChangeApplication' as model_name") // Add model_name for the first query
            );

        // Define the first table query
        $query1 = DB::table('mutation_applications as ma')
            ->leftJoin('property_masters', 'ma.property_master_id', '=', 'property_masters.id')
            ->leftJoin('old_colonies', 'property_masters.new_colony_name', '=', 'old_colonies.id')
            ->leftJoin('property_lease_details', 'property_masters.id', '=', 'property_lease_details.property_master_id')
            ->where('ma.created_by', '=', Auth::id())
            ->where('ma.status', '!=', getServiceType('APP_WD'))
            ->select(
                'ma.id',
                'ma.created_at',
                'ma.application_no',
                'ma.status',
                'property_masters.old_propert_id',
                'property_masters.new_colony_name',
                'old_colonies.name as colony_name',
                'property_masters.block_no',
                'property_masters.plot_or_property_no',
                'property_lease_details.presently_known_as',
                DB::raw("'MutationApplication' as model_name") // Add model_name for the first query
            );

        //Deed Of Apartment
        $query3 = DB::table('deed_of_apartment_applications as doa')
            ->leftJoin('property_masters', 'doa.property_master_id', '=', 'property_masters.id')
            ->leftJoin('old_colonies', 'property_masters.new_colony_name', '=', 'old_colonies.id')
            ->leftJoin('property_lease_details', 'property_masters.id', '=', 'property_lease_details.property_master_id')
            ->where('doa.created_by', '=', Auth::id())
            ->where('doa.status', '!=', getServiceType('APP_WD'))
            ->select(
                'doa.id',
                'doa.created_at',
                'doa.application_no',
                'doa.status',
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

        // Execute the combined query
        // $query = $combinedQuery->get();

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
        // $order = $columns[$orderColumnIndex] ?? 'id'; // Default order by 'id' if index is invalid
        // $dir = $request->input('order.0.dir');

        // Use raw SQL to sort by concatenated columns
        // $query->orderBy($order, $dir);

        // Apply ordering and limit/offset
        $applications = $combinedQuery->offset($start)
            ->limit($limit)
            ->get();
        $data = [];
        foreach ($applications as $key => $application) {
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
            $nestedData['status'] = '<div class="badge rounded-pill ' . $class . ' p-2 text-uppercase px-3">' . ucwords($itemName) . '</div>';
            $model = base64_encode($application->model_name);

            // Prepare actions
            $action = '<button type="button" class="btn btn-danger px-5" onclick="withdrawApplication(\'' . $application->application_no . '\')">Withdraw Application</button>';
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


    //for withdraw the applications - - SOURAV CHAUHAN (7/oct/2024)
    public function withdrawApplication(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $applicationNo = $request->applicationNo;
                $status = getServiceType('APP_WD');

                //withdraw in applications table
                $application = Application::where('application_no', $applicationNo)->first();
                if ($application->status == getServiceType('APP_NEW')) {
                    $application->status = $status;
                    if ($application->save()) {
                        $modelName = $application->model_name;
                        $modelId = $application->model_id;
                        switch ($modelName) {
                            case 'MutationApplication':
                                $serviceType = getServiceType('SUB_MUT');
                                $mutationApplication = MutationApplication::find($modelId);
                                $mutationApplication->status = $status;
                                $mutationApplication->save();
                                break;
                            case 'DeedOfApartmentApplication':
                                $serviceType = getServiceType('DOA');
                                $mutationApplication = DeedOfApartmentApplication::find($modelId);
                                $mutationApplication->status = $status;
                                $mutationApplication->save();
                                break;
                            case 'LandUseChangeApplication':
                                $serviceType = getServiceType('LUC');
                                $lucApplication = LandUseChangeApplication::find($modelId);
                                $lucApplication->status = $status;
                                $lucApplication->save();
                                break;
                            default:
                                break;
                        }
                        //entry to application movement for withdraw
                        $applicationMovement = ApplicationMovement::create([
                            // 'assigned_by' => Auth::user()->id,
                            'service_type' => $serviceType, //for mutation,LUC,DOA etc
                            'model_id' => $modelId,
                            'status' => getServiceType('APP_WD'), //for new application, objected application, rejected, approved etc
                            'application_no' => $applicationNo,
                        ]);

                        if ($applicationMovement) {
                            $response = ['status' => true, 'message' => 'Application Withdrawn Successfully'];
                        } else {
                            Log::info("| " . Auth::user()->email . " | issue in saving to application movement table");
                            $response = ['status' => false, 'message' => 'Application not Withdrawn'];
                        }
                    } else {
                        Log::info("| " . Auth::user()->email . " | issue in saving to applications table");
                        $response = ['status' => false, 'message' => 'Application not Withdrawn'];
                    }
                } else {
                    Log::info("| " . Auth::user()->email . " | Appication is withdrawn or any other process is done on it, so cant withdraw");
                    $response = ['status' => false, 'message' => "Some process is running on Application, so can't be Withdrawn"];
                }
                return json_encode($response);
            });
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            $response = ['status' => false, 'message' => $e->getMessage()];
            return json_encode($response);
        }
    }


    //for withdraw applications view - SOURAV CHAUHAN (8/oct/2024)
    public function applicationsWithdrawDetails()
    {
        return view('application.withdraw.index');
    }

    //for fetching the applications which are withdraw successfully - SOURAV CHAUHAN (8/oct/2024)
    public function getWithdrawApplications(Request $request)
    {
        // Define the columns that can be ordered and searched
        $columns = ['id', 'old_property_id'];

        // Start query
        $query2 = DB::table('land_use_change_applications as lca')
            ->leftJoin('property_masters', 'lca.property_master_id', '=', 'property_masters.id')
            ->leftJoin('old_colonies', 'property_masters.new_colony_name', '=', 'old_colonies.id')
            ->leftJoin('property_lease_details', 'property_masters.id', '=', 'property_lease_details.property_master_id')
            ->where('lca.created_by', '=', Auth::id())
            ->where('lca.status', '=', getServiceType('APP_WD'))
            ->select(
                'lca.id',
                'lca.created_at',
                'lca.application_no',
                'property_masters.old_propert_id',
                'property_masters.new_colony_name',
                'old_colonies.name as colony_name',
                'property_masters.block_no',
                'property_masters.plot_or_property_no',
                'property_lease_details.presently_known_as',
                DB::raw("'LandUseChangeApplication' as model_name") // Add model_name for the first query
            );

        // Define the first table query
        $query1 = DB::table('mutation_applications as ma')
            ->leftJoin('property_masters', 'ma.property_master_id', '=', 'property_masters.id')
            ->leftJoin('old_colonies', 'property_masters.new_colony_name', '=', 'old_colonies.id')
            ->leftJoin('property_lease_details', 'property_masters.id', '=', 'property_lease_details.property_master_id')
            ->where('ma.created_by', '=', Auth::id())
            ->where('ma.status', '=', getServiceType('APP_WD'))
            ->select(
                'ma.id',
                'ma.created_at',
                'ma.application_no',
                'property_masters.old_propert_id',
                'property_masters.new_colony_name',
                'old_colonies.name as colony_name',
                'property_masters.block_no',
                'property_masters.plot_or_property_no',
                'property_lease_details.presently_known_as',
                DB::raw("'MutationApplication' as model_name") // Add model_name for the first query
            );

        // Define the third table query
        $query3 = DB::table('deed_of_apartment_applications as doa')
            ->leftJoin('property_masters', 'doa.property_master_id', '=', 'property_masters.id')
            ->leftJoin('old_colonies', 'property_masters.new_colony_name', '=', 'old_colonies.id')
            ->leftJoin('property_lease_details', 'property_masters.id', '=', 'property_lease_details.property_master_id')
            ->where('doa.created_by', '=', Auth::id())
            ->where('doa.status', '=', getServiceType('APP_WD'))
            ->select(
                'doa.id',
                'doa.created_at',
                'doa.application_no',
                'property_masters.old_propert_id',
                'property_masters.new_colony_name',
                'old_colonies.name as colony_name',
                'property_masters.block_no',
                'property_masters.plot_or_property_no',
                'property_lease_details.presently_known_as',
                DB::raw("'DeedOfApartmentApplication' as model_name") // Add model_name for the first query
            );

        // Define the second table query
        // $query2 = DB::table('another_table as at') // Replace 'another_table' with your actual table name
        // ->leftJoin('property_masters', 'at.property_master_id', '=', 'property_masters.id')
        // ->leftJoin('old_colonies', 'property_masters.new_colony_name', '=', 'old_colonies.id')
        // ->leftJoin('property_lease_details', 'property_masters.id', '=', 'property_lease_details.property_master_id')
        // ->where('at.created_by', '=', Auth::id())
        // ->select(
        //     'at.id', // Ensure this is compatible with the first query
        //     'property_masters.old_propert_id',
        //     'property_masters.new_colony_name',
        //     'old_colonies.name as colony_name',
        //     'property_masters.block_no',
        //     'property_masters.plot_or_property_no',
        //     'property_lease_details.presently_known_as',
        //     DB::raw("'AnotherTable' as model_name") // Add model_name for the second query
        // );

        // Combine all three queries using UNION
        // $combinedQuery = $query1;
        $combinedQuery = $query1->union($query2)->union($query3);

        // Execute the combined query
        // $query = $combinedQuery->get();

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
        // $order = $columns[$orderColumnIndex] ?? 'id'; // Default order by 'id' if index is invalid
        // $dir = $request->input('order.0.dir');

        // Use raw SQL to sort by concatenated columns
        // $query->orderBy($order, $dir);

        // Apply ordering and limit/offset
        $applications = $combinedQuery->offset($start)
            ->limit($limit)
            ->get();

        $data = [];
        // dd($applications);
        foreach ($applications as $key => $application) {
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
                case 'DeedOfApartmentApplication':
                    $appliedFor = 'DOA';
                    break;
                case 'LandUseChangeApplication':
                    $appliedFor = 'LUC';
                    break;
                default:
                    // Default action
                    break;
            }
            $nestedData['applied_for'] = '<label class="badge bg-info mx-1">' . $appliedFor . '</label>';
            $model = base64_encode($application->model_name);

            // Prepare actions
            // $action = '<button type="button" class="btn btn-danger px-5" onclick="withdrawApplication(\'' . $application->application_no . '\')">Withdraw Application</button>';
            // $nestedData['action'] = $action;
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
    //for deleting application  - SOURAV CHAUHAN -- moved to ApplicationController from MutationController and modified by Nitin on 08-10-2024
    public function deleteApplication(Request $request)
    {
        // dd($request->all());
        $transactionSuccess = false;
        DB::transaction(function () use ($request, &$transactionSuccess) {
            $applicationId = $request->modalId;
            if (isset($request->modalName)) {
                $tempModelName = $request->modalName;
                $model = '\\App\\Models\\' . $tempModelName;
                $instance = new $model();
                $serviceType = getServiceType($instance->serviceType->item_code);
            } else {
                $applicationType = $request->applicationType;
                $keyInConfig = $applicationType;
                if ($applicationType == 'SUB_MUT') {
                    $keyInConfig = 'MUTATION';
                }
                $tempModelName = config('applicationDocumentType.' . $keyInConfig . '.TempModelName');
                $serviceType = getServiceType($applicationType);
            }

            // Delete application
            $instance = new GeneralFunctions();
            $deleted = $instance->deleteApplicationAllTempData($tempModelName, $applicationId, $serviceType);
            if ($deleted) {
                $transactionSuccess = true;
            }
        });

        // Determine the response based on the transaction success
        if ($transactionSuccess) {
            $response = ['status' => true, 'message' => 'Data deleted successfully'];
        } else {
            $response = ['status' => false, 'message' => 'Something went wrong!'];
        }
        return json_encode($response);
    }
}
