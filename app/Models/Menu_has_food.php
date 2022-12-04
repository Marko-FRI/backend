<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCompositePrimaryKeyTrait;

use App\Models\Restaurant_has_food;

class Menu_has_food extends Model
{
    use HasFactory, HasCompositePrimaryKeyTrait;

    protected $table = 'menu_has_food';
    protected $primaryKey = ['id_menu', 'id_restaurant_has_food'];

    protected $fillable = [
        'id_menu',
        'id_restaurant_has_food'
    ];

    /**
     * Get the restaurant_has_food that owns the Menu_has_food
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant_has_food(): BelongsTo
    {
        return $this->belongsTo(Restaurant_has_food::class, 'id_restaurant_has_food', 'id_restaurant_has_food');
    }
}
