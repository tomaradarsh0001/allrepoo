<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Page;
use App\Models\ComponentSection;

class Component extends Model
{
    use HasFactory;
    public $timestamps =  false;
    protected $fillabale = ['page_id', 'name', 'heading_eng', 'heading_hin','is_active'];
    protected $guarded = [];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
    public function sections()
    {
        return $this->hasMany(ComponentSection::class);
    }
}
