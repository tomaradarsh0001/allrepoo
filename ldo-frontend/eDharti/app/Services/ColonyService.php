<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\OldColony;
use App\Models\PropertyMaster;
use App\Models\SplitedPropertyDetail;
use Illuminate\Support\Facades\DB;

class ColonyService
{
    public function getColonyList()
    {
        return OldColony::where('zone_code', '!=', '_')->orderBy('name','asc')->get();
    }

    public function getAllColonies()
    {
        return OldColony::whereNotNull('zone_code')->where(DB::raw('TRIM(zone_code)'), '<>', "_")->orderBy('zone_code')->orderBy('name')->get();
    }
    public function blocksInColony($colonyId, $leaseHoldOnly)
    {
        //get not splited properties
        return PropertyMaster::where('old_colony_name', $colonyId)
            ->when($leaseHoldOnly != false, function ($query) {
                return $query->where('status', 951);
            })
            ->select('block_no')->distinct()->orderBy('block_no')->get();
    }
    public function propertiesInBlock($colonyId, $blockId, $leaseHoldOnly)
    {
        //get not splited properties
        $singlePropeties = PropertyMaster::whereNull('is_joint_property')->where('old_colony_name', $colonyId)->when($blockId != "null", function ($query) use ($blockId) {
            return $query->where('block_no', $blockId);
        }, function ($query) {
            return $query->whereNull('block_no');
        })->when($leaseHoldOnly != false, function ($query) {
            return $query->where('status', 951);
        })->orderByRaw('CASE
                WHEN plot_or_property_no REGEXP "^[0-9]+$" THEN 1
                ELSE 2
            END, 
            CASE
                WHEN plot_or_property_no REGEXP "^[0-9]+$" THEN LENGTH(plot_or_property_no)
                ELSE 0
            END,
            CASE
                WHEN plot_or_property_no REGEXP "^[0-9]+$" THEN CAST(plot_or_property_no AS UNSIGNED)
                ELSE plot_or_property_no
            END')->get(); //if plot no is mumeric then order by numeric value

        //get splited properties
        $splitedPropertiesMasterIds = PropertyMaster::whereNotNull('is_joint_property')->where('old_colony_name', $colonyId)->where('block_no', $blockId)->select('id')->get();
        $splitedProperties = SplitedPropertyDetail::whereIn('property_master_id', $splitedPropertiesMasterIds)->when($leaseHoldOnly != false, function ($query) {
            return $query->where('property_status', 951);
        })->orderByRaw('CASE
                    WHEN plot_flat_no REGEXP "^[0-9]+$" THEN 1
                    ELSE 2
                END, 
                CASE
                    WHEN plot_flat_no REGEXP "^[0-9]+$" THEN LENGTH(plot_flat_no)
                    ELSE 0
                END,
                CASE
                    WHEN plot_flat_no REGEXP "^[0-9]+$" THEN CAST(plot_flat_no AS UNSIGNED)
                    ELSE plot_flat_no
                END')->get();

        //merge both
        $propertiesInColony = $singlePropeties->merge($splitedProperties);
        return $propertiesInColony;
    }

    public function misDoneForColonies()
    {
        $colonyIds = PropertyMaster::select('old_colony_name')->distinct()->pluck('old_colony_name');
        if ($colonyIds->count() > 0) {
            $foundColonies = OldColony::whereIn('id', $colonyIds)->orderBy('name')->get();
        }
        return $foundColonies;
    }

    public function leaseHoldProperties($colonyId = null)
    {
        return DB::table('property_masters as pm')
            ->join('property_lease_details as pld', 'pm.id', '=', 'pld.property_master_id')
            ->leftJoin('splited_property_details as spd', 'pm.id', '=', 'spd.property_master_id')
            ->whereIn('pm.property_type', [47, 48]) //only residential and commercial are required -  added on 08072024
            ->where(function ($query) use ($colonyId) {
                if (is_null($colonyId)) {
                    return $query->where([
                        ['pm.is_joint_property', '=', null],
                        ['pm.status', '=', 951]
                    ])
                        ->orwhere([
                            ['pm.is_joint_property', '<>', null],
                            ['spd.property_status', '=', 951]
                        ]);
                } else {
                    return $query->where([
                        ['pm.is_joint_property', '=', null],
                        ['pm.old_colony_name', '=', $colonyId],
                        ['pm.status', '=', 951]
                    ])
                        ->orwhere([
                            ['pm.is_joint_property', '<>', null],
                            ['pm.old_colony_name', '=', $colonyId],
                            ['spd.property_status', '=', 951]
                        ]);
                }
            })
            ->select('pm.id', 'pm.is_joint_property', 'spd.id as splited_id', 'pm.old_propert_id as property_id', 'pm.property_type')
            ->addSelect(DB::raw('case when pm.is_joint_property is null then pld.plot_area_in_sqm else spd.area_in_sqm end as plot_area, case when pm.is_joint_property is null then pld.presently_known_as else spd.presently_known_as end as presently_known_as'))
            ->get();
    }

    public function allPropertiesInColony($colonyId)
    {
        return DB::table('property_masters as pm')
            ->leftJoin('property_lease_details as pld', 'pm.id', '=', 'pld.property_master_id')
            ->leftJoin('items as ipt', 'pm.property_type', '=', 'ipt.id')
            ->leftJoin('items as ips', 'pm.property_sub_type', '=', 'ips.id')
            ->leftJoin('splited_property_details as spd', 'pm.id', '=', 'spd.property_master_id')
            ->where('pm.old_colony_name', '=', $colonyId)
            ->select('pm.id', 'spd.id as splited_id', 'pm.property_type', 'ipt.item_name as property_type_name', 'ips.item_name as property_subtype_name')
            ->addSelect(DB::raw('case when pm.is_joint_property is null then pm.status else spd.property_status end as property_status, case when pm.is_joint_property is null then pld.plot_area_in_sqm else spd.area_in_sqm end as plot_area, case when pm.is_joint_property is null then pld.plot_value else spd.plot_value end as plot_value_ldo, case when pm.is_joint_property is null then pld.plot_value_cr else spd.plot_value_cr end as plot_value_cr'))
            ->get();
    }
}
