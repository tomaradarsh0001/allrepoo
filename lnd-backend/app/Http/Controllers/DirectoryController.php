<?php

namespace App\Http\Controllers;

use App\Models\Directory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectoryController extends Controller
{
    //
    public function create()
    {
        return view('directory.input-form');
    }

    public function store(Request $request)
    {
        $request->validate([

            'name_eng' => 'required|string',
            'name_hin' => 'required|string',

        ]);


        $directories = [
            'name_eng' => $request->name_eng,
            'name_hin' => $request->name_hin,
            'designation_eng' => $request->designation_eng,
            'designation_hin' => $request->designation_hin,
            'section_eng' => $request->section_eng,
            'section_hin' => $request->section_hin,
            'room_no' => $request->room_no,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'is_active' => $request->is_active,
            'sort_order' => $request->sort_order,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ];

        Directory::create($directories);
        return back()->with('status', 'Contact added successfully!');
    }

    public function list()
    {

        $directories = Directory::all();
        return view('directory.list', ['directories' => $directories]);
    }

    public function edit($id)
    {
        $directories = Directory::find($id);
        return view('directory.input-form', ['directories' => $directories]);
    }

    public function view($id)
    {
        $directory = Directory::find($id);
        return view('directory.input-form', ['directories' => $directory, 'mode' => 'view']);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name_eng' => 'required|string',
            'name_hin' => 'required|string',

        ]);

        $directoryItem = Directory::find($id);
        if ($directoryItem) {
            $directoryData = [
                'name_eng' => $request->name_eng,
                'name_hin' => $request->name_hin,
                'designation_eng' => $request->designation_eng,
                'designation_hin' => $request->designation_hin,
                'section_eng' => $request->section_eng,
                'section_hin' => $request->section_hin,
                'room_no' => $request->room_no,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'is_active' => $request->is_active,
                'sort_order' => $request->sort_order,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];


            $directoryItem->update($directoryData);
            return back()->with('status', 'Contact updated successfully!');
        }

        return back()->with('error', 'Contact not found.');
    }

    public function updateStatus(Request $request, $id)
    {
        $directories = Directory::find($id);
        if ($directories) {
            $directories->is_active = $request->is_active;
            $directories->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }    

    public function delete($id)
    {
        if ($id && $id != "") {
            $deleted = Directory::where('id', $id)->delete();
            if ($deleted) {
                return back()->with('status', 'Contact deleted successfully');
            } else {
                return back()->with('error', 'Something went wrong. Contact not deleted');
            }
        }
    }
    public function apiDirectory($lang = NULL)
    {

        $heading = $lang == 'hindi' ? 'कौन कौन है' : "Who's Who";


        $directoryItems = Directory::where('is_active', 1)
            ->orderBy('sort_order')
            ->get();

        if ($directoryItems->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'No Contacts found'], 404);
        }

        $returnArray = ['heading' => $heading];
        foreach ($directoryItems as $directoryItem) {
            $directory_data = [
                'id' => $directoryItem->id,
                'name' => ($lang == 'hindi') ? $directoryItem->name_hin : $directoryItem->name_eng,
                'designation' => ($lang == 'hindi') ? $directoryItem->designation_hin : $directoryItem->designation_eng,
                'section' => ($lang == 'hindi') ? $directoryItem->section_hin : $directoryItem->section_eng,
                'room_no' => $directoryItem->room_no,
                'telephone' => $directoryItem->telephone,
                'email' => $directoryItem->email,
                'sort_order' => $directoryItem->sort_order,
            ];
            $returnArray['items'][] = $directory_data;
        }

        return response()->json($returnArray, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
