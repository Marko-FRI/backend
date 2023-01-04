<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCompositePrimaryKeyTrait;

use App\Models\Restaurant;
use App\Models\User;

class Review extends Model
{
    use HasFactory, HasCompositePrimaryKeyTrait;

    protected $table = 'reviews';
    protected $primaryKey = ['id_user', 'id_restaurant'];

    protected $fillable = [
        'id_user',
        'id_restaurant',
        'comment',
        'rating'
    ];

    /**
     * Get the restaurant that owns the Review
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'id_restaurant', 'id_restaurant');
    }

    /**
     * Get the user that owns the Review
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
