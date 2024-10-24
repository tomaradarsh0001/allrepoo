<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\AppointmentDetail;

class AppointmentRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    public function __construct(AppointmentDetail $appointment)
    {
        $this->appointment = $appointment;
    }

    public function build()
    {
        return $this->view('emails.appointmentRejected')
                    ->subject('Your Appointment has been Rejected')
                    ->with(['appointment' => $this->appointment]);
    }
}
