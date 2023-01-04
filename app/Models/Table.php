<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $table = 'tables';
    protected $primaryKey = 'id_table';

    protected $fillable = [
        'id_restaurant',
        'number_of_seats',
        'description'
    ];
}
