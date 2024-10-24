<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = ['title_eng', 'title_hin', 'description_eng', 'description_hin', 'document_path', 'is_active', 'display_till', 'sort_order', 'created_by', 'updated_by'];
}
