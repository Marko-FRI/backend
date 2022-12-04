<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alergen extends Model
{
    use HasFactory;

    protected $table = 'alergens';
    protected $primaryKey = 'id_alergen';

    protected $fillable = [
        'name'
    ];
}
