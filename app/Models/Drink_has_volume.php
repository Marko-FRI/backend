<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Volume;

class Drink_has_volume extends Model
{
    use HasFactory;

    protected $table = 'drink_has_volume';
    protected $primaryKey = 'id_drink_has_volume';

    protected $fillable = [
        'id_volume',
        'id_drink',
        'price'
    ];

    /**
     * Get the volume that owns the Restaurant_has_drink
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function volume(): BelongsTo
    {
        return $this->belongsTo(Volume::class, 'id_volume', 'id_volume');
    }

    /**
     * Get the drink that owns the Drink_has_volume
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function drink(): BelongsTo
    {
        return $this->belongsTo(Drink::class, 'id_drink', 'id_drink');
    }
}
