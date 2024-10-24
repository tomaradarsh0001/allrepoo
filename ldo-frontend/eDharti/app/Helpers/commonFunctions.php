<?php

use App\Models\Item;
use App\Models\PropertyLeaseDetail;
use App\Models\PropertyMaster;
use App\Models\SplitedPropertyDetail;
use Illuminate\Support\Facades\Log;

if (!function_exists('customNumFormat')) {
    function customNumFormat($num)
    {
        if ($num < 1000) {
            return $num;
        } else {
            $numStr = (string)$num;
            $decArray = explode('.', $numStr);
            $decimalPart = (count($decArray) > 1) ? $decArray[1] : '';
            $numParts = [];
            $devideBy = 1000;
            $intPart = (int)($num / $devideBy);
            $numParts[] = str_pad($num % $devideBy, 3, '0', STR_PAD_LEFT); //initially we need saperator before three digits from right, ones , thens, hundreds
            $devideBy = 100;
            while ($intPart > 99) {
                $tempInt = (int)($intPart / $devideBy);
                $numParts[] = str_pad($intPart % $devideBy, 2, '0', STR_PAD_LEFT); // add ',' after every two digits from rightafter 
                $intPart = $tempInt;
            }
            $intPart =  $intPart . ',' . implode(',', array_reverse($numParts));
            if (strlen($decimalPart) > 0) {
                return $intPart . '.' . $decimalPart;
            } else {
                return $intPart;
            }
        }
    }
}

if (!function_exists('dateDiffInYears')) {
    function dateDiffInYears($date1, $date2)
    {

        // Convert strings to DateTime objects
        $d1 = new \DateTime($date1);
        $d2 = new \DateTime($date2);

        // Calculate the difference between the two dates
        $interval = $d1->diff($d2);

        // Get the difference in years
        return $interval->y;
    }
}

if (!function_exists('getServiceType')) {
    function getServiceType($code)
    {
        $item = Item::where('item_code', $code)->first();
        if ($item) {
            return $item->id;
        } else {
            Log::info("Item not available for " . $code);
        }
    }
}

if (!function_exists('getServiceNameByCode')) {
    function getServiceNameByCode($code)
    {
        $item = Item::where('item_code', $code)->first();
        if ($item) {
            return $item->item_name;
        } else {
            Log::info("Item not available for " . $code);
        }
    }
}

if (!function_exists('getServiceCodeById')) {
    function getServiceCodeById($id)
    {
        $item = Item::where('id', $id)->first();
        if ($item) {
            return $item->item_code;
        } else {
            Log::info("Item not available for " . $id);
        }
    }
}

if (!function_exists('getServiceNameById')) {
    function getServiceNameById($id)
    {
        $item = Item::find($id);
        if ($item) {
            return $item->item_name;
        } else {
            Log::info("Item available for " . $id);
        }
    }
}

if (!function_exists('getServiceTypeColorCode')) {
    function getServiceTypeColorCode($code)
    {
        $item = Item::where('item_code', $code)->first();
        if ($item) {
            return $item->color_code;
        } else {
            Log::info("Color Code not available for " . $code);
        }
    }
}

if (!function_exists('getStatusName')) {
    function getStatusName($code)
    {
        $item = Item::where('item_code', $code)->first();
        if ($item) {
            return $item->id;
        } else {
            Log::info("Item not available for " . $code);
        }
    }
}

if (!function_exists('getBlockThroughLocality')) {
    function getBlockThroughLocality($locality)
    {
        $blocks = PropertyMaster::select('block_no')
            ->where('new_colony_name', $locality)
            ->orderByRaw("CAST(block_no AS UNSIGNED), block_no")
            ->distinct()
            ->get();
        return $blocks;
    }
}

if (!function_exists('getPlotThroughBlock')) {
    function getPlotThroughBlock($locality, $block)
    {
        $plots = PropertyMaster::where('new_colony_name', $locality)
            ->where('block_no', $block)
            ->get();
        $data = [];
        foreach ($plots as $plot) {
            if ($plot->is_joint_property) {
                $splited = SplitedPropertyDetail::select('plot_flat_no')->where('property_master_id', $plot->id)->get();
                // dd($splited);
                foreach ($splited as $split) {
                    $data[] = $split->plot_flat_no;
                }
            } else {
                $data[] = $plot->plot_or_property_no;
            }
        }
        return array_unique($data);
    }
}

if (!function_exists('getKnownAsThroughPlot')) {
    function getKnownAsThroughPlot($locality, $block, $plot)
    {
        $property = PropertyMaster::where('new_colony_name', $locality)
            ->where('block_no', $block)
            ->where('plot_or_property_no', $plot)
            ->first();

        if ($property) {
            // If property is found, retrieve the presently known names
            $property_master_id = $property->id;
            $knownAs = PropertyLeaseDetail::where('property_master_id', $property_master_id)
                ->pluck('presently_known_as')
                ->toArray();  // Convert collection to array
        } else {
            // If property not found, retrieve the plot/flat numbers from Splited Property Detail table
            $knownAs = [];
            $data = SplitedPropertyDetail::where('plot_flat_no', $plot)
                ->get();

            foreach ($data as $known) {
                $knownAs[] = $known->plot_flat_no;
            }
        }
        return array_unique($knownAs);
    }
}



if (!function_exists('getStatusDetailsById')) {
    function getStatusDetailsById($id)
    {
        $item = Item::find($id);
        if ($item) {
            return $item;
        } else {
            Log::info("Item not available for " . $id);
        }
    }
}

if (!function_exists('truncate_url')) {
    function truncate_url($url, $length = 20, $ellipsis = '....')
    {
        if (strlen($url) <= $length) {
            return $url;
        }

        return substr($url, 0, $length) . $ellipsis;
    }
}
if (!function_exists('getAge')) {
    function getAge($dob)
    {
        $dobDate = new DateTime($dob);
        $today = new DateTime('today');
        $age = $dobDate->diff($today)->y;
        return $age;
    }
}
if (!function_exists('getItemsByGroupId')) {
    function getItemsByGroupId($id)
    {
        return Item::where('group_id', $id)->where('is_active', 1)->orderBy('item_order')->get();
    }
}
if (!function_exists('getApplicationStatusList')) {
    function getApplicationStatusList($withDisposed = false)
    {
        $applicationStatusList = getItemsByGroupId(1031);
        if ($withDisposed) {
            //remove aproved and rejected status
            $applicationStatusList = $applicationStatusList->whereNotIn('item_code', ['APP_APR', 'APP_REJ']);

            // Manually create a new Item model instance for "Disposed"
            $disposedStatus = new Item();
            $disposedStatus->item_code = 'APP_DIS';
            $disposedStatus->item_name = 'Disposed';
            $disposedStatus->item_order = 6;

            // Append the new model instance to the existing collection
            $applicationStatusList = $applicationStatusList->push($disposedStatus);
        }
        return $applicationStatusList->values();  // Reset the keys again after push
    }
}
