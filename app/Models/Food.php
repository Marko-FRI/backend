<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Food_has_alergen;

class Food extends Model
{
    use HasFactory;

    protected $table = 'food';
    protected $primaryKey = 'id_food';

    protected $fillable = [
        'description'
    ];

    /**
     * Get all of the alergens for the Food
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function alergens(): HasMany
    {
        return $this->hasMany(Food_has_alergen::class, 'id_food', 'id_food');
    }   
}
