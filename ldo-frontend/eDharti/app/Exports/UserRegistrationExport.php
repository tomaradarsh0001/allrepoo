<?php

namespace App\Exports;

use App\Models\UserRegistration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UserRegistrationExport implements FromCollection
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function view(): View
    {
        return view('exports.user_registrations_excel', [
            'registrations' => $this->query->get()
        ]);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return UserRegistration::all();
    }
}
