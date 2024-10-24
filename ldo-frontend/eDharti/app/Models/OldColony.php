<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OldColony extends Model
{
    use HasFactory;
    protected $table = 'old_colonies';

    protected $fillable = ['name', 'code'];
    public static function colonyIdByColonyCode($code)
    {

        return Self::select('id')->where('code', $code)->get();
    }

    public function propertyMasters()
    {
        return $this->hasMany(PropertyMaster::class, 'old_colony_name');
    }

    public function propertyMastersNew()
    {
        return $this->hasMany(PropertyMaster::class, 'new_colony_name');
    }

    // Added by Lalit on 02/08/2024 Define the inverse relationship if needed
    public function userRegistrations()
    {
        return $this->hasMany(UserRegistration::class, 'locality');
    }

    // Define the relationship with UserProperty
    public function userProperties()
    {
        return $this->hasMany(UserProperty::class, 'locality', 'id');
    }

    public function grievances()
    {
        return $this->hasMany(AdminPublicGrievance::class, 'colony', 'id');
    }

}
