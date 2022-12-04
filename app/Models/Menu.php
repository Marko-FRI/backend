<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Menu_has_food;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'id_restaurant',
        'image_path',
        'price',
        'category'
    ];

    /**
     * Get all of the menu_has_food for the Menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menu_has_food(): HasMany
    {
        return $this->hasMany(Menu_has_food::class, 'id_menu', 'id_menu');
    }
}
