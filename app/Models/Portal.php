<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
        'description',
        'file_url',
        'img_url',
        'category_id',
    ];
}
