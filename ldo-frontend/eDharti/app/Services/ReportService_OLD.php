<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\PropertyMaster;
use DB;
use Exception;
use Illuminate\Support\Facades\DB as FacadesDB;

class ReportService
{

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

    public function filterResults($filter = [], $withLimit = true)
    {

        $limitConditions = "";
        $countRows = "";
        if ($withLimit) {
            $limit = isset($filter['limit']) ? $filter['limit'] : 50;
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

        $propLeaseDetails = "select *  ,  case when date_of_expiration is null then null else timestampdiff(year, doe, date_of_expiration ) end as lease_tenure from property_lease_details";
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
                $leaseTenureWhere[] = "p1.lease_tenure between 25 and 50";
            }
            if (in_array('50 - 75', $filter['leaseTenure'])) {
                $leaseTenureWhere[] = "p1.lease_tenure between 50 and 75";
            }
            if (in_array('75+', $filter['leaseTenure'])) {
                $leaseTenureWhere[] = "p1.lease_tenure > 75";
            }
            $propLeaseDetails = "select * from ($propLeaseDetails)p1 where " . implode(' or ', $leaseTenureWhere);
        }
        $queryPart1 = "select pm.id, pm.old_propert_id, pm.unique_propert_id, pm.land_type, pm.old_colony_name, pm.property_type, pm.property_sub_type, pm.status, round(pld.plot_area_in_sqm) as area_in_sqm, (CASE WHEN pmd.is_gr_revised_ever = 1 THEN 'yes' ELSE 'No' END) AS gr, (SELECT GROUP_CONCAT(lessee_name SEPARATOR ', ') FROM property_transferred_lessee_details tld1 WHERE tld1.property_master_id = pm.id AND tld1.batch_transfer_id = (SELECT MAX(batch_transfer_id) FROM property_transferred_lessee_details tld2 WHERE tld1.property_master_id = tld2.property_master_id) GROUP BY tld1.property_master_id) AS lesse_name, pld.lease_tenure,pld.type_of_lease, pld.presently_known_as as address, coalesce(pid.total_dues,0) as total_dues, pmd.re_rented_date,pcd.phone_no,pld.gr_in_re_rs from 

        (select * from property_masters  where is_joint_property is null)pm
        
        $miscjoin join ($propMiscDetails) pmd on pm.id= pmd.property_master_id
        left join property_inspection_demand_details pid  on pm.id = pid.property_master_id
        left join property_contact_details pcd  on pm.id = pcd.property_master_id
        join ($propLeaseDetails) pld on pm.id = pld.property_master_id";

        $queryPart2 = "select pm.id, spd.old_property_id as old_propert_id, spd.child_prop_id, pm.land_type, pm.old_colony_name, pm.property_type, pm.property_sub_type, spd.property_status as status, round(spd.area_in_sqm) as area_in_sqm, (CASE WHEN pmd.is_gr_revised_ever = 1 THEN 'yes' ELSE 'No' END), (SELECT GROUP_CONCAT(lessee_name SEPARATOR ', ') FROM property_transferred_lessee_details tld1 WHERE tld1.splited_property_detail_id = spd.id AND tld1.batch_transfer_id = (SELECT MAX(batch_transfer_id) FROM property_transferred_lessee_details tld2 WHERE tld1.splited_property_detail_id = tld2.splited_property_detail_id) GROUP BY tld1.splited_property_detail_id) AS lesse_name, pld.lease_tenure,pld.type_of_lease, spd.presently_known_as as address, coalesce(pid.total_dues,0) as total_dues, pmd.re_rented_date,pcd.phone_no, pld.gr_in_re_rs from 
        splited_property_details spd
        left join property_masters pm on spd.property_master_id = pm.id
        join ($propLeaseDetails) pld on pm.id = pld.property_master_id
        $miscjoin join ($propMiscDetails) pmd on spd.id= pmd.splited_property_detail_id
        left join property_inspection_demand_details pid  on spd.id = pid.splited_property_detail_id
        left join property_contact_details pcd  on pm.id = pcd.splited_property_detail_id";
		
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
            $where[] = "property_type in (" . implode(',', $property_types) . ")";
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
                $groundRent = "gr_in_re_rs between $min and $max";
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
                    $totalDues = "total_dues <= $max ";
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

        $query = "SELECT 
        $countRows t.id,t.old_propert_id,t.unique_propert_id,t.area_in_sqm, t.gr, t.lesse_name, t.lease_tenure, t.address, t.total_dues,t.re_rented_date, t.phone_no, t.gr_in_re_rs, prop_groups.item_name AS land_type, prop_status.item_name AS status, prop_type.item_name AS land_use
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
            $counter = DB::select('SELECT FOUND_ROWS() AS counter')[0]->counter;
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
}
