<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\ComponentSection;
use App\Models\Page;
use Illuminate\Http\Request;
use Auth;

class ComponentController extends Controller
{
    
    public function create()
    {
        $pages = Page::orderBy('name')->get();
        $data['pages'] = $pages;
        return view('component.input-form', $data);
    }

    public function store(Request $request)
    {
        $id = $request->id;
        try {
            if ($id) {
                $component = Component::find($id);
                if (!empty($component)) {
                    $component->update(['heading_eng' => $request->headingEng, 'heading_hin' => $request->headingHin, 'name' => $request->name, 'is_active' => $request->input('is_active', 1)]);
                } else {
                    //nothing 
                }
            } else {
                $component = Component::create(['page_id' => $request->page_id, 'name' => $request->name, 'heading_eng' => $request->headingEng, 'heading_hin' => $request->headingHin, 'is_active' => $request->input('is_active', 1)]);
                $id = $component->id;
            }


            if (count($request->title_eng) > 0) {
                $images = $request->file('image');
                $filePath = [];
                if ($images) {
                    foreach ($images as $index => $image) {
                        if ($image != null) {
                            $fileName = date('YmdHis') . '-' . $index . '.' . $image->getClientOriginalExtension();
                            $path = 'assets/images/' . $fileName;
                            $image->storeAs('public', $path);
                            $filePath[$index] = 'storage/' . $path;
                        }
                    }
                }

                foreach ($request->title_eng as $i => $title) {
                    if (isset($request->sectionId[$i]) && $request->sectionId[$i] > 0) {
                        $componentId = $request->sectionId[$i];
                        $section = ComponentSection::find($componentId);
                        $section->created_by = Auth::id();
                    } else {
                        $section = new ComponentSection();
                    }
                    // dd($request->all());
                    $section->component_id = $id;
                    $section->parent_id = null;
                    $section->title_eng = $title;
                    $section->title_hin = $request->title_hin[$i];
                    $section->subtitle_eng = $request->subtitle_eng[$i];
                    $section->subtitle_hin = $request->subtitle_hin[$i];
                    $section->content_eng = $request->content_eng[$i];
                    $section->content_hin = $request->content_hin[$i];
                    if (isset($filePath[$i])) {
                        $section->image =  $filePath[$i];
                    }

                    $section->link = $request->link[$i];
                    $section->link_type = $request->link_type[$i];
                    $section->updated_by = Auth::id();
                    $section->save();
                }
            }
            return redirect()->back()->with('status', 'Data saved successfully');
        } catch (\Exception $th) {
            dd($th);
        }
    }

    public function list()
    {
        $data['components'] = Component::all();
        return view('component.list', $data);
    }

    public function updateStatus(Request $request, $id)
    {
        $component = Component::find($id);
        if ($component) {
            $component->is_active = $request->is_active;
            $component->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
    

    public function edit($id)
    {
        $component = Component::find($id);
        $pages = Page::orderBy('name')->get();
        $data['pages'] = $pages;
        if (!empty($component)) {
            $data['component'] = $component;
            return view('component.input-form', $data);
        } else {
            return back()->with('error', 'could not find the component');
        }
    }

    public function view($id)
    {
        $component = Component::find($id);
        $pages = Page::orderBy('name')->get();
        $data['pages'] = $pages;
        if (!empty($component)) {
            $data['component'] = $component;
            $data['viewMode'] = true; // Add a flag to indicate view mode
            return view('component.input-form', $data);
        } else {
            return back()->with('error', 'Could not find the component');
        }
    }


    public function getComponentData($componentName, $lang = 'english')
    {
        if ($componentName != "") {
            $component = Component::where('name', $componentName)->where('is_active', 1)->first();
            if (empty($component)) {
                return (['status' => 'error', 'message' => 'component not found']);
            }
            $column_suffix = $lang == 'hindi' ? '_hin' : '_eng';
            $data = ['heading' => $component->{'heading' . $column_suffix}];
            $sections = [];
            if ($component->sections->count() > 0) {
                foreach ($component->sections as $section) {
                    $sectionData = [
                        'title' => $section->{'title' . $column_suffix},
                        'subtitle' => $section->{'subtitle' . $column_suffix},
                        'content' => $section->{'content' . $column_suffix},
                        'image' => $section->image,
                        'link' => $section->link,
                        'link_type' => $section->link_type
                    ];
                    $sections[] = $sectionData;
                }
            }
            $data['sections'] = $sections;
            $data['status'] = 'success';
            $data['code'] = 200;
            return $data;
        } else {
            return (['status' => 'error', 'message' => 'component name is required']);
        }
    }

    public function getServiceComponentData($lang = 'english')
    {
        $componentName = 'Services'; 
        $component = Component::where('name', $componentName)->where('is_active', 1)->first();
        
        if (empty($component)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Component not found'
            ], 404);
        }

        $column_suffix = $lang == 'hindi' ? '_hin' : '_eng';
        $data = [
            'heading' => $component->{'heading' . $column_suffix}
        ];

        $sections = [];
        if ($component->sections->count() > 0) {
            foreach ($component->sections as $section) {
                $sectionData = [
                    'servicesIcon' => $section->image,
                    'servicesTitle' => $section->{'title' . $column_suffix},
                    'description' => $section->{'content' . $column_suffix},
                    'link' => $section->link
                ];
                $sections[] = $sectionData;
            }
        }

        $data['sections'] = $sections;
        $data['status'] = 'success';
        $data['code'] = 200;

        return response()->json($data);
    }
}

