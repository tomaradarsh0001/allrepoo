<?php

namespace App\Services;

use App\Models\Item;
use App\Models\PropertyMaster;
use App\Models\SplitedPropertyDetail;

class PropertyMasterService
{
    public function formatPropertyDetails($prop, $getSplited = false, $splitedPropId = null)
    {
        $leaseTenure = dateDiffInYears($prop->propertyLeaseDetail->date_of_expiration, $prop->propertyLeaseDetail->doe);
        $leaseTypeName = Item::itemNameById($prop->propertyLeaseDetail->type_of_lease);
        $leaseDate = $prop->propertyLeaseDetail->doe;
        $prop->proprtyTypeName = Item::itemNameById($prop->property_type);
        $prop->proprtySubtypeName = Item::itemNameById($prop->property_sub_type);
        $prop->landTypeName =  Item::itemNameById($prop->land_type);
        $prop->statusName = Item::itemNameById($prop->status);
        $prop->address = $prop->propertyLeaseDetail->presently_known_as;
        $prop->email = $prop->phone_no = null;
        if (isset($prop->propertyContactDetail) && !is_null($prop->propertyContactDetail)) {
            $prop->email = $prop->propertyContactDetail->email;
            $prop->phone_no = $prop->propertyContactDetail->phone_no;
        }
        $prop->leaseTenure = $leaseTenure;
        $prop->leaseTypeName = $leaseTypeName;
        $prop->leaseDate = $leaseDate;
        $prop->landSize = $prop->propertyLeaseDetail->plot_area_in_sqm;
        $prop->rgr = (!empty($prop->PropertyMiscDetail)) ? $prop->PropertyMiscDetail->is_gr_revised_ever : null;
        $prop->lesseName = (!is_null($prop->currentLesseeName)) ? $prop->currentLesseeName->lessees_name : null; //lesse name can be null id property splited
        if (!$getSplited) {
            return $prop;
        }
        $returnRows = [];
        $splitedProps = $prop->splitedPropertyDetail;
        foreach ($splitedProps as $sprop) {

            $sprop->old_propert_id = $sprop->old_property_id;
            $sprop->unique_propert_id = $sprop->child_prop_id;
            $sprop->proprtyTypeName = Item::itemNameById($prop->property_type);
            $sprop->proprtySubtypeName = Item::itemNameById($prop->property_sub_type);
            $sprop->landTypeName =  Item::itemNameById($prop->land_type);
            $sprop->statusName = Item::itemNameById($sprop->property_status);
            $sprop->address = $sprop->presently_known_as;
            $sprop->leaseTenure = $leaseTenure;
            $sprop->leaseTypeName = $leaseTypeName;
            $sprop->leaseDate = $leaseDate;
            $sprop->landSize = $sprop->area_in_sqm;
            $sprop->rgr = (!empty($sprop->PropertyMiscDetail)) ? $sprop->PropertyMiscDetail->is_gr_revised_ever : null;
            $lesseNames = isset($sprop->currentLesseeName) ? $sprop->currentLesseeName->lessees_name : null;
            $sprop->lesseName = $lesseNames;
            if ($getSplited && $splitedPropId == $sprop->id) {
                return $sprop;
                break;
            }
            $returnRows[] = $sprop;
        }
        return $returnRows;
    }

    public function propertyFromSelected($propertyId)
    {
        // dd("property id = $propertyId");
        if (strpos($propertyId, '_')) {
            $id_arr = explode('_', $propertyId);
            $masterPropertyId = $id_arr[0];
            $childPropertyId = $id_arr[1];
            if ($masterPropertyId != "" && $childPropertyId != "") {
                $masterProperty = PropertyMaster::find($masterPropertyId);
                $childProperty = SplitedPropertyDetail::find($childPropertyId);
                return ['status' => 'success', 'masterProperty' => $masterProperty, 'childProperty' => $childProperty];
            } else {
                return ['status' => 'error', 'details' => config('messages.property.error.invalidId')];
            }
        } else {
            $masterProperty = PropertyMaster::where('old_propert_id', $propertyId)->first();
            if (empty($masterProperty)) {
                $childProperty = SplitedPropertyDetail::where('old_property_id', $propertyId)->first();
                if (empty($childProperty)) {
                    return ['status' => 'error', 'details' => config('messages.property.error.notFound')];
                }
                $masterPropertyId = $childProperty->property_master_id;
                $masterProperty = PropertyMaster::find($masterPropertyId);
                return ['status' => 'success', 'masterProperty' => $masterProperty, 'childProperty' => $childProperty];
            } else {
                return ['status' => 'success', 'masterProperty' => $masterProperty];
            }
        }
    }
}