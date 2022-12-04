<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCompositePrimaryKeyTrait;

use App\Models\Table;

class Reservation_has_table extends Model
{
    use HasFactory, HasCompositePrimaryKeyTrait;
    
    protected $table = 'reservation_has_table';
    protected $primaryKey = ['id_reservation', 'id_table', 'date_and_time_of_reservation'];

    protected $fillable = [
        'id_reservation',
        'id_table',
        'date_and_time_of_reservation'
    ];

    /**
     * Get the table that owns the Reservation_has_table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class, 'id_table', 'id_table');
    }
}
