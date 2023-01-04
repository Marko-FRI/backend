<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Reservation;

class Selected_menu extends Model
{
    use HasFactory;

    protected $table = 'selected_menus';
    protected $primaryKey = 'id_selected_menu';

    protected $fillable = [
        'id_reservation',
        'id_menu',
        'quantity'
    ];

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
