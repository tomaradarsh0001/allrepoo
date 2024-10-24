<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\PropertyMaster;
use App\Models\SplitedPropertyDetail;
use Exception;
use Illuminate\Support\Facades\DB;

class ReportService
{

    protected $propertyMasterService;

    public function __construct()
    {
        $pms = new PropertyMasterService();
        $this->propertyMasterService = $pms;
    }
    public function tabularRecord()
    {
        //Total property id's created
        $totalNazulProperties = PropertyMaster::where('land_type', '45')->count();
        $totalRehabilitationProperties = PropertyMaster::where('land_type', '46')->count();
        $totalId = PropertyMaster::count();
        $totalFreeHoldLeaseHold = PropertyMaster::whereIn('status', [951, 952])->count();
        $freeHoldProperties = PropertyMaster::where('status', 952)->count();
        $leaseHoldProperties = PropertyMaster::where('status', 951)->count();

        //Section wise breakup of data
        $propertiesBySection = PropertyMaster::select('section_code', DB::raw('COUNT(*) as total_properties'))
            ->groupBy('section_code')
            ->get();
        // dd($propertiesBySection);

        $sections = [
            'LS1' => [933, 689, 657, 244],
            'LS2A' => [1192, 752, 718, 440],
            'LS2B' => [245, 245, 244, 0],
            'LS3' => [655, 533, 505, 122],
            'LS4' => [1186, 1063, 455, 123],
            'LS5' => [1164, 1088, 994, 76],
            'PS1' => [21360, 18267, 11568, 3093],
            'PS2' => [17920, 15022, 10801, 2898],
            'PS3' => [16323, 13955, 7883, 2368],
            'RPC' => [971, 176, 58, 795]
        ];
        $data = [];

        // Initialize completedProperties outside of the loop
        $completedProperties = 0;

        foreach ($sections as $key => $section) {
            // Reset completedProperties for each section
            $completedProperties = 0;

            foreach ($propertiesBySection as $properties) {
                if ($properties->section_code == $key) {
                    $completedProperties = $properties->total_properties;
                    // Break once the section is found to improve performance
                    break;
                }
            }

            $singleSectionData = [
                'section' => $key,
                'total_properties' => $section[0],
                'history_entered' => $section[1],
                'fully_completed' => $section[2],
                'history_pending' => $section[3],
                'completed_properties' => $completedProperties
            ];
            $data[] = $singleSectionData;
        }


        //dd($totalRehabilitationProperties,$totalNazulProperties);
        $data = [$totalNazulProperties, $totalRehabilitationProperties, $totalId, $totalFreeHoldLeaseHold, $freeHoldProperties, $leaseHoldProperties, $data];
        return $data;
    }

    public function latestRecords()
    {
        $singleProperty = PropertyMaster::latest()->take(100)->get();
    }

