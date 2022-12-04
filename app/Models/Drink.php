<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Drink_has_volume;

class Drink extends Model
{
    use HasFactory;

    protected $table = 'drinks';
    protected $primaryKey = 'id_drink';

    protected $fillable = [
        'price',
        'description'
    ];

    /**
     * Get all of the volumes for the Drink
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function volumes(): HasMany
    {
        return $this->hasMany(Drink_has_volume::class, 'id_drink', 'id_drink');
    }
}
