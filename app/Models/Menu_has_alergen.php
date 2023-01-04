<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCompositePrimaryKeyTrait;

use App\Models\Alergen;

class Menu_has_alergen extends Model
{
    use HasFactory, HasCompositePrimaryKeyTrait;

    protected $table = 'menu_has_alergens';
    protected $primaryKey = ['id_menu', 'id_alergen'];

    protected $fillable = [
        'id_food',
        'id_alergen'
    ];

    /**
     * Get the alergen that owns the Menu_has_alergen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function alergen(): BelongsTo
    {
        return $this->belongsTo(Alergen::class, 'id_alergen', 'id_alergen');
    }
}
