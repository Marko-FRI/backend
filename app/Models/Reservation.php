<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Reservation_has_table;
use App\Models\Selected_menu;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';
    protected $primaryKey = 'id_reservation';

    protected $fillable = [
        'id_user',
        'number_of_personel'
    ];

    /**
     * Get all of the tables for the Reservation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tables(): HasMany
    {
        return $this->hasMany(Reservation_has_table::class, 'id_reservation', 'id_reservation');
    }

    /**
     * Get all of the selected_menus for the Reservation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function selected_menus(): HasMany
    {
        return $this->hasMany(Selected_menu::class, 'id_reservation', 'id_reservation');
    }
}
