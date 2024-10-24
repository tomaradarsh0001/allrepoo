<?php

namespace App\Imports;

use App\Models\LndoLandRate;
use App\Models\OldColony;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class OldLndoLandRatesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Fetch the OldColony record by colony_code
        $oldColony = OldColony::where('code', $row['colony_code'])->first();

        if (!$oldColony) {
            return null; 
        }

        // Transform date values
        $dateFrom = $this->transformDate($row['date_from']);
        $dateTo = $this->transformDate($row['date_to']);

        // Condition 2: Skip if both dates are null or empty
        if (empty($row['date_from']) && empty($row['date_to'])) {
            return null;
        }

        // Condition 1: Skip if all four land rate fields are null
        if (is_null($row['residential_land_rate']) &&
            is_null($row['commercial_land_rate']) &&
            is_null($row['institutional_land_rate']) &&
            is_null($row['industrial_land_rate'])) {
            return null;
        }

        // Condition 3: Check if a record with the same old_colony_id and date range already exists
        $existingRecord = LndoLandRate::where('old_colony_id', $oldColony->id)
                                      ->where('date_from', $dateFrom)
                                      ->where('date_to', $dateTo)
                                      ->first();

        if ($existingRecord) {
            // Check if any of the new values are non-null and should be updated
            $shouldUpdate = false;

            if (is_null($existingRecord->residential_land_rate) && !is_null($row['residential_land_rate'])) {
                $existingRecord->residential_land_rate = $row['residential_land_rate'];
                $shouldUpdate = true;
            }

            if (is_null($existingRecord->commercial_land_rate) && !is_null($row['commercial_land_rate'])) {
                $existingRecord->commercial_land_rate = $row['commercial_land_rate'];
                $shouldUpdate = true;
            }

            if (is_null($existingRecord->institutional_land_rate) && !is_null($row['institutional_land_rate'])) {
                $existingRecord->institutional_land_rate = $row['institutional_land_rate'];
                $shouldUpdate = true;
            }

            if (is_null($existingRecord->industrial_land_rate) && !is_null($row['industrial_land_rate'])) {
                $existingRecord->industrial_land_rate = $row['industrial_land_rate'];
                $shouldUpdate = true;
            }

            if ($shouldUpdate) {
                $existingRecord->save();
            }

            return null;
        }

        // Create a new record if no existing record is found
        return new LndoLandRate([
            'old_colony_id' => $oldColony->id,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'residential_land_rate' => $row['residential_land_rate'],
            'commercial_land_rate' => $row['commercial_land_rate'],
            'institutional_land_rate' => $row['institutional_land_rate'],
            'industrial_land_rate' => $row['industrial_land_rate'],
        ]);
    }

    /**
     * Transform Excel date value to \DateTime object.
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
