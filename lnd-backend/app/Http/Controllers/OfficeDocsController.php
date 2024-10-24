<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office_docs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;


class OfficeDocsController extends Controller
{

    public function create()
    {
        $categories = Category::where('is_active', 1)->orderBy('name')->get();
        return view('officedocs.input-form', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $request->validate([

            'category_id' => 'required|exists:categories,id',
            'title_eng' => 'required|string',
            'title_hin' => 'required|string',
            'attachment_eng' => 'nullable|file',
            'attachment_hin' => 'nullable|file',
        ]);


        $officeData = [
            'category_id' => $request->category_id,
            'display_till' => $request->display_till ?: null,
            'title_eng' => $request->title_eng,
            'title_hin' => $request->title_hin,
            'description_eng' => $request->description_eng,
            'description_hin' => $request->description_hin,
            'is_active' => $request->is_active,
            'sort_order' => $request->sort_order,
            'attachment_eng' => $request->attachment_eng,
            'attachment_hin' => $request->attachment_hin,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ];


        $folders = [
            'eng' => 'officedocs_eng',
            'hin' => 'officedocs_hin'
        ];

        foreach ($folders as $lang => $folder) {
            $fileKey = 'attachment_' . $lang;
            if ($request->hasFile($fileKey)) {
                $uploadedFile = $request->file($fileKey);
                $fileName = str_replace(' ', '_', $uploadedFile->getClientOriginalName());
                $filePath = $uploadedFile->storeAs($folder, $fileName, 'public');
                $officeData[$fileKey] = $fileName;
            }
        }

        // dd($officeData); 
        Office_docs::create($officeData);
        return back()->with('status', 'Office document added successfully!');
    }


    public function list()
    {
        $officedocs = Office_docs::with('category')->get(); 
        return view('officedocs.list', ['officedocs' => $officedocs]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title_eng' => 'required|string',
            'title_hin' => 'required|string',
            'attachment_eng' => 'nullable|file',
            'attachment_hin' => 'nullable|file',
        ]);
    
        $officeItem = Office_docs::find($id);
        if (!$officeItem) {
            return back()->with('error', 'Document not found.');
        }
    
        $officeData = [
            'category_id' => $request->category_id,
            'display_till' => $request->display_till ?: null,
            'title_eng' => $request->title_eng,
            'title_hin' => $request->title_hin,
            'description_eng' => $request->description_eng,
            'description_hin' => $request->description_hin,
            'is_active' => $request->is_active,
            'sort_order' => $request->sort_order,
            'updated_by' => Auth::id(),
        ];
    
        $folders = [
            'eng' => 'officedocs_eng',
            'hin' => 'officedocs_hin'
        ];
    
        foreach ($folders as $lang => $folder) {
            $fileKey = 'attachment_' . $lang;
            if ($request->hasFile($fileKey)) {
                $uploadedFile = $request->file($fileKey);
                $fileName = str_replace(' ', '_', $uploadedFile->getClientOriginalName());
                $filePath = $uploadedFile->storeAs($folder, $fileName, 'public');
                $officeData[$fileKey] = $fileName;
            } else {
                $officeData[$fileKey] = $officeItem->$fileKey;
            }
        }
    
        $officeItem->update($officeData);
    
        return back()->with('status', 'Office document updated successfully!');
    }    

    public function deleteAttachment($id, $lang)
    {
        $officeItem = Office_docs::find($id);
        if (!$officeItem) {
            return back()->with('error', 'Document not found.');
        }

        $fileKey = 'attachment_' . $lang;
        $folderPath = $lang === 'eng' ? 'public/officedocs_eng/' : 'public/officedocs_hin/';

        if ($officeItem->$fileKey && Storage::exists($folderPath . $officeItem->$fileKey)) {
            Storage::delete($folderPath . $officeItem->$fileKey);
            $officeItem->$fileKey = null;
            $officeItem->save();
        }

        return back()->with('status', 'Attachment deleted successfully!');
    }

    public function edit($id)
    {
        $officedocs = Office_docs::find($id);
        $categories = Category::where('is_active', 1)->orderBy('sort_order')->get();
        return view('officedocs.input-form', ['officedocs' => $officedocs, 'categories' => $categories]);
    }

    public function view($id)
    {
        $officedocs = Office_docs::find($id);
        $categories = Category::where('is_active', 1)->orderBy('name')->get();
        return view('officedocs.input-form', ['officedocs' => $officedocs, 'categories' => $categories, 'mode' => 'view']);
    }

    public function delete($id)
    {
        if ($id && $id != "") {
            $deleted = Office_docs::where('id', $id)->delete();
            if ($deleted) {
                return back()->with('status', 'Document deleted successfully');
            } else {
                return back()->with('error', 'Something went wrong. Document not deleted');
            }
        }
    }


    public function updateStatus(Request $request, $id)
    {
        $officedocs = Office_docs::find($id);
        if ($officedocs) {
            $officedocs->is_active = $request->is_active;
            $officedocs->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
    
    public function getOfficeData($categoryId, $lang = 'english')
    {
        if (!empty($categoryId)) {

            $column_suffix = $lang == 'hindi' ? '_hin' : '_eng';
            $folder = $lang == 'hindi' ? 'officedocs_hin' : 'officedocs_eng';

            if ($categoryId == 2 || $categoryId == 3) {
                $officeDocs = Office_docs::whereIn('category_id', [2, 3])->get();
            } else {
                $officeDocs = Office_docs::where('category_id', $categoryId)->get();
            }

            if ($officeDocs->isEmpty()) {
                return response()->json(['status' => 'error', 'message' => 'No documents found for this category.'], 404);
            }

            $docsData = [];
            foreach ($officeDocs as $doc) {
                $attachment = $doc->{'attachment' . $column_suffix} 
                    ? url('storage/' . $folder . '/' . $doc->{'attachment' . $column_suffix}) 
                    : null;
                    
                $docData = [
                    'id' => $doc->id,
                    'docName' => $doc->{'title' . $column_suffix},
                    'docDescription' => $doc->{'description' . $column_suffix},
                    'uploadedDate' => $doc->created_at->format('Y-m-d'),
                    'attachment' => $attachment
                ];
                $docsData[] = $docData;
            }

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $docsData
            ]);
        } else {
            
            return response()->json(['status' => 'error', 'message' => 'Category ID is required'], 400);
        }
    }
}
