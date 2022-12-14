<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Restaurant_image;
use App\Models\Review;
use App\Models\Per_day_schedule;
use App\Models\Table;
use App\Models\Favourite;
use App\Models\Restaurant_has_food;
use App\Models\Restaurant_has_drink;
use App\Models\Menu;

class Restaurant extends Model
{
    use HasFactory;

    protected $table = 'restaurants';
    protected $primaryKey = 'id_restaurant';

    protected $fillable = [
        'id_user',
        'name',
        'description',
        'address'
    ];

    /**
     * Get all of the images for the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(Restaurant_image::class, 'id_restaurant', 'id_restaurant');
    }

    /**
     * Get all of the reviews for the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'id_restaurant', 'id_restaurant');
    }

    /**
     * Get the user associated with the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id_user', 'id_user');
    }

    /**
     * Get all of the schedules for the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedule(): HasMany
    {
        return $this->hasMany(Per_day_schedule::class, 'id_restaurant', 'id_restaurant');
    }

    /**
     * Get all of the tables for the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tables(): HasMany
    {
        return $this->hasMany(Table::class, 'id_restaurant', 'id_restaurant');
    }

    /**
     * Get all of the favourites for the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favourites(): HasMany
    {
        return $this->hasMany(Favourite::class, 'id_restaurant', 'id_restaurant');
    }

    /**
     * Get all of the restaurant_has_food for the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function restaurant_has_food(): HasMany
    {
        return $this->hasMany(Restaurant_has_food::class, 'id_restaurant', 'id_restaurant');
    }

    /**
     * Get all of the drinks for the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function drinks(): HasMany
    {
        return $this->hasMany(Restaurant_has_drink::class, 'id_restaurant', 'id_restaurant');
    }

    /**
     * Get all of the menus for the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'id_restaurant', 'id_restaurant');
    }
    
    /*
    public function average_rating() {
        return Review::where('id_restaurant', $this->attributes["id_restaurant"])->avg('rating');
    }
    */
}
