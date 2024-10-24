<?php

namespace App\Http\Controllers\application;

use App\Helpers\GeneralFunctions;
use App\Http\Controllers\Controller;
use App\Models\TempConversionApplication;
use App\Services\PropertyMasterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConversionController extends Controller
{
    public function step1Submit(Request $request, PropertyMasterService $propertyMasterService)
    {
        // dd($request->all());

        $messages = [
            'statusofapplicant.required' => 'Please select the applicant status',
            'convNameAsOnLease.required' => 'Executed in favour of is required',
            'convExecutedOnAsOnLease.required' => 'Executed on is required',
            'convRegnoAsOnLease.required' => 'Regn. No. is required',
            'convBooknoAsOnLease.required' => 'Book No. is required',
            'convVolumenoAsOnLease.required' => 'Volume No. is required',
            'convPagenoFrom.required' => 'Page No. From is required',
            'convPagenoTo.required' => 'Page No.  To is required',
            'convRegdateAsOnLease.required' => 'Regn. Date. is required',
        ];

        $validator = Validator::make($request->all(), [
            'statusofapplicant' => 'required',
            'convNameAsOnLease' => 'required',
            'convExecutedOnAsOnLease' => 'required',
            'convRegnoAsOnLease' => 'required',
            'convBooknoAsOnLease' => 'required',
            'convVolumenoAsOnLease' => 'required',
            'convPagenoFrom' => 'required',
            'convPagenoTo' => 'required',
            'convRegdateAsOnLease' => 'required',
        ], $messages);

        if ($validator->fails()) {
            // Log the error message if validation fails
            Log::info("| " . Auth::user()->email . " | Conversion step first all values not entered: " . json_encode($validator->errors()));
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        // try {
        return DB::transaction(function () use ($request, $propertyMasterService) {
            $tempConversion = null;
            $updateId = $request->updateId;
            $properties = $propertyMasterService->propertyFromSelected($request->propertyid);
            if ($properties['status'] == 'error') {
                return response()->json($properties);
            }
            $masterProperty = $properties['masterProperty'];
            $childProperty = isset($properties['childProperty']) ? $properties['childProperty'] : null;

            $tempConversion = TempConversionApplication::updateOrCreate(
                ['id' => $updateId],
                [
                    'old_property_id' => $request->propertyid,
                    'new_property_id' => $masterProperty->unique_propert_id,
                    'property_master_id' => $masterProperty->id,
                    'splited_property_detail_id' => !is_null($childProperty) ? $childProperty->id : null,
                    'status_of_applicant' => $request->statusofapplicant,
                    'applicant_name' => $request->convname,
                    'relation_prefix' => $request->convRelationPrefix,
                    'relation_name' => $request->convRelationName,
                    'executed_on' => $request->convExecutedOnAsOnLease,
                    'reg_no' => $request->convRegnoAsOnLease,
                    'book_no' => $request->convBooknoAsOnLease,
                    'volume_no' => $request->convVolumenoAsOnLease,
                    'page_no' => $request->convPagenoFrom . '-' . $request->convPagenoTo,
                    'reg_date' => $request->convRegdateAsOnLease,
                    'is_court_order' => $request->courtorderConversion == 'Yes',
                    'case_no' => $request->convCaseNo ?? null,
                    'case_detail' => $request->convCaseDetail ?? null,
                    'updated_by' => Auth::id(),
                ]
            );
            if ($tempConversion->wasRecentlyCreated) {
                $tempConversion->created_by = Auth::id();
                $tempConversion->save();
            }

            $modelName = 'TempConversionApplication';
            $modelId = $tempConversion->id;
            $serviceType = 'CONVERSION';

            $stepOneSubmit = GeneralFunctions::storeTempCoApplicants($serviceType, $modelName, $modelId, $masterProperty->newColony->code, $request);
            if ($stepOneSubmit) {
                return response()->json(['status' => 'success', 'message' => 'Property Details Saved Successfully', 'data' => $tempConversion]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong!', 'data' => 0]);
            }
            /* if ($updateId != '0') {
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
                        $tempSubstitutionMutation->sought_on_basis_of = $request->soughtByApplicant;
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
                        'sought_on_basis_of' => $request->soughtByApplicant,
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
                } */
            // return json_encode($response);
        });
        // } catch (\Exception $e) {
        //     Log::info($e->getMessage());
        //     return response()->json(['status' => false, 'message' => 'An error occurred while mutation appliation submission'], 500);
        // }
    }
}
