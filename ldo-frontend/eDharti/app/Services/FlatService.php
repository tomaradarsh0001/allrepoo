<?php

// app/Services/FlatService.php

namespace App\Services;

use App\Helpers\GeneralFunctions as HelpersGeneralFunctions;
use App\Models\ApplicationStatus;
use App\Models\CurrentLesseeDetail;
use App\Models\Flat;
use App\Models\FlatHistory;
use App\Models\OldColony;
use App\Models\Item;
use App\Models\PropertyMaster;
use App\Models\PropertyTransferLesseeDetailHistory;
use App\Models\PropertyTransferredLesseeDetail;
use App\Models\SectionMisHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FlatService
{
    // Stored MIS flat details By Lalit on 24/09/2024
    public function storeFlatDetails($request)
    {
        try {
            $transactionSuccess = false;

            DB::transaction(function () use ($request, &$transactionSuccess) {
                if ($request->property_master_id) {
                    $user = User::find(Auth::id());

                    // Check if the property already exists
                    $is_property_exist = Flat::where('property_master_id', $request->property_master_id)
                        ->where('flat_number', $request->flatNumber)->first();

                    if ($is_property_exist) {
                        // Redirect with a message indicating the flat already exists
                        throw new \Exception('Flat already exists with flat number ' . $request->flatNumber . ' on this property.');
                    } else {
                        // Check if total flats exceed 200
                        if ($request->is_joint_property) {
                            $totalFlats = Flat::where('property_master_id', $request->property_master_id)
                                ->where('splited_property_id', $request->splited_property_id)->count();
                        } else {
                            $totalFlats = Flat::where('property_master_id', $request->property_master_id)->count();
                        }

                        if ($totalFlats > 200) {
                            // Throw an exception if the flats exceed the allowed limit
                            throw new \Exception('You cannot create more than 200 flats on this property.');
                        }

                        // Store flat details
                        $flatId = self::flatDetailsStore($request, $user);
                        self::currentLesseeDetailsStore($request, $user, $flatId);
                        self::propertyTransferLesseeDetailsStore($request, $user, $flatId);

                        $transactionSuccess = true;
                    }
                }
            });

            if ($transactionSuccess) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Flat creation failed: ' . $e->getMessage());
            // Return the error message for proper display on redirection
            return $e->getMessage();
        }
    }


    public function updateFlatDetails($request)
    {
        try {
            $transactionSuccess = false;
            DB::transaction(function () use ($request, &$transactionSuccess) {
                if ($request->flatId && $request->property_master_id) {
                    $user = User::find(Auth::id());
                    self::flatDetailsUpdate($request, $user);
                    self::currentLesseeDetailsUpdate($request, $user);
                    self::propertyTransferLesseeDetailsUpdate($request, $user);
                    // Update Or Insert record in section mis history table - Lalit tiwari (16/Oct/2024)
                    if(Auth::user()->hasAnyRole('section-officer') && !empty($request->serviceType) && !empty($request->modalId)){
                        self::insertRecordAppStatusAndSectionMisHis($request);
                    }
                    $transactionSuccess = true;
                }
            });
            if ($transactionSuccess) {
                return true;
            } else {
                Log::info("transaction failed");
                return false;
            }
        } catch (\Exception $e) {
            Log::info($e);
            return $e->getMessage();
        }
    }

    // public function updateContactDetails($id, $request)
    public function insertRecordAppStatusAndSectionMisHis($request)
    {
        try {
            $serviceType = getServiceType($request->serviceType);
            $modalId = $request->modalId;

            $iseditedOrApprovedEver = SectionMisHistory::where('service_type', $serviceType)
                ->where('model_id', $modalId)
                ->where('property_master_id', $request->masterId)
                ->orderBy('id', 'desc')
                ->first();

            if ($iseditedOrApprovedEver) {
                if ($iseditedOrApprovedEver->is_active == 1 && $iseditedOrApprovedEver->permission_to == Auth::user()->id) {
                    $iseditedOrApprovedEver->is_active = 0;
                    $iseditedOrApprovedEver->save();
                    //Check if record exist in Application status, if yes then update is_mis_checked & mis_checked_by
                    $checkApplicationRecExists = ApplicationStatus::where([['service_type', $serviceType],['model_id', $modalId]])->first();
                    if ($checkApplicationRecExists) {
                        $checkApplicationRecExists->is_mis_checked = true;
                        $checkApplicationRecExists->mis_checked_by = Auth::user()->id;
                        $checkApplicationRecExists->save();
                    }
                } 
            } else {
                $applicantNo = $request->applicantNo;
                $applicationStatus = ApplicationStatus::where('service_type', $serviceType)->where('model_id', $modalId)->first();
                if ($applicationStatus) {
                    $applicationStatus->is_mis_checked = true;
                    $applicationStatus->mis_checked_by = Auth::user()->id;
                    $applicationStatus->save();
                } else {
                    $applicationStatus = ApplicationStatus::create([
                        'service_type' => $serviceType,
                        'model_id' => $modalId,
                        'reg_app_no' => $applicantNo,
                        'is_mis_checked' => true,
                        'mis_checked_by' => Auth::user()->id,
                        'is_scan_file_checked' => false,
                        'is_uploaded_doc_checked' => false,
                        'created_by' => Auth::user()->id,
                    ]);
                }
                if ($applicationStatus) {
                    SectionMisHistory::create([
                        'service_type' => $serviceType,
                        'model_id' => $modalId,
                        'section_code' => trim($request->sectionCode),
                        'old_property_id' => $request->oldPropertyId,
                        'new_property_id' => $request->newPropertyId,
                        'property_master_id' => $request->masterId,
                        'created_by' => Auth::user()->id,
                    ]);
                    
                } 
            }
        } catch (\Exception $e) {
            Log::info($e);
            return redirect()->back()->with('failure', $e->getMessage());
        }
    }

    public function generateUniqueFlatId()
    {
        $lastRecord = Flat::latest()->first();
        if ($lastRecord) {
            $lastId = (int) substr($lastRecord->unique_flat_id, 1);
            $nextId = 'F' . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $nextId = 'F000001';
        }
        return $nextId;
    }

    //create a automated file number
    public function getFileNumber($landType, $colonyCode, $block, $plotNo, $flatNo)
    {
        if (empty($landType)) {
            $landType = 'LT';
        } else {
            $item = new Item;
            $data = $item->itemNameById($landType);
            if ($data == 'Rehabilitation') {
                $landType = 'R';
            } else {
                $landType = 'N';
            }
        }
        if (empty($colonyCode)) {
            $colonyCode = 'Col';
        } else {
            $data = OldColony::where('id', $colonyCode)->first();
            $colonyCode = $data['code'];
        }
        if (empty($block)) {
            $block = '0';
        }
        if (empty($plotNo)) {
            $plotNo = 'P';
        }
        if (empty($flatNo)) {
            $flatNo = 'F';
        }
        $fileNo = 'DL/' . $landType . '/' . $colonyCode . '/' . $block . '/' . $plotNo . '/' . $flatNo;
        return $fileNo;
    }

    public function flatDetailsStore($request, $user)
    {
       
        $propertyDetails = PropertyMaster::find($request->property_master_id);
        if(!empty($propertyDetails->new_colony_name)){
            $colony = OldColony::find($propertyDetails->new_colony_name);
        }
        // Calculate area in sqm
        $area_in_sqm = $plot_area_in_sqm = '';
        $area_in_sqm = $this->calculateAreaInSqm($request->area, $request->unit);
        // Save to database
        $flats = Flat::create([
            'property_master_id'       => $request->property_master_id ?? '',
            'old_property_id'          => $request->old_propert_id ?? '',
            'unique_property_id'       => $request->unique_propert_id ?? '',
            'splitted_property_id'      => $request->splitted_property_id ?? null,
            'unique_flat_id'           => self::generateUniqueFlatId(),
            'locality'                 => $request->locality ?? $propertyDetails->new_colony_name,
            'block'                    => $request->block ?? $propertyDetails->block_no,
            'plot'                     => $request->plot ?? $propertyDetails->plot_or_property_no,
            'known_as'                 => $request->knownas ?? $propertyDetails->block_no.'/'.$propertyDetails->plot_or_property_no.'/'.$colony->name,
            'flat_number'              => $request->flatNumber ?? '',
            'unique_file_no'           => self::getFileNumber($request->land_type, $request->locality, $request->block, $request->plot, $request->flatNumber),
            'area'                     => $request->area ?? '',
            'unit'                     => $request->unit ?? '',
            'area_in_sqm'              => $area_in_sqm ?? '',
            'property_flat_status'     => $request->propertyFlatStatus ?? '',
            'builder_developer_name'   => $request->nameofBuilder ?? '',
            'original_buyer_name'      => $request->originalBuyerName ?? '',
            'purchase_date'            => $request->purchaseDate,
            'present_occupant_name'    => $request->presentOccupantName ?? '',
            'is_active'                => true,
            'created_by'               => $user->id,
            'updated_by'               => $user->id,
        ]);

        return $flats->id;
    }
    public function flatDetailsUpdate($request, $user)
    {
        $flatDetails = Flat::find($request->flatId);
        $oldFlatDetails = $flatDetails->getOriginal();
        if ($flatDetails) {
            // Calculate area in sqm
            $area_in_sqm = '';
            $area_in_sqm = $this->calculateAreaInSqm($request->area, $request->unit);
            $flatDetails->property_master_id = isset($request->property_master_id) ? $request->property_master_id :  $flatDetails->property_master_id;
            $flatDetails->old_property_id = isset($request->old_propert_id) ? $request->old_propert_id :  $flatDetails->old_property_id;
            $flatDetails->unique_property_id = isset($request->unique_propert_id) ? $request->unique_propert_id :  $flatDetails->unique_property_id;
            $flatDetails->splitted_property_id = isset($request->splitted_property_id) ? $request->splitted_property_id :  $flatDetails->splitted_property_id;
            $flatDetails->unique_file_no = self::getFileNumber($request->land_type, $request->locality, $request->block, $request->plot, $request->flatNumber);
            $flatDetails->locality = isset($request->locality) ? $request->locality :  $flatDetails->locality;
            $flatDetails->block = isset($request->block) ? $request->block :  $flatDetails->block;
            $flatDetails->plot = isset($request->plot) ? $request->plot :  $flatDetails->plot;
            $flatDetails->known_as = isset($request->knownas) ? $request->knownas :  $flatDetails->known_as;
            $flatDetails->flat_number = isset($request->flatNumber) ? $request->flatNumber :  $flatDetails->flat_number;
            $flatDetails->area = isset($request->area) ? $request->area :  $flatDetails->area;
            $flatDetails->unit = isset($request->unit) ? $request->unit :  $flatDetails->unit;
            $flatDetails->area_in_sqm = isset($area_in_sqm) ? $area_in_sqm :  $flatDetails->area_in_sqm;
            $flatDetails->property_flat_status = isset($request->propertyFlatStatus) ? $request->propertyFlatStatus :  $flatDetails->property_flat_status;
            $flatDetails->builder_developer_name = isset($request->nameofBuilder) ? $request->nameofBuilder :  $flatDetails->builder_developer_name;
            $flatDetails->original_buyer_name = isset($request->originalBuyerName) ? $request->originalBuyerName :  $flatDetails->original_buyer_name;
            $flatDetails->purchase_date = isset($request->purchaseDate) ? $request->purchaseDate :  $flatDetails->purchase_date;
            $flatDetails->present_occupant_name = isset($request->presentOccupantName) ? $request->presentOccupantName :  $flatDetails->present_occupant_name;
            $flatDetails->updated_by = Auth::id();
            if ($flatDetails->isDirty()) {
                $flatDetails->updated_by = Auth::id();
                $flatDetails->save();
                $changes = $flatDetails->getChanges();
                $flatHistory = new FlatHistory;
                $flatHistory->flat_id = $request->flatId;
                $flatHistory->property_master_id = $flatDetails->property_master_id;
                $flatHistory->new_property_master_id = $request->property_master_id;
                foreach ($changes as $key => $change) {
                    if ($key != 'updated_at' && $key != 'updated_by' && $key != 'old_property_id' &&  $key != 'unique_property_id') {
                        $flatHistory->$key = $oldFlatDetails[$key];
                        $newKey = 'new_' . $key;
                        $flatHistory->$newKey = $change;
                    }
                }
                $flatHistory->updated_by = Auth::id();
                if ($flatHistory->save()) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    public function propertyTransferLesseeDetailsStore($request, $user, $flatId)
    {

        PropertyTransferredLesseeDetail::create([
            'property_master_id' => $request->property_master_id ?? '',
            'splited_property_detail_id'    => $request->splitted_property_id ?? null,
            'old_property_id' => $request->old_propert_id ?? '',
            'flat_id' => $flatId ?? '',
            'plot_flat_no' => $request->flatNumber ?? '',
            'process_of_transfer' => "Original",
            'transferDate' => $request->purchaseDate,
            'lessee_name' => $request->originalBuyerName ?? '',
            'lessee_age' => null,
            'property_share' => null,
            'lessee_pan_no' => null,
            'lessee_aadhar_no' => null,
            'batch_transfer_id' => 1,
            'previous_batch_transfer_id' => null,
            'created_by' => Auth::id()
        ]);
    }

    public function propertyTransferLesseeDetailsUpdate($request, $user)
    {
        if (!empty($request->flatId)) {
            $propertyTransferredLesseeDetail = PropertyTransferredLesseeDetail::withTrashed()->where('flat_id', $request->flatId)->first();
            $oldPropertyTransferLesseeDetailHistory = $propertyTransferredLesseeDetail->getOriginal();
            $propertyTransferredLesseeDetail->property_master_id = $request->property_master_id ?? '';
            $propertyTransferredLesseeDetail->splited_property_detail_id = $request->splitted_property_id ?? null;
            $propertyTransferredLesseeDetail->old_property_id = $request->old_propert_id ?? '';
            $propertyTransferredLesseeDetail->plot_flat_no = $request->flatNumber ?? '';
            $propertyTransferredLesseeDetail->transferDate = $request->purchaseDate;
            $propertyTransferredLesseeDetail->lessee_name = $request->originalBuyerName ?? '';
            if ($propertyTransferredLesseeDetail->isDirty()) {
                $propertyTransferredLesseeDetail->updated_by = Auth::id();
                $propertyTransferredLesseeDetail->save();
                $changes = $propertyTransferredLesseeDetail->getChanges();
                $propertyTransferLesseeDetailHistory = new PropertyTransferLesseeDetailHistory;
                $propertyTransferLesseeDetailHistory->flat_id = $request->flatId;
                foreach ($changes as $key => $change) {
                    if ($key != 'updated_at' && $key != 'updated_by') {
                        $propertyTransferLesseeDetailHistory->$key = $oldPropertyTransferLesseeDetailHistory[$key];
                        $newKey = 'new_' . $key;
                        $propertyTransferLesseeDetailHistory->$newKey = $change;
                        $propertyTransferLesseeDetailHistory->property_master_id = $propertyTransferredLesseeDetail->property_master_id;
                        $propertyTransferLesseeDetailHistory->splited_property_detail_id = $propertyTransferredLesseeDetail->splited_property_detail_id;
                        $propertyTransferLesseeDetailHistory->lessee_id = $propertyTransferredLesseeDetail->id;
                    }
                }
                $propertyTransferLesseeDetailHistory->updated_by = Auth::id();
                $propertyTransferLesseeDetailHistory->save();
                return true;
            }
        } else {
            return false;
        }
    }

    public function currentLesseeDetailsStore($request, $user, $flatId)
    {
        // Calculate area in sqm
        $area_in_sqm = '';
        $area_in_sqm = $this->calculateAreaInSqm($request->area, $request->unit);
        CurrentLesseeDetail::create([
            'property_master_id' => $request->property_master_id ?? '',
            'splited_property_detail_id'    => $request->splitted_property_id ?? null,
            'old_property_id' => $request->old_propert_id ?? '',
            'flat_id' => $flatId ?? '',
            'property_status' => $request->propertyFlatStatus ?? '',
            'lessees_name' => $request->originalBuyerName ?? '',
            'property_known_as' => $request->knownas ?? '',
            'area' => $request->area ?? '',
            'unit' => $request->unit ?? '',
            'area_in_sqm' => $area_in_sqm,
            'created_by' => Auth::id()
        ]);
    }

    public function currentLesseeDetailsUpdate($request, $user)
    {
        // Calculate area in sqm
        $area_in_sqm = '';
        $area_in_sqm = $this->calculateAreaInSqm($request->area, $request->unit);
        if (!empty($request->flatId)) {
            // Find the current lessee detail using the flat ID
            $currentLesseeDetail = CurrentLesseeDetail::where('flat_id', $request->flatId)->first();
            // Check if the object was found
            if ($currentLesseeDetail) {
                // Update the properties of the object
                $currentLesseeDetail->property_master_id = $request->property_master_id ?? null;
                $currentLesseeDetail->splited_property_detail_id = $request->splitted_property_id ?? null;
                $currentLesseeDetail->old_property_id = $request->old_propert_id ?? null;
                $currentLesseeDetail->property_status = $request->propertyFlatStatus ?? '';
                $currentLesseeDetail->lessees_name = $request->originalBuyerName ?? '';
                $currentLesseeDetail->property_known_as = $request->knownas ?? '';
                $currentLesseeDetail->area = $request->area ?? '';
                $currentLesseeDetail->unit = $request->unit ?? '';
                $currentLesseeDetail->area_in_sqm = $area_in_sqm;
                $currentLesseeDetail->updated_by = auth()->id(); // Authenticated user ID
                $currentLesseeDetail->updated_at = now(); // Ensure the updated timestamp is stored
                // Save the updated object
                $currentLesseeDetail->save();
            }
        }
    }

    private function calculateAreaInSqm($area, $unit)
    {
        $getItemCode = Item::where('id', $unit)->where('group_id', 1008)->pluck('item_code')->first();

        if ($getItemCode) {
            switch (trim($getItemCode)) {
                case 'H':
                    return $area * 10000;
                case 'Y':
                    return $area * 0.836127;
                case 'M':
                    return $area;
                case 'F':
                    return $area * 0.092903;
                case 'A':
                    return $area * 4046.86;
                default:
                    return 0;
            }
        }

        return 0;
    }
}
