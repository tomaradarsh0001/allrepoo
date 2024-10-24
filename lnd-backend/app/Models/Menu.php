<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['label_eng', 'label_hin', 'parent_id', 'sort_order','is_active', 'location', 'page_id', 'link', 'link_in_new_tab', 'created_by'];

    public function submenus(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }
}
