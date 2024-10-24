<?php

namespace App\Exports;

use App\Models\NewlyAddedProperty;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class NewlyAddedPropertyExport implements FromCollection
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function view(): View
    {
        return view('exports.newly_added_properties_excel', [
            'properties' => $this->query->get()
        ]);
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return NewlyAddedProperty::all();
    }
}
