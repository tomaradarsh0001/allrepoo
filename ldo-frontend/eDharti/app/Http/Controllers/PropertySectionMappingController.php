<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Services\ColonyService;
use App\Services\MisService;
use App\Models\OldColony;
use App\Models\Item;
use App\Models\User;
use App\Models\Section;
use App\Models\PropertySectionMapping;
use DB;
use Illuminate\Support\Facades\Log;
use Auth;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class PropertySectionMappingController extends Controller
{
    public function propertyAssignment(ColonyService $colonyService){
        $colonyList = $colonyService->getColonyList();
        $sections = Section::whereIn('id', [30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40])->get();

        $alreadyLinked = PropertySectionMapping::all();
        $colonyIds = $alreadyLinked->pluck('colony_id')->unique();
        $colonies = OldColony::whereIn('id', $colonyIds)->pluck('name', 'id');
        
        $alreadyLinked->transform(function ($data) use ($colonies) {
            // Set the colony name from the $colonies collection or default to 'Unknown'
            $data->colonyName = $colonies->get($data->colony_id, 'Unknown');
            return $data;
        });
        
        // Group the data by colony name, section, and property type
        $groupedData = $alreadyLinked->groupBy(function ($data) {
          
            return $data->colonyName . '-' . $data->section_id . '-' . $data->property_type;
        });
        $aggregatedData = $groupedData->map(function ($items, $key) {
            $firstItem = $items->first(); 
            $section = Section::where('id', $firstItem->section_id)->first();

            $item = (object)[
                'colonyName' => $firstItem->colonyName,
                'section_name' => $section->name ?? null,
                'section_code' => $section->section_code ?? null,
                'property_type' => $firstItem->property_type,
                'created_by' => $firstItem->created_by,
                'details' => $items
            ];
            return $item;
        })->values();

        // Paginate aggregated data
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10; // Number of items per page
        $currentItems = $aggregatedData->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $paginatedData = new LengthAwarePaginator(
            $currentItems,
            $aggregatedData->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );

        $item = new Item();
        $user = new User();
        return view('property-assign.index',compact(['colonyList','sections','paginatedData','item','user']));
    }

    public function isColonyAssignedToSection(Request $request){
        $isColonyAssigned = PropertySectionMapping::where('colony_id',$request->colony)->first();
        $colonyName = OldColony::where('id', $request->colony)->pluck('name')->first();
        if($isColonyAssigned){
            $response = ['status' => false, 'message' => 'Assignment already done for '.$colonyName. ' colony'];
        } else {
            $response = ['status' => true, 'message' => 'Colony available'];
        }
        return json_encode($response);

    }

    public function getAllPropertyTypes(Request $request,MisService $misService){
        //property type and subtypes for colony
        $colonyID = $request->colony;
        $typesSubtypesAlreadyDone = PropertySectionMapping::where('colony_id',$colonyID)->get();
        $groupedData = [];

        foreach ($typesSubtypesAlreadyDone as $entry) {
            $type = $entry->property_type;
            $subtype = $entry->property_subtype;

            // Initialize the type if it doesn't exist
            if (!isset($groupedData[$type])) {
                $groupedData[$type] = [
                    'type' => $type,
                    'subtypes' => []
                ];
            }

            // Add the subtype under the correct type
            $groupedData[$type]['subtypes'][] = $subtype;
        }

        // Transform the data to a more readable format
        $formattedData = [];

        foreach ($groupedData as $type => $data) {
            $formattedData[] = [
                'typeId' => $type,
                'subtypes' => $data['subtypes']
            ];
        }


        $propertyTypes = $misService->getItemsByGroupId(1052);
        $typeData = $propertyTypes[0]['items'];
        $data = [];

        foreach($typeData as $propertyType) {
            $propertyTypeSubTypeMapping = DB::table('property_type_sub_type_mapping')
                                            ->where('type', $propertyType->id)
                                            ->get();
                                            
            foreach ($propertyTypeSubTypeMapping as $mapping) {
                $item = new Item;
                $subTypeId = $mapping->sub_type;
                $name = $item->itemNameById($subTypeId);
                
                if (!isset($data[$propertyType->id])) {
                    $data[$propertyType->id] = [
                        'typeId' => $propertyType->id,
                        'type' => $propertyType->item_name,
                        'subTypes' => []
                    ];
                }

                $data[$propertyType->id]['subTypes'][] = [
                    'subId' => $subTypeId,
                    'subType' => $name
                ];
            }
        }
        
        $data = array_values($data);
        $formattedData = array_values($formattedData);
        $finalData['data'] = $data;
        $finalData['formattedData'] = $formattedData;
        return response()->json($finalData);
    }

    public function propertyAssignmentStore(Request $request){
        try {
            $count = 0;
            $colonyName = OldColony::where('id', $request->colony)->pluck('name')->first();
            $colonyId = $request->colony;
            $section_id = $request->section;
            $section = Section::where('id',$section_id)->value('name');
            $messages = [];
            $item = new Item;

            if(isset($request->propTypes)){
                foreach ($request->propTypes as $key => $propType) {
                    if (isset($request->subTypes[$key])) {
                        foreach ($request->subTypes[$key] as $subType) {
    
                            $isColonySectionAlreadymapped = PropertySectionMapping::where('colony_id',$colonyId)->where('section_id',$section)->where('property_type',$key)->where('property_subtype',$subType)->exists();
    
                            $isColonyTypeSubtypeAlreadymapped = PropertySectionMapping::where('colony_id',$colonyId)->where('property_type',$key)->where('property_subtype',$subType)->exists();
    
    
                            // Prepare messages for user feedback
                            if ($isColonySectionAlreadymapped) {
                                $messages[] = "Type ".$item->itemNameById($key)." and subtype ".$item->itemNameById($subType)." is already linked with the section '$section' for colony '$colonyName'.";
                            } elseif ($isColonyTypeSubtypeAlreadymapped) {
                                $messages[] = "Type ".$item->itemNameById($key)." and subtype ".$item->itemNameById($subType)." is already linked with the colony '$colonyName', in a different section.";
                            }
    
                            if(!$isColonySectionAlreadymapped && !$isColonyTypeSubtypeAlreadymapped){
                                PropertySectionMapping::create([
                                    'colony_id' => $colonyId,
                                    'section_id' => $section_id,
                                    'property_type' => $key,
                                    'property_subtype' => $subType,
                                    'created_by' => Auth::id()
                                ]);
                                $count += 1;
                            }
                        }
                    }
                }
                if ($count == 0) {
                    if (empty($messages)) {
                        $messages[] = $colonyName . ' colony already linked with same or other section.';
                    }
                    return redirect()->back()->with('failure', implode('# ', $messages));
                } else {
                    $messages[] = $colonyName . ' colony linked with ' . $section . ' section successfully.';
                    return redirect()->back()->with('success', implode(' #', $messages));
                }
            } else {
                return redirect()->back()->with('failure', 'No values selected');
            }
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('failure',$e->getMessage());
        }
    }
}
