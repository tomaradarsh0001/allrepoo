<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Office_docs extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'title_eng', 'title_hin', 'description_eng', 'description_hin', 'attachment_eng', 'attachment_hin', 'is_active', 'sort_order', 'created_by', 'updated_by', 'display_till'];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }



}

