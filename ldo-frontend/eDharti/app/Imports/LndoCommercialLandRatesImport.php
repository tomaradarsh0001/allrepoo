<?php

namespace App\Imports;

use App\Models\LndoCommercialLandRate;
use App\Models\OldColony;
use Illuminate\Support\Facades\Auth;  // Add the Auth facade
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class LndoCommercialLandRatesImport implements ToModel, WithHeadingRow
{
    public function model(array $row): LndoCommercialLandRate|null
    {
        // Fetch the OldColony record by colony_code
        $oldColony = OldColony::where('code', $row['colony_code'])->first();

        if (!$oldColony) {
            return null; // Skip if OldColony is not found
        }

        // Transform date values
        $dateFrom = $this->transformDate($row['date_from']);
        $dateTo = $this->transformDate($row['date_to']);

        // Skip if both dates are null or empty
        if (empty($row['date_from']) && empty($row['date_to'])) {
            return null;
        }

        // Skip if commercial_land_rate is null
        if (is_null($row['commercial_land_rate'])) {
            return null;
        }

        // Check if a record with the same old_colony_id and date range already exists
        $existingRecord = LndoCommercialLandRate::where('colony_id', $oldColony->id)
                                      ->where('date_from', $dateFrom)
                                      ->where('date_to', $dateTo)
                                      ->first();

        if ($existingRecord) {
            // Check if the commercial_land_rate needs to be updated
            if (is_null($existingRecord->land_rate) && !is_null($row['commercial_land_rate'])) {
                $existingRecord->land_rate = $row['commercial_land_rate'];
                $existingRecord->save();
            }
            return null;
        }

        // Create a new record if no existing record is found
        return new LndoCommercialLandRate([
            'colony_id' => $oldColony->id,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'land_rate' => $row['commercial_land_rate'],
            'created_by' => Auth::id(), // Set the created_by field to the ID of the currently authenticated user
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
