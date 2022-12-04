<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Restaurant_has_drink;
use App\Models\Reservation;

class Selected_menu extends Model
{
    use HasFactory;

    protected $table = 'selected_menus';
    protected $primaryKey = 'id_selected_menu';

    protected $fillable = [
        'id_restaurant_has_drink',
        'id_reservation',
        'id_menu',
        'note'
    ];

    /**
     * Get the restaurant_has_drink associated with the Selected_menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function restaurant_has_drink(): HasOne
    {
        return $this->hasOne(Restaurant_has_drink::class, 'id_restaurant_has_drink', 'id_restaurant_has_drink');
    }

    /**
     * Get the menu that owns the Selected_menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function menu(): HasOne
    {
        return $this->HasOne(Menu::class, 'id_menu', 'id_menu');
    }

    /**
     * Get the reservation that owns the Selected_menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'id_reservation', 'id_reservation');
    }
}
