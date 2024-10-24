<?php

namespace App\Imports;

use App\Models\CircleLandRate;
use App\Models\OldColony;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class CircleRatesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $oldColony = OldColony::where('code', $row['colony_code'])->first();
        // echo '<pre>';
       
        if (!$oldColony) {
            // echo 'Colony code not found:'. $row['colony_code'];
            // exit;
            return null; 

        }

        $dateFrom = !empty($row['date_from']) ? $this->transformDate($row['date_from']) : NULL;
        $dateTo = !empty($row['date_to']) ? $this->transformDate($row['date_to']) : NULL;

        $residentialLandRate = $this->convertToNullIfEmptyOrNonNumeric($row['residential_land_rate']);
        $commercialLandRate = $this->convertToNullIfEmptyOrNonNumeric($row['commercial_land_rate']);
        $institutionalLandRate = $this->convertToNullIfEmptyOrNonNumeric($row['institutional_land_rate']);
        $industrialLandRate = $this->convertToNullIfEmptyOrNonNumeric($row['industrial_land_rate']);
        // print_r($residentialLandRate.'---'.$commercialLandRate.'---'.$institutionalLandRate.'---'.$industrialLandRate);

        // Condition 1: Skip if all four land rate fields are null
        if (is_null($residentialLandRate) &&
            is_null($commercialLandRate) &&
            is_null($institutionalLandRate) &&
            is_null($industrialLandRate)) {
            return null;
        }
       
        // Condition 2: Skip if both dates are null
        if (is_null($dateFrom) && is_null($dateTo)) {
            return null;
        }

        // Continue processing if at least one date is provided
        $existingRecord = CircleLandRate::where('old_colony_id', $oldColony->id)
                                         ->where('date_from', $dateFrom)
                                         ->where('date_to', $dateTo)
                                         ->first();

        if ($existingRecord) {
            // Check if any of the new values are non-null and should be updated
            /* $shouldUpdate = false;

            if (is_null($existingRecord->residential_land_rate) && !is_null($residentialLandRate)) {
                $existingRecord->residential_land_rate = $residentialLandRate;
                $shouldUpdate = true;
            }

            if (is_null($existingRecord->commercial_land_rate) && !is_null($commercialLandRate)) {
                $existingRecord->commercial_land_rate = $commercialLandRate;
                $shouldUpdate = true;
            }

            if (is_null($existingRecord->institutional_land_rate) && !is_null($institutionalLandRate)) {
                $existingRecord->institutional_land_rate = $institutionalLandRate;
                $shouldUpdate = true;
            }

            if (is_null($existingRecord->industrial_land_rate) && !is_null($industrialLandRate)) {
                $existingRecord->industrial_land_rate = $industrialLandRate;
                $shouldUpdate = true;
            } 

            if ($shouldUpdate) {
                $existingRecord->save();
            }*/

            return null;
        }

        $cr_rates = new CircleLandRate([
            'old_colony_id' => $oldColony->id,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'residential_land_rate' => $residentialLandRate,
            'commercial_land_rate' => $commercialLandRate,
            'institutional_land_rate' => $institutionalLandRate,
            'industrial_land_rate' => $industrialLandRate,
        ]);

        // print_r($cr_rates);
        // Create a new record if no existing record is found
        return $cr_rates;
    }

    /**
     * Convert empty or non-numeric value to null.
     *
     * @param mixed $value
     * @return mixed
     */
    private function convertToNullIfEmptyOrNonNumeric($value)
    {
        if (is_numeric($value) && $value == 0) {
            return 0;
        }
    
        return empty($value) ? null : $value;
    }
    

    /**
     * Transform date value to \DateTime.
     *
     * @param mixed $value
     * @return \DateTime|null
     */
    private function transformDate($value)
    {
        try {
            if (is_numeric($value)) {
                return ExcelDate::excelToDateTimeObject($value);
            } elseif (strtotime($value) !== false) {
                return new \DateTime($value);
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }
}
