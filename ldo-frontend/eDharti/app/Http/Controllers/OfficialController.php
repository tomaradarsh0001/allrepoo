<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApplicationMovement;
use App\Services\UserRegistrationService;
use App\Models\PropertyMaster;
use App\Models\User;
use App\Models\Item;
use App\Models\ApplicationStatus;
use App\Models\NewlyAddedProperty;
use App\Models\UserRegistration;
use App\Models\SectionMisHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NewlyAddedPropertyExport; // Create this export class
use App\Exports\UserRegistrationExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\Helpers\GeneralFunctions;
use App\Services\SettingsService;
use App\Services\CommunicationService;
use App\Mail\CommonMail;
use App\Models\CurrentLesseeDetail;
use App\Models\Flat;
use App\Models\FlatHistory;
use App\Models\OldColony;
use App\Models\PropertyLeaseDetail;
use App\Models\PropertyTransferLesseeDetailHistory;
use App\Models\PropertyTransferredLesseeDetail;
use App\Models\SplitedPropertyDetail;
use App\Services\ColonyService;
use App\Services\MisService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Http;
use App\Services\FlatService;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Config;

class OfficialController extends Controller
{
    protected $userRegistrationService;
    protected $communicationService;
    protected $settingsService;
    protected $flatService;

    public function __construct(UserRegistrationService $userRegistrationService, CommunicationService $communicationService, SettingsService $settingsService, FlatService $flatService)
    {
        $this->userRegistrationService = $userRegistrationService;
        $this->communicationService = $communicationService;
        $this->settingsService = $settingsService;
        $this->flatService = $flatService;
    }

    public function index(Request $request)
    {
        $getStatusId = '';
        if ($request->query('status')) {
            $getStatusId = Item::where('item_code', Crypt::decrypt($request->query('status')))->value('id');
        }
        $user = Auth::user();
        $filterPermissionArr = [];
        $permissionMap = [
            'view.registration.new' => 'RS_NEW',
            'view.registration.approved' => 'RS_APP',
            'view.registration.rejected' => 'RS_REJ',
            'view.registration.under_review' => 'RS_UREW',
            'view.registration.reviewed' => 'RS_REW',
            'view.registration.pending' => 'RS_PEN',
        ];

        $allPermissions = $user->getAllPermissions();
        foreach ($allPermissions as $permission) {
            if (isset($permissionMap[$permission->name])) {
                $filterPermissionArr[] = $permissionMap[$permission->name];
            }
        }

        if (!empty($filterPermissionArr)) {
            $items = Item::where('group_id', 17000)
                ->whereIn('item_code', $filterPermissionArr)
                ->get();
        }
        return view('officials.register-users.indexDatatable', compact('items', 'getStatusId', 'user'));
    }

