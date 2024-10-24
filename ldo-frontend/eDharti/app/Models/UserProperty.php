<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProperty extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Define the relationship with OldColony
    public function oldColony()
    {
        return $this->belongsTo(OldColony::class, 'locality', 'id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'property_master_id', 'new_property_id');
    }
}
