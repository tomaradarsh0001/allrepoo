<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OldPropertId;
use App\Models\PropertyMaster;
use App\Models\OldColony;
use App\Models\SplitedPropertyDetail;
use App\Models\Item;
use Illuminate\Support\Facades\Http;


class MPropertyIdController extends Controller
{
    // public function propertySearchById(Request $request){


    //     $property = OldPropertId::where('PropertyID', $request->property_id)->whereIn('Status', ['LH', 'FH', 'VT', 'OTH'])->first();

    //     $check_property_id_in_new_record = PropertyMaster::where('old_propert_id', $request->property_id)->count() ;

    //     if($check_property_id_in_new_record == 0 && !empty($property)){

    //         $colony = OldColony::colonyIdByColonyCode($property->ColonyCode);
    //         $property_status = Item::getItemIdUsingItemCode($property->Status, 109);
    //         $land_type = Item::getItemIdUsingItemCode($property->LandType, 1051);

    //         $response = ['status' => true, 'message' => 'Property details fetched', 'data' => ['property_id' => $request->property_id, 'file_number' => $property->FileNumber, 'property_status' => $property_status, 'colony_id' => $colony['0']->id, 'land_type' => $land_type]];
    //     } elseif($check_property_id_in_new_record > 0){
    //         $response = ['status' => false, 'message' => 'Provided Property ID is already saved.', 'data' => NULL];
    //     }else{
    //         $response = ['status' => false, 'message' => 'Provided Property ID is wrong/dulicate marked.', 'data' => NULL];
    //     }

    //     return json_encode($response);
    // }
    public function propertySearchById(Request $request)
    {

        $response = Http::post('https://ldo.gov.in/eDhartiAPI/Api/GetValues/PropertyWiseStatus?EnteredPropertyID=' . $request->property_id);
        
        $jsonData = $response->json();
        if(isset($jsonData['Message'])){
            $response = ['status' => false, 'message' => 'Provided Property ID is not available.', 'data' => NULL];
        } else {
            // $property = OldPropertId::where('PropertyID', $request->property_id)->whereIn('Status', ['LH', 'FH', 'VT', 'OTH'])->first();

            $check_property_id_in_new_record = PropertyMaster::where('old_propert_id', $request->property_id)->count();
            $check_property_id_in_child_property = SplitedPropertyDetail::where('old_property_id', $request->property_id)->count();
            // dd($check_property_id_in_new_record,$check_property_id_in_child_property);

            if ($check_property_id_in_new_record == 0 && $check_property_id_in_child_property == 0) {
                $colony = OldColony::colonyIdByColonyCode($jsonData[0]['ColonyCode']);
                $property_status = Item::getItemIdUsingItemCode($jsonData[0]['Status'], 109);
                $land_type = Item::getItemIdUsingItemCode($jsonData[0]['LandType'], 1051);
                $response = ['status' => true, 'message' => 'Property details fetched', 'data' => ['property_id' => $request->property_id, 'file_number' => $jsonData[0]['FileNumber'], 'property_status' => $property_status, 'colony_id' => $colony['0']->id, 'land_type' => $land_type]];
            } elseif ($check_property_id_in_child_property > 0){
                $splitedProperty = SplitedPropertyDetail::where('old_property_id', $request->property_id)->first();
                $parentProperty = PropertyMaster::find($splitedProperty->property_master_id);
                $response = ['status' => false, 'message' => 'Provided Property ID is a child property of '.$parentProperty->old_propert_id, 'data' => NULL];
            } elseif ($check_property_id_in_new_record > 0) {
                $response = ['status' => false, 'message' => 'Provided Property ID is already saved.', 'data' => NULL];
            } else {
                $response = ['status' => false, 'message' => 'Provided Property ID is wrong/dulicate marked.', 'data' => NULL];
            }

        }
        return json_encode($response);

    }

    public function isPropertyAvailable(Request $request){
        $propertyId = $request->property_id;
        if (preg_match('/^\d{5}$/', $propertyId)) {
            $isPropertyExists = PropertyMaster::where('old_propert_id', $propertyId)->first();
            if($isPropertyExists){
                $data = [
                    'location' => 'parent',
                    'id' => $isPropertyExists['id']
                ];
                $response = ['status' => false, 'message' => 'Id not available','data' => $data];
            } else {
                $isSplitedPropertyExists = SplitedPropertyDetail::where('old_property_id', $propertyId)->first();
                if($isSplitedPropertyExists){
                    $data = [
                        'location' => 'child',
                        'id' => $isSplitedPropertyExists['id']
                    ];
                    $response = ['status' => false, 'message' => 'Id not available','data' => $data];
                } else {
                    $response = ['status' => true, 'message' => 'Id available'];
                }
            }
            
        } else {
            $response = ['status' => false, 'message' => 'Id not available'];
        }
        return json_encode($response);

    }
}
