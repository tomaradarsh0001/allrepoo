<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Exception;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $parentMenus = $this->getParentMenu();
        $data['parentMenus'] = $parentMenus;
        return view('menu.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentMenu = $this->getParentMenu();
        $data['parentMenu'] = $parentMenu;
        return view('menu.input-form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $row = [
                'label_eng' => $request->label_eng,
                'label_hin' => $request->label_hin,
                'parent_id' => $request->parent_id != '' ? $request->parent_id : null,
                'sort_order' => $request->sort_order,
                'location' => $request->location,
                'link' => $request->link,
                'link_in_new_tab' => $request->link_in_new_tab,
                'is_active' => $request->input('is_active', 1),
                'page_id' => null,
                'created_by' => Auth::id()
            ];
            if (isset($request->id) && $request->id != '') {
                $menu = Menu::find($request->id);
                if (!empty($menu)) {
                    $updated = $menu->update($row);
                    if ($updated) {
                        return back()->with('status', 'menu updated successfully');
                    }
                }
            } else {
                $added = Menu::create($row);
                if ($added) {
                    return back()->with('status', 'menu added successfully');
                }
            }
        } catch (\Exception $err) {
            return back()->with('error', $err->getMessage());
        }
    }

    public function delete($id)
    {
        if ($id && $id != "") {
            $deleted = Menu::where('id', $id)->delete();
            if ($deleted) {
                return back()->with('status', 'Menu deleted successfully');
            } else {
                return back()->with('error', 'Something went wrong. Menu not deleted');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $parentMenu = $this->getParentMenu();
        $data['parentMenu'] = $parentMenu;
        $data['menu'] = Menu::find($id);
        return view('menu.input-form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function getParentMenu($location = '', $is_active = 1)
    {
        $menuQuery = Menu::whereNull('parent_id');
        if ($location != '') {
            $menuQuery = $menuQuery->where('location', $location);
        } else {
            $menuQuery = $menuQuery->orderBy('location');
        }
        if ($is_active) {
            $menuQuery = $menuQuery->where('is_active', 1);
        }
        return $menuQuery->orderBy('sort_order')->get();
    }

    public function view($id)
    {
        $menu = Menu::find($id);
        $parentMenu = $this->getParentMenu();
        $data['parentMenu'] = $parentMenu;
        $data['menu'] = $menu;
        return view('menu.input-form', $data);
    }

    public function updateStatus(Request $request, $id)
    {
        $menu = Menu::find($id);
        if ($menu) {
            $menu->is_active = $request->is_active;
            $menu->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function apiHeaderMenu($lang = '')
    {
        if ($lang != '' && $lang != 'hindi' && $lang != 'english') {
            return response()->json(['status' => 'error', 'message' => 'Invalid Language']);
        }
        $location = 'header';
        $menus = $this->getParentMenu($location, 1);
        if ($menus->count() == 0) {
            return response()->json(['status' => 'error', 'message' => 'No data found']);
        } else {
            $returnArray = [];
            foreach ($menus as $menu) {
                $menu_data = [
                    'name' => ($lang == 'hindi') ? $menu->label_hin : $menu->label_eng,
                    'link' => ($menu->link != '') ? $menu->link : '/',
                    'new_tab' => $menu->link_in_new_tab,
                    'sort_order' => $menu->sort_order
                ];
                if ($menu->submenus && $menu->submenus->count() > 0) {
                    $submenus = [];
                    foreach ($menu->submenus as $sm) {
                        if ($sm->is_active == 1) {
                            array_push($submenus, [
                                'name' => ($lang == 'hindi') ? $sm->label_hin : $sm->label_eng,
                                'link' => ($sm->link != '') ? $sm->link : '#',
                                'new_tab' => $sm->link_in_new_tab,
                                'sort_order' => $sm->sort_order
                            ]);
                        }
                    }
                    $menu_data['submenus'] = $submenus;
                }
                array_push($returnArray, $menu_data);
            }

            return response()->json(['data' => $returnArray, 'code' => 200]);
        }
    }

    public function apiFooterMenu($lang = '')
    {
        if ($lang != '' && $lang != 'hindi' && $lang != 'english') {
            return response()->json(['status' => 'error', 'message' => 'Invalid Language']);
        }
        $location = 'footer';
        $menus = $this->getParentMenu($location, 1);
        if ($menus->count() == 0) {
            return response()->json(['status' => 'error', 'message' => 'No data found']);
        } else {
            $returnArray = [];
            foreach ($menus as $menu) {
                $menu_data = [
                    'name' => ($lang == 'hindi') ? $menu->label_hin : $menu->label_eng,
                    'link' => ($menu->link != '') ? $menu->link : '/',
                    'new_tab' => $menu->link_in_new_tab,
                    'sort_order' => $menu->sort_order
                ];
                if ($menu->submenus && $menu->submenus->count() > 0) {
                    $submenus = [];
                    foreach ($menu->submenus as $sm) {
                        if ($sm->is_active == 1) {
                            array_push($submenus, [
                                'name' => ($lang == 'hindi') ? $sm->label_hin : $sm->label_eng,
                                'link' => ($sm->link != '') ? $sm->link : '#',
                                'new_tab' => $sm->link_in_new_tab,
                                'sort_order' => $sm->sort_order
                            ]);
                        }
                    }
                    $menu_data['submenus'] = $submenus;
                }
                array_push($returnArray, $menu_data);
            }

            return response()->json(['data' => $returnArray, 'code' => 200]);
        }
    }
}
