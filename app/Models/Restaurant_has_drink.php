<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Drink_has_volume;

class Restaurant_has_drink extends Model
{
    use HasFactory;

    protected $table = 'restaurant_has_drinks';
    protected $primaryKey = 'id_restaurant_has_drink';

    protected $fillable = [
        'id_restaurant',
        'id_drink_has_volume'
    ];

    /**
     * Get the drink_has_volume that owns the Restaurant_has_drink
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function drink_has_volume(): BelongsTo
    {
        return $this->belongsTo(Drink_has_volume::class, 'id_drink_has_volume', 'id_drink_has_volume');
    }
}
