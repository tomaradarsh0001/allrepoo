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
use Illuminate\Support\Facades\DB; // Import DB facade for transactions
use Carbon\Carbon;
use App\Models\Otp;
use DateTime;

class AppointmentDetailController extends Controller
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
        if (!$request->user()->can('view.appointment')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $columns = ['id', 'unique_id', 'name', 'meeting_purpose', 'meeting_date', 'meeting_timeslot', 'is_attended', 'status'];

        $query = AppointmentDetail::query();

        if ($request->status) {
            $query->where('appointment_details.status', $request->status);
        }

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

        $limit = $request->input('length');
        $start = $request->input('start');

        $orderColumnIndex = $request->input('order.0.column');
        $order = $columns[$orderColumnIndex] ?? 'unique_id';
        $dir = $request->input('order.0.dir', 'asc');

        $query->orderBy('unique_id', $dir);

        $appointmentDetails = $query->offset($start)
            ->limit($limit)
            ->get();

        $data = [];
        foreach ($appointmentDetails as $appointmentDetail) {

            $today = now()->startOfDay();
            $meetingDate = Carbon::parse($appointmentDetail->meeting_date)->startOfDay();

            $nestedData['id'] = $appointmentDetail->id;
            $nestedData['unique_id'] = $appointmentDetail->unique_id;
            $nestedData['name'] = $appointmentDetail->name;
            $nestedData['address'] = implode('/', array_filter([$appointmentDetail->locality, $appointmentDetail->block, $appointmentDetail->plot]));
            $nestedData['meeting_purpose'] = '<b>' . $appointmentDetail->meeting_purpose . '</b> <br>' . $appointmentDetail->meeting_description;
            $nestedData['meeting_date_time'] = [
                'meeting_date' => $appointmentDetail->meeting_date->format('Y-m-d'),
                'meeting_timeslot' => $appointmentDetail->meeting_timeslot,
                'nature_of_visit' => $appointmentDetail->nature_of_visit
            ];

            $nestedData['is_attended'] = $appointmentDetail->is_attended;

            // Add plain status field (without HTML formatting) for internal use
            $nestedData['plain_status'] = $appointmentDetail->status;

            // Add HTML-encoded status for display purposes
            if ($appointmentDetail->status == 'Approved') {
                $nestedData['status'] = '<span class="theme-badge badge-resolved">' . $appointmentDetail->status . '</span>';
            } elseif ($appointmentDetail->status == 'Rejected') {
                $nestedData['status'] = '<span class="theme-badge badge-cancelled">' . $appointmentDetail->status . '</span>';
            } elseif ($appointmentDetail->status == 'Completed') {
                $nestedData['status'] = '<span class="theme-badge badge-reopen">' . $appointmentDetail->status . '</span>';
            }

            $nestedData['remark'] = $appointmentDetail->remark;

            if ($appointmentDetail->status == 'Approved' && $request->user()->can('reject.appointment') && $meetingDate->gt($today)) {
                $nestedData['action'] = '<button class="btn btn-danger btn-sm" onclick="openRejectConfirmationModal(' . $appointmentDetail->id . ')">Reject</button>';
            } else {
                $nestedData['action'] = '';
            }


            $data[] = $nestedData;
        }

        $json_data = [
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        ];

        return response()->json($json_data);
    }

    public function updateAttendance(Request $request, $id)
    {
        try {
            $appointment = AppointmentDetail::findOrFail($id);

            $validatedData = $request->validate([
                'is_attended' => 'required|boolean',
                'remark' => 'nullable|string' // Add validation for the remark (optional)
            ]);

            if ($appointment->status === 'Approved') {
                // Update attendance status
                $appointment->is_attended = $validatedData['is_attended'];

                // If the appointment is attended, mark it as completed
                $appointment->status = 'Completed';

                // Store the remark (even if it's empty)
                $appointment->remark = $validatedData['remark'];

                $appointment->save();

                return response()->json(['success' => true, 'message' => 'Attendance status updated and appointment marked as Completed.']);
            }

            return response()->json(['success' => false, 'message' => 'Attendance can only be marked for Approved appointments.'], 403);

        } catch (\Exception $e) {
            \Log::error('Failed to update attendance status: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to update attendance status.'], 500);
        }
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
        DB::beginTransaction();
        try {
            // Ensure propertyId is set correctly based on checkbox
            $request->merge(['propertyId' => $request->has('propertyId') ? 1 : 0]);

            // Validate request data
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

            // Check if the OTP was verified for the given mobile or email
            $otpRecord = Otp::where(function ($query) use ($request) {
                $query->where('mobile', $request->mobile)
                    ->orWhere('email', $request->email);
            })
                ->where(function ($query) {
                    $query->where('is_mobile_verified', '1')
                        ->orWhere('is_email_verified', '1');
                })
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$otpRecord) {
                // If OTP is not verified, throw an exception or handle accordingly
                return redirect()->back()->with('failure', 'OTP verification is required.');
            }

            // Check if an appointment already exists within the same week
            $existingAppointment = AppointmentDetail::where(function ($query) use ($validatedData) {
                $query->where('email', $validatedData['email'])
                    ->orWhere('mobile', $validatedData['mobile']);
            })
                ->whereRaw('YEARWEEK(meeting_date, 1) = YEARWEEK(?, 1)', [$validatedData['appointmentDate']])
                ->exists();

            // If an appointment exists, prevent booking and handle it as a failure
            if ($existingAppointment) {
                // Rollback transaction and delete the OTP record since the appointment cannot be created
                DB::rollBack();
                $otpRecord->delete();
                return redirect()->back()->with('failure', 'You have already booked an appointment in this week. Please book in the next available week.');
            }

            // Generate a unique ID for the appointment
            $uniqueId = $this->commonService->getUniqueID(AppointmentDetail::class, 'AP', 'unique_id');

            // Handle file upload if stakeholder proof is provided
            $dateandtime = now()->format('YmdHis');
            $filePath = null;
            if ($request->hasFile('stakeholderProof')) {
                $file = $request->file('stakeholderProof');
                $fileName = "{$uniqueId}_{$dateandtime}." . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('stakeholder_docs', $fileName, 'public');
            }
            // Save the appointment details
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

            DB::commit();


            // Dispatch email notification jobs
            UserAppointmentJob::dispatch($appointment);

            return redirect()->back()->with('success', 'Appointment has been scheduled successfully, Appointment ID:-' . $uniqueId);
        } catch (\Exception $e) {

            DB::rollBack();

            // Delete the OTP record since the appointment creation failed
            if (isset($otpRecord)) {
                $otpRecord->delete();
            }

            \Log::error('Failed to create appointment: ' . $e->getMessage());
            return redirect()->route('appointmentDetail')->with('failure', 'Failed to create appointment. Please try again.');
        }
    }

    protected function getStandardWeekNumber($date)
    {
        // Ensure $date is an instance of DateTime
        if (!$date instanceof DateTime) {
            $date = new DateTime($date); // Convert the string to DateTime if it's not already
        }

        $startOfYear = new DateTime($date->format('Y') . '-01-01');
        $dayOfWeek = $startOfYear->format('w'); // Day of the week (0 = Sunday, 6 = Saturday)
        $startOfWeekOffset = $dayOfWeek === 0 ? 1 : 0; // Adjust if year starts on Sunday
        $daysSinceStartOfYear = $date->diff($startOfYear)->days + 1;

        return (int) ceil(($daysSinceStartOfYear + $dayOfWeek - $startOfWeekOffset) / 7);
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
        $totalSlotsPerDay = count($this->availableTimeSlots()); // Total time slots per day

        // Retrieve dates where all slots are booked for a particular day
        $fullyBookedDates = AppointmentDetail::select('meeting_date')
            ->groupBy('meeting_date')
            ->havingRaw('COUNT(meeting_timeslot) >= ?', [$totalSlotsPerDay])
            ->get()
            ->map(function ($date) {
                return $date->meeting_date->format('Y-m-d');
            })
            ->toArray(); // Ensure this is an array

        // Calculate fully booked weeks using the standard week calculation
        $fullyBookedWeeks = [];
        foreach ($fullyBookedDates as $date) {
            $dateObj = new DateTime($date);
            $weekNumber = $this->getStandardWeekNumber($dateObj);
            $fullyBookedWeeks[] = $weekNumber;
        }

        // Remove duplicates and reindex to ensure it's a proper array
        $fullyBookedWeeks = array_values(array_unique($fullyBookedWeeks));

        return response()->json([
            'fullyBookedDates' => $fullyBookedDates,
            'fullyBookedWeeks' => $fullyBookedWeeks // This should now be an indexed array
        ]);
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