    public function filterResults($filter = [], $withLimit =  true)
    {
        $limitConditions = "";
        $countRows = "";
        if ($withLimit) {
            $limit  = isset($filter['limit']) ? $filter['limit'] : 50;
            $pageNum = isset($filter['page']) ? $filter['page'] : 1;
            $offset = ($pageNum - 1) * $limit;
            $limitConditions = "limit $limit offset $offset";
            $countRows = "SQL_CALC_FOUND_ROWS";
        }


        /** property Misc details */
        $propMiscDetails = "select * from property_misc_details";
        $miscjoin = 'left';
        if (isset($filter['reEnteredSince'])) {
            $reEnteredWhere = [];
            $miscjoin = 'inner';
            if (in_array("y5+", $filter['reEnteredSince'])) { // filter more than 5 years
                $reEnteredWhere[] = "DATEDIFF(CURDATE(), re_rented_date) > 5 * 365";
            }
            if (in_array("1y - 5y", $filter['reEnteredSince'])) { // 1 year to 5 years
                $reEnteredWhere[] = "DATEDIFF(CURDATE(), re_rented_date) BETWEEN 1 * 365 AND 5 * 365";
            }
            if (in_array("6m - 1y", $filter['reEnteredSince'])) { // 6 months to 1 year
                $reEnteredWhere[] = "DATEDIFF(CURDATE(), re_rented_date) BETWEEN 6 * 30 AND 12 * 30";
            }
            if (in_array('1m - 6m', $filter['reEnteredSince'])) { // 1 month to 6 months
                $reEnteredWhere[] = "DATEDIFF(CURDATE(), re_rented_date) BETWEEN 1 * 30 AND 6 * 30";
            }
            if (in_array("m1-", $filter['reEnteredSince'])) { // less than a month
                $reEnteredWhere[] = "DATEDIFF(CURDATE(), re_rented_date) < 1 * 30";
            }

            if (!empty($reEnteredWhere)) {
                $propMiscDetails .= " WHERE " . implode(" OR ", $reEnteredWhere);
            }
        }

        $propLeaseDetails = "select *,case when date_of_expiration is null then null else timestampdiff(year, doe, date_of_expiration ) end as lease_tenure from property_lease_details";
        if (isset($filter['leaseTenure'])) {
            $leaseTenureWhere = [];
            if (in_array('Perpetual', $filter['leaseTenure'])) {
                $leaseTenureWhere[] = "p1.lease_tenure is null";
            }
            if (in_array('0 - 5', $filter['leaseTenure'])) {
                $leaseTenureWhere[] = "p1.lease_tenure between 0 and 5";
            }
            if (in_array('5 - 25', $filter['leaseTenure'])) {
                $leaseTenureWhere[] = "p1.lease_tenure between 5 and 25";
            }
            if (in_array('25 - 50', $filter['leaseTenure'])) {
                $leaseTenureWhere[] =  "p1.lease_tenure between 25 and 50";
            }
            if (in_array('50 - 75', $filter['leaseTenure'])) {
                $leaseTenureWhere[] = "p1.lease_tenure between 50 and 75";
            }
            if (in_array('75+', $filter['leaseTenure'])) {
                $leaseTenureWhere[] = "p1.lease_tenure > 75";
            }
            $propLeaseDetails = "select * from ($propLeaseDetails)p1 where " . implode(' or ', $leaseTenureWhere);
        }

        /**for not splited property */
        $queryPart1 = "select pm.id,  
            pm.old_propert_id, 
            pm.unique_propert_id, 
            pm.land_type, 
            pm.old_colony_name, 
            pm.property_type, 
            pm.property_sub_type, 
            pm.status, 
            round(pld.plot_area_in_sqm,2) as area_in_sqm, 
            (CASE WHEN pmd.is_gr_revised_ever = 1 THEN 'yes' ELSE 'No' END) AS gr, 
            cld.lessees_name as lesse_name, 
            
            pld.lease_tenure,
            pld.type_of_lease,
            pld.plot_value_cr as land_value,
            pld.presently_known_as as address, 
            coalesce(pid.total_dues,0) as total_dues, 
            pmd.re_rented_date,
            pcd.phone_no,
            coalesce(pld.present_ground_rent, cast(pld.gr_in_re_rs AS DECIMAL(12,2))) as gr_in_re_rs from 

                (select * from property_masters  where is_joint_property is null)pm
                
                $miscjoin join ($propMiscDetails) pmd on pm.id= pmd.property_master_id
                left join property_inspection_demand_details pid  on pm.id = pid.property_master_id
                left join current_lessee_details cld on pm.id = cld.property_master_id
                left join property_contact_details pcd  on pm.id = pcd.property_master_id
                join ($propLeaseDetails) pld on pm.id = pld.property_master_id";

        $queryPart2 = "select pm.id,
                spd.old_property_id as old_propert_id, 
                spd.child_prop_id, 
                pm.land_type, 
                pm.old_colony_name, 
                pm.property_type, 
                pm.property_sub_type, 
                spd.property_status as status, 
                round(spd.area_in_sqm, 2) as area_in_sqm, 
                (CASE WHEN pmd.is_gr_revised_ever = 1 THEN 'yes' ELSE 'No' END), 
                cld.lessees_name as lesse_name, 
                pld.lease_tenure,
                pld.type_of_lease,
                spd.plot_value_cr as land_value,
                spd.presently_known_as as address, 
                coalesce(pid.total_dues,0) as total_dues, 
                pmd.re_rented_date,
                pcd.phone_no, 
                coalesce(spd.present_ground_rent, 0) as gr_in_re_rs from 
                splited_property_details spd
                left join property_masters pm on spd.property_master_id = pm.id
                join ($propLeaseDetails) pld on pm.id = pld.property_master_id
                $miscjoin join ($propMiscDetails) pmd on spd.id= pmd.splited_property_detail_id
                left join property_inspection_demand_details pid  on spd.id = pid.splited_property_detail_id
                left join current_lessee_details cld on spd.id = cld.splited_property_detail_id
                left join property_contact_details pcd  on spd.id = pcd.splited_property_detail_id";

        // $baseQuery = "
        // union all
        // ";
        $where = [];
        /** this code gets data form proprty_masters table adding where condition if found in filters */

        if (isset($filter['land_type'])) {
            $landType = $filter['land_type'];
            $where[] = "land_type = $landType";
        }
        if (isset($filter['property_type'])) {
            $property_types = $filter['property_type'];
            $where[] =  "property_type in (" . implode(',', $property_types) . ")";
        }
        if (isset($filter['property_sub_type'])) {
            $property_sub_types = $filter['property_sub_type'];
            $where[] = "property_sub_type in (" . implode(", ", $property_sub_types) . ")";
        }

        if (isset($filter['land_status'])) {
            $landStatus = $filter['land_status'];
            $where[] = "status in (" . implode(",", $landStatus) . ") ";
        }

        if (isset($filter['colony'])) {
            $colony = $filter['colony'];
            $where[] = "old_colony_name in (" . implode(",", $colony) . ") ";
        }

        if (isset($filter['propertyId'])) {
            $propertyId = $filter['propertyId'];
            $where[] = "old_propert_id like '$propertyId%'";
        }

        /** filters in lease Details */
        if (isset($filter['land_size'])) {
            $min = $filter['land_size']['min'];
            $max = $filter['land_size']['max'];
            if ($min != "" && $max != "") {
                $landSizeFIlter = "area_in_sqm between $min and $max";
            } else {
                if ($min != "") {
                    $landSizeFIlter = "area_in_sqm >=  $min ";
                }
                if ($max != "") {
                    $landSizeFIlter = "area_in_sqm <=  $max ";
                }
            }

            $where[] = $landSizeFIlter;
        }

        if (isset($filter['land_value'])) {
            $min = $filter['land_value']['min'];
            $max = $filter['land_value']['max'];
            if ($min != "" && $max != "") {
                $landValueFIlter = "land_value between $min and $max";
            } else {
                if ($min != "") {
                    $landValueFIlter = "land_value >=  $min ";
                }
                if ($max != "") {
                    $landValueFIlter = "land_value <=  $max ";
                }
            }

            $where[] = $landValueFIlter;
        }
        if (isset($filter['leaseDeed'])) {
            $where[] = "type_of_lease in(" . implode(', ', $filter['leaseDeed']) . ")";
        }
        if (isset($filter['propertyAddress'])) {
            $where[] = "address like '%" . $filter['propertyAddress'] . "%'";
        }

        if (isset($filter['groundRent'])) {

            $min = $filter['groundRent']['min'];
            $max = $filter['groundRent']['max'];
            if ($min != "" && $max != "") {
                $groundRent =  "gr_in_re_rs between $min and $max";
            } else {
                if ($min != "") {
                    $groundRent = "gr_in_re_rs >=  $min ";
                }
                if ($max != "") {
                    $groundRent = "gr_in_re_rs <=  $max ";
                }
            }
            $where[] = $groundRent;
        }

        /**lesse name filter */
        if (isset($filter['name'])) {
            $where[] = "lesse_name like '%" . $filter['name'] . "%'";
        }
        /**contact filter */
        if (isset($filter['contact'])) {
            $where[] = "phone_no like '" . $filter['contact'] . "%'";
        }

        /** property inspection details */ //outstandingDues
        if (isset($filter['outstandingDues'])) {
            $min = $filter['outstandingDues']['min'];
            $max = $filter['outstandingDues']['max'];
            if ($min != "" && $max != "") {
                $totalDues = "total_dues between $min and $max";
            } else {
                if ($min != "") {
                    $totalDues = "total_dues >= $min ";
                }
                if ($max != "") {
                    $totalDues =  "total_dues <= $max ";
                }
            }
            $where[] = $totalDues;
        }
        // $filterConditions = empty($where) ? "" : ' where ' . implode(' and ', $where);
        $part1FilterConditions = empty($where) ? "" : ' where part1.' . implode(' and part1.', $where);
        $part2FilterConditions = empty($where) ? "" : ' where part2.' . implode(' and part2.', $where);
        $joins = "LEFT JOIN items AS prop_groups ON t.land_type = prop_groups.id
        LEFT JOIN items AS prop_status ON t.status = prop_status.id
        LEFT JOIN items AS prop_type ON t.property_type = prop_type.id";
        $query =  "SELECT 
        $countRows t.id,t.old_propert_id,t.unique_propert_id,t.area_in_sqm, t.gr, t.lesse_name, t.lease_tenure, t.address,t.land_value, t.total_dues,t.re_rented_date, t.phone_no, t.gr_in_re_rs, prop_groups.item_name AS land_type, prop_status.item_name AS status, prop_type.item_name AS land_use
        FROM";
        if ((empty($filter) || (count($filter) == 1 && array_key_first($filter) == 'page')) && $withLimit) // when report page is loaded with no filter allpied or filter has only page keys
        {
            //dd('empty');
            $query =  "SELECT 
                $countRows t.id,t.old_propert_id,t.unique_propert_id,t.area_in_sqm, t.gr, t.lesse_name, t.lease_tenure, t.address,t.land_value, t.total_dues,t.re_rented_date, t.phone_no, t.gr_in_re_rs, prop_groups.item_name AS land_type, prop_status.item_name AS status, prop_type.item_name AS land_use
                FROM(
                    select * from (";
            $notSplitedCount = PropertyMaster::whereNull('is_joint_property')->count();
            $splitedCount = SplitedPropertyDetail::count();
            $totalCount = $notSplitedCount + $splitedCount;
            if (($offset + $limit) <= $notSplitedCount) { // when current page should only contain not splited properties no nned to union part 2
                $query .= "$queryPart1 $limitConditions) as part1) t $joins";
            } else if ($offset < $notSplitedCount && $notSplitedCount < ($offset + $limit)) {
                $limit1 = $limit - ($notSplitedCount - $offset);
                $query .= "$queryPart1 offset $offset) as part1
                        union all
                select * from (
                $queryPart2
                $limit1
                )
                as part2
                        ) t $joins";
            } else {
                $offset2 = $offset - $notSplitedCount;
                $query .= "select * from (
                            $queryPart2
                            limit $limit offset $offset2
                            )
                            as part2
                            ) t 
                            $joins";
            }
        } else { // In case of report export and filter applied
            $query =  "SELECT 
            $countRows t.id,t.old_propert_id,t.unique_propert_id,t.area_in_sqm, t.gr, t.lesse_name, t.lease_tenure, t.address,t.land_value, t.total_dues,t.re_rented_date, t.phone_no, t.gr_in_re_rs, prop_groups.item_name AS land_type, prop_status.item_name AS status, prop_type.item_name AS land_use
            FROM (
                select * from (
            $queryPart1) as part1
            $part1FilterConditions
            
            union all
            select * from (
            $queryPart2)
            as part2
            $part2FilterConditions
            ) t 
            $joins
            $limitConditions
            ";
        }


