<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\hasOneThrough;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Table;
use App\Models\Selected_menu;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Menu;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';
    protected $primaryKey = 'id_reservation';

    protected $fillable = [
        'id_user',
        'id_table',
        'number_of_personel',
        'date_and_time_of_reservation',
        'note'
    ];

    /**
     * Get the table associated with the Reservation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function table(): HasOne
    {
        return $this->hasOne(Table::class, 'id_table', 'id_table');
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

    /**
     * Get the restaurant associated with the Reservation
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOneThrough
     */
    public function restaurant(): hasOneThrough
    {
        return $this->hasOneThrough(Restaurant::class, Table::class, 'id_table', 'id_restaurant', 'id_table', 'id_restaurant');
    }

    /**
     * Get the user that owns the Reservation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
