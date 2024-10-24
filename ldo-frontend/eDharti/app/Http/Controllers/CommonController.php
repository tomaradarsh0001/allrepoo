<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PropertyMaster;
use App\Models\PropertyLeaseDetail;

class CommonController extends Controller
{
    public function changeLeaseExpirationDate(Request $request){

        $property_details = ['49461', '75648', '50466', '63880', '48862', '63886', '75647', '50425', '20904', '49390', '49460', '49915', '49385', '63860', '27008', '25889', '64008', '64010', '21118', '64035', '64046', '64045', '64044', '64053', '49405', '64038', '64037', '64039', '26365', '64041', '64040', '66427', '66943', '38405', '78546', '76988', '43885', '60785', '62073', '22635', '28572', '29713', '56191', '54423', '56362', '38089'];
        for($i =0; $i< count($property_details); $i++){

            $master_property  = PropertyMaster::where('old_propert_id', $property_details[$i])->first();

            $lease_details = PropertyLeaseDetail::where('property_master_id', $master_property->id)->first();

            if($lease_details){
                $date_of_execution = new \DateTime($lease_details->doe);
                $date_of_execution->modify('+99 years'); // 99 years ahead
                $lease_details->date_of_expiration = $date_of_execution->format('Y-m-d');
                $lease_details->save();
                echo 'PID: '.$property_details[$i].'--DOE: '.$lease_details->doe.'--DOEx '.$lease_details->date_of_expiration.'<br>';

            }
        }
        
    }
}
