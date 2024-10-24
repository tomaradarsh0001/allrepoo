<?php

namespace App\Http\Controllers;

use App\Helpers\UserActionLogHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MisService;
use App\Services\ColonyService;
use App\Services\MisMultiplePropertyService;
use App\Models\PropertyMaster;
use Illuminate\Support\Facades\Log;
use App\Models\Item;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\SplitedPropertyDetail;
use App\Models\UserActionLog;
use App\Models\PropertyLeaseDetail;
use App\Models\PropertyTransferredLesseeDetail;
use App\Models\PropertyInspectionDemandDetail;
use App\Models\PropertyMiscDetail;
use App\Models\PropertyContactDetail;
use App\Models\CurrentLesseeDetail;
use App\Models\PropertyMasterHistory;
use App\Models\PropertyLeaseDetailHistory;
use App\Models\PropertyTransferLesseeDetailHistory;
use App\Models\PropInspDemandDetailHistory;
use App\Models\PropertyContactDetailsHistory;
use App\Models\PropertyMiscDetailHistory;
use App\Models\SplitedPropertyDetailHistory;
use App\Models\ApplicationStatus;
use App\Models\Flat;
use App\Models\NewlyAddedProperty;
use App\Models\SectionMisHistory;
use App\Models\UserRegistration;
use DB;
use Auth;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class MisController extends Controller
{
    public function index(MisService $misService, ColonyService $colonyService)
    {

        $colonyList = $colonyService->getColonyList();
        $propertyStatus = $misService->getItemsByGroupId(109);
        $landTypes = $misService->getItemsByGroupId(1051);
        $leaseTypes = $misService->getItemsByGroupId(102);
        $propertyTypes = $misService->getItemsByGroupId(1052);
        $landTransferTypes = $misService->getItemsByGroupId(1057);
        $areaUnit = $misService->getItemsByGroupId(1008);

        return view('mis', compact(['colonyList', 'propertyStatus', 'landTypes', 'leaseTypes', 'propertyTypes', 'landTransferTypes', 'areaUnit']));
    }

    public function misFormMultiple(MisService $misService, ColonyService $colonyService)
    {

        $colonyList = $colonyService->getColonyList();
        $propertyStatus = $misService->getItemsByGroupId(109);
        $landTypes = $misService->getItemsByGroupId(1051);
        $leaseTypes = $misService->getItemsByGroupId(102);
        $propertyTypes = $misService->getItemsByGroupId(1052);
        $landTransferTypes = $misService->getItemsByGroupId(1057);
        $areaUnit = $misService->getItemsByGroupId(1008);

        return view('mis.multiple-property', compact(['colonyList', 'propertyStatus', 'landTypes', 'leaseTypes', 'propertyTypes', 'landTransferTypes', 'areaUnit']));
    }

    public function prpertySubTypes(Request $request, MisService $misService)
    {
        $subTypes = $misService->getRelatedSubTypes($request);
        return response()->json($subTypes);
    }

    //store the MIS Form data
    public function store(Request $request, MisService $misService)
    {

        //validation rules
        $rules = [
            'property_id' => 'unique:property_masters,old_propert_id|unique:splited_property_details,old_property_id',
            'file_number' => 'required',
            'present_colony_name' => 'required',
            'old_colony_name' => 'required',
            'property_status' => 'required',
            'land_type' => 'required',
            // 'transferred' => 'required',//as its not required 18 april 2024
            'address' => 'required',
            'GR' => 'required',
            'Supplementary' => 'required',
            'Reentered' => 'required'
        ];


        //Validation msssages
        $messages = [
            'property_id' => 'Property Id already saved earlier',
            'file_number' => 'File Number is required',
            // 'transferred.required' => 'Please specify property is transferred or not',
            'address' => 'Address is required',
            'GR' => 'Please specify GR ever revised or not',
            'Supplementary' => 'Please specify supplementary lease deed executed or not',
            'Reentered' => 'Please specify property re-entered or not',
        ];


        //Validation
        $validated = $request->validate($rules, $messages);

        try {
            $response = $misService->storeMisData($request);

            if ($response) {
                // Transaction was successful
                return redirect()->back()->with('success', 'Property details saved successfully.');
            } else if ($response == false) {
                // Transaction failed
                return redirect()->back()->with('failure', 'Property details not saved');
            } else {
                return redirect()->back()->with('failure', $response);
            }
            //dd($response);

        } catch (\Exception $e) {
            Log::info($e);
            return redirect()->back()->with('failure', $e->getMessage());
        }
    }

    public function misStoreMultiple(Request $request, MisMultiplePropertyService $misMultiplePropertyService)
    {
        //validation rules
        $rules = [
            'property_id' => 'unique:property_masters,old_propert_id|unique:splited_property_details,old_property_id',
            'file_number' => 'required',
            'present_colony_name' => 'required',
            'old_colony_name' => 'required',
            'property_status' => 'required',
            'land_type' => 'required',
        ];


        //Validation msssages
        $messages = [
            'property_id' => 'Property Id already saved earlier',
            'file_number' => 'File Number is required',
        ];


        //Validation
        $validated = $request->validate($rules, $messages);

        try {
            $response = $misMultiplePropertyService->storeMisMultipleData($request);

            if ($response) {
                // Transaction was successful
                return redirect()->back()->with('success', 'Property details saved successfully.');
            } else if ($response == false) {
                // Transaction failed
                return redirect()->back()->with('failure', 'Property details not saved');
            } else {
                return redirect()->back()->with('failure', $response);
            }
            //dd($response);

        } catch (\Exception $e) {
            Log::info($e);
            return redirect()->back()->with('failure', $e->getMessage());
        }
    }


    // public function propertDetails(MisService $misService)
    // {
    //     $propertyDetails = $misService->propertDetails();
    //     $item = new Item();
    //     $user = new User();
    //     return view('mis.details', compact(['propertyDetails', 'item', 'user']));
    // }
    public function propertDetails(Request $request, MisService $misService, ColonyService $colonyService)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $colonyList = $colonyService->misDoneForColonies();
        if ($user->can('view.all.details')) {
            // $misData = PropertyMaster::latest()->get();
            $dataWithPagination = PropertyMaster::query()->latest()->paginate(20);
        } else {
            // $misData = PropertyMaster::where('created_by', $userId)->latest()->get();
            $dataWithPagination = PropertyMaster::where('created_by', $userId)->latest()->paginate(20);
        }

        $item = new Item();
        if ($request->ajax()) {
            if ($user->can('view.all.details')) {
                $dataWithPagination = PropertyMaster::query()
                    ->when($request->search_term, function ($q) use ($request) {
                        $q->where('old_propert_id', 'like', '%' . $request->search_term . '%')
                            ->orWhere('unique_propert_id', 'like', '%' . $request->search_term . '%')
                            ->orWhereHas('splitedPropertyDetail', function ($query) use ($request) {
                                $query->where('old_property_id', 'like', '%' . $request->search_term . '%');
                            });
                    })
                    ->when($request->date && $request->dateEnd, function ($q) use ($request) {
                        $q->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime($request->date)))
                            ->where('created_at', '<=',  date('Y-m-d 23:59:00', strtotime($request->dateEnd)));
                        // dd($q->toSql(),$q->getBindings());

                    })
                    ->when($request->date && $request->dateEnd == null, function ($q) use ($request) {
                        $q->whereDate('created_at', $request->date);
                    })
                    ->latest()
                    ->paginate(20);
            } else {
                $dataWithPagination = PropertyMaster::query()
                    ->when($request->seach_term, function ($q) use ($request) {
                        $q->where('old_propert_id', 'like', '%' . $request->seach_term . '%')
                            ->orWhere('unique_propert_id', 'like', '%' . $request->seach_term . '%');
                    })
                    ->when($request->date && $request->dateEnd, function ($q) use ($request) {
                        $q->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime($request->date)))
                            ->where('created_at', '<=',  date('Y-m-d 23:59:00', strtotime($request->dateEnd)));
                    })
                    ->when($request->date, function ($q) use ($request) {
                        $q->where('created_at', 'like', '%' . $request->date . '%');
                    })
                    ->where('created_by', $userId)->latest()->paginate(20);
            }

            // Manage user search report action activity lalit on 22/07/24
            if (isset($request->seach_term) && isset($request->date) && isset($request->dateEnd)) {
                $action_link = '<a href="' . url("/property-details/" . $request->seach_term . "/view") . '" target="_blank">' . $request->seach_term . '</a>';
                UserActionLogHelper::UserActionLog('search', url("/property-details/" . $request->seach_term . "/view"), 'searchProperty', "Property " . $action_link . " has been searched by " . Auth::user()->name . ".");
            } else if (isset($request->date) && isset($request->dateEnd)) {
                UserActionLogHelper::UserActionLog('search', url("/property-details"), 'searchProperty', "Property from " . date('Y-m-d', strtotime($request->date)) . " To " . date('Y-m-d', strtotime($request->dateEnd)) . " has been searched by " . Auth::user()->name . ".");
            } else if (isset($request->seach_term)) {
                $action_link = '<a href="' . url("/property-details/" . $request->seach_term . "/view") . '" target="_blank">' . $request->seach_term . '</a>';
                UserActionLogHelper::UserActionLog('search', url("/property-details/" . $request->seach_term . "/view"), 'searchProperty', "Property " . $action_link . " has been searched by " . Auth::user()->name . ".");
            }
            return view('mis.pagination_child', compact(['item', 'user', 'dataWithPagination', 'colonyList']))->render();
        }
        return view('mis.details', compact(['item', 'user', 'dataWithPagination', 'colonyList']));
    }


    public function propertyChildDetails($id, MisService $misService)
    {
        $item = new Item();
        $propertyChildDetails = $misService->propertyChildDetails($id);
        $viewDetails = $propertyChildDetails['ParentData'];
        $childData = $propertyChildDetails['childData'];
        $separatedData = [];

        // added for showing same precess seperatly if eecuted on different dates;
        $separatedData = self::getSeparatedPropertyTransferDetails($childData->propertyTransferredLesseeDetails);
        // dd($separatedData);


        return view('mis.child-preview', compact(['viewDetails', 'item', 'separatedData', 'childData']));
    }



    //for single property full details page
    public function viewDetails($property, MisService $misService, Request $request)
    {
        // Retrieve the user's roles
        $roles = Auth::user()->roles[0]->name;
        $additionalDataJson = $request->query('params');
        $isChecked = 0;
        $additionalData =  $flatData['flatDetails'] = [];
        $disableButtons = false;
        $disableApproveButtons = $hideRequestEditButtons =  true;
       
        if (isset($additionalDataJson)) {
            $additionalData = json_decode($additionalDataJson, true);
            $serviceType = getServiceType($additionalData[0]);
            //Get Flat Details - Lalit Tiwari (15/Oct/2024)
            if(!empty($additionalData[2])){
                $uRegData = UserRegistration::where('applicant_number', $additionalData[2])->first();
                if(!empty($uRegData->flat_id)){
                    $flatDetails = Flat::find($uRegData->flat_id);
                    $flatData['flatDetails'] = $flatDetails;
                } else {
                    if(!empty($uRegData->flat_no)){
                        $disableButtons = true;
                        $hideRequestEditButtons = false;
                    }
                    $flatData['flatDetails']['flat_number'] = $uRegData->flat_no;
                }
            }
            $isChecked = 1;
            $applicationStatus = SectionMisHistory::where('service_type', $serviceType)
                ->where('model_id', $additionalData[1])
                ->where('property_master_id', $property)
                ->orderBy('id', 'desc')
                ->first();
            if ($applicationStatus) {
                //Lalit (18/09/2024) :- Check if Edit Request is active, so disable approve button
                $checkEditRequestexists = SectionMisHistory::where([['section_code', $applicationStatus->section_code], ['old_property_id', $applicationStatus->old_property_id], ['is_active', $applicationStatus->is_active]])->exists();
                if ($checkEditRequestexists) {
                    $disableApproveButtons = false;
                }

                if($additionalData[0] === 'RS_NEW_REG'){
                    //Lalit (18/09/2024) :- Check if User Registration is approved, so hide request edit button
                    $checkRegistrationStatus = UserRegistration::with('item')->where('id', $applicationStatus->model_id)->first();
                    if ($checkRegistrationStatus && $checkRegistrationStatus->item->item_code == 'RS_APP') {
                        $hideRequestEditButtons = false;
                    }
                }
                
                if($additionalData[0] === 'RS_NEW_PRO'){
                     //Lalit (03/10/2024) :- Check if User Registration is approved, so hide request edit button
                    $checkNewPropertyStatus = NewlyAddedProperty::with('item')->where('id', $applicationStatus->model_id)->first();
                    if ($checkNewPropertyStatus && $checkNewPropertyStatus->item->item_code == 'RS_APP') {
                        $hideRequestEditButtons = false;
                    }
                }
               
                
                if ($applicationStatus->is_active == 1) {

                    $permissionTo = User::find($applicationStatus->permission_to);
                    $permissionTosection = $permissionTo->sections;

                    $loginUser = User::find(Auth::user()->id);
                    $loginUsersection = $loginUser->sections;

                    $permissionTosectionCodes = $permissionTosection->pluck('section_code')->toArray();
                    $loginUsersectionCodes = $loginUsersection->pluck('section_code')->toArray();

                    $commonSectionCodes = array_intersect($permissionTosectionCodes, $loginUsersectionCodes);

                    if (!empty($commonSectionCodes)) {
                        $disableButtons = false;
                    } else {
                        $disableButtons = true;
                    }
                } else {

                    $disableButtons = true;
                }
            }
        } else {
            $applicationStatus = '';
        }

        $item = new Item();
        $viewDetails = $misService->viewDetails($property);
        $separatedData = [];
        $separatedData = self::getSeparatedPropertyTransferDetails($viewDetails->propertyTransferredLesseeDetails->where('splited_property_detail_id', null));
        // dd($flatData['flatDetails']);
        // dd($flatData['flatDetails']['flat_number']);
        return view('mis.preview', compact(['viewDetails', 'item', 'separatedData', 'isChecked', 'additionalData', 'disableButtons', 'applicationStatus', 'disableApproveButtons','hideRequestEditButtons','flatData','roles']));
    }

    public function editDetails(Request $request, $id, MisService $misService, ColonyService $colonyService)
    {
        //Added by Lalit on 17/09/2024 Get Additional data from url as query params to get inserted into application_status & section_mis_histories
        $additionalDataJson = $request->query('params');
        if (isset($additionalDataJson)) {
            $additionalData = json_decode($additionalDataJson, true);
        }
        $propertyDetail = $misService->viewDetails($id);
        $separatedData = [];

        $separatedData = self::getSeparatedPropertyTransferDetails($propertyDetail->propertyTransferredLesseeDetails->where('splited_property_detail_id', null), true);
        //Lease Details
        $propertyLeaseDetail = $propertyDetail->propertyLeaseDetail;
        //dd($propertyLeaseDetail);

        //Tranfer Lessee Details
        if (isset($separatedData['Original'])) {
            $original = $separatedData['Original'];
        } else {
            $original = [];
        }

        //dd($original);
        if (isset($separatedData['Conversion'])) {
            $conversion = $separatedData['Conversion'];
        } else {
            $conversion = [];
        }

        $keysToRemove = ['Original', 'Conversion'];
        $filteredTransferDetails = collect($separatedData)->except($keysToRemove)->toArray();
        //dd($filteredTransferDetails);

        $colonyList = $colonyService->getColonyList();
        $propertyStatus = $misService->getItemsByGroupId(109);
        $landTypes = $misService->getItemsByGroupId(1051);
        $leaseTypes = $misService->getItemsByGroupId(102);
        $propertyTypes = $misService->getItemsByGroupId(1052);
        $landTransferTypes = $misService->getItemsByGroupId(1057);
        $areaUnit = $misService->getItemsByGroupId(1008);

        //SubTypes Old
        $propertytypeSubtpeMapping = DB::table('property_type_sub_type_mapping')->where('type', $propertyLeaseDetail->property_type_as_per_lease)->get();
        $subTypeIds = [];
        foreach ($propertytypeSubtpeMapping as $data) {
            $subTypeId = $data->sub_type;
            $subTypeIds[] = $subTypeId;
        }
        $subTypes = Item::whereIn('id', $subTypeIds)->get();


        //SubTypes Old if land transfered
        $subTypesNew = '';
        if ($propertyLeaseDetail->is_land_use_changed) {
            $propertytypeSubtpeMappingNew = DB::table('property_type_sub_type_mapping')->where('type', $propertyLeaseDetail->property_type_at_present)->get();
            $subTypeIdsNew = [];
            foreach ($propertytypeSubtpeMappingNew as $dataNew) {
                $subTypeId = $dataNew->sub_type;
                $subTypeIdsNew[] = $subTypeId;
            }
            $subTypesNew = Item::whereIn('id', $subTypeIdsNew)->get();
        }

        //Property Inspection and Demand Details
        $propertyInspectionDemandDetail = $propertyDetail->propertyInspectionDemandDetail;

        //Property Misc Details
        $propertyMiscDetail = $propertyDetail->propertyMiscDetail;

        //Property Contact Details
        $propertyContactDetail = $propertyDetail->propertyContactDetail;

        return view('mis.edit', compact(['colonyList', 'propertyStatus', 'landTypes', 'leaseTypes', 'propertyTypes', 'landTransferTypes', 'areaUnit', 'propertyDetail', 'original', 'conversion', 'propertyLeaseDetail', 'subTypes', 'subTypesNew', 'filteredTransferDetails', 'propertyInspectionDemandDetail', 'propertyMiscDetail', 'propertyContactDetail', 'additionalData']));
    }

    public function editChildDetails($id, MisService $misService, ColonyService $colonyService)
    {
        $childDetails = SplitedPropertyDetail::where('id', $id)->first();
        $parentId = $childDetails['property_master_id'];
        $propertyDetail = $misService->viewDetails($parentId);
        $separatedData = [];

        //comented by sourav - 29/july/2024
        // foreach ($childDetails->propertyTransferredLesseeDetails as $transferDetail) {
        //     $processOfTransfer = $transferDetail->process_of_transfer;

        //     // Check if the process_of_transfer value is already a key in $separatedData
        //     if (!array_key_exists($processOfTransfer, $separatedData)) {
        //         // If not, create a new array for this process_of_transfer value
        //         $separatedData[$processOfTransfer] = [];
        //     }

        //     // Add the current $transferDetail to the corresponding array in $separatedData
        //     $separatedData[$processOfTransfer][] = $transferDetail;
        // }

        //added by sourav - 29/july/2024
        $separatedData = self::getSeparatedPropertyTransferDetails($childDetails->propertyTransferredLesseeDetails, true);

        //Lease Details
        $propertyLeaseDetail = $propertyDetail->propertyLeaseDetail;
        //dd($propertyLeaseDetail);
        //Tranfer Lessee Details
        if (isset($separatedData['Original'])) {
            $original = $separatedData['Original'];
        } else {
            $original = [];
        }

        //dd($original);
        if (isset($separatedData['Conversion'])) {
            $conversion = $separatedData['Conversion'];
        } else {
            $conversion = [];
        }


        $keysToRemove = ['Original', 'Conversion'];
        $filteredTransferDetails = collect($separatedData)->except($keysToRemove)->toArray();


        $colonyList = $colonyService->getColonyList();
        $propertyStatus = $misService->getItemsByGroupId(109);
        $landTypes = $misService->getItemsByGroupId(1051);
        $leaseTypes = $misService->getItemsByGroupId(102);
        $propertyTypes = $misService->getItemsByGroupId(1052);
        $landTransferTypes = $misService->getItemsByGroupId(1057);
        $areaUnit = $misService->getItemsByGroupId(1008);

        //SubTypes Old
        $propertytypeSubtpeMapping = DB::table('property_type_sub_type_mapping')->where('type', $propertyLeaseDetail->property_type_as_per_lease)->get();
        $subTypeIds = [];
        foreach ($propertytypeSubtpeMapping as $data) {
            $subTypeId = $data->sub_type;
            $subTypeIds[] = $subTypeId;
        }
        $subTypes = Item::whereIn('id', $subTypeIds)->get();


        //SubTypes Old if land transfered
        $subTypesNew = '';
        if ($propertyLeaseDetail->is_land_use_changed) {
            $propertytypeSubtpeMappingNew = DB::table('property_type_sub_type_mapping')->where('type', $propertyLeaseDetail->property_type_at_present)->get();
            $subTypeIdsNew = [];
            foreach ($propertytypeSubtpeMappingNew as $dataNew) {
                $subTypeId = $dataNew->sub_type;
                $subTypeIdsNew[] = $subTypeId;
            }
            $subTypesNew = Item::whereIn('id', $subTypeIdsNew)->get();
        }

        //Property Inspection and Demand Details
        $propertyInspectionDemandDetail = $childDetails->propertyInspectionDemandDetail;

        //Property Misc Details
        $propertyMiscDetail = $childDetails->propertyMiscDetail;

        //Property Contact Details
        $propertyContactDetail = $propertyDetail->propertyContactDetail;
        $childContactDetail = $childDetails->propertyContactDetail;


        return view('mis.edit-multiple', compact(['colonyList', 'propertyStatus', 'landTypes', 'leaseTypes', 'propertyTypes', 'landTransferTypes', 'areaUnit', 'propertyDetail', 'original', 'conversion', 'propertyLeaseDetail', 'subTypes', 'subTypesNew', 'filteredTransferDetails', 'propertyInspectionDemandDetail', 'propertyMiscDetail', 'propertyContactDetail', 'childContactDetail', 'childDetails']));
    }
    public function update($id, Request $request, MisService $misService)
    {
        $response = $misService->update($id, $request);
        if ($response) {
            // Transaction was successful
            return redirect()->back()->with('success', 'Property details Updated successfully.');
        } else if ($response == false) {
            // Transaction failed
            return redirect()->back()->with('failure', 'Property details not updated');
        } else {
            return redirect()->back()->with('failure', $response);
        }
    }

    // Delete Leasee details in favour of
    public function destroyOriginalById($id, Request $request, MisService $misService)
    {
        if (!empty($id)) {
            $result = $misService->delete($id, $request);
            if ($result) {
                $response = ['status' => true, 'message' => 'Original lease details ' . $id . ' successfully in-activated.'];
            } else {
                $response = ['status' => false, 'message' => 'Original lease details ID is wrong.', 'data' => NULL];
            }
            return json_encode($response);
        }
    }

    // Delete Land transfer through batch id
    public function destroyLandTransferByBatchId($batchTransferId, $propertyMasterId, Request $request, MisService $misService)
    {
        if (!empty($batchTransferId) && !empty($propertyMasterId)) {

            $result = $misService->deleteLandTransferByBatchId($batchTransferId, $propertyMasterId, $request);
            if ($result) {
                $response = ['status' => true, 'message' => 'Land Transfer lease details ' . $batchTransferId . ' successfully in-activated.'];
            } else {
                $response = ['status' => false, 'message' => 'Land Transfer details ID is wrong.', 'data' => NULL];
            }
            return json_encode($response);
        }
    }

    // Delete Land transfer through unique id
    public function destroyLandTransferByIndividualId($landTransferId, $batchTransferId, $propertyMasterId, Request $request, MisService $misService)
    {
        if (!empty($landTransferId) && !empty($batchTransferId) && !empty($propertyMasterId)) {

            $result = $misService->delete($landTransferId, $request);
            if ($result) {
                // //Check if more record exist with same batch id
                $isExist = $misService->checkMoreRecordExistForBatchId($batchTransferId, $propertyMasterId, $request);
                if ($isExist) {
                    $response = ['status' => true, 'message' => 'Land Transfer lease details ' . $batchTransferId . ' successfully in-activated.', 'data' => 'exist'];
                } else {
                    $response = ['status' => true, 'message' => 'Land Transfer lease details ' . $batchTransferId . ' successfully in-activated.', 'data' => 'notexist'];
                }
                // $response = ['status' => true, 'message' => 'Land Transfer lease details ' . $batchTransferId . ' successfully in-activated.'];
            } else {
                $response = ['status' => false, 'message' => 'Land Transfer details ID is wrong.', 'data' => 'notexist'];
            }
            return json_encode($response);
        }
    }

    // get old property id record
    public function getOldPropertyStatusValue($propertyId, $propertyStatusId, Request $request, MisService $misService)
    {
        if (!empty($propertyId) && !empty($propertyStatusId)) {

            $oldStatusId = $misService->getOldPropertyStatus($propertyId, $request);
            if (!empty($oldStatusId) && !empty($propertyStatusId) && ($oldStatusId != $propertyStatusId)) {
                $response = ['status' => true, 'message' => 'Property id is different', 'data' => 'true', 'oldStatusId' => $oldStatusId];
            } else {
                $response = ['status' => true, 'message' => 'Property id is same', 'data' => 'false'];
            }
            return json_encode($response);
        }
    }

    public function softDeleteOldPropertyStatusRecord(Request $request, MisService $misService)
    {
        if (!empty($request->oldPropertyDbStatusId) && ($request->oldPropertyDbStatusId == 952) && !empty($request->conversion)) {
            foreach ($request->conversion as $id => $name) {
                $misService->softDeleteRecordFromPropertyTransferLeaseDetails($id);
            }
        } else if (!empty($request->oldPropertyDbStatusId) && ($request->oldPropertyDbStatusId == 1124) && !empty($request->propertyId)) {
            $misService->updateRecordAsNUllInPropertyLeaseDetailsVacant($request->propertyId);
        } else if (!empty($request->oldPropertyDbStatusId) && ($request->oldPropertyDbStatusId == 1342) && !empty($request->propertyId)) {
            $misService->updateRecordAsNUllInPropertyLeaseDetailsOthers($request->propertyId);
        }
        return response()->json(['success' => true, 'message' => 'soft deleted successfully.']);
    }




    /** function added by nitin  -  to get seprataed details */

    private function getSeparatedPropertyTransferDetails($rows, $editing = false)
    {

        $separatedData = [];
        $keysToRemove = $editing ? ['Original', 'Conversion'] : []; // if not editing then do not remove any process types
        foreach ($rows as $transferDetail) {
            //added for only showing parent details - SOURAV CHAUHAN (18/July/2024)
            $propertyDetails = PropertyMaster::where('id', $transferDetail->property_master_id)->first();
            if ($transferDetail->splited_property_detail_id == null) {
                $processOfTransfer = $transferDetail->process_of_transfer;
                $dateOfTransfer = $transferDetail->transferDate; //Added By Nitin
                if (!in_array($processOfTransfer, $keysToRemove)) {
                    // Check if the process_of_transfer value is already a key in $separatedData
                    if (!array_key_exists($dateOfTransfer, $separatedData)) {
                        // If not, create a new array for this process_of_transfer value
                        $separatedData[$dateOfTransfer] = [];
                    }
                    if (!array_key_exists($processOfTransfer, $separatedData[$dateOfTransfer])) {
                        $separatedData[$dateOfTransfer][$processOfTransfer] = [];
                    }
                    // Add the current $transferDetail to the corresponding array in $separatedData
                    $separatedData[$dateOfTransfer][$processOfTransfer][] = $transferDetail;  // modified by Nitin}
                } else { //original and conversoin processes are handled sapearately in case of edit
                    if (!array_key_exists($processOfTransfer, $separatedData)) {
                        $separatedData[$processOfTransfer] = [];
                    }
                    // Add the current $transferDetail to the corresponding array in $separatedData
                    $separatedData[$processOfTransfer][] = $transferDetail;  // modified by Nitin}
                }
            } else {
                $processOfTransfer = $transferDetail->process_of_transfer;
                $dateOfTransfer = $transferDetail->transferDate; //Added By Nitin
                if (!in_array($processOfTransfer, $keysToRemove)) {
                    // Check if the process_of_transfer value is already a key in $separatedData
                    if (!array_key_exists($dateOfTransfer, $separatedData)) {
                        // If not, create a new array for this process_of_transfer value
                        $separatedData[$dateOfTransfer] = [];
                    }
                    if (!array_key_exists($processOfTransfer, $separatedData[$dateOfTransfer])) {
                        $separatedData[$dateOfTransfer][$processOfTransfer] = [];
                    }
                    // Add the current $transferDetail to the corresponding array in $separatedData
                    $separatedData[$dateOfTransfer][$processOfTransfer][] = $transferDetail;  // modified by Nitin}
                } else { //original and conversoin processes are handled sapearately in case of edit
                    if (!array_key_exists($processOfTransfer, $separatedData)) {
                        $separatedData[$processOfTransfer] = [];
                    }
                    // Add the current $transferDetail to the corresponding array in $separatedData
                    $separatedData[$processOfTransfer][] = $transferDetail;  // modified by Nitin}
                }
            }
        }
        // dd($separatedData);
        ksort($separatedData);
        return $separatedData;
    }

    public function updateChild($id, Request $request, MisMultiplePropertyService $misMultiplePropertyService)
    {
        //Validation
        $response = $misMultiplePropertyService->updateChild($id, $request);
        if ($response) {
            // Transaction was successful
            return redirect()->back()->with('success', 'Property details Updated successfully.');
        } else if ($response == false) {
            // Transaction failed
            return redirect()->back()->with('failure', 'Property details not updated');
        } else {
            return redirect()->back()->with('failure', $response);
        }
    }
    public function viewPropertyDetails($property, MisService $misService)
    {
        $item = new Item();
        $viewDetails = $misService->viewDetails($property);

        // code modified by nitin created new function to ge sapated data;
        $separatedData = self::getSeparatedPropertyTransferDetails($viewDetails->propertyTransferredLesseeDetails);

        return view('mis.view_property_details', compact(['viewDetails', 'item', 'separatedData']));
    }


    //Get user action log details Lalit On 18/07/2024
    /*public function actionLogListings(Request $request)
    {
        if ($request->ajax()) {
            $query = UserActionLog::with(['user', 'module'])
                ->orderBy('created_at', 'desc');
            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = Carbon::parse($request->start_date)->startOfDay();
                $end_date = Carbon::parse($request->end_date)->endOfDay();
                $query->whereBetween('created_at', [$start_date, $end_date]);
            } else {
                $query->whereDate('created_at', Carbon::today());
            }
            $data = $query->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['user_name']), Str::lower($request->get('search')))) {
                                return true;
                            } else if (Str::contains(Str::lower($row['module_name']), Str::lower($request->get('search')))) {
                                return true;
                            } else if (Str::contains(Str::lower($row['action']), Str::lower($request->get('search')))) {
                                return true;
                            } else if (Str::contains(Str::lower($row['description']), Str::lower($request->get('search')))) {
                                return true;
                            } else if (Str::contains(Str::lower($row['created_at']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            return false;
                        });
                    }
                })
                ->addColumn('user_name', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('module_name', function ($row) {
                    return $row->module->name;
                })
                ->editColumn('description', function ($row) {
                    // Assuming description contains the HTML for the anchor tag
                    return $row->description;
                })
                ->rawColumns(['description'])
                ->make(true);
        }
        return view('user-action-logs.index');
    }*/

    public function actionLogListings(Request $request)
    {
        return view('user-action-logs.indexDatatable');
    }

    public function getUserActionLogs(Request $request)
    {
        $query = UserActionLog::query()
            ->leftJoin('users', 'user_action_logs.user_id', '=', 'users.id')
            ->leftJoin('modules', 'user_action_logs.module_id', '=', 'modules.id')
            ->select(
                'user_action_logs.*',
                'users.name as uname',
                'modules.name as mname',
                'user_action_logs.created_at'
            );

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = Carbon::parse($request->start_date)->startOfDay();
            $end_date = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('user_action_logs.created_at', [$start_date, $end_date]);
        } else {
            // Optionally, you can filter for today's data if no date range is provided
            $query->whereDate('user_action_logs.created_at', Carbon::today());
        }

        $columns = ['uname', 'mname', 'action', 'description', 'created_at'];
        $totalData = $query->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search) {
                $q->where('user_action_logs.id', 'LIKE', "%{$search}%")
                    ->orWhere('users.name', 'LIKE', "%{$search}%")
                    ->orWhere('modules.name', 'LIKE', "%{$search}%")
                    ->orWhere('user_action_logs.action', 'LIKE', "%{$search}%")
                    ->orWhere('user_action_logs.description', 'LIKE', "%{$search}%");
            });

            $totalFiltered = $query->count();
        }

        $getActionLogsData = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $data = [];
        foreach ($getActionLogsData as $getActionLogDetail) {
            $nestedData = [];
            $nestedData['uname'] = $getActionLogDetail->uname;
            $nestedData['mname'] = $getActionLogDetail->mname;
            $nestedData['action'] = $getActionLogDetail->action;
            $nestedData['description'] = $getActionLogDetail->description;
            $nestedData['created_at'] = $getActionLogDetail->created_at->format('d/m/Y H:i:s');
            $data[] = $nestedData;
        }

        $json_data = [
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        ];

        return response()->json($json_data);
    }



    //For deleting the property details - SOURAV CHAUHAN (11 july 2024)
    public function propertyDestroy($id)
    {
        //added transaction to delete the property details from all tables SOURAV CHAUHAN 18/July/2024
        try {
            $transactionSuccess = false;
            DB::transaction(function () use ($id, &$transactionSuccess) {
                $propertyDetails = PropertyMaster::find($id);
                if ($propertyDetails) {
                    $propertyMasterHistory = PropertyMasterHistory::where('property_master_id', $id)->delete();
                    $propertyLeaseDetail = PropertyLeaseDetail::where('property_master_id', $id)->delete();
                    $propertyLeaseDetailHistory = PropertyLeaseDetailHistory::where('property_master_id', $id)->delete();
                    $splitedPropertyDetail = SplitedPropertyDetail::where('property_master_id', $id)->get();
                    foreach ($splitedPropertyDetail as $splitedProperty) {
                        SplitedPropertyDetailHistory::where('splited_property_detail_id', $splitedProperty->id)->delete();
                        $splitedProperty->delete();
                    }
                    $propertyTransferredLesseeDetail = PropertyTransferredLesseeDetail::where('property_master_id', '=', $id)->withTrashed()->forceDelete();
                    $propertyTransferLesseeDetailHistory = PropertyTransferLesseeDetailHistory::where('property_master_id', $id)->delete();
                    $currentLesseeDetail = CurrentLesseeDetail::where('property_master_id', $id)->delete();
                    $propertyInspectionDemandDetail = PropertyInspectionDemandDetail::where('property_master_id', $id)->delete();
                    $propInspDemandDetailHistory = PropInspDemandDetailHistory::where('property_master_id', $id)->delete();
                    $propertyMiscDetail = PropertyMiscDetail::where('property_master_id', $id)->delete();
                    $propertyMiscDetailHistory = PropertyMiscDetailHistory::where('property_master_id', $id)->delete();
                    $propertyContactDetail = PropertyContactDetail::where('property_master_id', $id)->delete();
                    $propertyContactDetailsHistory = PropertyContactDetailsHistory::where('property_master_id', $id)->delete();
                    $propertyDetails->delete();
                    // Helper function to Manage User Activity / Action Logs for MIS
                    $property_id_link = '<a href="' . url("/property-details/{$id}/view") . '" target="_blank">' . $id . '</a>';
                    UserActionLogHelper::UserActionLog('delete', url("/property-details/$id/view"), 'propertyProfarma', "Property " . $property_id_link . " has been deleted by user " . Auth::user()->name . ".");
                    $transactionSuccess = true;
                } else {
                    return redirect()->back()->with('failure', 'Property not found.');
                }
            });

            if ($transactionSuccess) {
                return redirect()->back()->with('success', 'Property details deleted successfully.');
            } else {
                Log::info("transaction failed");
                return redirect()->back()->with('failure', 'Property details not deleted.');
            }
        } catch (\Exception $e) {
            Log::info($e);
            return $e->getMessage();
        }
    }
}
