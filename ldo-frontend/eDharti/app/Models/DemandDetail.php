<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemandDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function demand(): BelongsTo
    {
        return $this->belongsTo(Demand::class, 'demand_id', 'id');
    }
}
