<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\RestaurantAdminController;
use App\Http\Controllers\FooterController;

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

Route::get('/homePage', [HomeController::class, 'index']);
Route::get('/homePageMoreReviews', [HomeController::class, 'moreReviews']);

Route::get('/restaurantsFirstLoad', [HomeController::class, 'restaurantsFirstLoad']);
Route::get('/restaurants', [HomeController::class, 'filtered']);

Route::get('/restaurant/{id_restaurant}', [RestaurantController::class, 'index'])->where('id', '[0-9]+');

Route::get('/moreReviews', [ReviewController::class, 'moreReviews']);

Route::get('/footerData', [FooterController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);

    Route::post('/addReview', [ReviewController::class, 'index']);

    Route::post('/restaurantAvaliability', [ReservationController::class, 'checkAvaliability']);
    Route::post('/reserveRestaurant', [ReservationController::class, 'index']);

    Route::post('/favourite', [FavouriteController::class, 'index']);

    Route::get('/profile', [UserController::class, 'getUserData']);

    Route::delete('/deleteReservation', [ReservationController::class, 'deleteReservation']);

    Route::get('/moreActiveReservations', [UserController::class, 'loadMoreActiveReservations']);
    Route::get('/morePastReservations', [UserController::class, 'loadMorePastReservations']);

    Route::post('/checkChangeInPassword', [UserController::class, 'checkChangeInPassword']);
    Route::put('/editProfile', [UserController::class, 'editProfile']);
    Route::post('/editProfileImage', [UserController::class, 'editProfileImage']);

    Route::get('/adminRestaurantData', [RestaurantAdminController::class, 'index']);
    Route::get('/moreAdminActiveReservations', [RestaurantAdminController::class, 'moreAdminActiveReservations']);
    Route::get('/moreAdminPastReservations', [RestaurantAdminController::class, 'moreAdminPastReservations']);
    Route::delete('/deleteAdminReservation', [RestaurantAdminController::class, 'deleteAdminReservation']);
});
