<?php

namespace App\Http\Controllers;

use App\Models\EsoCourt;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Imports\EsoCourtsImport;
use Maatwebsite\Excel\Facades\Excel;
// use Illuminate\Support\Facades\Log;
class EsoCourtController extends Controller
{
    //    
    public function create()
    {
        $sections = Section::where('is_active', 1)->orderBy('short_name')->get();
        return view('esocourt.input-form', ['sections' => $sections]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'file_no' => 'required|string',
            'case_no' => 'required|string',
            'subject' => 'required|string',
            'judgement_link' => 'nullable|file',
        ]);
    
        $caseData = [
            'section_id' => $request->section_id,
            'file_no' => $request->file_no,
            'active_in_court' => $request->active_in_court,
            'case_no' => $request->case_no,
            'commencement_date' => $request->commencement_date ?: null,
            'subject' => $request->subject,
            'status' => $request->status,
            'ldoh' => $request->ldoh ?: null,
            'ndoh' => $request->ndoh ?: null,
            'closing_date' => $request->closing_date ?: null,
            'additional_info' => $request->additional_info,
            'remarks' => $request->remarks,
            'is_active' => $request->is_active,
            'sort_order' => $request->sort_order,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ];
    
        if ($request->hasFile('judgement_link')) {
            $uploadedFile = $request->file('judgement_link');
            $fileName = str_replace(' ', '_', $uploadedFile->getClientOriginalName());
            $filePath = $uploadedFile->storeAs('esocourt', $fileName, 'public');
            $caseData['judgement_link'] = $fileName;
        }
    
        EsoCourt::create($caseData);
        return back()->with('status', 'Case added successfully!');
    }

    public function list()
    {
        $cases = EsoCourt::with('section')->get(); 
        return view('esocourt.list', ['cases' => $cases]);
    }

    
    public function update(Request $request, $id)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'file_no' => 'required|string',
            'case_no' => 'required|string',
            'subject' => 'required|string',
            'judgement_link' => 'nullable|file',
        ]);
    
        $caseItem = EsoCourt::find($id);
        if (!$caseItem) {
            return back()->with('error', 'Case not found.');
        }
    
        $caseData = [
            'section_id' => $request->section_id,
            'file_no' => $request->file_no,
            'active_in_court' => $request->active_in_court,
            'case_no' => $request->case_no,
            'commencement_date' => $request->commencement_date ?: null,
            'subject' => $request->subject,
            'status' => $request->status,
            'ldoh' => $request->ldoh ?: null,
            'ndoh' => $request->ndoh ?: null,
            'closing_date' => $request->closing_date ?: null,
            'additional_info' => $request->additional_info,
            'remarks' => $request->remarks,
            'is_active' => $request->is_active,
            'sort_order' => $request->sort_order,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ];
    
        if ($request->hasFile('judgement_link')) {
            $uploadedFile = $request->file('judgement_link');
            $fileName = str_replace(' ', '_', $uploadedFile->getClientOriginalName());
            $filePath = $uploadedFile->storeAs('esocourt', $fileName, 'public');
            $caseData['judgement_link'] = $fileName;
        }
    
        $caseItem->update($caseData);
    
        return back()->with('status', 'Case updated successfully!');
    }    

    public function deleteJudgement($id)
    {
        $caseItem = EsoCourt::find($id);
        if ($caseItem) {
            
            Storage::delete('public/esocourt/' . $caseItem->judgement_link);
            
            
            $caseItem->judgement_link = null;
            $caseItem->save();
            
            return back()->with('status', 'Judgement deleted successfully!');
        }
        return back()->with('error', 'Judgement not found.');
    }

    public function edit($id)
    {
        $cases = EsoCourt::find($id);
        $sections = Section::where('is_active', 1)->orderBy('sort_order')->get();
        return view('esocourt.input-form', ['cases' => $cases, 'sections' => $sections]);
    }

    public function view($id)
    {
        $cases = EsoCourt::find($id);
        $sections = Section::where('is_active', 1)->orderBy('sort_order')->get();
        return view('esocourt.input-form', ['cases' => $cases, 'sections' => $sections, 'mode' => 'view']);
    }

    public function delete($id)
    {
        if ($id && $id != "") {
            $deleted = EsoCourt::where('id', $id)->delete();
            if ($deleted) {
                return back()->with('status', 'Case deleted successfully');
            } else {
                return back()->with('error', 'Something went wrong. Case not deleted');
            }
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $cases = EsoCourt::find($id);
        if ($cases) {
            $cases->is_active = $request->is_active;
            $cases->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
    
    public function excelUpload(){
        return view('esocourt.esocourt-excel');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new EsoCourtsImport, $request->file('file'));
            return redirect()->back()->with('status', 'File uploaded successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'File upload failed: ' . $e->getMessage()]);
        }
    }

    public function apiEsoCourt($lang = NULL)
    {
        $heading = $lang == 'hindi' ? 'ईएसओ न्यायालय मामले' : 'ESO Court Cases';
    
        $cases = EsoCourt::with('section')
            ->where('is_active', 1)
            ->orderBy('section_id->sort_order')
            ->get();
    
        if ($cases->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'No Cases found'], 404);
        }
    
        $returnArray = ['heading' => $heading, 'items' => []];
        foreach ($cases as $case) {
            $sectionName = $case->section->short_name;
            $caseData = [
                'section' => $sectionName,
                'file_no' => $case->file_no,
                'active_in_court' => $case->active_in_court,
                'case_no' => $case->case_no,
                'status'=> $case->status,
                'commencement_date' => $case->commencement_date,
                'subject' => $case->subject,
                'ldoh' => $case->ldoh,
                'ndoh' => $case->ndoh,
                'closing_date' => $case->closing_date,
                'judgement_link' => $case->judgement_link,
                'is_active' => $case->is_active,
                'sort_order' => $case->sort_order,
            ];
            $returnArray['items'][] = $caseData;
        }
    
        return response()->json($returnArray, 200, [], JSON_UNESCAPED_UNICODE);
    }
    

}
