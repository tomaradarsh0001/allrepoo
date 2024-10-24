<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\OldColony;
use DB;

class ColonyService
{
    public function getColonyList()
    {
        return OldColony::where('zone_code', '!=', '_')->orderBy('zone_code')->get();
    }

    public function getAllColonies()
    {
        return OldColony::whereNotNull('zone_code')->where(DB::raw('TRIM(zone_code)'), '<>', "_")->orderBy('zone_code')->orderBy('name')->get();
    }
}
