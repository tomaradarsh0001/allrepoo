<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_eng', 'name_hin', 'designation_eng', 'designation_hin', 
        'room_no', 'telephone', 'email', 'section_eng', 'section_hin', 'sort_order', 
        'is_active', 'created_by', 'updated_by'
    ];
}