        /* if (!empty($filter)) {
            exit($query);
        } */
        try {
            $rows = DB::select($query);
        } catch (Exception $th) {
            throw $th;
        }

        if (!$withLimit) {
            return $rows;
        } else {
            $counter = isset($totalCount) ? $totalCount : DB::select('SELECT FOUND_ROWS() AS counter')[0]->counter;
            return ['rows' => $rows, 'counter' => $counter, /* 'query' => $query, 'filter' => $filter */];
        }
    }

    public function getDistinctSubTypes($types = [])
    {
        if ($types == null) {
            $types = [];
        }
        if (count($types) > 0) {
            $qMark = implode(', ', array_fill(0, count($types), '?'));
            $query = "SELECT 
                        items.id, item_name
                    FROM
                        items
                            JOIN
                        (SELECT DISTINCT
                            (sub_type)
                        FROM
                            property_type_sub_type_mapping
                        WHERE
                            type IN ($qMark)) t ON items.id = t.sub_type
                    ORDER BY item_name;";
            // exit($query);
            $subtypes = DB::select($query, $types);
            return $subtypes;
        } else {
            return [];
        }
    }

    public function detailedReport($filter = [], $export = false)
    {
        // Start building the query
        $query = DB::table('property_masters as pm')
            ->select(
                'pm.id',
                'pm.unique_propert_id',
                'pm.old_propert_id',
                DB::raw('CASE WHEN pm.is_joint_property IS NULL THEN NULL ELSE spd.child_prop_id END AS child_prop_id'),
                'pm.property_type',
                'pm.property_sub_type',
                DB::raw('CASE WHEN pm.is_joint_property IS NULL THEN pm.status ELSE spd.property_status END AS property_status'),
                'pm.file_no',
                'pm.section_code as section',
                DB::raw('CASE WHEN pm.is_joint_property IS NULL THEN pld.presently_known_as ELSE spd.presently_known_as END AS address'),
                'pld.premium',
                'pld.premium_in_paisa',
                'pld.type_of_lease',
                DB::raw('CASE WHEN pm.is_joint_property IS NULL THEN pld.plot_area_in_sqm ELSE spd.area_in_sqm END AS area_in_sqm'),
                DB::raw('CASE WHEN pm.is_joint_property IS NULL THEN COALESCE(pld.present_ground_rent, pld.gr_in_re_rs) ELSE COALESCE(spd.present_ground_rent, pld.gr_in_re_rs) END AS ground_rent')
            )
            ->leftjoin('property_lease_details as pld', 'pm.id', '=', 'pld.property_master_id')
            ->leftJoin('splited_property_details as spd', 'pm.id', '=', 'spd.property_master_id');

        // Join items table for property_type, property_sub_type, and property_status
        $query->leftJoin('items as item_type', 'item_type.id', '=', 'pm.property_type')
            ->leftJoin('items as item_sub_type', 'item_sub_type.id', '=', 'pm.property_sub_type')
            ->leftJoin('items as item_property_status', function ($join) {
                $join->on('item_property_status.id', '=', DB::raw('CASE WHEN pm.is_joint_property IS NULL THEN pm.status ELSE spd.property_status END'));
            });

        // Select item names
        $query->addSelect(
            'item_type.item_name as propertyType',
            'item_sub_type.item_name as propertySubtype',
            'item_property_status.item_name as propertyStatus'
        );

        if ($export) {
            $query->addSelect(
                'pm.land_type',
                'pm.unique_file_no',
                DB::raw('CASE WHEN pm.is_joint_property IS NULL THEN pld.plot_area ELSE spd.current_area END AS area'),
                'oc.name as colony',
                'pm.block_no as block',
                DB::raw('CASE WHEN pm.is_joint_property IS NULL THEN pm.plot_or_property_no ELSE spd.plot_flat_no END AS plot_no'),
                DB::raw('CASE WHEN pm.is_joint_property IS NULL THEN pld.presently_known_as ELSE spd.presently_known_as END AS presently_known_as'),
                'pld.doa as date_of_allotment',
                'pld.doe as date_of_execution',
                'pld.date_of_expiration',
                DB::raw("CASE WHEN pld.is_land_use_changed = 1 THEN 'yes' ELSE 'no' END AS is_land_use_changed"),
                DB::raw("CASE WHEN pmd.is_gr_revised_ever = 1 THEN 'yes' ELSE 'no' END AS rgr"),
                'pld.start_date_of_gr',
                'pld.first_rgr_due_on',
                'pld.rgr_duration',
                'pld.property_type_at_present',
                'pld.property_sub_type_at_present',
                'pid.last_inspection_ir_date',
                'pid.last_demand_id',
                'pid.last_demand_letter_date',
                'pid.last_demand_amount',
                'pid.last_amount_received',
                'pid.last_amount_received_date',
                'pid.total_dues',
                'pcd.address as lessee_address',
                'pcd.phone_no as lessee_phone',
                'pcd.email as lessee_email',
                'cld.lessees_name as current_lesse_name',
                'users.name as created_by',
                'pm.created_at'
            )
                ->leftJoin('old_colonies as oc', 'pm.old_colony_name', '=', 'oc.id')
                ->leftJoin('property_inspection_demand_details as pid', function ($join) {
                    $join->on('pm.id', '=', 'pid.property_master_id')
                        ->where(function ($query) {
                            $query->where(function ($query) {
                                $query->whereNull('pm.is_joint_property')
                                    ->whereNull('pid.splited_property_detail_id');
                            })->orWhere(function ($query) {
                                $query->whereNotNull('pm.is_joint_property')
                                    ->whereColumn('spd.id', '=', 'pid.splited_property_detail_id');
                            });
                        });
                })
                ->leftJoin('property_contact_details as pcd', function ($join) {
                    $join->on('pm.id', '=', 'pcd.property_master_id')
                        ->where(function ($query) {
                            $query->where(function ($query) {
                                $query->whereNull('pm.is_joint_property')
                                    ->whereNull('pcd.splited_property_detail_id');
                            })->orWhere(function ($query) {
                                $query->whereNotNull('pm.is_joint_property')
                                    ->whereColumn('spd.id', '=', 'pcd.splited_property_detail_id');
                            });
                        });
                })
                ->leftJoin('property_misc_details as pmd', function ($join) {
                    $join->on('pm.id', '=', 'pmd.property_master_id')
                        ->where(function ($query) {
                            $query->where(function ($query) {
                                $query->whereNull('pm.is_joint_property')
                                    ->whereNull('pmd.splited_property_detail_id');
                            })->orWhere(function ($query) {
                                $query->whereNotNull('pm.is_joint_property')
                                    ->whereColumn('spd.id', '=', 'pmd.splited_property_detail_id');
                            });
                        });
                })
                ->leftJoin('current_lessee_details as cld', function ($join) {
                    $join->on('pm.id', '=', 'cld.property_master_id')
                        ->where(function ($query) {
                            $query->where(function ($query) {
                                $query->whereNull('pm.is_joint_property')
                                    ->whereNull('cld.splited_property_detail_id');
                            })->orWhere(function ($query) {
                                $query->whereNotNull('pm.is_joint_property')
                                    ->whereColumn('spd.id', '=', 'cld.splited_property_detail_id');
                            });
                        });
                })
                ->leftJoin('users', 'pm.created_by', '=', 'users.id');

            // Join items for land_type, lease_type, present property type and subtype
            $query->leftJoin('items as land_type_names', 'land_type_names.id', '=', 'pm.land_type')
                ->leftJoin('items as lease_type_names', 'lease_type_names.id', '=', 'pld.type_of_lease')
                ->leftJoin('items as item_present_type_names', 'item_present_type_names.id', '=', 'pld.property_type_at_present')
                ->leftJoin('items as item_present_sub_type_names', 'item_present_sub_type_names.id', '=', 'pld.property_sub_type_at_present');

            // Add item names to select
            $query->addSelect(
                'land_type_names.item_name as landType',
                'lease_type_names.item_name as leaseDeed',
                'item_present_type_names.item_name as presentPropertyType',
                'item_present_sub_type_names.item_name as presentPropertySubtype'
            );
        }

        // Apply filters
        if (!empty($filter)) {
            if (isset($filter['colony'])) {
                $query->whereIn('pm.old_colony_name', $filter['colony']);
            }
            if (isset($filter['landType']) && $filter['landType'] != "") {
                $query->where('pm.land_type', $filter['landType']);
            }
            if (isset($filter['property_status'])) {
                $query->whereIn(DB::raw('CASE WHEN pm.is_joint_property IS NULL THEN pm.status ELSE spd.property_status END'), $filter['property_status']);
            }
            if (isset($filter['property_type'])) {
                $query->whereIn('pm.property_type', $filter['property_type']);
            }
            if (isset($filter['property_sub_type'])) {
                $query->whereIn('pm.property_sub_type', $filter['property_sub_type']);
            }
            if (isset($filter['leaseDeed'])) {
                $query->whereIn('pld.type_of_lease', $filter['leaseDeed']);
            }
        }

        // For debugging, you can uncomment the line below to see the generated SQL
        // dd($query->toSql(), $query->getBindings());

        return $export ? $query->get() : $query->paginate(50);
    }



    /* public function detailedReport($filter = [], $export = false)
    {
        // dd($filter);
        $rowQuery = PropertyMaster::select('*');
        $rows = $this->applyFilter($rowQuery, $filter);

        if ($export) {
            return $rows->get();
        } else {
            $nonSplitCountQuery = PropertyMaster::selectRaw('COUNT(*) as count')
                ->whereNull('is_joint_property');

            // Count of split properties
            $splitCountQuery = PropertyMaster::selectRaw('COUNT(DISTINCT splited_property_details.id) as count')
                ->join('splited_property_details', 'property_masters.id', '=', 'splited_property_details.property_master_id')
                ->whereNotNull('property_masters.is_joint_property');

            // Apply filters to both queries
            $nonSplitCount = $this->applyFilter($nonSplitCountQuery, $filter)->first()->count;
            $splitCount = $this->applyFilter($splitCountQuery, $filter)->first()->count;

            // Total count
            $total = $nonSplitCount + $splitCount;
            return ['data' => $rows->paginate(50), 'total' => $total];
        }
    }

    private function applyFilter($query, $filter)
    {
        return $query->when(!empty($filter), function ($query) use ($filter) {
            if (isset($filter['colony'])) {
                $query = $query->whereIn('old_colony_name', $filter['colony']);
            }
            if (isset($filter['landType']) && $filter['landType'] != "") {
                $query = $query->where('land_type', $filter['landType']);
            }
            if (isset($filter['property_status'])) {
                $query->where(function ($q) use ($filter) {
                    $q->where(function ($subQuery) use ($filter) {
                        // Filter for non-split properties with the given status
                        $subQuery->whereNull('is_joint_property')
                            ->whereIn('status', $filter['property_status']);
                    })->orWhereHas('splitedPropertyDetail', function ($subQuery) use ($filter) {
                        // Filter for split properties' children with the given status
                        $subQuery->whereIn('status', $filter['property_status']);
                    });
                });
            }
            if (isset($filter['property_type'])) {
                $query = $query->whereIn('property_type', $filter['property_type']);
            }
            if (isset($filter['property_sub_type'])) {
                $query = $query->whereIn('property_sub_type', $filter['property_sub_type']);
            }
            if (isset($filter['leaseDeed'])) {
                $query = $query->whereHas('propertyLeaseDetail', function ($subQuery) use ($filter) {
                    $subQuery->whereIn('type_of_lease', $filter['leaseDeed']);
                });
            }
        });
    } */
}
