<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyTrough;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Menu_has_food;
use App\Models\Category;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'id_restaurant',
        'id_category',
        'name',
        'image_path',
        'price'
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

    /**
     * Get the category associated with the Menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'id_category', 'id_category');
    }
}