    public function getRegisteredUsers(Request $request)
    {
        // Get the logged-in user
        $user = Auth::user();
        $sections = $user->sections->pluck('id');
        // Define the query outside of the AJAX block
        $query = UserRegistration::query()
            ->with('oldColony')
            ->leftJoin('application_movements', function ($join) {
                $join->on('user_registrations.applicant_number', '=', 'application_movements.application_no')
                    ->whereIn('application_movements.id', function ($subQuery) {
                        $subQuery->select(DB::raw('MAX(id)'))
                            ->from('application_movements')
                            ->groupBy('application_no');
                    });
            })
            ->leftJoin('users as assigned_by_user', 'application_movements.assigned_by', '=', 'assigned_by_user.id')
            ->leftJoin('users as assigned_to_user', 'application_movements.assigned_to', '=', 'assigned_to_user.id')
            // ->leftJoin('users', 'user_registrations.user_id', '=', 'users.id')
            ->leftJoin('items', 'user_registrations.status', '=', 'items.id')
            ->leftJoin('old_colonies', 'user_registrations.locality', '=', 'old_colonies.id')
            ->leftjoin('application_statuses', 'user_registrations.id', '=', 'application_statuses.model_id')
            ->leftjoin('sections', 'sections.id', '=', 'user_registrations.section_id')
            ->select(
                'user_registrations.*',
                'items.item_name',
                'items.item_code',
                'application_movements.assigned_by',
                'assigned_by_user.name as assigned_by_name',
                'application_movements.assigned_to',
                'assigned_to_user.name as assigned_to_name',
                'old_colonies.name as old_colony_name',
                'application_statuses.is_mis_checked',
                'application_statuses.is_scan_file_checked',
                'application_statuses.is_uploaded_doc_checked',
                'application_statuses.mis_checked_by',
                'application_statuses.scan_file_checked_by',
                'application_statuses.uploaded_doc_checked_by',
                'user_registrations.remarks',
                DB::raw("CONCAT_WS('/', block, plot, old_colonies.name) as property_details"), // Add this line
                'user_registrations.created_at',
                'sections.section_code',
                'sections.name as section_name',
            )
            ->whereIn('section_id', $sections);



        // Apply status filter if provided
        if ($request->status) {
            $query->where('user_registrations.status', $request->status);
        }

        // Apply status condition based on user role
        //Commented by Lalit on :- 30/09/2024, I have comment this after discussion with Amita Mam on like we can display all recored to deputy l & do but add one more column section and show column visiblity according to role.
        /*$query->when($request->status || $user->roles[0]['name'] == 'deputy-lndo', function ($q) use ($request, $user) {
            if ($user->roles[0]['name'] == 'deputy-lndo') {
                $q->where('user_registrations.status', $request->status ?? getStatusName('RS_UREW'));
            } else {
                $q->where('user_registrations.status', $request->status);
            }
        });*/


        $columns = ['id', 'applicant_number', 'name', 'property_details', 'user_type', 'purpose_of_registation', 'document', 'remarks', 'status', 'created_at'];

        $totalData = $query->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        if ($request->input('order.0.column')) {
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
        } else {
            $order = $columns['9'];
            $dir = 'desc';
        }

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search) {
                $q->where('user_registrations.applicant_number', 'LIKE', "%{$search}%")
                    ->orWhere('user_registrations.name', 'LIKE', "%{$search}%")
                    ->orWhere(function ($q) use ($search) {
                        $q->where('user_registrations.block', 'LIKE', "%{$search}%")
                            ->orWhere('user_registrations.plot', 'LIKE', "%{$search}%")
                            ->orWhere('old_colonies.name', 'LIKE', "%{$search}%");
                    })
                    ->orWhere('user_registrations.remarks', 'LIKE', "%{$search}%");
            });

            $totalFiltered = $query->count();
        }

        $getRegistrationDetails = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
        $data = [];
        foreach ($getRegistrationDetails as $getRegistrationDetail) {
            $mis_checked_by = User::find($getRegistrationDetail->mis_checked_by);
            $scan_file_checked_by = User::find($getRegistrationDetail->scan_file_checked_by);
            $uploaded_doc_checked_by = User::find($getRegistrationDetail->uploaded_doc_checked_by);
            $nestedData = [];
            $nestedData['id'] = $getRegistrationDetail->id;
            $nestedData['applicant_number'] = $getRegistrationDetail->applicant_number;
            $nestedData['name'] = $getRegistrationDetail->name;
            $nestedData['property_details'] = ucfirst($getRegistrationDetail->block) . '/' . ucfirst($getRegistrationDetail->plot) . '/' . ucfirst($getRegistrationDetail->old_colony_name);
            $nestedData['user_type'] = $getRegistrationDetail->user_type;
            $nestedData['purpose_of_registation'] = $getRegistrationDetail->purpose_of_registation === 'existing_property'
                ? 'Existing Property'
                : 'Allotment';
            $nestedData['section'] = '<label for="area" class="form-label">' . $getRegistrationDetail->section_code . ' <small class="form-text text-muted">(' . $getRegistrationDetail->section_name . ')</small></label>';
            $nestedData['documents'] = [
                'sale_deed_doc' => $getRegistrationDetail->sale_deed_doc,
                'builder_buyer_agreement_doc' => $getRegistrationDetail->builder_buyer_agreement_doc,
                'lease_deed_doc' => $getRegistrationDetail->lease_deed_doc,
                'substitution_mutation_letter_doc' => $getRegistrationDetail->substitution_mutation_letter_doc,
                'owner_lessee_doc' => $getRegistrationDetail->owner_lessee_doc,
                'other_doc' => $getRegistrationDetail->other_doc,
                'authorised_signatory_doc' => $getRegistrationDetail->authorised_signatory_doc,
                'chain_of_ownership_doc' => $getRegistrationDetail->chain_of_ownership_doc,
            ];
            $nestedData['activity'] = [
                'mis' => !empty($getRegistrationDetail->is_mis_checked) ? $getRegistrationDetail->is_mis_checked : 'NA',
                'scanned_files' => !empty($getRegistrationDetail->is_scan_file_checked) ? $getRegistrationDetail->is_scan_file_checked : 'NA',
                'uploaded_doc' => !empty($getRegistrationDetail->is_uploaded_doc_checked) ? $getRegistrationDetail->is_uploaded_doc_checked : 'NA',
                'mis_checked_by' => !empty($getRegistrationDetail->mis_checked_by) ? $mis_checked_by->name : '',
                'scan_file_checked_by' => !empty($getRegistrationDetail->scan_file_checked_by) ? $scan_file_checked_by->name : '',
                'uploaded_doc_checked_by' => !empty($getRegistrationDetail->uploaded_doc_checked_by) ? $uploaded_doc_checked_by->name : '',
                'mis_color_code' => !empty(getServiceTypeColorCode('MIS_CHECK')) ? getServiceTypeColorCode('MIS_CHECK') : '',
                'scan_file_color_code' => !empty(getServiceTypeColorCode('SCAN_CHECK')) ? getServiceTypeColorCode('SCAN_CHECK') : '',
                'uploaded_doc_color_code' => !empty(getServiceTypeColorCode('UP_DOC_CHE')) ? getServiceTypeColorCode('UP_DOC_CHE') : '',
            ];

            $nestedData['remark'] = [
                'remark' => !empty($getRegistrationDetail->remarks) ? strip_tags($getRegistrationDetail->remarks) : 'NA',
                'assigned_by_name' => !empty($getRegistrationDetail->assigned_by_name) ? $getRegistrationDetail->assigned_by_name : 'NA',
            ];
            $statusClasses = [
                'RS_REJ' => 'highlight_value statusRejected',
                'RS_NEW' => 'highlight_value statusNew',
                'RS_UREW' => 'highlight_value statusWarning',
                'RS_REW' => 'highlight_value statusSecondary',
                'RS_PEN' => 'highlight_value bg-light-info',
                'RS_APP' => 'highlight_value landtypeFreeH',
            ];
            $class = $statusClasses[$getRegistrationDetail->item_code] ?? 'text-secondary bg-light';
            $nestedData['status'] = '<span class="' . $class . '">' . ucwords($getRegistrationDetail->item_name) . '</span>';
            $nestedData['created_at'] = $getRegistrationDetail->created_at->format('d/m/Y H:i:s');
            $nestedData['action'] = '<a href="' . url('register/user/' . $getRegistrationDetail->id . '/view') . '">
                <button type="button" class="btn btn-success px-5">View</button>
            </a>';
            $data[] = $nestedData;
        }

        $json_data = [
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        ];

        return response()->json($json_data);
    }

    //Add by lalit on 31/07/2024 to show register user listing
    public function getUserRegistrations($user, $sections)
    {
        // Base query builder
        $query = UserRegistration::with('oldColony')
            ->leftJoin('application_movements', function ($join) {
                $join->on('user_registrations.applicant_number', '=', 'application_movements.application_no')
                    ->whereIn('application_movements.id', function ($subQuery) {
                        $subQuery->select(DB::raw('MAX(id)'))
                            ->from('application_movements')
                            ->groupBy('application_no');
                    });
            })
            ->leftJoin('users as assigned_by_user', 'application_movements.assigned_by', '=', 'assigned_by_user.id')
            ->leftJoin('users as assigned_to_user', 'application_movements.assigned_to', '=', 'assigned_to_user.id')
            ->leftJoin('items', 'user_registrations.status', '=', 'items.id')
            ->select(
                'user_registrations.*',
                'items.item_name',
                'items.item_code',
                'application_movements.assigned_by',
                'assigned_by_user.name as assigned_by_name',
                'application_movements.assigned_to',
                'assigned_to_user.name as assigned_to_name'
            )
            ->whereIn('section_id', $sections)
            ->orderBy('user_registrations.created_at', 'asc');

        // Adjust query based on user role
        if ($user->roles[0]['name'] == 'deputy-lndo') {
            $query->where('user_registrations.status', getStatusName('RS_UREW'));
        }

        // Paginate results
        $dataWithPagination = $query->paginate(20);

        return $dataWithPagination;
    }

    //Add by lalit on 31/07/2024 to update register user status
    public function updateStatus($id, Request $request)
    {
        if (!empty($id)) {
            // DB::transaction(function () use ($id, $request) {
            $updateStatus = UserRegistration::where('id', $id)->update([
                'status' => getStatusName('RS_REJ'),
                'remarks' => $request->remarks
            ]);

            if ($updateStatus) {
                $registerUser = UserRegistration::where('id', $id)->first();

                $applicationMovement = ApplicationMovement::create([
                    'assigned_by' => Auth::user()->id,
                    'service_type' => getServiceType('RS_NEW_REG'),
                    // 'service_type' => getServiceType('RS_REG'),
                    'model_id' => $id,
                    'status' => getStatusName('RS_REJ'),
                    'application_no' => $registerUser->applicant_number,
                    'remarks' => $request->remarks
                ]);

                if ($applicationMovement) {
                    if ($registerUser) {
                        ApplicationStatus::create([
                            // 'service_type' => getServiceType('RS_REG'),
                            'service_type' => getServiceType('RS_NEW_REG'),
                            'model_id' => $id,
                            'reg_app_no' => $registerUser->applicant_number,
                            'is_mis_checked' => $request->is_mis_checked ? true : false,
                            'is_scan_file_checked' => $request->is_scan_file_checked ? true : false,
                            'is_uploaded_doc_checked' => $request->is_uploaded_doc_checked ? true : false,
                            'created_by' => Auth::user()->id,
                        ]);

                        $data = [
                            'name' => $registerUser->name,
                            'email' => $registerUser->email,
                            'regNo' => $registerUser->applicant_number,
                            'remark' => $registerUser->remarks
                        ];
                        $action = 'REG_REJ';
                        $this->settingsService->applyMailSettings($action);
                        Mail::to($registerUser->email)->send(new CommonMail($data, $action));
                        $this->communicationService->sendSmsMessage($data, $registerUser->mobile, $action, $registerUser->country_code);
                        $this->communicationService->sendWhatsAppMessage($data, $registerUser->mobile, $action, $registerUser->country_code);
                    }
                    return response()->json(['status' => 'success', 'message' => Config::get('messages.success.mis_rejected')]);
                }
            } else {
                return response()->json(['status' => 'failure', 'message' => Config::get('messages.error.mis_rejected_failed')]);
            }
            // });
        }
    }


    //Add by lalit on 31/07/2024 to get register user details for view page
    public function details($id)
    {
        try {
            if (!empty($id)) {
                // Retrieve the user's roles
                $roles = Auth::user()->roles[0]->name;
                $data = [];
                $regUserDetails = UserRegistration::find($id);
                $scannedFiles = [];
                if ($regUserDetails) {
                    //Fetch Suggested Property Id from Property Master
                    $property = PropertyMaster::where('new_colony_name', $regUserDetails->locality)->where('block_no', $regUserDetails->block)->where('plot_or_property_no', $regUserDetails->plot)->first();
                    if (!empty($property['id'])) {
                        /*$response = Http::get('https://ldo.gov.in/eDhartiAPI/Api/GetValues/PropertyDocList?PropertyID=' . $property['old_propert_id']);
                        $jsonData = $response->json();
                        if ($jsonData) {
                            $scannedFiles['baseUrl'] = $jsonData[0]['Path'];
                            foreach ($jsonData[0]['ListFileName'] as $data) {
                                $scannedFiles['files'][] = $data['PropertyFileName'];
                            }
                        }*/
                        // Replace Above code by Lalit On 09/19/2024 to handle above 3rd party api https://ldo.gov.in either it works or fails.
                        // Make the request with a timeout of 10 seconds
                        $response = Http::timeout(10)->get('https://ldo.gov.in/eDhartiAPI/Api/GetValues/PropertyDocList?PropertyID=' . $property['old_propert_id']);

                        // Check if the api working & giving response for scanned files
                        if ($response->successful()) {
                            $jsonData = $response->json();
                            // Proceed if jsonData is not empty
                            if (!empty($jsonData)) {
                                $scannedFiles['baseUrl'] = $jsonData[0]['Path'];
                                foreach ($jsonData[0]['ListFileName'] as $data) {
                                    $scannedFiles['files'][] = $data['PropertyFileName'];
                                }
                            } else {
                                // Handle case where the response is empty or not as expected
                                Log::warning('API response returned empty or invalid data.');
                                $scannedFiles['files'] = [];
                            }
                        } else {
                            // Handle non-successful HTTP response
                            Log::error('API request failed with status: ' . $response->status());
                            $scannedFiles['files'] = [];
                        }
                        $data['propertyMasterId'] = $property['id'];
                        $data['suggestedPropertyId'] = $property['old_propert_id'];
                        $data['oldPropertyId'] = $property['old_propert_id'];
                        $data['uniquePropertyId'] = $property['unique_propert_id'];
                        $data['sectionCode'] = $property['section_code'];
                    } else {
                        $data['propertyMasterId'] = '';
                        $data['suggestedPropertyId'] = '';
                        $data['oldPropertyId'] = '';
                        $data['uniquePropertyId'] = '';
                        $data['sectionCode'] = '';
                    }
                    //Get Flat Details if property is related to flat - Lalit tiwari (16/Oct/2024)
                    if (!empty($regUserDetails->flat_id)) {
                        $flatDetails = Flat::find($regUserDetails->flat_id);
                        $data['flatDetails'] = $flatDetails;
                    } else {
                        $data['flatDetails'] = [];
                    }
                    $data['details'] = $regUserDetails;
                    $data['applicationMovementId'] = $id;
                    // $checkList = ApplicationStatus::where('service_type', getServiceType('RS_REG'))->where('model_id', $id)->first();
                    $checkList = ApplicationStatus::where('service_type', getServiceType('RS_NEW_REG'))->where('model_id', $id)->first();
                    return view('officials.register-users.details', compact(['data', 'roles', 'checkList', 'scannedFiles']));
                }
            }
        } catch (RequestException $e) {
            // Catch any request exceptions, such as timeouts or network issues
            Log::error('Error fetching API data: ' . $e->getMessage());
            $scannedFiles['files'] = [];
        } catch (\Exception $e) {
            // Catch other general exceptions
            Log::error('Unexpected error: ' . $e->getMessage());
            $scannedFiles['files'] = [];
        }
    }

    //Add by lalit on 31/07/2024 to approve user registeration
    public function approvedUserRegistration(Request $request)
    {
        if (!empty($request->registrationId) && !empty($request->suggestedPropertyId) && !empty($request->oldPropertyId) && !empty($request->emailId)) {
            //Check user email is alredy exist in user table
            $emailExists = User::where('email', $request->emailId)->exists();
            if ($emailExists) {
                // Email exists in the users table
                return redirect()->route('regiserUserListings')->with('failure', 'Email is already registered with us.');
            }

            $getUserRegistrationDetails = UserRegistration::find($request->registrationId);
            if (!empty($getUserRegistrationDetails->id)) {
                if ($getUserRegistrationDetails->status == getStatusName('RS_APP')) {
                    return redirect()->route('regiserUserListings')->with('success', 'User has already approved.');
                } else {
                    $result = $this->userRegistrationService->approveRegistration($request);
                    if ($result) {
                        return redirect()->route('regiserUserListings')->with('success', 'User registration approved successfully.');
                    } else {
                        return redirect()->route('regiserUserListings')->with('failure', 'Failed to approve user registration.');
                    }
                }
            } else {
                return redirect()->route('regiserUserListings')->with('failure', 'Invalid user registration id, please check.');
            }
        } else {
            return redirect()->route('regiserUserListings')->with('failure', 'RegistrationId, SuggestedPropertyId & OldPropertyId should not be empty.');
        }
    }

    //Add by lalit on 31/07/2024 to update register user status as rejected
    public function rejectUserRegistration($id, Request $request)
    {
        if (!empty($id)) {
            $updateStatus = UserRegistration::where('id', $id)->update(['status' => 'rejected', 'remarks' => $request->remarks]);
            if ($updateStatus) {
                return redirect()->route('regiserUserListings')->with('success', 'User registration rejected successfully.');
            } else {
                return redirect()->route('regiserUserListings')->with('failure', 'User registration does not rejected.');
            }
        }
    }

    //Add by lalit on 01/08/2024 to update register user status as under review
    public function reviewUserRegistration($id, Request $request)
    {
        $result = $this->userRegistrationService->moveUnderReviewApplication($id, $request);
        if ($result) {
            return response()->json(['status' => 'success', 'message' => Config::get('messages.success.user_registration_under_review')]);
        } else {
            return response()->json(['status' => 'failure', 'message' => Config::get('messages.error.user_registration_under_review_failed')]);
        }
    }

    //Add by lalit on 06/08/2024 to approve review application
    public function approvedReviewApplication(Request $request)
    {
        if (!empty($request->applicationMovementId) && !empty($request->remarks)) {
            $result = $this->userRegistrationService->approveReviewRequest($request);
            if ($result) {
                // return redirect()->route('reviewApplicationsListings')->with('success', 'Application request approved successfully.');
                return redirect()->back()->with('success', 'Application request approved successfully.');
            } else {
                // return redirect()->route('reviewApplicationsListings')->with('failure', 'Failed approve application request.');
                return redirect()->back()->with('failure', 'Failed approve application request.');
            }
        } else {
            // return redirect()->route('reviewApplicationsListings')->with('failure', 'ApplicationId & Remarks should not be empty.');
            return redirect()->back()->with('failure', 'ApplicationId & Remarks should not be empty.');
        }
    }

    public function applicantNewProperties(Request $request)
    {
        $getStatusId = '';
        if ($request->query('status')) {
            $getStatusId = Item::where('item_code', $status = Crypt::decrypt($request->query('status')))->value('id');
        }
        $user = Auth::user();
        $filterPermissionArr = [];
        $permissionMap = [
            'view.registration.new' => 'RS_NEW',
            'view.registration.approved' => 'RS_APP',
            'view.registration.rejected' => 'RS_REJ',
            'view.registration.under_review' => 'RS_UREW',
            'view.registration.reviewed' => 'RS_REW',
            'view.registration.pending' => 'RS_PEN',
        ];

        $allPermissions = $user->getAllPermissions();
        foreach ($allPermissions as $permission) {
            if (isset($permissionMap[$permission->name])) {
                $filterPermissionArr[] = $permissionMap[$permission->name];
            }
        }

        if (!empty($filterPermissionArr)) {
            $items = Item::where('group_id', 17000)
                ->whereIn('item_code', $filterPermissionArr)
                ->get();
        }
        return view('officials.applicant.indexDatatable', compact('items', 'getStatusId'));
    }

    public function getApplicantPropertyListings(Request $request)
    {
        // Get the logged-in user
        $user = Auth::user();
        $sections = $user->sections->pluck('id');

        // Define the query outside of the AJAX block
        $query = NewlyAddedProperty::query()
            ->with('oldColony')
            ->leftJoin('application_movements', function ($join) {
                $join->on('newly_added_properties.applicant_number', '=', 'application_movements.application_no')
                    ->whereIn('application_movements.id', function ($subQuery) {
                        $subQuery->select(DB::raw('MAX(id)'))
                            ->from('application_movements')
                            ->groupBy('application_no');
                    });
            })
            ->leftJoin('users as assigned_by_user', 'application_movements.assigned_by', '=', 'assigned_by_user.id')
            ->leftJoin('users as assigned_to_user', 'application_movements.assigned_to', '=', 'assigned_to_user.id')
            ->leftJoin('users', 'newly_added_properties.user_id', '=', 'users.id')
            ->leftJoin('items', 'newly_added_properties.status', '=', 'items.id')
            ->leftJoin('old_colonies', 'newly_added_properties.locality', '=', 'old_colonies.id')
            ->select(
                'newly_added_properties.*',
                'newly_added_properties.applicant_number',
                'users.name as name',
                'newly_added_properties.block',
                'newly_added_properties.plot',
                'old_colonies.name as old_colony_name',
                'newly_added_properties.remarks',
                'items.item_name',
                'items.item_code',
                DB::raw("CONCAT_WS('/', newly_added_properties.block, newly_added_properties.plot, old_colonies.name) as property_details"),
                'newly_added_properties.created_at',
            )
            ->whereIn('section_id', $sections);

        // Apply status filter if provided
        if ($request->status) {
            $query->where('newly_added_properties.status', $request->status);
        }

        // Apply status condition based on user role
        $query->when($request->status || $user->roles[0]['name'] == 'deputy-lndo', function ($q) use ($request, $user) {
            if ($user->roles[0]['name'] == 'deputy-lndo') {
                $q->where('newly_added_properties.status', $request->status ?? getStatusName('RS_UREW'));
            } else {
                $q->where('newly_added_properties.status', $request->status);
            }
        });

        // Define the columns that can be ordered
        $columns = ['id', 'applicant_number', 'name', 'property_details', 'remarks', 'status', 'created_at'];

        $totalData = $query->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        if ($request->input('order.0.column')) {
            $orderColumnIndex = $request->input('order.0.column');
            $order = $columns[$orderColumnIndex] ?? 'id'; // Use 'id' as default if index is out of bounds
            $dir = $request->input('order.0.dir');
        } else {
            $order = $columns['7'] ?? 'id'; // Use 'id' as default if index is out of bounds
            $dir = 'desc';
        }

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search) {
                $q->where('newly_added_properties.applicant_number', 'LIKE', "%{$search}%")
                    ->orWhere('users.name', 'LIKE', "%{$search}%")
                    ->orWhere(function ($q) use ($search) {
                        $q->where('newly_added_properties.block', 'LIKE', "%{$search}%")
                            ->orWhere('newly_added_properties.plot', 'LIKE', "%{$search}%")
                            ->orWhere('old_colonies.name', 'LIKE', "%{$search}%");
                    })
                    ->orWhere('newly_added_properties.remarks', 'LIKE', "%{$search}%");
            });

            $totalFiltered = $query->count();
        }

        $getNewPropertyDetails = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $data = [];
        foreach ($getNewPropertyDetails as $getNewPropertyDetail) {
            $nestedData['id'] = $getNewPropertyDetail->id;
            $nestedData['applicant_number'] = $getNewPropertyDetail->applicant_number;
            $nestedData['name'] = $getNewPropertyDetail->name;
            $nestedData['property_details'] = ucfirst($getNewPropertyDetail->block) . '/' . ucfirst($getNewPropertyDetail->plot) . '/' . ucfirst($getNewPropertyDetail->old_colony_name);
            $nestedData['documents'] = [
                'sale_deed_doc' => $getNewPropertyDetail->sale_deed_doc,
                'builder_buyer_agreement_doc' => $getNewPropertyDetail->builder_buyer_agreement_doc,
                'lease_deed_doc' => $getNewPropertyDetail->lease_deed_doc,
                'substitution_mutation_letter_doc' => $getNewPropertyDetail->substitution_mutation_letter_doc,
                'other_doc' => $getNewPropertyDetail->other_doc,
                'owner_lessee_doc' => $getNewPropertyDetail->owner_lessee_doc,
                'authorised_signatory_doc' => $getNewPropertyDetail->authorised_signatory_doc,
                'chain_of_ownership_doc' => $getNewPropertyDetail->chain_of_ownership_doc,
            ];

            $nestedData['remark'] = [
                'remark' => !empty($getNewPropertyDetail->remarks) ? strip_tags($getNewPropertyDetail->remarks) : 'NA',
                'assigned_by_name' => !empty($getNewPropertyDetail->assigned_by_name) ? $getNewPropertyDetail->assigned_by_name : 'NA'
            ];
            $statusClasses = [
                'RS_REJ' => 'text-danger bg-light-danger',
                'RS_NEW' => 'text-primary bg-light-primary',
                'RS_UREW' => 'text-warning bg-light-warning',
                'RS_REW' => 'text-white bg-secondary',
                'RS_PEN' => 'text-info bg-light-info',
                'RS_APP' => 'text-success bg-light-success',
            ];
            $class = $statusClasses[$getNewPropertyDetail->item_code] ?? 'text-secondary bg-light';
            $nestedData['status'] = '<div class="badge rounded-pill ' . $class . ' p-2 text-uppercase px-3">' . ucwords($getNewPropertyDetail->item_name) . '</div>';
            $nestedData['created_at'] = $getNewPropertyDetail->created_at->format('Y-m-d H:i:s');
            $nestedData['action'] = '<a href="' . url('applicant/property/' . $getNewPropertyDetail->id . '/view') . '">
            <button type="button" class="btn btn-success px-5">View</button>
        </a>';
            $data[] = $nestedData;
        }

        $json_data = [
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        ];

        return response()->json($json_data);
    }

    //Add by lalit on 21/08/2024 to show register user listing
    public function getApplicantNewProperties($user, $sections)
    {
        // Base query builder
        $query = NewlyAddedProperty::with('oldColony')
            ->leftJoin('application_movements', function ($join) {
                $join->on('newly_added_properties.applicant_number', '=', 'application_movements.application_no')
                    ->whereIn('application_movements.id', function ($subQuery) {
                        $subQuery->select(DB::raw('MAX(id)'))
                            ->from('application_movements')
                            ->groupBy('application_no');
                    });
            })
            ->leftJoin('users as assigned_by_user', 'application_movements.assigned_by', '=', 'assigned_by_user.id')
            ->leftJoin('users as assigned_to_user', 'application_movements.assigned_to', '=', 'assigned_to_user.id')
            ->leftJoin('users', 'newly_added_properties.user_id', '=', 'users.id')
            ->leftJoin('items', 'newly_added_properties.status', '=', 'items.id')
            ->select(
                'newly_added_properties.*',
                'users.name as name',
                'items.item_name',
                'items.item_code',
                'application_movements.assigned_by',
                'assigned_by_user.name as assigned_by_name',
                'application_movements.assigned_to',
                'assigned_to_user.name as assigned_to_name'
            )
            ->whereIn('section_id', $sections)
            ->orderBy('newly_added_properties.created_at', 'desc');

        // Adjust query based on user role
        if ($user->roles[0]['name'] == 'deputy-lndo') {
            $query->where('newly_added_properties.status', getStatusName('RS_UREW'));
        }

        // Paginate results
        $dataWithPagination = $query->paginate(20);

        return $dataWithPagination;
    }

    //Add by lalit on 21/08/2024 to get new property details
    public function newPropertyDetails($id)
    {
        if (!empty($id)) {
            // Retrieve the user's roles
            $roles = Auth::user()->roles[0]->name;
            $data = [];
            $regUserDetails = NewlyAddedProperty::with(['applicantDetails', 'user'])->find($id);
            if ($regUserDetails) {
                //Fetch Suggested Property Id from Property Master
                $property = PropertyMaster::where('new_colony_name', $regUserDetails->locality)->where('block_no', $regUserDetails->block)->where('plot_or_property_no', $regUserDetails->plot)->first();
                // dd($property);
                if (!empty($property['id'])) {
                    $data['propertyMasterId'] = $property['id'];
                    $data['suggestedPropertyId'] = $property['old_propert_id'];
                    $data['oldPropertyId'] = $property['old_propert_id'];
                    $data['file_no'] = $property['file_no'];
                    $data['uniquePropertyId'] = $property['unique_propert_id'];
                    $data['sectionCode'] = trim($property['section_code']);
                } else {
                    $data['propertyMasterId'] = '';
                    $data['suggestedPropertyId'] = '';
                    $data['oldPropertyId'] = '';
                    $data['file_no'] = '';
                    $data['uniquePropertyId'] = '';
                    $data['sectionCode'] = '';
                }
                $data['details'] = $regUserDetails;
                $data['applicationMovementId'] = $id;
                $checkList = ApplicationStatus::where('service_type', getServiceType('RS_NEW_PRO'))->where('model_id', $id)->first();
                // dd($data);
                return view('officials.applicant.details', compact('data', 'roles', 'checkList'));
            }
        }
    }

    //Add by lalit on 21/08/2024 to update register user status as under review
    public function reviewApplicantNewProperty($id, Request $request)
    {
        $result = $this->userRegistrationService->moveUnderReviewNewProperty($id, $request);
        if ($result) {
            return redirect()->route('applicantNewProperties')->with('success', 'Property successfully moved to under review.');
        } else {
            return redirect()->route('applicantNewProperties')->with('failure', 'Failed to moved to under review. Something went wrong');
        }
    }

    //Add by lalit on 21/08/2024 to approve review application
    public function approvedReviewApplicantNewProperty(Request $request)
    {
        if (!empty($request->applicationMovementId) && !empty($request->remarks)) {
            $result = $this->userRegistrationService->approveReviewNewPropertyRequest($request);
            if ($result) {
                // return redirect()->route('reviewApplicationsListings')->with('success', 'Application request approved successfully.');
                return redirect()->back()->with('success', 'Application request approved successfully.');
            } else {
                // return redirect()->route('reviewApplicationsListings')->with('failure', 'Failed approve application request.');
                return redirect()->back()->with('failure', 'Failed approve application request.');
            }
        } else {
            // return redirect()->route('reviewApplicationsListings')->with('failure', 'ApplicationId & Remarks should not be empty.');
            return redirect()->back()->with('failure', 'ApplicationId & Remarks should not be empty.');
        }
    }

    //Add by lalit on 31/07/2024 to update register user status
    public function rejectApplicantNewProperty($id, Request $request)
    {
        if (!empty($id)) {
            // DB::transaction(function () use ($id, $request) {
            $updateStatus = NewlyAddedProperty::where('id', $id)->update(['status' => getStatusName('RS_REJ'), 'remarks' => $request->remarks]);
            if ($updateStatus) {
                $getPropertyDetailsObj =  NewlyAddedProperty::where('id', $id)->first();
                $applicationMovement = ApplicationMovement::create([
                    'assigned_by'           => Auth::user()->id,
                    'service_type'          => getServiceType('RS_NEW_PRO'),
                    'model_id'              => $id,
                    'status'                => getStatusName('RS_REJ'),
                    'application_no'        => $getPropertyDetailsObj->applicant_number,
                    'remarks'               => $request->remarks
                ]);
                if ($applicationMovement) {
                    ApplicationStatus::create([
                        'service_type'              => getServiceType('RS_NEW_PRO'),
                        'model_id'                  => $id,
                        'reg_app_no'                => $getPropertyDetailsObj->applicant_number,
                        'is_mis_checked'            => $request->is_mis_checked ? true : false,
                        'is_scan_file_checked'      => $request->is_scan_file_checked ? true : false,
                        'is_uploaded_doc_checked'   => $request->is_uploaded_doc_checked ? true : false,
                        'created_by'                => Auth::user()->id,
                    ]);
                    $getUserDetailsObj =  User::find($getPropertyDetailsObj->user_id);
                    if ($getUserDetailsObj) {
                        $data = [
                            'name' => $getUserDetailsObj->name,
                            'regNo' => $getPropertyDetailsObj->applicant_number,
                            'remark' => $request->remarks
                        ];
                        $action = 'N_PRO_REJ';
                        $this->settingsService->applyMailSettings($action);
                        Mail::to($getUserDetailsObj->email)->send(new CommonMail($data, $action));
                        $this->communicationService->sendSmsMessage($data, $getUserDetailsObj->mobile_no, $action, $getUserDetailsObj->country_code);
                        $this->communicationService->sendWhatsAppMessage($data, $getUserDetailsObj->mobile_no, $action, $getUserDetailsObj->country_code);
                    }
                    return redirect()->back()->with('success', 'Applicant property rejected successfully.');
                };
            } else {
                return redirect()->back()->with('failure', 'Applicant property does not rejected.');
            }
            // });
        }
    }

    //Add by lalit on 21/08/2024 to approve user registeration
    public function approvedApplicantNewProperty(Request $request)
    {
        if (!empty($request->newlyAddedPropertyId) && !empty($request->suggestedPropertyId) && !empty($request->oldPropertyId)) {
            $getNewlyAddedPropertyDetails = NewlyAddedProperty::find($request->newlyAddedPropertyId);
            if (!empty($getNewlyAddedPropertyDetails->id)) {
                if ($getNewlyAddedPropertyDetails->status == getStatusName('RS_APP')) {
                    return redirect()->route('applicantNewProperties')->with('success', 'Property has been already approved.');
                } else {
                    $result = $this->userRegistrationService->approveApplicantNewProperty($request);
                    if ($result) {
                        return redirect()->route('applicantNewProperties')->with('success', 'Applicant new property approved successfully.');
                    } else {
                        return redirect()->route('applicantNewProperties')->with('failure', 'Failed to approve applicant property.');
                    }
                }
            } else {
                return redirect()->route('applicantNewProperties')->with('failure', 'Invalid property id, please check.');
            }
        } else {
            return redirect()->route('applicantNewProperties')->with('failure', 'RegistrationId, SuggestedPropertyId & OldPropertyId should not be empty.');
        }
    }


    //for checking is property free before approving
    public function checkProperty($id)
    {
        $isPropertyFree = GeneralFunctions::isPropertyFree($id);
        $status = $isPropertyFree['success'];
        $message = $isPropertyFree['message'];
        $details = $isPropertyFree['details'];
        return response()->json(['success' => $status, 'message' => $message, 'details' => $details]);
    }

    //for approvind Mis details by section - Sourav Chauhan (06/Sep/2024)
    public function approveMis(Request $request)
    {
        try {
            $serviceType = getServiceType($request->serviceType);
            $modalId = $request->modalId;

            $iseditedOrApprovedEver = SectionMisHistory::where('service_type', $serviceType)
                ->where('model_id', $modalId)
                ->where('property_master_id', $request->masterId)
                ->orderBy('id', 'desc')
                ->first();

            if ($iseditedOrApprovedEver) {
                if ($iseditedOrApprovedEver->is_active == 1 && $iseditedOrApprovedEver->permission_to == Auth::user()->id) {
                    $iseditedOrApprovedEver->is_active = 0;
                    if ($iseditedOrApprovedEver->save()) {
                        return response()->json(['status' => 'success', 'message' => 'Mis approved successfully.']);
                    } else {
                        return response()->json(['status' => 'failure', 'message' => 'Mis not approved. Something went wrong.']);
                    }
                } else {
                    return response()->json(['status' => 'failure', 'message' => 'You can not approve MIS dtails for this property.']);
                }
            } else {
                $applicantNo = $request->applicantNo;
                $applicationStatus = ApplicationStatus::where('service_type', $serviceType)->where('model_id', $modalId)->first();
                if ($applicationStatus) {
                    $applicationStatus->is_mis_checked = true;
                    $applicationStatus->mis_checked_by = Auth::user()->id;
                    $applicationStatus->save();
                } else {
                    $applicationStatus = ApplicationStatus::create([
                        'service_type' => $serviceType,
                        'model_id' => $modalId,
                        'reg_app_no' => $applicantNo,
                        'is_mis_checked' => true,
                        'mis_checked_by' => Auth::user()->id,
                        'is_scan_file_checked' => false,
                        'is_uploaded_doc_checked' => false,
                        'created_by' => Auth::user()->id,
                    ]);
                }
                if ($applicationStatus) {
                    $sectionMisHistory = SectionMisHistory::create([
                        'service_type' => $serviceType,
                        'model_id' => $modalId,
                        'section_code' => trim($request->sectionCode),
                        'old_property_id' => $request->oldPropertyId,
                        'new_property_id' => $request->newPropertyId,
                        'property_master_id' => $request->masterId,
                        'created_by' => Auth::user()->id,
                    ]);
                    if ($sectionMisHistory) {
                        return response()->json(['status' => 'success', 'message' => Config::get('messages.success.mis_approved')]);
                    } else {
                        return response()->json(['status' => 'failure', 'message' => Config::get('messages.success.mis_approved_failed')]);
                    }
                } else {
                    return response()->json(['status' => 'failure', 'message' => Config::get('messages.success.mis_approved_failed')]);
                }
            }
        } catch (\Exception $e) {
            Log::info($e);
            return response()->json(['status' => 'failure', 'message' => $e->getMessage()]);
        }
    }


    //for checking scanned files by section - Sourav Chauhan (09/Sep/2024)
    public function scannedFilesChecked(Request $request)
    {
        try {
            $serviceType = getServiceType($request->serviceType);
            $modalId = $request->modalId;
            $applicantNo = $request->applicantNo;
            $applicationStatus = ApplicationStatus::where('service_type', $serviceType)->where('model_id', $modalId)->first();
            if ($applicationStatus) {
                $applicationStatus->is_scan_file_checked = true;
                $applicationStatus->scan_file_checked_by = Auth::user()->id;
                $applicationStatus->save();
            } else {
                $applicationStatus = ApplicationStatus::create([
                    'service_type' => $serviceType,
                    'model_id' => $modalId,
                    'reg_app_no' => $applicantNo,
                    'is_mis_checked' => false,
                    'is_scan_file_checked' => true,
                    'scan_file_checked_by' => Auth::user()->id,
                    'is_uploaded_doc_checked' => false,
                    'created_by' => Auth::user()->id,
                ]);
            }

            if ($applicationStatus) {
                return response()->json(['status' => 'success', 'message' => Config::get('messages.success.scanned_files_checked')]);
            } else {
                return response()->json(['status' => 'failure', 'message' => Config::get('messages.error.scanned_files_check_failed')]);
            }
        } catch (\Exception $e) {
            Log::info($e);
            return response()->json(['status' => 'failure', 'message' => $e->getMessage()]);
        }
    }


    //for checking uploaded Documents by section - Sourav Chauhan (09/Sep/2024)
    public function uploadedDocsChecked(Request $request)
    {
        try {
            $serviceType = getServiceType($request->serviceType);
            $modalId = $request->modalId;
            $applicantNo = $request->applicantNo;
            $applicationStatus = ApplicationStatus::where('service_type', $serviceType)->where('model_id', $modalId)->first();
            if ($applicationStatus) {
                $applicationStatus->is_uploaded_doc_checked = true;
                $applicationStatus->uploaded_doc_checked_by = Auth::user()->id;
                $applicationStatus->save();
            } else {
                $applicationStatus = ApplicationStatus::create([
                    'service_type' => $serviceType,
                    'model_id' => $modalId,
                    'reg_app_no' => $applicantNo,
                    'is_mis_checked' => false,
                    'is_scan_file_checked' => false,
                    'is_uploaded_doc_checked' => true,
                    'uploaded_doc_checked_by' => Auth::user()->id,
                    'created_by' => Auth::user()->id,
                ]);
            }

            if ($applicationStatus) {
                return response()->json(['success' => true, 'message' => 'Uploaded Documents checked successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Uploaded Documents not checked.']);
            }
        } catch (\Exception $e) {
            Log::info($e);
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function misUpdateRequestList()
    {
        return view('officials.updated_property_listing');
    }

    public function getUpdatePropertyDetailsList(Request $request)
    {
        // Get logged-in user (not used, can be removed or used for permission checks)
        $user = Auth::user();

        // Main query with necessary joins and select columns
        $query = SectionMisHistory::query()
            ->leftJoin('items', 'section_mis_histories.service_type', '=', 'items.id')
            ->leftJoin('users', 'section_mis_histories.permission_to', '=', 'users.id')
            ->leftJoin('flats', 'section_mis_histories.property_master_id', '=', 'flats.property_master_id')
            ->select(
                'section_mis_histories.*',
                'items.item_name',
                'users.name',
                'flats.unique_flat_id'
            )
            ->whereIn('section_mis_histories.id', function ($subquery) {
                $subquery->selectRaw('MAX(id)')
                    ->from('section_mis_histories')
                    ->groupBy('section_mis_histories.property_master_id');
            })
            ->orderBy('section_mis_histories.created_at', 'desc');

        // Define the columns for ordering and searching
        $columns = ['id', 'old_property_id', 'new_property_id', 'property_master_id'];

        // Get the total number of records
        $totalData = $query->count();
        $totalFiltered = $totalData;

        // Handle pagination, ordering, and filtering
        $limit = $request->input('length');
        $start = $request->input('start');
        $orderColumnIndex = $request->input('order.0.column');
        $order = $columns[$orderColumnIndex];
        $dir = $request->input('order.0.dir');

        // If search filter is applied, add search conditions
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('section_mis_histories.old_property_id', 'LIKE', "%{$search}%")
                    ->orWhere('section_mis_histories.new_property_id', 'LIKE', "%{$search}%")
                    ->orWhere('section_mis_histories.property_master_id', 'LIKE', "%{$search}%")
                    ->orWhere('section_mis_histories.section_code', 'LIKE', "%{$search}%")
                    ->orWhere('items.item_name', 'LIKE', "%{$search}%")
                    ->orWhere('flats.unique_flat_id', 'LIKE', "%{$search}%")
                    ->orWhere('section_mis_histories.remarks', 'LIKE', "%{$search}%");
            });

            $totalFiltered = $query->count(); // Update filtered count
        }

        // Apply pagination and ordering
        $getUpdatedPropertyListing = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $data = [];
        $autoIncrementId = $start + 1; // For auto-incrementing ID based on pagination

        foreach ($getUpdatedPropertyListing as $getUpdatedProperty) {
            $totalRequest = '';
            //Get Total Count Request done by section
            $totalRequest = SectionMisHistory::where([['service_type', $getUpdatedProperty->service_type], ['model_id', $getUpdatedProperty->model_id], ['section_code', $getUpdatedProperty->section_code], ['old_property_id', $getUpdatedProperty->old_property_id], ['remarks', '!=', null]])->count();
            $permission_asked_by = User::find($getUpdatedProperty->permission_to);

            $propertyId = '';
            if (!empty($getUpdatedProperty->old_property_id) && $getUpdatedProperty->new_property_id) {
                $propertyId .= '<div class="cursor-pointer text-primary"><a href="' . route('viewDetails', $getUpdatedProperty->old_property_id) . '">' . $getUpdatedProperty->new_property_id . '</a></div><span class="text-secondary">(' . $getUpdatedProperty->old_property_id . ')</span>';
            }
            if (!empty($getUpdatedProperty->unique_flat_id)) {
                $propertyId .= '<br><span class="text-secondary">(' . $getUpdatedProperty->unique_flat_id . ')</span>';
            }
            // Prepare data for each row
            $nestedData = [
                'id' => $autoIncrementId++, // Auto-incremented ID
                'property_id' => $propertyId,
                'service_type' => $getUpdatedProperty->item_name,
                'section_code' => $getUpdatedProperty->section_code,
                'request' => [
                    'permissionBy' => $getUpdatedProperty->permission_by ?: '',
                    'permissionTo' => $getUpdatedProperty->permission_to ?: '',
                    'permissionAt' => $getUpdatedProperty->permission_at ?: '',
                    'isActive' => $getUpdatedProperty->is_active ?: 0,
                    'remarks' => $getUpdatedProperty->remarks ? strip_tags($getUpdatedProperty->remarks) : '',
                    'createdBy' => $getUpdatedProperty->created_by ?: '',
                    'permission_asked_by' => $permission_asked_by ? $permission_asked_by->name : '',
                    'totalRequest' => $totalRequest,
                ],

            ];

            // Action buttons
            $action = '<div class="d-flex gap-3">';
            if (auth()->user()->can('section.property.mis.update.request') && !empty($getUpdatedProperty->remarks) && empty($getUpdatedProperty->permission_by)) {
                $action .= '<button type="button" class="btn btn-primary px-5 edit-permission-btn" data-bs-toggle="modal" data-bs-target="#editPermissionModal" data-section-mis-history-id="' . $getUpdatedProperty->id . '" data-service-type="' . $getUpdatedProperty->service_type . '" data-model-id="' . $getUpdatedProperty->model_id . '">Allow Edit</button>';
            }
            $action .= '<a href="' . url('users/' . $getUpdatedProperty->id . '/delete') . '"><button type="button" class="btn btn-secondary px-5">Archive</button></a></div>';

            $nestedData['action'] = $action;
            $data[] = $nestedData;
        }

        // Prepare JSON response
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        ]);
    }

    public function allowEditPermission(Request $request)
    {
        // Validate input data
        if (!empty($request->sectionMisHistoryId) && !empty($request->serviceType) && !empty($request->modelId)) {
            // Start transaction
            DB::beginTransaction();

            try {
                // Update SectionMisHistory
                $isUpdate = SectionMisHistory::where('id', $request->sectionMisHistoryId)
                    ->update([
                        'permission_by' => Auth::user()->id,
                        'permission_at' => Carbon::now(),
                        'is_active' => 1
                    ]);

                if (!$isUpdate) {
                    // Rollback if update failed
                    DB::rollBack();
                    return response()->json(['status' => 'failure', 'message' => Config::get('messages.error.edit_mis_request_granted_failed')]);
                }

                // Update ApplicationStatus
                $isApplicationStatusUpdate = ApplicationStatus::where('service_type', $request->serviceType)
                    ->where('model_id', $request->modelId)
                    ->update([
                        'is_mis_checked' => 0,
                        'mis_checked_by' => null
                    ]);

                if (!$isApplicationStatusUpdate) {
                    // Rollback if update failed
                    DB::rollBack();
                    return response()->json(['status' => 'failure', 'message' =>  Config::get('messages.custom.edit_mis_request_granted_failed_1')]);
                }

                // Commit transaction if all updates succeeded
                DB::commit();
                // return redirect()->back()->with('success', 'Edit permission successfully granted.');
                return response()->json(['status' => 'success', 'message' => Config::get('messages.success.edit_mis_request_granted')]);
            } catch (\Exception $e) {
                // Rollback transaction if there is an exception
                DB::rollBack();
                return response()->json(['status' => 'failure', 'message' => $e->getMessage()]);
            }
        } else {
            return response()->json(['status' => 'failure', 'message' =>  Config::get('messages.custom.edit_mis_request_granted_failed_2')]);
        }
    }

    public function createFlatForm(ColonyService $colonyService, MisService $misService)
    {
        $colonyList = $colonyService->getColonyList();
        $propertyTypes = $misService->getItemsByGroupId(1052);
        $areaUnit = $misService->getItemsByGroupId(1008);
        $propertyStatus = $misService->getItemsByGroupIdForFlatOnly(109);
        return view('officials.flat.create_flat_form', compact(['colonyList', 'propertyTypes', 'areaUnit', 'propertyStatus']));
    }

    // Get Property Details By Lalit on 25/09/2024
    public function getPropertyDetails(Request $request)
    {
        try {
            // Initialize variables with default empty values
            $id = $oldPropertyId = $uniquePropertyId = $splittedPropertyId = $fileNo = $plotAreaInSqMt = $groundRent = $doe = $propertyStatusName = $leaseItemName = $leaseName = $landType = '';

            // Validate request inputs
            if (!empty($request->locality) && !empty($request->block) && !empty($request->plot)) {

                // Fetch property details based on locality, block, and plot
                $property = PropertyMaster::where('new_colony_name', $request->locality)
                    ->where('block_no', $request->block)
                    ->where('plot_or_property_no', $request->plot)
                    ->first();

                // Check if the property exists
                if ($property) {
                    // Get property status
                    $getPropertyStatus = Item::where('id', $property->status)
                        ->where('group_id', 109)
                        ->select('item_name')
                        ->first();

                    // Fetch lease details and associated items and lessee info
                    $leaseDetails = PropertyLeaseDetail::leftJoin('items', 'property_lease_details.type_of_lease', '=', 'items.id')
                        ->leftJoin('current_lessee_details', 'property_lease_details.property_master_id', '=', 'current_lessee_details.property_master_id')
                        ->where('property_lease_details.property_master_id', $property->id)
                        ->select('property_lease_details.*', 'items.item_name', 'current_lessee_details.lessees_name')
                        ->first();

                    // Construct ground rent
                    $groundRent = $leaseDetails ? $leaseDetails->gr_in_re_rs . '.' . ($leaseDetails->gr_in_paisa ?? $leaseDetails->gr_in_aana) : '';

                    // Assign values to variables
                    $id = $property->id;
                    $oldPropertyId = $property->old_propert_id;
                    $uniquePropertyId = $property->unique_propert_id;
                    $plotAreaInSqMt = $leaseDetails->plot_area_in_sqm ?? '';
                    $doe = $leaseDetails->doe ? Carbon::parse($leaseDetails->doe)->format('d/m/Y') : '';
                    $propertyStatusName = $getPropertyStatus->item_name ?? '';
                    $leaseItemName = $leaseDetails->item_name ?? '';
                    $leaseName = $leaseDetails->lessees_name ?? '';
                    $landType = $property->land_type;
                } else {
                    // Handle split property details
                    $getSplittedDetails = SplitedPropertyDetail::where('plot_flat_no', $request->plot)
                        ->where('presently_known_as', $request->knownas)
                        ->first();

                    if ($getSplittedDetails) {
                        $property = PropertyMaster::where('new_colony_name', $request->locality)
                            ->where('block_no', $request->block)
                            ->where('id', $getSplittedDetails->property_master_id)
                            ->first();

                        if ($property) {
                            $getPropertyStatus = Item::where('id', $property->status)
                                ->where('group_id', 109)
                                ->select('item_name')
                                ->first();

                            $leaseDetails = PropertyLeaseDetail::leftJoin('items', 'property_lease_details.type_of_lease', '=', 'items.id')
                                ->leftJoin('current_lessee_details', 'property_lease_details.property_master_id', '=', 'current_lessee_details.property_master_id')
                                ->where('property_lease_details.property_master_id', $property->id)
                                ->select('property_lease_details.*', 'items.item_name', 'current_lessee_details.lessees_name')
                                ->first();

                            $groundRent = $leaseDetails ? $leaseDetails->gr_in_re_rs . '.' . ($leaseDetails->gr_in_paisa ?? $leaseDetails->gr_in_aana) : '';

                            $id = $property->id;
                            $oldPropertyId = $getSplittedDetails->old_property_id;
                            $uniquePropertyId = $property->unique_propert_id;
                            $splittedPropertyId = $getSplittedDetails->id;
                            $plotAreaInSqMt = $leaseDetails->plot_area_in_sqm ?? '';
                            $doe = $leaseDetails->doe ? Carbon::parse($leaseDetails->doe)->format('m/d/Y') : '';
                            $propertyStatusName = $getPropertyStatus->item_name ?? '';
                            $leaseItemName = $leaseDetails->item_name ?? '';
                            $leaseName = $leaseDetails->lessees_name ?? '';
                            $landType = $property->land_type;
                        }
                    }
                }

                if ($id) {
                    // Build the data HTML table
                    $data = '<input type="hidden" name="property_master_id" id="property_master_id" value="' . $id . '">
                            <input type="hidden" name="old_propert_id" id="old_propert_id" value="' . $oldPropertyId . '">
                            <input type="hidden" name="unique_propert_id" id="unique_propert_id" value="' . $uniquePropertyId . '">
                            <input type="hidden" name="splitted_property_id" id="splitted_property_id" value="' . $splittedPropertyId . '">
                            <input type="hidden" name="land_type" id="land_type" value="' . $landType . '">
                            <table class="table report-item">
                                <tbody>
                                    <tr>
                                        <td>Property ID: <span class="highlight_value">' . $uniquePropertyId . ' (' . $oldPropertyId . ')</span></td>
                                        <td>Land Size: <span class="highlight_value">' . $plotAreaInSqMt . ' Sq. Mtr.</span></td>
                                        <td>Land Value: <span class="highlight_value">&#8377;' . $groundRent . '</span></td>
                                        <td>Date of Execution : <span class="highlight_value">' . $doe . '</span></td>
                                    </tr>
                                    <tr>
                                        <td>Property Status: <span class="highlight_value">' . $propertyStatusName . '</span></td>
                                        <td>Lease Type: <span class="highlight_value">' . $leaseItemName . '</span></td>
                                        <td colspan="2">Present Lessee: <span class="highlight_value lessee_address">' . $leaseName . '</span></td>
                                    </tr>
                                </tbody>
                            </table>';
                    return response()->json(['data' => $data]);
                } else {
                    // Return custom error when no record is found
                    return response()->json(['error' => 'Property details not found.'], 404);
                }
            } else {
                return response()->json(['error' => 'Invalid input. Please provide valid locality, block, and plot details.'], 400);
            }
        } catch (\Exception $e) {
            // Return custom error for exception
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    //Function to search property for Auto suggestion for MiS Flat Form - Lalit (23/Oct/2024)
    public function searchProperty(Request $request)
    {
        // Validate the incoming request data
        $request->validate(['query' => 'required|string']);
        // Access the 'query' input from the request
        $searchTerm = $request->input('query');
        // Search for properties where old_property_id matches the search term
        $properties = PropertyMaster::where('old_propert_id', 'LIKE', '%' . $searchTerm . '%')->get(['old_propert_id']);
        // Return the results as a JSON response
        return response()->json($properties);
    }

    //Function to get property details through Auto suggestion selected property for Flat MIS - Lalit (23/Oct/2024)
    public function getPropertyData($propertyId)
    {
        try {
            // Initialize variables with default empty values
            $id = $oldPropertyId = $uniquePropertyId = $splittedPropertyId = $fileNo = $plotAreaInSqMt = $groundRent = $doe = $propertyStatusName = $leaseItemName = $leaseName = $landType = '';

            // Validate request inputs
            if (!empty($propertyId)) {

                $property = PropertyMaster::where('old_propert_id', $propertyId)->first();

                // Check if the property exists
                if ($property) {
                    // Get property status
                    $getPropertyStatus = Item::where('id', $property->status)
                        ->where('group_id', 109)
                        ->select('item_name')
                        ->first();

                    // Fetch lease details and associated items and lessee info
                    $leaseDetails = PropertyLeaseDetail::leftJoin('items', 'property_lease_details.type_of_lease', '=', 'items.id')
                        ->leftJoin('current_lessee_details', 'property_lease_details.property_master_id', '=', 'current_lessee_details.property_master_id')
                        ->where('property_lease_details.property_master_id', $property->id)
                        ->select('property_lease_details.*', 'items.item_name', 'current_lessee_details.lessees_name')
                        ->first();

                    // Construct ground rent
                    $groundRent = $leaseDetails ? $leaseDetails->gr_in_re_rs . '.' . ($leaseDetails->gr_in_paisa ?? $leaseDetails->gr_in_aana) : '';

                    // Assign values to variables
                    $id = $property->id;
                    $oldPropertyId = $property->old_propert_id;
                    $uniquePropertyId = $property->unique_propert_id;
                    $plotAreaInSqMt = $leaseDetails->plot_area_in_sqm ?? '';
                    $doe = $leaseDetails->doe ? Carbon::parse($leaseDetails->doe)->format('d/m/Y') : '';
                    $propertyStatusName = $getPropertyStatus->item_name ?? '';
                    $leaseItemName = $leaseDetails->item_name ?? '';
                    $leaseName = $leaseDetails->lessees_name ?? '';
                    $landType = $property->land_type;
                } else {
                    // Handle split property details
                    $getSplittedDetails = SplitedPropertyDetail::where('old_property_id', $propertyId)->first();

                    if ($getSplittedDetails) {
                        $property = PropertyMaster::where('old_propert_id', $propertyId)->where('id', $getSplittedDetails->property_master_id)->first();

                        if ($property) {
                            $getPropertyStatus = Item::where('id', $property->status)
                                ->where('group_id', 109)
                                ->select('item_name')
                                ->first();

                            $leaseDetails = PropertyLeaseDetail::leftJoin('items', 'property_lease_details.type_of_lease', '=', 'items.id')
                                ->leftJoin('current_lessee_details', 'property_lease_details.property_master_id', '=', 'current_lessee_details.property_master_id')
                                ->where('property_lease_details.property_master_id', $property->id)
                                ->select('property_lease_details.*', 'items.item_name', 'current_lessee_details.lessees_name')
                                ->first();

                            $groundRent = $leaseDetails ? $leaseDetails->gr_in_re_rs . '.' . ($leaseDetails->gr_in_paisa ?? $leaseDetails->gr_in_aana) : '';

                            $id = $property->id;
                            $oldPropertyId = $getSplittedDetails->old_property_id;
                            $uniquePropertyId = $property->unique_propert_id;
                            $splittedPropertyId = $getSplittedDetails->id;
                            $plotAreaInSqMt = $leaseDetails->plot_area_in_sqm ?? '';
                            $doe = $leaseDetails->doe ? Carbon::parse($leaseDetails->doe)->format('m/d/Y') : '';
                            $propertyStatusName = $getPropertyStatus->item_name ?? '';
                            $leaseItemName = $leaseDetails->item_name ?? '';
                            $leaseName = $leaseDetails->lessees_name ?? '';
                            $landType = $property->land_type;
                        }
                    }
                }

                if ($id) {
                    // Build the data HTML table
                    $data = '<input type="hidden" name="property_master_id" id="property_master_id" value="' . $id . '">
                            <input type="hidden" name="old_propert_id" id="old_propert_id" value="' . $oldPropertyId . '">
                            <input type="hidden" name="unique_propert_id" id="unique_propert_id" value="' . $uniquePropertyId . '">
                            <input type="hidden" name="splitted_property_id" id="splitted_property_id" value="' . $splittedPropertyId . '">
                            <input type="hidden" name="land_type" id="land_type" value="' . $landType . '">
                            <table class="table report-item">
                                <tbody>
                                    <tr>
                                        <td>Property ID: <span class="highlight_value">' . $uniquePropertyId . ' (' . $oldPropertyId . ')</span></td>
                                        <td>Land Size: <span class="highlight_value">' . $plotAreaInSqMt . ' Sq. Mtr.</span></td>
                                        <td>Land Value: <span class="highlight_value">&#8377;' . $groundRent . '</span></td>
                                        <td>Date of Execution : <span class="highlight_value">' . $doe . '</span></td>
                                    </tr>
                                    <tr>
                                        <td>Property Status: <span class="highlight_value">' . $propertyStatusName . '</span></td>
                                        <td>Lease Type: <span class="highlight_value">' . $leaseItemName . '</span></td>
                                        <td colspan="2">Present Lessee: <span class="highlight_value lessee_address">' . $leaseName . '</span></td>
                                    </tr>
                                </tbody>
                            </table>';
                    return response()->json(['data' => $data]);
                } else {
                    // Return custom error when no record is found
                    return response()->json(['error' => 'Property details not found.'], 404);
                }
            } else {
                return response()->json(['error' => 'Invalid input. Please provide valid locality, block, and plot details.'], 400);
            }
        } catch (\Exception $e) {
            // Return custom error for exception
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
        // Retrieve the property details based on old_property_id
        // $property = PropertyMaster::where('old_propert_id', $propertyId)->first();
        // if ($property) {
        //     return response()->json($property);
        // }
        // return response()->json(['message' => 'Property not found'], 404);
    }

    public function storeFlatDetails(Request $request)
    {
        // Validate input data
        $validated = $request->validate([
            // 'locality' => 'required|numeric',
            // 'block' => 'required|string',
            // 'plot' => 'required|string',
            // 'knownas' => 'required|string|max:255',
            'propertyFlatStatus' => 'required|numeric',
            'flatNumber' => 'required|string|max:255',
            'purchaseDate' => 'required',
            'area' => 'required|numeric',
            'unit' => 'required|string|max:255',
            'originalBuyerName' => 'required|string|max:255',
            'presentOccupantName' => 'required|string|max:255',
            'nameofBuilder' => 'required|string|max:255',
        ]);

        //Check if searchPropertyId is empty then check validation for locality, block & plot - lalit (23/Oct/2024)
        if(!isset($request->searchPropertyId)){
            // Check locality,block,plot,knownas validation when properyt id is not selected through auto suggestion
            $validated = $request->validate([
                'locality' => 'required|numeric',
                'block' => 'required|string',
                'plot' => 'required|string',
                'knownas' => 'required|string|max:255'
            ]);
        }
        

        // Call service to store flat details
        $response = $this->flatService->storeFlatDetails($request);

        // Handle response
        if ($response === true) {
            // Success - transaction was successful
            return redirect('flats')->with('success', 'Flat details saved successfully.');
        } else if ($response === false) {
            // Failure - transaction failed without a message
            return redirect()->back()->with('failure', 'Flat details could not be saved. Please try again.');
        } else {
            // Failure with a message (e.g. exception message)
            return redirect()->back()->with('failure', $response);
        }
    }


    public function updateFlatDetails(Request $request)
    {

        $validated = $request->validate([
            'locality' => 'required|numeric',
            'block' => 'required|string',
            'plot' => 'required|string',
            'knownas' => 'required|string|max:255',
            'propertyFlatStatus' => 'required|numeric',
            'flatNumber' => 'required|string|max:255',
            'purchaseDate' => 'required',
            'area' => 'required|numeric',
            'unit' => 'required|string|max:255',
            'originalBuyerName' => 'required|string|max:255',
            'presentOccupantName' => 'required|string|max:255',
            'nameofBuilder' => 'required|string|max:255',
        ]);

        $response = $this->flatService->updateFlatDetails($request);
        if ($response) {
            // Transaction was successful
            return redirect('flats')->with('success', 'Flat details saved successfully.');
        } else if ($response == false) {
            // Transaction failed
            return redirect()->back()->with('failure', 'Flat details not saved');
        } else {
            return redirect()->back()->with('failure', $response);
        }
    }

    public function flats(Request $request)
    {
        return view('officials.flat.indexDatatable');
    }

    public function getFlats(Request $request)
    {
        // Get the logged-in user
        $user = Auth::user();
        $query = Flat::query()->select('flats.*');
        $columns = ['id', 'property_master_id', 'unique_flat_id', 'flat_number', 'unique_file_no', 'known_as', 'area', 'builder_developer_name', 'original_buyer_name', 'purchase_date', 'present_occupant_name'];

        $totalData = $query->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        if ($request->input('order.0.column')) {
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
        } else {
            $order = $columns['0'];
            $dir = 'desc';
        }

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search) {
                $q->where('flats.property_master_id', 'LIKE', "%{$search}%")
                    ->orWhere('flats.unique_flat_id', 'LIKE', "%{$search}%")
                    ->orWhere('flats.flat_number', 'LIKE', "%{$search}%")
                    ->orWhere('flats.unique_file_no', 'LIKE', "%{$search}%")
                    ->orWhere('flats.known_as', 'LIKE', "%{$search}%")
                    ->orWhere('flats.area_in_sqm', 'LIKE', "%{$search}%")
                    ->orWhere('flats.builder_developer_name', 'LIKE', "%{$search}%")
                    ->orWhere('flats.original_buyer_name', 'LIKE', "%{$search}%")
                    ->orWhere('flats.purchase_date', 'LIKE', "%{$search}%")
                    ->orWhere('flats.present_occupant_name', 'LIKE', "%{$search}%");
            });

            $totalFiltered = $query->count();
        }

        $getFlatData = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
        $counter = 1; // Initialize counter for auto-increment
        $data = [];
        foreach ($getFlatData as $flat) {
            $nestedData['id'] = $counter++; // Auto-incremented ID
            $nestedData['flat_number'] = $flat->flat_number;
            $nestedData['property_master_id'] = '
                <div class="text-primary">
                    ' . $flat->unique_property_id . '
                </div>
                <span class="text-secondary">(' . $flat->old_property_id . ')</span>';
            $nestedData['unique_file_no'] = '
                <div class="text-secondary">
                    ' . $flat->unique_file_no . '
                </div>
                <span class="text-secondary">(' . $flat->unique_flat_id . ')</span>';
            $nestedData['address'] = $flat->known_as;
            $nestedData['area'] = $flat->area_in_sqm;
            $nestedData['builder_developer_name'] = $flat->builder_developer_name;
            $nestedData['original_buyer_name'] = $flat->original_buyer_name;
            $nestedData['purchase_date'] = Carbon::parse($flat->purchase_date)->format('d/m/Y');
            $nestedData['present_occupant_name'] = $flat->present_occupant_name;
            $nestedData['action'] = '
                <a href="' . url('flat/' . $flat->id . '/view') . '">
                    <button type="button" class="btn btn-success px-5">View</button>
                </a>
                <a href="' . url('flat/' . $flat->id . '/edit') . '">
                    <button type="button" class="btn btn-primary px-5">Edit</button>
                </a>
                <button type="button" class="btn btn-danger px-5 delete-btn" data-id="' . $flat->id . '" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>';


            $data[] = $nestedData;
        }


        $json_data = [
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        ];

        return response()->json($json_data);
    }

    public function viewFlatDetails($id, Request $request)
    {
        try {
            if (!empty($id)) {
                $flatData = Flat::query()->leftJoin('property_masters', 'property_masters.id', '=', 'flats.property_master_id')->leftJoin('old_colonies', 'old_colonies.id', '=', 'flats.locality')->leftJoin('items', 'items.id', '=', 'flats.property_flat_status')->leftJoin('current_lessee_details', function ($join) {
                    $join->on('flats.id', '=', 'current_lessee_details.flat_id');
                    //  ->orOn('flats.property_master_id', '=', 'current_lessee_details.property_master_id');
                })->select('flats.*', 'property_masters.unique_file_no as main_file_no', 'old_colonies.*', 'old_colonies.name as colony_name', 'items.item_name as property_status', 'current_lessee_details.lessees_name as lessees_name', 'current_lessee_details.property_known_as as property_known_as')->where('flats.id', $id)->first();
                // dd($flatData);
                return view('officials.flat.view', compact('flatData'));
            }
        } catch (\Exception $e) {
            // Return custom error for exception
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function editFlatDetails($id, Request $request, ColonyService $colonyService, MisService $misService)
    {
        try {
            if (!empty($id)) {
                //Added by Lalit on 17/09/2024 Get Additional data from url as query params to get inserted into application_status & section_mis_histories
                $additionalDataJson = $request->query('params');
                if (isset($additionalDataJson)) {
                    $additionalData = json_decode($additionalDataJson, true);
                } else {
                    $additionalData = [];
                }

                $blocks = $blocks = $blocks = [];
                $flatData = Flat::find($id);
                $colonyList = $colonyService->getColonyList();
                $propertyTypes = $misService->getItemsByGroupId(1052);
                $areaUnit = $misService->getItemsByGroupId(1008);
                $propertyStatus = $misService->getItemsByGroupIdForFlatOnly(109);

                if (!empty($flatData->locality)) {
                    $blocks = getBlockThroughLocality($flatData->locality);
                }
                if (!empty($flatData->locality) && !empty($flatData->block)) {
                    $plots = getPlotThroughBlock($flatData->locality, $flatData->block);
                }
                if (!empty($flatData->locality) && !empty($flatData->block) && !empty($flatData->plot)) {
                    $knownAs = getKnownAsThroughPlot($flatData->locality, $flatData->block, $flatData->plot);
                }
                if (!empty($flatData->locality) && !empty($flatData->block) && !empty($flatData->plot) && !empty($flatData->known_as)) {
                    $property = self::getProperty($flatData->locality, $flatData->block, $flatData->plot, $flatData->known_as);
                }
                // dd($flatData->known_as, $knownAs[0]);
                return view('officials.flat.update_flat_form', compact(['flatData', 'colonyList', 'blocks', 'plots', 'knownAs', 'property', 'propertyTypes', 'areaUnit', 'propertyStatus', 'additionalData']));
            }
        } catch (\Exception $e) {
            // Return custom error for exception
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function flatDestroy(Request $request)
    {
        try {
            $transactionSuccess = false;
            DB::transaction(function () use ($request, &$transactionSuccess) {
                $flatDetails = Flat::find($request->deleteId);
                if ($flatDetails) {
                    //Check if flat property is splitted
                    if (!empty($flatDetails['splitted_property_id'])) {
                        PropertyTransferredLesseeDetail::where([['flat_id', $flatDetails['id']], ['splited_property_detail_id', $flatDetails['splitted_property_id']], ['property_master_id', $flatDetails['property_master_id']]])->delete();
                        CurrentLesseeDetail::where([['flat_id', $flatDetails['id']], ['splited_property_detail_id', $flatDetails['splitted_property_id']], ['property_master_id', $flatDetails['property_master_id']]])->delete();
                        PropertyTransferLesseeDetailHistory::where([['flat_id', $flatDetails['id']], ['splited_property_detail_id', $flatDetails['splitted_property_id']], ['property_master_id', $flatDetails['property_master_id']]])->delete();
                        FlatHistory::where('flat_id', $flatDetails['id'])->delete();
                        $flatDetails->delete();
                    } else {
                        PropertyTransferredLesseeDetail::where([['flat_id', $flatDetails['id']], ['property_master_id', $flatDetails['property_master_id']]])->delete();
                        CurrentLesseeDetail::where([['flat_id', $flatDetails['id']], ['property_master_id', $flatDetails['property_master_id']]])->delete();
                        PropertyTransferLesseeDetailHistory::where([['flat_id', $flatDetails['id']], ['property_master_id', $flatDetails['property_master_id']]])->delete();
                        FlatHistory::where('flat_id', $flatDetails['id'])->delete();
                        $flatDetails->delete();
                    }
                    // Here we have to write delete functionality for other table also
                    $transactionSuccess = true;
                } else {
                    return redirect()->back()->with('failure', 'Flat not found.');
                }
            });

            if ($transactionSuccess) {
                return response()->json(['status' => 'success', 'message' => Config::get('messages.success.flat_deleted')]);
            } else {
                Log::info("transaction failed");
                return response()->json(['status' => 'failure', 'message' => Config::get('messages.success.flat_deleted_error')]);
            }
        } catch (\Exception $e) {
            Log::info($e);
            return $e->getMessage();
        }
    }

    //for request edit permisison from lndo through section officer - Lalit tiwari (09/Sep/2024)
    public function requestEditMis(Request $request)
    {
        try {
            $serviceType = getServiceType($request->serviceType);
            $modalId = $request->modalId;

            $sectionMisHistory = SectionMisHistory::create([
                'service_type' => $serviceType,
                'model_id' => $modalId,
                'section_code' => trim($request->sectionCode),
                'old_property_id' => $request->oldPropertyId,
                'new_property_id' => $request->newPropertyId,
                'property_master_id' => $request->masterId,
                'permission_to' => Auth::user()->id,
                'is_active' => 0,
                'remarks'   => $request->remarks,
                'created_by' => Auth::user()->id,
            ]);
            if ($sectionMisHistory) {
                return response()->json(['status' => 'success', 'message' => Config::get('messages.success.edit_mis_request')]);
            } else {
                return response()->json(['status' => 'failure', 'message' => Config::get('messages.error.edit_mis_request_failed')]);
            }
        } catch (\Exception $e) {
            Log::info($e);
            return response()->json(['status' => 'failure', 'message' => $e->getMessage()]);
        }
    }

    public function getProperty($locality, $block, $plot, $knownas)
    {
        try {
            // Initialize variables with default empty values
            $id = $oldPropertyId = $uniquePropertyId = $splittedPropertyId = $fileNo = $plotAreaInSqMt = $groundRent = $doe = $propertyStatusName = $leaseItemName = $leaseName = $landType = '';

            // Validate request inputs
            if (!empty($locality) && !empty($block) && !empty($plot)) {

                // Fetch property details based on locality, block, and plot
                $property = PropertyMaster::where('new_colony_name', $locality)
                    ->where('block_no', $block)
                    ->where('plot_or_property_no', $plot)
                    ->first();

                // Check if the property exists
                if ($property) {
                    // Get property status
                    $getPropertyStatus = Item::where('id', $property->status)
                        ->where('group_id', 109)
                        ->select('item_name')
                        ->first();

                    // Fetch lease details and associated items and lessee info
                    $leaseDetails = PropertyLeaseDetail::leftJoin('items', 'property_lease_details.type_of_lease', '=', 'items.id')
                        ->leftJoin('current_lessee_details', 'property_lease_details.property_master_id', '=', 'current_lessee_details.property_master_id')
                        ->where('property_lease_details.property_master_id', $property->id)
                        ->select('property_lease_details.*', 'items.item_name', 'current_lessee_details.lessees_name')
                        ->first();

                    // Construct ground rent
                    $groundRent = $leaseDetails ? $leaseDetails->gr_in_re_rs . '.' . ($leaseDetails->gr_in_paisa ?? $leaseDetails->gr_in_aana) : '';

                    // Assign values to variables
                    $id = $property->id;
                    $oldPropertyId = $property->old_propert_id;
                    $uniquePropertyId = $property->unique_propert_id;
                    $plotAreaInSqMt = $leaseDetails->plot_area_in_sqm ?? '';
                    $doe = $leaseDetails->doe ? Carbon::parse($leaseDetails->doe)->format('d/m/Y') : '';
                    $propertyStatusName = $getPropertyStatus->item_name ?? '';
                    $leaseItemName = $leaseDetails->item_name ?? '';
                    $leaseName = $leaseDetails->lessees_name ?? '';
                    $landType = $property->land_type;
                } else {
                    // Handle split property details
                    $getSplittedDetails = SplitedPropertyDetail::where('plot_flat_no', $plot)
                        ->where('presently_known_as', $knownas)
                        ->first();

                    if ($getSplittedDetails) {
                        $property = PropertyMaster::where('new_colony_name', $locality)
                            ->where('block_no', $block)
                            ->where('id', $getSplittedDetails->property_master_id)
                            ->first();

                        if ($property) {
                            $getPropertyStatus = Item::where('id', $property->status)
                                ->where('group_id', 109)
                                ->select('item_name')
                                ->first();

                            $leaseDetails = PropertyLeaseDetail::leftJoin('items', 'property_lease_details.type_of_lease', '=', 'items.id')
                                ->leftJoin('current_lessee_details', 'property_lease_details.property_master_id', '=', 'current_lessee_details.property_master_id')
                                ->where('property_lease_details.property_master_id', $property->id)
                                ->select('property_lease_details.*', 'items.item_name', 'current_lessee_details.lessees_name')
                                ->first();

                            $groundRent = $leaseDetails ? $leaseDetails->gr_in_re_rs . '.' . ($leaseDetails->gr_in_paisa ?? $leaseDetails->gr_in_aana) : '';

                            $id = $property->id;
                            $oldPropertyId = $getSplittedDetails->old_property_id;
                            $uniquePropertyId = $property->unique_propert_id;
                            $splittedPropertyId = $getSplittedDetails->id;
                            $plotAreaInSqMt = $leaseDetails->plot_area_in_sqm ?? '';
                            $doe = $leaseDetails->doe ? Carbon::parse($leaseDetails->doe)->format('d/m/Y') : '';
                            $propertyStatusName = $getPropertyStatus->item_name ?? '';
                            $leaseItemName = $leaseDetails->item_name ?? '';
                            $leaseName = $leaseDetails->lessees_name ?? '';
                            $landType = $property->land_type;
                        }
                    }
                }

                if ($id) {
                    // Return the variables as JSON
                    return [
                        'id' => $id,
                        'oldPropertyId' => $oldPropertyId,
                        'uniquePropertyId' => $uniquePropertyId,
                        'splittedPropertyId' => $splittedPropertyId,
                        'fileNo' => $fileNo,
                        'plotAreaInSqMt' => $plotAreaInSqMt,
                        'groundRent' => $groundRent,
                        'doe' => $doe,
                        'propertyStatusName' => $propertyStatusName,
                        'leaseItemName' => $leaseItemName,
                        'leaseName' => $leaseName,
                        'landType' => $landType,
                    ];
                } else {
                    // Return custom error when no record is found
                    return [];
                }
            } else {
                return [];
            }
        } catch (\Exception $e) {
            // Return custom error for exception
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function getFlatDetails(Request $request)
    {
        $getFlat = Flat::where('id', $request->flatId)->first();
        return $getFlat;
    }
}
