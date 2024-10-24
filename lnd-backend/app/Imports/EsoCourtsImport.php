<?php

namespace App\Imports;

use App\Models\EsoCourt;
use App\Models\Section;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EsoCourtsImport implements ToModel
{
    public function model(array $row)
    {
        $section = Section::where('name_eng', $row[1])->first();

        if (!$section) {
            return null;
        }

        return new EsoCourt([
            'section_id'         => $section->id,
            'file_no'            => $row[0],
            'case_no'            => $row[2],
            'commencement_date'  => $this->transformDate($row[3]),
            'subject'            => $row[4],
            'status'             => $row[5],
            'ndoh'               => $this->transformDate($row[6]),
            'additional_info'    => $row[7],
            'created_by'         => $row[8],
            'updated_by'         => $row[9],
        ]);
    }

    /**
     *
     * @param mixed $value
     * @return \DateTime|null
     */
    private function transformDate($value)
    {
        try {
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value);
            } elseif (strtotime($value) !== false) {
                return new \DateTime($value);
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }
}
