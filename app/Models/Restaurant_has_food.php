<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Food;

class Restaurant_has_food extends Model
{
    use HasFactory;

    protected $table = 'restaurant_has_food';
    protected $primaryKey = 'id_restaurant_has_food';

    protected $fillable = [
        'id_restaurant',
        'id_food'
    ];

    /**
     * Get the food that owns the Restaurant_has_food
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class, 'id_food', 'id_food');
    }
}
