<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCompositePrimaryKeyTrait;

use App\Models\Alergen;

class Food_has_alergen extends Model
{
    use HasFactory, HasCompositePrimaryKeyTrait;

    protected $table = 'food_has_alergens';
    protected $primaryKey = ['id_food', 'id_alergens'];

    protected $fillable = [
        'id_food',
        'id_alergens'
    ];

    /**
     * Get the alergen that owns the Food_has_alergen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function alergen(): BelongsTo
    {
        return $this->belongsTo(Alergen::class, 'id_alergen', 'id_alergen');
    }
}
