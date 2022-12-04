<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Per_day_schedule extends Model
{
    use HasFactory;

    protected $table = 'per_day_schedules';
    protected $primaryKey = 'id_per_day_schedule';

    protected $fillable = [
        'id_restaurant',
        'start_of_shift',
        'end_of_shift',
        'day',
        'note'
    ];
}
