<?php

namespace App\Http\Controllers\application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempSubstitutionMutation;
use App\Models\TempCoapplicant;
use App\Models\PropertyMaster;
use App\Models\TempDocument;
use App\Models\TempDocumentKey;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use DB;
use App\Helpers\GeneralFunctions;
use Illuminate\Support\Facades\Validator;

class MutationContoller extends Controller
{
    // for storing mutation step first data - Sourav Chauhan (17/sep/2024)
    public function mutationStepFirst(Request $request)
    {
        //document values validation - SOURAV CHAUHAN (7/oct/2024)
        $messages = [
            'statusofapplicant.required' => 'Please select the applicant status',
            'mutNameAsConLease.required' => 'Executed in favour of is required',
            'mutExecutedOnAsConLease.required' => 'Executed on is required',
            'mutRegnoAsConLease.required' => 'Regn. No. is required',
            'mutBooknoAsConLease.required' => 'Book No. is required',
            'mutVolumenoAsConLease.required' => 'Volume No. is required',
            'mutPagenoAsConLease.required' => 'Page No. is required',
            'mutRegdateAsConLease.required' => 'Regn. Date. is required',
            'soughtByApplicantDocuments.required' => 'Sought by applicant document is required',
        ];

        $validator = Validator::make($request->all(), [
            'statusofapplicant' => 'required',
            'mutNameAsConLease' => 'required',
            'mutExecutedOnAsConLease' => 'required',
            'mutRegnoAsConLease' => 'required',
            'mutBooknoAsConLease' => 'required',
            'mutVolumenoAsConLease' => 'required',
            'mutPagenoAsConLease' => 'required',
            'mutRegdateAsConLease' => 'required',
            'soughtByApplicantDocuments' => 'required'
        ], $messages);

        if ($validator->fails()) {
            // Log the error message if validation fails
            Log::info("| " . Auth::user()->email . " | Mutation step first all values not entered: " . json_encode($validator->errors()));
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        // dd($request->all());

        try {
            return DB::transaction(function () use ($request) {
                $tempSubstitutionMutation = null;
                $updateId = $request->updateId;
                if ($request->propertyStatus == 'Free Hold') {
                    $propertyStatus = 952;
                } else {
                    $propertyStatus = 951;
                }
                if ($updateId != '0') {
                    $tempSubstitutionMutation = TempSubstitutionMutation::find($updateId);
                    if (isset($tempSubstitutionMutation)) {
                        $tempSubstitutionMutation->property_status = $propertyStatus;
                        $tempSubstitutionMutation->status_of_applicant = $request->statusofapplicant;
                        $tempSubstitutionMutation->name_as_per_lease_conv_deed = $request->mutNameAsConLease;
                        $tempSubstitutionMutation->executed_on = $request->mutExecutedOnAsConLease;
                        $tempSubstitutionMutation->reg_no_as_per_lease_conv_deed = $request->mutRegnoAsConLease;
                        $tempSubstitutionMutation->book_no_as_per_lease_conv_deed = $request->mutBooknoAsConLease;
                        $tempSubstitutionMutation->volume_no_as_per_lease_conv_deed = $request->mutVolumenoAsConLease;
                        $tempSubstitutionMutation->page_no_as_per_lease_conv_deed = $request->mutPagenoAsConLease;
                        $tempSubstitutionMutation->reg_date_as_per_lease_conv_deed = $request->mutRegdateAsConLease;
                        $tempSubstitutionMutation->sought_on_basis_of_documents = json_encode($request->soughtByApplicantDocuments);
                        $tempSubstitutionMutation->property_stands_mortgaged = $request->mutPropertyMortgaged;
                        $tempSubstitutionMutation->mortgaged_remark = ($request->mutPropertyMortgaged == 1) ? $request->mutMortgagedRemarks : NULL;
                        $tempSubstitutionMutation->is_basis_of_court_order = $request->mutCourtorder;
                        $tempSubstitutionMutation->updated_by = Auth::user()->id;
                        if ($tempSubstitutionMutation->save()) {
                            $modelId = $tempSubstitutionMutation->id;
                            $stepOneSubmit = $this->updateTempCoApplicants($request->coapplicants, $modelId);
                            if ($stepOneSubmit) {
                                $response = ['status' => true, 'message' => 'Property Details Updated Successfully', 'data' => $tempSubstitutionMutation];
                            } else {
                                $response = ['status' => false, 'message' => 'Something went wrong!', 'data' => 0];
                            }
                        } else {
                            $response = ['status' => false, 'message' => 'Something went wrong!', 'data' => 0];
                        }
                    } else {
                        $response = ['status' => false, 'message' => 'Something went wrong!', 'data' => 0];
                    }
                } else {
                    $propertyDetails = PropertyMaster::where('old_propert_id', $request->propertyid)->first();
                    $tempSubstitutionMutation = TempSubstitutionMutation::create([
                        'old_property_id' => $request->propertyid,
                        'new_property_id' => $propertyDetails['unique_propert_id'],
                        'property_master_id' => $propertyDetails['id'],
                        'property_status' => $propertyStatus,
                        'status_of_applicant' => $request->statusofapplicant,
                        'name_as_per_lease_conv_deed' => $request->mutNameAsConLease,
                        'executed_on' => $request->mutExecutedOnAsConLease,
                        'reg_no_as_per_lease_conv_deed' => $request->mutRegnoAsConLease,
                        'book_no_as_per_lease_conv_deed' => $request->mutBooknoAsConLease,
                        'volume_no_as_per_lease_conv_deed' => $request->mutVolumenoAsConLease,
                        'page_no_as_per_lease_conv_deed' => $request->mutPagenoAsConLease,
                        'reg_date_as_per_lease_conv_deed' => $request->mutRegdateAsConLease,
                        'sought_on_basis_of_documents' => json_encode($request->soughtByApplicantDocuments),
                        'property_stands_mortgaged' => $request->mutPropertyMortgaged,
                        'mortgaged_remark' => ($request->mutPropertyMortgaged == 1) ? $request->mutMortgagedRemarks : NULL,
                        'is_basis_of_court_order' => $request->mutCourtorder,
                        'created_by' => Auth::user()->id,
                    ]);
                    if ($tempSubstitutionMutation) {
                        $modelId = $tempSubstitutionMutation->id;
                        $stepOneSubmit = $this->storeTempCoApplicants($request->coapplicants, $modelId);
                        if ($stepOneSubmit) {
                            $response = ['status' => true, 'message' => 'Property Details Saved Successfully', 'data' => $tempSubstitutionMutation];
                        } else {
                            $response = ['status' => false, 'message' => 'Something went wrong!', 'data' => 0];
                        }
                    } else {
                        $response = ['status' => false, 'message' => 'Something went wrong!', 'data' => 0];
                    }
                }
                return json_encode($response);
            });
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['status' => false, 'message' => 'An error occurred while mutation appliation submission'], 500);
        }
    }


