<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $rows;
    public function __construct($data)
    {
        $this->rows = $data;
    }
    public function collection()
    {
        return collect($this->rows);
    }

    public function headings(): array
    {
        return [
            'Old Property Id',
            'Unique Property Id',
            'Land Type',
            'Land Status',
            'Lease Tenure',
            'Land Use',
            'Area(SQM)',
            "Address",
            "Lesse/Owner Name",
            'Ground Rent(Rs)',
            'Status of RGR'
        ];
    }

    /*  public function array(): array
    {
        //return $this->output;
        return $this->data;
        /*  $result = [];
        foreach ($this->chunks as $chunk) {
            foreach ($chunk as $item) {
                $result[] = [
                    'old_propert_id' => $item->old_propert_id,
                    'unique_propert_id' => $item->unique_propert_id,
                    'land_type' => $item->land_type,
                    'status' => $item->status,
                    'lease_tenure' => $item->lease_tenure,
                    'land_use' => $item->land_use,
                    'area' => $item->area_in_sqm,
                    'address' => $item->address,
                    'lesse_name' => $item->lesse_name,
                    'gr_in_re_rs' => $item->gr_in_re_rs,
                    'gr' => $item->gr,
                ];
            }
        }
        return $result; /
    } */

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
