<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Category;
use App\Models\Menu_has_alergen;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'id_restaurant',
        'id_category',
        'name',
        'description',
        'price',
        'image_path',
        'discount'
    ];

    /**
     * Get the category associated with the Menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'id_category', 'id_category');
    }

    /**
     * Get all of the alergens for the Menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function alergens(): HasMany
    {
        return $this->hasMany(Menu_has_alergen::class, 'id_menu', 'id_menu');
    }
}
