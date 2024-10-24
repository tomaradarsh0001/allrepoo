<?php

namespace App\Http\Controllers;

// AppointmentDetailController Swati Mishra
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppointmentDetail;
use App\Models\Item;
use Illuminate\View\View;
use App\Services\ColonyService;
use App\Services\MisService;
use App\Services\CommonService;
use App\Jobs\AdminAppointmentJob;
use App\Jobs\UserAppointmentJob;
use App\Jobs\ApprovalAppointmentJob;
use App\Jobs\RejectionAppointmentJob;
use Illuminate\Support\Facades\Auth;

class OLDAppointmentDetailController extends Controller
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }


    /*public function index(Request $request)
    {

        $today = now()->startOfDay();

        AppointmentDetail::where('status', 'Approved')
            ->where('meeting_date', '<', $today)
            ->update(['status' => 'Completed']);

        $status = $request->input('status', 'Approved');

        $appointments = AppointmentDetail::query()
            ->when($status === 'Approved', function ($query) use ($today) {
                return $query->where('status', 'Approved')
                    ->where('meeting_date', '>=', $today)
                    ->orderBy('meeting_date', 'asc');
            })
            ->when($status === 'Completed', function ($query) {
                return $query->where('status', 'Completed')
                    ->orderBy('meeting_date', 'desc');
            })
            ->when($status === 'Rejected', function ($query) {
                return $query->where('status', 'Rejected')
                    ->orderBy('meeting_date', 'desc');
            })
            ->get();

        return view('appointment.vistor_form.index', compact('appointments', 'status'));
    }*/

    public function index(Request $request)
    {
        return view('appointment.vistor_form.indexDatatable');
    }

    public function getAppointments(Request $request)
    {
        // Define the columns that can be ordered and searched
        $columns = ['id', 'unique_id', 'name', 'meeting_purpose', 'meeting_date', 'meeting_timeslot', 'status'];

        // Start query
        $query = AppointmentDetail::query();

        // Apply status filter if provided
        if ($request->status) {
            $query->where('appointment_details.status', $request->status);
        } 
        
        // Apply search filter for global search
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search) {
                $q->where('unique_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('locality', 'like', "%{$search}%")
                    ->orWhere('block', 'like', "%{$search}%")
                    ->orWhere('plot', 'like', "%{$search}%")
                    ->orWhere('meeting_purpose', 'like', "%{$search}%")
                    ->orWhere('meeting_description', 'like', "%{$search}%")
                    ->orWhere('meeting_date', 'like', "%{$search}%")
                    ->orWhere('meeting_timeslot', 'like', "%{$search}%")
                    ->orWhere('nature_of_visit', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $totalData = $query->count();
        $totalFiltered = $totalData;

        // Pagination parameters
        $limit = $request->input('length');
        $start = $request->input('start');

        // Order by requested column
        $orderColumnIndex = $request->input('order.0.column');
        $order = $columns[$orderColumnIndex] ?? 'id'; // Default order by 'id' if index is invalid
        $dir = $request->input('order.0.dir');

        // Use raw SQL to sort by concatenated columns
        if ($order === 'meeting_purpose') {
            $query->orderByRaw("CONCAT(meeting_purpose, ' ', meeting_description) $dir");
        } else if ($order === 'meeting_date_time') {
            // Sort by meeting_date and meeting_timeslot separately
            $query->orderBy('meeting_date', $dir)
                ->orderBy('meeting_timeslot', $dir);
        } else {
            $query->orderBy($order, $dir);
        }

        // Apply ordering and limit/offset
        $appointmentDetails = $query->offset($start)
            ->limit($limit)
            ->get();

        $data = [];
        foreach ($appointmentDetails as $appointmentDetail) {
            $nestedData['id'] = $appointmentDetail->id;
            $nestedData['unique_id'] = $appointmentDetail->unique_id;
            $nestedData['name'] = $appointmentDetail->name;
            $nestedData['address'] = implode('/', array_filter([$appointmentDetail->locality, $appointmentDetail->block, $appointmentDetail->plot]));
            $nestedData['meeting_purpose'] = '<b>' . $appointmentDetail->meeting_purpose . '</b> <br>' . $appointmentDetail->meeting_description;
            $nestedData['meeting_date_time'] = "<i class='bx bx-calendar'></i> " . $appointmentDetail->meeting_date->format('Y-m-d') . "<br>
                                        <i class='bx bx-time-five' style='color: blue;'></i>
                                        <span style='font-size: 0.875em; color: blue;'>
                                            " . $appointmentDetail->meeting_timeslot . "
                                            " . ($appointmentDetail->nature_of_visit == 'Online' ?
                "<i class='bx bxs-circle me-1' style='color: green;'></i>" :
                "<i class='bx bxs-circle me-1' style='color: red;'></i>") . "
                                        </span>";

            // Conditionally set status badge
            if ($appointmentDetail->status == 'Approved') {
                $nestedData['status'] = '<span class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">' . $appointmentDetail->status . '</span>';
            } elseif ($appointmentDetail->status == 'Rejected') {
                $nestedData['status'] = '<span class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">' . $appointmentDetail->status . '</span>';
            } elseif ($appointmentDetail->status == 'Completed') {
                $nestedData['status'] = '<span class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">' . $appointmentDetail->status . '</span>';
            }

            // Conditionally set action button
            if ($appointmentDetail->status == 'Approved') {
                $nestedData['action'] = '<button class="btn btn-danger btn-sm" onclick="openRejectConfirmationModal(' . $appointmentDetail->id . ')">Reject</button>';
            } else {
                $nestedData['action'] = '';
            }

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



    public function create(ColonyService $colonyService, MisService $misService)
    {
        $colonyList = $colonyService->getColonyList();
        $propertyTypes = $misService->getItemsByGroupId(1052);

        $meetingPurposes = Item::where('group_id', 7003)
            ->orderBy('item_name', 'asc')
            ->pluck('item_name')
            ->toArray();

        // array_unshift($meetingPurposes, "Select Meeting Purpose*");
        array_push($meetingPurposes, "Others");

        return view('appointment.vistor_form.create', compact(['colonyList', 'propertyTypes', 'meetingPurposes']));
    }


    public function store(Request $request)
    {
        try {
            $request->merge(['propertyId' => $request->has('propertyId') ? 1 : 0]);

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'mobile' => 'required|string|size:10',
                'email' => 'required|email|max:255',
                'pan_number' => 'required|string|size:10',
                'locality' => 'nullable|string|max:255',
                'block' => 'nullable|string|max:255',
                'plot' => 'nullable|string|max:255',
                'known_as' => 'nullable|string|max:255',
                'propertyId' => 'nullable|boolean',
                'localityFill' => 'nullable|string|max:255|required_if:propertyId,1',
                'blocknoFill' => 'nullable|string|max:255|required_if:propertyId,1',
                'plotnoFill' => 'nullable|string|max:255|required_if:propertyId,1',
                'knownasFill' => 'nullable|string|max:255',
                'isStakeholder' => 'nullable|boolean',
                'stakeholderProof' => 'nullable|file|mimes:pdf|max:5120|required_if:isStakeholder,1',
                'natureOfVisit' => 'required|in:Online,Offline',
                'meetingPurpose' => 'required|string|max:255',
                'meetingDescription' => 'required|string',
                'appointmentDate' => 'required|date|after_or_equal:today',
                'meetingTime' => 'required|string',
            ]);

            $uniqueId = $this->commonService->getUniqueID(AppointmentDetail::class, 'AP', 'unique_id');

            $dateandtime = now()->format('YmdHis');
            $filePath = null;
            if ($request->hasFile('stakeholderProof')) {
                $file = $request->file('stakeholderProof');
                $fileName = "{$uniqueId}_{$dateandtime}." . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('stakeholder_docs', $fileName, 'public');
            }

            $appointment = new AppointmentDetail();
            $appointment->unique_id = $uniqueId;
            $appointment->name = $validatedData['name'];
            $appointment->mobile = $validatedData['mobile'];
            $appointment->email = $validatedData['email'];
            $appointment->pan_number = $validatedData['pan_number'];
            $appointment->is_property_id_known = $validatedData['propertyId'];
            $appointment->locality = $validatedData['propertyId'] ? $validatedData['localityFill'] : $validatedData['locality'];
            $appointment->block = $validatedData['propertyId'] ? $validatedData['blocknoFill'] : $validatedData['block'];
            $appointment->plot = $validatedData['propertyId'] ? $validatedData['plotnoFill'] : $validatedData['plot'];
            $appointment->known_as = $validatedData['propertyId'] ? $validatedData['knownasFill'] : $validatedData['known_as'];
            $appointment->is_stakeholder = $validatedData['isStakeholder'] ?? 0;
            $appointment->stakeholder_doc = $filePath;
            $appointment->nature_of_visit = $validatedData['natureOfVisit'];
            $appointment->meeting_purpose = $validatedData['meetingPurpose'];
            $appointment->meeting_description = $validatedData['meetingDescription'] ?? null;
            $appointment->meeting_date = $validatedData['appointmentDate'];
            $appointment->meeting_timeslot = $validatedData['meetingTime'];
            $appointment->status = 'Approved';

            $appointment->save();

            // Dispatch the jobs to send emails
            UserAppointmentJob::dispatch($appointment);
            // AdminAppointmentJob::dispatch($appointment);

            return redirect()->back()->with('success', 'Appointment has been scheduled successfully, Appointment ID:-' . $uniqueId);
        } catch (\Exception $e) {

            \Log::error('Failed to create appointment: ' . $e->getMessage());

            return redirect()->route('appointmentDetail')->with('failure', 'Failed to create appointment. Please try again.');
        }
    }

    //AvailableTimeslots for meeting
    protected function availableTimeSlots()
    {
        return [
            '02:00 PM-02:15 PM',
            '02:15 PM-02:30 PM',
            '02:30 PM-02:45 PM',
            '02:45 PM-03:00 PM',
        ];
    }

    // To fetch remaining timeslots for booking appointment
    public function getAvailableTimeSlots(Request $request)
    {
        $date = $request->input('date');

        if (!preg_match('/\d{4}-\d{2}-\d{2}/', $date)) {
            return response()->json(['error' => 'Invalid date format'], 400);
        }

        $timeSlots = $this->availableTimeSlots();
        $bookedSlots = AppointmentDetail::where('meeting_date', $date)->pluck('meeting_timeslot')->toArray();
        $availableSlots = array_diff($timeSlots, $bookedSlots);

        return response()->json(array_values($availableSlots));
    }

    // Fetching the fully booked dates for appointment where no timeslot available for disabling the date
    public function getFullyBookedDates()
    {
        $totalSlotsPerDay = count($this->availableTimeSlots());

        // Retrieve dates where all slots are booked, and format them to "Y-m-d"
        $fullyBookedDates = AppointmentDetail::select('meeting_date')
            ->groupBy('meeting_date')
            ->havingRaw('COUNT(meeting_timeslot) >= ?', [$totalSlotsPerDay])
            ->get()
            ->map(function ($date) {
                return $date->meeting_date->format('Y-m-d'); //Retieving the date of fully booked
            })
            ->toArray();

        // Log the fully booked dates for debugging
        \Log::info('Fully booked dates:', $fullyBookedDates);

        return response()->json($fullyBookedDates);
    }



    public function updateStatus(Request $request, $id)
    {
        try {
            $appointment = AppointmentDetail::findOrFail($id);

            if ($request->status == 'Rejected') {
                $appointment->status = 'Rejected';
                $appointment->remark = $request->input('remark');
                RejectionAppointmentJob::dispatch($appointment);
            }

            $appointment->save();

            return redirect()->back()->with('success', 'Appointment has been rejected successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to update appointment status: ' . $e->getMessage());

            return redirect()->back()->with('failure', 'Failed to update status. Please try again.');
        }
    }
}
