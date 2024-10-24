<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    //
    public function create()
    {
        $news = News::all();
        return view('news.input-form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_eng' => 'required|string',
            'description_eng' => 'required|string|max:5000',
            'title_hin' => 'required|string',
            'description_hin' => 'required|string|max:5000',
            'document_path' => 'nullable|file',
        ]);
    
        $newsData = [
            'title_eng' => $request->title_eng,
            'title_hin' => $request->title_hin,
            'description_eng' => $request->description_eng,
            'description_hin' => $request->description_hin,
            'is_active' => $request->is_active,
            'sort_order' => $request->sort_order,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ];
    
        if ($request->hasFile('document_path')) {
            $uploadedFile = $request->file('document_path');
            $fileName = str_replace(' ', '_', $uploadedFile->getClientOriginalName());
            $filePath = $uploadedFile->storeAs('news', $fileName, 'public');
            $newsData['document_path'] = $fileName;
        }
    
        News::create($newsData);
        return back()->with('status', 'News added successfully!');
    }
    

    public function list()
    {
        $news= News::get();
        return view('news.list',['news'=> $news]);
    }

    public function updateStatus(Request $request, $id)
    {
        $news = News::find($id);
        if ($news) {
            $news->is_active = $request->is_active;
            $news->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title_eng' => 'required|string',
            'description_eng' => 'required|string|max:5000',
            'title_hin' => 'required|string',
            'description_hin' => 'required|string|max:5000',
        ]);

        $newsItem = News::find($id);
        if ($newsItem) {
            $newsData = [
                'title_eng' => $request->title_eng,
                'title_hin' => $request->title_hin,
                'description_eng' => $request->description_eng,
                'description_hin' => $request->description_hin,
                'is_active' => $request->is_active,
                'sort_order' => $request->sort_order,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];
        
            // Handle file upload
            if ($request->hasFile('document_path')) {
                $uploadedFile = $request->file('document_path');
                $fileName = str_replace(' ', '_', $uploadedFile->getClientOriginalName());
                $filePath = $uploadedFile->storeAs('news', $fileName, 'public');
                $newsData['document_path'] = $fileName;
            }

            $newsItem->update($newsData);
            return back()->with('status', 'News updated successfully!');
        }

        return back()->with('error', 'News not found.');
    }

    public function deleteDocument($id)
    {
        $newsItem = News::find($id);
        if ($newsItem) {
            
            Storage::delete('public/news/' . $newsItem->document_path);
            
            
            $newsItem->document_path = null;
            $newsItem->save();
            
            return back()->with('status', 'Document deleted successfully!');
        }
        return back()->with('error', 'News not found.');
    }
    
    public function edit($id)
    {
        $news = News::find($id);
        return view('news.input-form', ['news' => $news]);
    }

    public function view($id)
    {
        $news = News::find($id);
        return view('news.input-form', ['news' => $news, 'mode' => 'view']);
    }


    public function delete($id)
    {
        if ($id && $id != "") {
            $deleted = News::where('id', $id)->delete();
            if ($deleted) {
                return back()->with('status', 'News deleted successfully');
            } else {
                return back()->with('error', 'Something went wrong. News not deleted');
            }
        }
    }
    
    // SwatiMishra 03-05-2024 11:11 AM api for news Start
    public function apiHeaderNews($lang = NULL)
    {

        $newsItems = News::where('is_active', 1)
                        ->orderBy('sort_order')
                        ->orderBy('created_at')
                        ->take(7)
                        ->get();

        if ($newsItems->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'No news found'], 404);
        }

        $returnArray = [];
        foreach ($newsItems as $newsItem) {
            // $documentUrl = null;
            // if (!empty($newsItem->document_path)) {
            //     $documentUrl = Storage::url('public/news/' . $newsItem->document_path);
            // }

            $news_data = [
                'id' => $newsItem->id,
                'title' => ($lang == 'hindi') ? $newsItem->title_hin : $newsItem->title_eng,
                // 'description' => ($lang == 'hindi') ? $newsItem->description_hin : $newsItem->description_eng,
                'sort_order' => $newsItem->sort_order,
                // 'document_url' => $documentUrl,
            ];
            $returnArray[] = $news_data;
        }

        return response()->json($returnArray, 200, [], JSON_UNESCAPED_UNICODE);
    }

// SwatiMishra 03-05-2024 11:11 AM api for news End

// SwatiMishra 03-05-2024 11:11 AM api for news Start
public function apiNewsDescription($lang = NULL)
    {

        $newsItems = News::where('is_active', 1)
                        ->orderBy('sort_order')
                        ->orderBy('created_at')
                        ->take(20)
                        ->get();

        if ($newsItems->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'No news found'], 404);
        }

        $returnArray = [];
        foreach ($newsItems as $newsItem) {
            // $documentUrl = null;
            // if (!empty($newsItem->document_path)) {
            //     $documentUrl = Storage::url('public/news/' . $newsItem->document_path);
            // }

            $documentUrl = null;

            if (!empty($newsItem->document_path)) {
                $documentUrl = url('storage/news/' . $newsItem->document_path);
            }
            $news_data = [
                'id' => $newsItem->id,
                'title' => ($lang == 'hindi') ? $newsItem->title_hin : $newsItem->title_eng,
                'description' => ($lang == 'hindi') ? $newsItem->description_hin : $newsItem->description_eng,
                'sort_order' => $newsItem->sort_order,
                'document_url' => $documentUrl,
            ];
            $returnArray[] = $news_data;
        }

        return response()->json($returnArray, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function newsDescriptionById($id, $lang = NULL)
    {
        $newsItem = News::where('id', $id)->where('is_active', 1)->first();

        if (!$newsItem) {
            return response()->json(['status' => 'error', 'message' => 'No news found'], 404);
        }

        $documentUrl = null;

        if (!empty($newsItem->document_path)) {
            $documentUrl = url('storage/news/' . $newsItem->document_path);
        }

        $news_data = [
            'id' => $newsItem->id,
            'title' => ($lang == 'hindi') ? $newsItem->title_hin : $newsItem->title_eng,
            'description' => ($lang == 'hindi') ? $newsItem->description_hin : $newsItem->description_eng,
            'document_url' => $documentUrl,
        ];

        return response()->json($news_data, 200, [], JSON_UNESCAPED_UNICODE);
    }


}

