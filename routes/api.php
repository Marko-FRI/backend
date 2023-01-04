<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavouriteController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|

test commit
*/

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

Route::get('/restaurantsFirstLoad', [HomeController::class, 'index']);
Route::get('/restaurants', [HomeController::class, 'filtered']);

Route::get('/restaurant/{id}', [RestaurantController::class, 'index'])->where('id', '[0-9]+');

Route::post('/moreReviews', [ReviewController::class, 'moreReviews']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);

    Route::post('/addReview', [ReviewController::class, 'index']);

    Route::post('/restaurantAvaliability', [ReservationController::class, 'checkAvaliability']);
    Route::post('/reserveRestaurant', [ReservationController::class, 'index']);

    Route::post('/favourite', [FavouriteController::class, 'index']);

    //Route::get('/profile', [UserController::class, 'getData']);
    
    //Route::get('/users', [UserController::class, 'index']);

    /*Route::get('/user', function (Request $request) {
        return $request->user();
    });*/
});
