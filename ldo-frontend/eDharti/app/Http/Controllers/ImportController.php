<?php

namespace App\Http\Controllers;

use App\Helpers\UserActionLogHelper;
use Illuminate\Http\Request;
use App\Imports\LndoCommercialLandRatesImport;
use App\Imports\CircleRatesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ImportController extends Controller
{
    public function importLndoLandRate(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        Excel::import(new LndoCommercialLandRatesImport, $request->file('file'));

        // Manage user import LndoLandRates action activity lalit on 22/07/24
        UserActionLogHelper::UserActionLog('import', url("/import-lndo-land-rates"), 'importLAndDoRates', "New L&Do rates imported successfully by " . Auth::user()->name);


        return redirect()->back()->with('success', 'L&DO Land rates imported successfully.');
    }

    public function importCircleRate(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        Excel::import(new CircleRatesImport, $request->file('file'));
        
        // Manage user import CircleRates action activity lalit on 22/07/24
        UserActionLogHelper::UserActionLog('import', url("/import-circle-rates"), 'importCircleRates', "New circle rates imported successfully by " . Auth::user()->name.".");

        return redirect()->back()->with('success', 'Circle rates imported successfully.');
    }
}
