<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant_image extends Model
{
    use HasFactory;
    
    protected $table = 'restaurant_images';
    protected $primaryKey = 'id_image';

    protected $fillable = [
        'id_restaurant',
        'image_path'
    ];
}
