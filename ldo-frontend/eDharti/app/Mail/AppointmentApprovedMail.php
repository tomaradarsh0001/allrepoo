<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\AppointmentDetail;

class AppointmentApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    public function __construct(AppointmentDetail $appointment)
    {
        $this->appointment = $appointment;
    }

    public function build()
    {
        return $this->view('emails.appointmentApproved')
                    ->subject('Your Appointment has been Approved')
                    ->with(['appointment' => $this->appointment]);
    }
}

