<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Demand extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function demandDetails(): HasMany
    {
        return $this->hasMany(DemandDetail::class);
    }
}