    //for storing temp co Applicants - Sourav Chauhan (17/sep/2024)
    protected function storeTempCoApplicants($coapplicants, $modelId)
    {
        try {
            $allSaved = true;
            foreach ($coapplicants as $coapplicant) {
                if (!empty($coapplicant['name'])) {
                    $tempCoapplicant = TempCoapplicant::create([
                        'service_type' => getServiceType('SUB_MUT'),
                        'model_name' => 'TempSubstitutionMutation',
                        'model_id' => $modelId,
                        'co_applicant_name' => $coapplicant['name'],
                        'co_applicant_gender' => $coapplicant['gender'],
                        'co_applicant_age' => $coapplicant['age'],
                        'co_applicant_father_name' => $coapplicant['fathername'],
                        'co_applicant_aadhar' => $coapplicant['aadharnumber'],
                        'co_applicant_pan' => $coapplicant['pannumber'],
                        'co_applicant_mobile' => $coapplicant['mobilenumber'],
                        'created_by' => Auth::user()->id,
                    ]);
                    if (!$tempCoapplicant) {
                        $allSaved = false;
                    }
                }
            }
            return $allSaved;
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['status' => false, 'message' => 'An error occurred while while storing Coapplicants', 'error' => $e->getMessage()], 500);
        }
    }

    //for storing temp co Applicants - Sourav Chauhan (17/sep/2024)
    protected function updateTempCoApplicants($coapplicants, $modelId)
    {
        try {
            $allSaved = true;
            $delCooapplicant = TempCoapplicant::where('model_id', $modelId)
                ->where('model_name', 'TempSubstitutionMutation')
                ->delete();
            foreach ($coapplicants as $coapplicant) {
                // dd($coapplicant);
                if (!empty($coapplicant['name'])) {
                    $tempCoapplicant = TempCoapplicant::create([
                        'service_type' => getServiceType('SUB_MUT'),
                        'model_name' => 'TempSubstitutionMutation',
                        'model_id' => $modelId,
                        'co_applicant_name' => $coapplicant['name'],
                        'co_applicant_gender' => $coapplicant['gender'],
                        'co_applicant_age' => $coapplicant['age'],
                        'co_applicant_father_name' => $coapplicant['fathername'],
                        'co_applicant_aadhar' => $coapplicant['aadharnumber'],
                        'co_applicant_pan' => $coapplicant['pannumber'],
                        'co_applicant_mobile' => $coapplicant['mobilenumber'],
                        'created_by' => Auth::user()->id,
                    ]);
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


    // for storing mutation step second documents data - Sourav Chauhan (17/sep/2024)
    public function mutationStepSecond(Request $request)
    {

        //documents validation - SOURAV CHAUHAN (7/oct/2024)
        $documentsRequired = array_keys(config('applicationDocumentType.MUTATION.Required'));
        foreach ($documentsRequired as $document) {
            $serviceType = getServiceType('SUB_MUT');
            $isDocUploaded = TempDocument::where('service_type', $serviceType)->where('model_id', $request->updateId)->where('document_type', $document)->first();
            if (empty($isDocUploaded)) {
                Log::info("| " . Auth::user()->email . " | Mutation step second all documents not uploaded");
                return response()->json(['status' => false, 'message' => 'Please provide all required documents.']);
            }
        }


        //document values validation - SOURAV CHAUHAN (7/oct/2024)
        // $validator = Validator::make($request->all(), [
        //     'affidavitsDateAttestation' => 'required',
        //     'affidavitsAttestedby' => 'required',
        //     'indemnityBondDateAttestation' => 'required',
        //     'indemnityBondattestedby' => 'required',
        //     'leaseConvDeedDateOfExecution' => 'required',
        //     'leaseConvDeedLesseename' => 'required',
        //     'panCertificateNo' => 'required',
        //     'panDateIssue' => 'required',
        //     'aadharCertificateNo' => 'required',
        //     'aadharDateIssue' => 'required',
        //     'newspaperName' => 'required',
        //     'publicNoticeDate' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     // Log the error message if validation fails
        //     Log::info("| " . Auth::user()->email . " | Mutation step second all documents keys not entered: " . json_encode($validator->errors()));
        //     return response()->json(['status' => false, 'message' => 'Please provide all required document values.']);
        // }

        try {
            return DB::transaction(function () use ($request) {
                $appMutId = $request->updateId;
                if (isset($appMutId)) { //if hidden ID available
                    $isMutAppAvailable = TempSubstitutionMutation::where('id', $appMutId)->first();
                    if (!empty($isMutAppAvailable)) { //if mutation available for this ID
                        // $serviceType = getServiceType('SUB_MUT');
                        // $applicationDocumentType = config('applicationDocumentType.MUTATION.Required');
                        //for storing documents data
                        // $doumentDataStored = $this->storeDataForDocuments($request, $serviceType, $applicationDocumentType);
                        // if ($doumentDataStored) {
                        $response = ['status' => true, 'message' => 'Property Documents Saved Successfully'];
                        // }
                    } else {
                        Log::info("| " . Auth::user()->email . " | Application not available in database");
                        $response = ['status' => false, 'message' => 'Something went wrong'];
                    }
                } else {
                    Log::info("| " . Auth::user()->email . " | Application ID not available in hidden");
                    $response = ['status' => false, 'message' => 'Something went wrong'];
                }
                return json_encode($response);
            });
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['status' => false, 'message' => 'An error occurred while storing appliation documents'], 500);
        }
    }


    // for storing mutation step third documents data - Sourav Chauhan (20/sep/2024)
    //moved to general functions by Nitin Raghuvanshi - 07-10-2024
    public function mutationStepThird(Request $request)
    {
        //documents validation - SOURAV CHAUHAN (10/oct/2024)
        // $documentsOptional = config('applicationDocumentType.MUTATION.Optional');
        // foreach($documentsOptional as $key => $documents){
        //     $serviceType = getServiceType('SUB_MUT');
        //     $isDocUploaded = TempDocument::where('service_type',$serviceType)->where('model_id',$request->updateId)->where('document_type',$key)->first();
        //     if(!empty($isDocUploaded)){
        //         foreach($documents as $inputName => $document){
        //             if(empty($request->$inputName)){
        //                 Log::info("| " . Auth::user()->email . " | Mutation step three " .$key." documents value ".$inputName." not entered");
        //                 return response()->json(['status' => false, 'message' => 'Please provide all required values for '.$key.' .']);
        //             }
        //         }
        //     }
        // }

        //document validation according to the checked documents at step one

        $tempSubstitutionMutation = TempSubstitutionMutation::find($request->updateId);
        if (isset($tempSubstitutionMutation)) {
            $serviceType = getServiceType('SUB_MUT');
            $checkedDocuments = json_decode($tempSubstitutionMutation['sought_on_basis_of_documents']);
            foreach ($checkedDocuments as $checkedDocument) {
                $itemName = getServiceNameByCode($checkedDocument);
                $isDocUploaded = TempDocument::where('service_type', $serviceType)->where('model_id', $request->updateId)->where('document_type', $itemName)->first();
                if (empty($isDocUploaded)) {
                    Log::info("| " . Auth::user()->email . " | Mutation step three " . $checkedDocument . " documents not uploaded");
                    return response()->json(['status' => false, 'message' => 'Please provide all required documents']);
                } else {
                    $consent = $request->agreeConsent;
                    if ($consent != 1) {
                        return response()->json(['status' => false, 'message' => 'Please agree to terms & conditions']);
                    }
                }
            }
        }

        try {
            return DB::transaction(function () use ($request) {
                $appMutId = $request->updateId;
                if (isset($appMutId)) { //if hidden ID available
                    $isMutAppAvailable = TempSubstitutionMutation::where('id', $appMutId)->first();
                    if (!empty($isMutAppAvailable)) { //if mutation available for this ID
                        $serviceType = getServiceType('SUB_MUT');
                        $applicationDocumentType = config('applicationDocumentType.MUTATION.Optional');

                            //for storing documents data
                            // $this->storeDataForDocuments($request,$serviceType,$applicationDocumentType);
                            $application = TempSubstitutionMutation::where('id',$appMutId)->first();
                            $application->undertaking = $request->agreeConsent;
                            if ($application->save()) {
                                $tempModelName = config('applicationDocumentType.MUTATION.TempModelName');
                                $paymentComplete = GeneralFunctions::paymentComplete($appMutId, $tempModelName);
                                if ($paymentComplete) {
                                    $transactionSuccess = true;
                                    //to convert temp application to final application - SOURAV CHAUHAN (1/Oct/2024)
                                    GeneralFunctions::convertTempAppToFinal($appMutId, $tempModelName,$paymentComplete);
                                    $response = ['status' => true, 'message' => 'Mutation application submitted Successfully'];
                                }
                            }
                    } else {
                        Log::info("| " . Auth::user()->email . " | Application not available in database");
                        $response = ['status' => false, 'message' => 'Something went wrong'];
                    }
                } else {
                    Log::info("| " . Auth::user()->email . " | Application ID not available in hidden");
                    $response = ['status' => false, 'message' => 'Something went wrong'];
                }
                return json_encode($response);
            });
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['status' => false, 'message' => 'An error occurred while storing application documents'], 500);
        }
    }

    //payment completion - SOURAV CHAUHAN (1/oct/2024)


    //for storing documents data - SOURAV CHAUHAN (20/sep/2024)
    protected function storeDataForDocuments($request, $serviceType, $applicationDocumentType)
    {
        $doumentDataStored = false;
        $firstLevelKeys = array_keys($applicationDocumentType);
        foreach ($firstLevelKeys as $firstLevelKey) { //document
            $values = $applicationDocumentType[$firstLevelKey];
            foreach ($values as $key => $value) { //no of values
                $savedDocumentDetails = TempDocument::where('service_type', $serviceType)->where('model_id', $request->updateId)->where('document_type', $firstLevelKey)->first();
                if ($savedDocumentDetails) {
                    $isDocumentValueAvailable = TempDocumentKey::where('temp_document_id', $savedDocumentDetails->id)->where('key', $key)->first();
                    if (!empty($isDocumentValueAvailable)) {
                        $isDocumentValueAvailable->value = $request->$key;
                        $isDocumentValueAvailable->updated_by = Auth::user()->id;
                        $isDocumentValueAvailable->save();
                    } else {
                        $tempDocumentKey = TempDocumentKey::create([
                            'temp_document_id' => $savedDocumentDetails->id,
                            'key' => $key,
                            'value' => $request->$key,
                            'created_by' => Auth::user()->id
                        ]);
                    }
                }
            }
        }
        $doumentDataStored = true;
        return $doumentDataStored;
    }
}
