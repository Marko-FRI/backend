<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCompositePrimaryKeyTrait;

use App\Models\Restaurant;

class Favourite extends Model
{
    use HasFactory, HasCompositePrimaryKeyTrait;

    protected $table = 'favourites';
    protected $primaryKey = ['id_user', 'id_restaurant'];

    protected $fillable = [
        'id_user',
        'id_restaurant'
    ];

    /**
     * Get the restaurant that owns the Favourite
     *
     * @return \Illuminate\Restaurant\Eloquent\Relations\BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'id_restaurant', 'id_restaurant');
    }
}
