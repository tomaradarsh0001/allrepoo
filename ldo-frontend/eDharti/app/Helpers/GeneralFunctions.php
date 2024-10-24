<?php

namespace App\Helpers;

use App\Models\UserActionLog;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use App\Models\Otp;
use App\Models\UserRegistration;
use App\Models\PropertyMaster;
use App\Models\UserProperty;
use App\Models\ApplicantUserDetail;
use App\Models\Item;
use App\Models\TempSubstitutionMutation;
use App\Models\TempCoapplicant;
use App\Models\MutationApplication;
use App\Models\Coapplicant;
use App\Models\DocumentKey;
use App\Models\TempDocumentKey;
use App\Models\TempDocument;
use App\Models\Document;
use App\Models\Application;
use App\Models\DeedOfApartmentApplication;
use App\Models\TempDeedOfApartment;
use App\Models\LandUseChangeApplication;
use App\Models\Payment;
use App\Models\ApplicationMovement;
use App\Models\TempLandUseChangeApplication;
use App\Models\PropertySectionMapping;
use Illuminate\Support\Facades\DB;
use URL;
use Illuminate\Support\Facades\Storage;
use App\Services\CommonService;
use Illuminate\Support\Facades\Log;

class GeneralFunctions
{

    //for generating otp
    public static function generateUniqueRandomNumber($digits)
    {
        $maxAttempts = 10;
        while ($maxAttempts > 0) {
            $randomNumber = mt_rand(pow(10, $digits - 1), pow(10, $digits) - 1); // Generate random number
            $exists = Otp::where('email_otp', $randomNumber)->where('mobile_otp', $randomNumber)->exists();
            if (!$exists) {
                return $randomNumber;
            }

            $maxAttempts--;
        }

        throw new Exception("Unable to generate a unique random number within the specified attempts.");
    }

    //for uploding file
    public static function uploadFile($file, $pathToUpload, $type)
    {
        $date = now()->format('YmdHis');
        $fileName = $type . '_' . $date . '.' . $file->extension();
        $path = $file->storeAs($pathToUpload, $fileName, 'public');
        return $path;
    }



