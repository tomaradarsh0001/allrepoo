<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsoCourt extends Model
{
    use HasFactory;
    protected $fillable = ['section_id', 'file_no', 'active_in_court', 'case_no', 'commencement_date', 'subject', 'status', 'ldoh', 'ndoh', 'closing_date', 'judgement_link', 'additional_info', 'remarks', 'is_active', 'sort_order', 'created_by', 'updated_by'];
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
