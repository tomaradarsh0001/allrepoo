<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyLeaseDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getLeaseTypeNameAttribute()
    {
        if (!is_null($this->type_of_lease)) {
            return Item::itemNameById($this->type_of_lease);
        }
        return null;
    }
}