    //For generating registration number
    public static function generateRegistrationNumber()
    {
        $lastRegistration = UserRegistration::latest('created_at')->first();

        if ($lastRegistration) {
            $lastNumber = intval(substr($lastRegistration->applicant_number, 4)); // Skip 'REG-'
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $formattedNumber = str_pad($newNumber, 7, '0', STR_PAD_LEFT);
        $registrationNumber = 'APL' . $formattedNumber;
        return $registrationNumber;
    }

    //For generating registration number
    public static function generateUniqueApplicationNumber($model, $column)
    {
        do {
            // Generate a 10-digit random number
            $randomNumber = random_int(1000000000, 9999999999); // 10 digits
            // Check for uniqueness in your model's table/column
            $numberExists = $model::where($column, $randomNumber)->exists();
        } while ($numberExists);

        return $randomNumber;
    }

    public static function isPropertyFree($propertyId)
    {
        $property = PropertyMaster::where('old_propert_id', $propertyId)->first();
        if ($property) {
            $ispropertyLinked = UserProperty::where('old_property_id', $propertyId)->first();
            if ($ispropertyLinked) {
                $applicant = ApplicantUserDetail::where('user_id', $ispropertyLinked['user_id'])->first();
                $applicantNumber = $applicant['applicant_number'];
                // $user = User::where('id',$ispropertyLinked['user_id'])->first();
                $data = [
                    'success' => false,
                    'message' => 'Property linked with another applicant ' . $applicantNumber . ' .',
                    'details' => '<h6 class="text-danger">Property linked with another applicant ' . $applicantNumber . '</h6>
                            <table class="table table-bordered property-table-info">
                                <tbody>
                                    <tr>
                                        <th>Name :</th>
                                        <td>' . $applicant->user->name . '</td>
                                        <th>Email :</th>
                                        <td>' . $applicant->user->email . '</td>
                                    </tr>
                                    <tr>
                                    <th>Mobile:</th>
                                        <td>' . $applicant->user->mobile_no . '</td>
                                        <th>Address:</th>
                                        <td>' . $applicant->address . '</td>
                                        
                                    </tr>
                                    <tr>
                                        <th>PAN:</th>
                                        <td>' . $applicant->pan_card . '</td>
                                        <th>Aadhaar:</th>
                                        <td>' . $applicant->aadhar_card . '</td>
                                    </tr>
                                </tbody>
                            </table>'
                ];
            } else {
                $data = [
                    'success' => true,
                    'message' => 'Property is free',
                    'details' => ''
                ];
            }
            return $data;
        }
    }

    public static function getItemsByGroupId($id)
    {
        return Item::where('group_id', $id)->get();
    }

    //For storing application temporary tables data to final tables - SOURAV CHAUHAN (1/Oct/2024)
    public static function convertTempAppToFinal($modelId, $modelName, $paymentComplete)
    {
        $instance = new self();
        $finalModel = Application::class;
        $prefix = "APP";
        $column = "application_no";
        $commonService = new CommonService;
        $applicationNo = $commonService->getUniqueID($finalModel, $prefix, $column);
        switch ($modelName) {
            case 'TempSubstitutionMutation':
                $serviceType = getServiceType('SUB_MUT');
                $transactionSuccess = $instance->convertMutationApplication($modelName, $modelId, $finalModel, $applicationNo, $serviceType, $paymentComplete);
                break;
            case 'TempDeedOfApartment':
                $serviceType = getServiceType('DOA');
                $transactionSuccess = $instance->convertDOAApplication($modelName, $modelId, $finalModel, $applicationNo, $serviceType, $paymentComplete);
                break;
            case 'TempLandUseChangeApplication':
                $serviceType = getServiceType('LUC');
                $transactionSuccess = $instance->convertLUCApplication($modelId, $finalModel, $applicationNo, $serviceType, $paymentComplete);
            default:
                break;
        }
        if ($transactionSuccess) {
            return response()->json(['status' => 'success', 'message' => 'Application submitted succesfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Application not submitted!']);
        }
    }

    //For storing mutation application temporary tables data to final tables - SOURAV CHAUHAN (1/Oct/2024)
    public function convertMutationApplication($modelName, $modelId, $finalModel, $applicationNo, $serviceType, $paymentComplete)
    {
        $transactionSuccess = false;
        DB::transaction(function () use ($modelName, &$transactionSuccess, &$modelId, &$finalModel, &$applicationNo, &$serviceType, &$paymentComplete) {

            //Step 1:- store the main details to mutation applications table - SOURAV CHAUHAN (3/Oct/2024)
            $newApp =  Self::storeMutationApplication($applicationNo, $modelId);
            if (!empty($newApp)) {
                //Step 2:- store the coapplicants details to coapplicants table - SOURAV CHAUHAN (3/Oct/2024)
                Self::storeCoapplicants($modelName, $modelId, $serviceType, 'MutationApplication', $newApp->id);

                //Step 3:- store the uploaded douments and thei keys to documents table - SOURAV CHAUHAN (3/Oct/2024)
                Self::storeDocuments($modelName, $modelId, $serviceType, 'MutationApplication', $newApp->id);

                //Step 5:- store to common applications table - SOURAV CHAUHAN (3/Oct/2024)
                Self::storeApplication($finalModel, $applicationNo, $serviceType, 'MutationApplication', $newApp->id);

                //Step 6:- update the payment table with new model and id - SOURAV CHAUHAN (9/Oct/2024)
                Self::updatePayment('MutationApplication', $newApp->id, $paymentComplete);

                //Step 7:- store the appliction movement - SOURAV CHAUHAN (9/Oct/2024)
                Self::applicationMovement($applicationNo, $newApp->id, $serviceType);

                //Step 8:- delete all the temp data - SOURAV CHAUHAN (3/Oct/2024)
                Self::deleteApplicationAllTempData($modelName, $modelId, $serviceType);
                $transactionSuccess = true;
            }
        });

        return $transactionSuccess;
    }

    //store the main details to mutation applications table - SOURAV CHAUHAN (3/Oct/2024)
    public function storeMutationApplication($applicationNo, $modelId)
    {
        //fetch details from temp table
        $tempSubstitutionMutation = TempSubstitutionMutation::find($modelId);
        $sectionId = self::findSection($tempSubstitutionMutation['property_master_id']);
        if ($tempSubstitutionMutation) {
            //store to final table
            $newApp =  MutationApplication::create([
                'application_no' => $applicationNo,
                'status' => getServiceType('APP_NEW'), //item code for new application
                'section_id' => $sectionId,
                'old_property_id' => $tempSubstitutionMutation['old_property_id'],
                'new_property_id' => $tempSubstitutionMutation['new_property_id'],
                'property_master_id' => $tempSubstitutionMutation['property_master_id'],
                'property_status' => $tempSubstitutionMutation['property_status'],
                'status_of_applicant' => $tempSubstitutionMutation['status_of_applicant'],
                'name_as_per_lease_conv_deed' => $tempSubstitutionMutation['name_as_per_lease_conv_deed'],
                'executed_on' => $tempSubstitutionMutation['executed_on'],
                'reg_no_as_per_lease_conv_deed' => $tempSubstitutionMutation['reg_no_as_per_lease_conv_deed'],
                'book_no_as_per_lease_conv_deed' => $tempSubstitutionMutation['book_no_as_per_lease_conv_deed'],
                'volume_no_as_per_lease_conv_deed' => $tempSubstitutionMutation['volume_no_as_per_lease_conv_deed'],
                'page_no_as_per_lease_conv_deed' => $tempSubstitutionMutation['page_no_as_per_lease_conv_deed'],
                'reg_date_as_per_lease_conv_deed' => $tempSubstitutionMutation['reg_date_as_per_lease_conv_deed'],
                'sought_on_basis_of' => $tempSubstitutionMutation['sought_on_basis_of'],
                'property_stands_mortgaged' => $tempSubstitutionMutation['property_stands_mortgaged'],
                'mortgaged_remark' => $tempSubstitutionMutation['mortgaged_remark'],
                'is_basis_of_court_order' => $tempSubstitutionMutation['is_basis_of_court_order'],
                'created_by' => Auth::user()->id
            ]);
            return $newApp;
        }
    }

    //to get section id by property master id - SOURAV CHAUHAN (14/Oct/2024)
    public function findSection($propertyMasterId)
    {
        $propertyDetails = PropertyMaster::find($propertyMasterId);
        $colony = $propertyDetails->new_colony_name;
        $propertyType = $propertyDetails->property_type;
        $propertySubType = $propertyDetails->property_sub_type;
        $propertySectionMapping = PropertySectionMapping::where('colony_id', $colony)->where('property_type', $propertyType)->where('property_subtype', $propertySubType)->first();
        $sectionId = $propertySectionMapping['section_id'];
        return $sectionId;
    }

    //store the coapplicants details to coapplicants table - SOURAV CHAUHAN (3/Oct/2024)
    public function storeCoapplicants($modelName, $modelId, $serviceType, $newModelName, $newModelId)
    {
        //fetch coapplicants
        $tempCoapplicants = TempCoapplicant::where('model_id', $modelId)
            ->where('model_name', $modelName)
            ->where('service_type', $serviceType)
            ->get();
        if ($tempCoapplicants) {
            foreach ($tempCoapplicants as $tempCoapplicant) {
                Coapplicant::create([
                    'service_type' => $tempCoapplicant->service_type,
                    'model_name' => $newModelName,
                    'model_id' => $newModelId,
                    'co_applicant_name' => $tempCoapplicant->co_applicant_name,
                    'co_applicant_gender' => $tempCoapplicant->co_applicant_gender,
                    'co_applicant_age' => $tempCoapplicant->co_applicant_age,
                    'co_applicant_father_name' => $tempCoapplicant->co_applicant_father_name,
                    'co_applicant_aadhar' => $tempCoapplicant->co_applicant_aadhar,
                    'co_applicant_pan' => $tempCoapplicant->co_applicant_pan,
                    'co_applicant_mobile' => $tempCoapplicant->co_applicant_mobile,
                    'created_by' => Auth::user()->id
                ]);
            }
        }
    }

    //store the uploaded douments to documents table - SOURAV CHAUHAN (3/Oct/2024)
    public function storeDocuments($modelName, $modelId, $serviceType, $newModelName, $newModelId)
    {
        //fetch documents
        $tempDocuments = TempDocument::where('model_id', $modelId)
            ->where('model_name', $modelName)
            ->where('service_type', $serviceType)
            ->get();
        if ($tempDocuments) {
            foreach ($tempDocuments as $tempDocument) {
                //store to documents table
                $document = Document::create([
                    'title' => $tempDocument->title,
                    'file_path' => $tempDocument->file_path,
                    'user_id' => Auth::user()->id,
                    'service_type' => $tempDocument->service_type,
                    'model_name' => $newModelName,
                    'model_id' => $newModelId,
                    'document_type' => $tempDocument->document_type

                ]);
                // if ($document) {
                //     //fetch document keys
                //     $tempDocumentKeys = TempDocumentKey::where('temp_document_id', $tempDocument->id)->get();
                //     if ($tempDocumentKeys) {
                //         Self::storeDocumentKeys($tempDocumentKeys,$document->id);
                //     }
                // }
            }
        }
    }

    //store the doument keys to documentkeys table - SOURAV CHAUHAN (3/Oct/2024)
    public function storeDocumentKeys($tempDocumentKeys, $documentId)
    {
        foreach ($tempDocumentKeys as $tempDocumentKey) {
            DocumentKey::create([
                'document_id' => $documentId,
                'key' => $tempDocumentKey->key,
                'value' => $tempDocumentKey->value,
                'created_by' => Auth::user()->id
            ]);
        }
    }

    //store the appliction movement - SOURAV CHAUHAN (9/Oct/2024)
    public function applicationMovement($applicationNo, $modelId, $serviceType)
    {
        //entry to application movement for withdraw
        $applicationMovement = ApplicationMovement::create([
            'assigned_by' => Auth::user()->id,
            'service_type' => $serviceType, //for mutation,LUC,DOA etc
            'model_id' => $modelId,
            'status' => getServiceType('APP_NEW'), //for new application
            'application_no' => $applicationNo,
        ]);
    }

    public function deleteApplicationAllTempData($modelName, $modelId, $serviceType)
    {
        //delete from temp main table
        switch ($modelName) {
            case 'TempSubstitutionMutation':
                TempSubstitutionMutation::find($modelId)?->delete();
                break;
            case 'TempDeedOfApartment':
                TempDeedOfApartment::find($modelId)?->delete();
            case "TempLandUseChangeApplication":
                $model = "\\App\\Models" . $modelName;
                $modelDelete = TempLandUseChangeApplication::find($modelId)?->delete();
                break;
            default:
                break;
        }

        //delete coapplicants from temp table
        TempCoapplicant::where('model_id', $modelId)
            ->where('model_name', $modelName)
            ->where('service_type', $serviceType)
            ->delete();

        //delete documents from temp table
        $tempDocuments = TempDocument::where('model_id', $modelId)
            ->where('model_name', $modelName)
            ->where('service_type', $serviceType)
            ->with('tempDocumentKeys')
            ->get();

        foreach ($tempDocuments as $tempDocument) {
            $tempDocument->tempDocumentKeys()->delete(); // Delete associated keys
            $tempDocument->delete(); // Delete the document
        }
        return true;
    }


    //store to common applications table - SOURAV CHAUHAN (3/Oct/2024)
    public function storeApplication($finalModel, $applicationNo, $serviceType, $modelName, $modelId)
    {
        $finalModel::create([
            'application_no' => $applicationNo,
            'service_type' => $serviceType,
            'model_name' => $modelName,
            'model_id' => $modelId,
            'status' => getServiceType('APP_NEW'),
            'created_by' => Auth::user()->id
        ]);
    }

    //For storing deed of apartment application temporary tables to final tables - Lalit (10/Oct/2024)
    public function convertDOAApplication($modelName, $modelId, $finalModel, $applicationNo, $serviceType, $paymentComplete)
    {

        $transactionSuccess = false;
        DB::transaction(function () use ($modelName, &$transactionSuccess, &$modelId, &$finalModel, &$applicationNo, &$serviceType, &$paymentComplete) {

            //Step 1:- store the main details to mutation applications table - SOURAV CHAUHAN (3/Oct/2024)
            $newApp =  Self::storeDOAApplication($applicationNo, $modelId);
            if (!empty($newApp)) {

                //Step 3:- store the uploaded douments and thei keys to documents table - SOURAV CHAUHAN (3/Oct/2024)
                Self::storeDocuments($modelName, $modelId, $serviceType, 'DeedOfApartmentApplication', $newApp->id);

                //Step 5:- store to common applications table - SOURAV CHAUHAN (3/Oct/2024)
                Self::storeApplication($finalModel, $applicationNo, $serviceType, 'DeedOfApartmentApplication', $newApp->id);

                //Step 6:- store to common applications table - SOURAV CHAUHAN (3/Oct/2024)
                Self::updatePayment('DeedOfApartmentApplication', $newApp->id, $paymentComplete);

                //Step 7:- store the appliction movement - SOURAV CHAUHAN (9/Oct/2024)
                Self::applicationMovement($applicationNo, $newApp->id, $serviceType);

                //Step 8:- delete all the temp data - SOURAV CHAUHAN (3/Oct/2024)
                Self::deleteApplicationAllTempData($modelName, $modelId, $serviceType);
                $transactionSuccess = true;
            }
        });

        return $transactionSuccess;
    }

    //store the main details to deed of apartment applications table - Lalit (10/Oct/2024)
    public function storeDOAApplication($applicationNo, $modelId)
    {
        //fetch details from temp table
        $tempDoa = TempDeedOfApartment::find($modelId);
        if ($tempDoa) {
            //store to final table
            $newApp =  DeedOfApartmentApplication::create([
                'application_no' => $applicationNo,
                'status' => getServiceType('APP_NEW'), //item code for new application
                'old_property_id' => $tempDoa['old_property_id'],
                'new_property_id' => $tempDoa['new_property_id'],
                'property_master_id' => $tempDoa['property_master_id'],
                'splited_property_detail_id' => $tempDoa['splited_property_detail_id'],
                'property_status' => $tempDoa['property_status'],
                'status_of_applicant' => $tempDoa['status_of_applicant'],
                'service_type' => getServiceType('DOA'),
                'applicant_name' => $tempDoa['applicant_name'],
                'applicant_address' => $tempDoa['applicant_address'],
                'building_name' => $tempDoa['building_name'],
                'locality' => $tempDoa['locality'],
                'block' => $tempDoa['block'],
                'plot' => $tempDoa['plot'],
                'known_as' => $tempDoa['known_as'],
                'flat_id' => $tempDoa['flat_id'],
                'isFlatNotListed' => $tempDoa['isFlatNotListed'],
                'flat_number' => $tempDoa['flat_number'],
                'builder_developer_name' => $tempDoa['builder_developer_name'],
                'original_buyer_name' => $tempDoa['original_buyer_name'],
                'present_occupant_name' => $tempDoa['present_occupant_name'],
                'purchased_from' => $tempDoa['purchased_from'],
                'purchased_date' => $tempDoa['purchased_date'],
                'flat_area' => $tempDoa['flat_area'],
                'plot_area' => $tempDoa['plot_area'],
                'undertaking' => $tempDoa['undertaking'],
                'created_by' => Auth::user()->id
            ]);
            return $newApp;
        }
    }

    //moved to general functions and modifications by Nitin on 07-10-2024
    public static function paymentComplete($modelId, $modelName)
    {
        $payment = Payment::create([
            'model' => $modelName,
            'model_id' => $modelId,
            'property_master_id' => 1,
            'master_old_property_id' => 11,
            'amount' => 5000,
            'reference_id' => bin2hex(random_bytes(10)),
            'reference_status' => true,

        ]);
        if ($payment) {
            return $payment->id;
        } else {
            return false;
        }
    }

    //moved to general functions and modifications by Nitin on 07-10-2024
    public static function updatePayment($model, $modelId, $paymentComplete)
    {

        $payment = Payment::find($paymentComplete);
        $payment->model = $model;
        $payment->model_id = $modelId;
        if ($payment->save()) {
            return true;
        } else {
            return false;
        }
    }

    public function convertLUCApplication($modelId, $finalModel, $applicationNo, $serviceType, $paymentComplete)
    {
        //store in LandUseChangeApplication Model

        $tempRow = TempLandUseChangeApplication::find($modelId);
        $tempModelName = 'TempLandUseChangeApplication';
        $modelName = 'LandUseChangeApplication';
        $tempAttributes = $tempRow->toArray();
        $transactionSuccess = false;
        unset($tempAttributes['id'], $tempAttributes['created_at'], $tempAttributes['updated_at'], $tempAttributes['created_by'], $tempAttributes['updated_by']);
        $tempAttributes['created_by'] = Auth::id();
        $tempAttributes['updated_by'] = Auth::id();
        $tempAttributes['application_no'] = $applicationNo;
        $tempAttributes['status'] = getServiceType('APP_NEW');
        $newRow = LandUseChangeApplication::create($tempAttributes);
        if ($newRow) {
            Self::storeDocuments($tempModelName, $modelId, $serviceType, $modelName, $newRow->id);
            Self::storeApplication($finalModel, $applicationNo, $serviceType, $modelName, $newRow->id);

            //Step 6:- store to common applications table - SOURAV CHAUHAN (3/Oct/2024)
            Self::updatePayment($modelName, $newRow->id, $paymentComplete);

            //Step 7:- store the appliction movement - SOURAV CHAUHAN (9/Oct/2024)
            Self::applicationMovement($applicationNo, $newRow->id, $serviceType);

            Self::deleteApplicationAllTempData($tempModelName, $modelId, $serviceType);
            $transactionSuccess = true;
        }
        return $transactionSuccess;
    }

    //for storing temp co Applicants 
    public static function storeTempCoApplicants($serviceType, $modelName, $modelId, $colonyCode, $request)
    {
        // dd($request->coapplicants);
        try {
            $allSaved = true;
            foreach ($request->coapplicants as $i => $coapplicant) {
                if (!empty($coapplicant['name'])) {
                    $user = Auth::user();
                    $userDetails = $user->applicantUserDetails;
                    $registrationNumber = $userDetails->applicant_number;
                    $date = now()->format('YmdHis');
                    $pathToUpload = 'public/' . $registrationNumber . '/' . $colonyCode . '/' . $serviceType . '/coapplicant/' . $i + 1;
                    $imageFile = $request->file("coapplicants.$i.photo");
                    // dd($file, $request->coapplicants);
                    // Check if the image exists for this coapplicant
                    if ($imageFile) {

                        $fileName = 'image-' . $date . '.' . $imageFile->getClientOriginalExtension();

                        // Save the image to storage
                        $imageFullPath = $pathToUpload .  '/' . $fileName;
                        Storage::put($imageFullPath, file_get_contents($imageFile));
                    } else {
                        $imageFullPath = null; // Handle missing file case
                    }
                    $aadhaarFile = $request->file("coapplicants.$i.aadhaarFile");
                    // dd($aadhaarFile, $request->coapplicants);
                    // Check if the image exists for this coapplicant
                    if ($aadhaarFile) {

                        $fileName = 'aadhaar-' . $date . '.' . $aadhaarFile->getClientOriginalExtension();

                        // Save the image to storage
                        $aadhaarFullPath = $pathToUpload .  '/' . $fileName;
                        Storage::put($aadhaarFullPath, file_get_contents($aadhaarFile));
                    } else {
                        $aadhaarFullPath = null; // Handle missing file case
                    }
                    $panFile = $request->file("coapplicants.$i.panFile");
                    if ($panFile) {

                        $fileName = 'pan-' . $date . '.' . $panFile->getClientOriginalExtension();

                        // Save the image to storage
                        $panFullPath = $pathToUpload .  '/' . $fileName;
                        Storage::put($panFullPath, file_get_contents($panFile));
                    } else {
                        $panFullPath = null; // Handle missing file case
                    }

                    // Save or update co-applicant details
                    $tempCoapplicant = TempCoapplicant::updateOrCreate(
                        ['id' => (isset($coapplicant['id']) && $coapplicant['id'] > 0) ? $coapplicant['id'] : 0],
                        [
                            'service_type' => getServiceType($serviceType),
                            'model_name' => $modelName,
                            'model_id' => $modelId,
                            'co_applicant_name' => $coapplicant['name'],
                            'co_applicant_gender' => $coapplicant['gender'],
                            'co_applicant_age' => $coapplicant['age'],
                            'co_applicant_father_name' => $coapplicant['fathername'],
                            'co_applicant_aadhar' => $coapplicant['aadharnumber'],
                            'co_applicant_pan' => $coapplicant['pannumber'],
                            'co_applicant_mobile' => $coapplicant['mobilenumber'],
                            'created_by' => Auth::id(),
                            'image_path' => $imageFullPath,
                            'aadhaar_file_path' => $aadhaarFullPath, // Store the full path
                            'pan_file_path' => $panFullPath // Store the full path
                        ]
                    );
                    if (!$tempCoapplicant) {
                        $allSaved = false;
                    }
                }
            }
            return $allSaved;
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['status' => false, 'message' => 'An error occurred while storing Coapplicants', 'error' => $e->getMessage()], 500);
        }
    }
}
