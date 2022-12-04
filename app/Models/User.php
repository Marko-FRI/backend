<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits;

use App\Models\Favourite;
use App\Models\Review;
use App\Models\Restaurant;
use App\Models\Reservation;

class User extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'credit_card',
        'profile_image_path',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'role'
    ];

    /**
     * Get all of the favourites for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favourites(): HasMany
    {
        return $this->hasMany(Favourite::class, 'id_user', 'id_user');
    }

    /**
     * Get all of the reviews for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'id_user', 'id_user');
    }

    /**
     * Get the restaurant associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function restaurant(): HasOne
    {
        return $this->hasOne(Restaurant::class, 'id_user', 'id_user');
    }

    /**
     * Get all of the reservations for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'id_user', 'id_user');
    }
}
