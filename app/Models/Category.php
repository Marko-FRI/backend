<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Restaurant;
use App\Models\Menu;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'id_category';

    protected $fillable = [
        'name',
        'image_path'
    ];

    /**
     * Get all of the restaurants for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function restaurants(): HasManyThrough
    {
        return $this->hasManyThrough(Restaurant::class, Menu::class, 'id_category', 'id_restaurant', 'id_category', 'id_restaurant');
    }
}
