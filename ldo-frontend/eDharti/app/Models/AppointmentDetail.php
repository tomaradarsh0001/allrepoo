<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'appointment_details';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_property_id_known' => 'boolean',
        'is_stakeholder' => 'boolean',
        'meeting_date' => 'date',
        // 'meeting_timeslot' => 'datetime:H:i:s',
        'status' => 'string',
        'nature_of_visit' => 'string',
    ];
}