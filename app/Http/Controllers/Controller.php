<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Favourite;
use App\Models\Restaurant;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $RESTAURANT_IMAGES_URL;
    protected $USER_IMAGES_URL;
    protected $MENU_IMAGES_URL;
    protected $CATEGORY_IMAGES_URL;
    protected $PLACEHOLDER_IMAGES_URL;
    protected $APP_IMAGES_URL;

    protected $before;

    public function __construct() {
        $url = URL::to('/') . '/images/';

        $this->RESTAURANT_IMAGES_URL = $url . 'restaurant_images/';
        $this->USER_IMAGES_URL = $url . 'user_images/';
        $this->MENU_IMAGES_URL = $url . 'menu_images/';
        $this->CATEGORY_IMAGES_URL = $url . 'category_images/';
        $this->PLACEHOLDER_IMAGES_URL = $url . 'placeholder_images/';

        $this->before = Carbon::createFromFormat('Y-m-d H:i:s', now())->subHours(2);
    }

    public function is_open($schedule) {
        $dayOfTheWeek = Carbon::now()->dayOfWeek;

        $current_time = Carbon::now()->format('H:i:s');

        foreach ($schedule as $day) {
            if ($this->get_day_index($day->day) == $dayOfTheWeek && $current_time >= $day->start_of_shift && $current_time <= $day->end_of_shift && $day->note == NULL)
                return true;
        }
        return false;
    }

    public function get_day_index($day) {
        switch($day) {
            case 'Ponedeljek':
                return 1;
            case 'Torek':
                return 2;
            case 'Sreda':
                return 3;
            case 'Četrtek':
                return 4;
            case 'Petek':
                return 5;
            case 'Sobota':
                return 6;
            case 'Nedelja':
                return 7;
        }
    }

    public function is_favourited($id_user, $id_restaurant) {
        if (Favourite::where('id_user', $id_user)->where('id_restaurant', $id_restaurant)->first())
            return true;
        return false;
    }

    public function addDataToReservations_UserView(&$currentReservations) {
        foreach ($currentReservations as $reservation) {
            $restaurant = $reservation->restaurant()->first();
            
            $reservation->name = $restaurant->name;

            $restaurant_image = $restaurant->images()->first();
            
            $reservation->image = $restaurant_image == null ? null : $this->RESTAURANT_IMAGES_URL . $restaurant_image->image_path;

            $reservation->pickedMenus = $reservation->selected_menus()
                                                    ->get(['id_menu', 'quantity'])
                                                    ->each(function ($selected_menu) {
                                                        $selected_menu->name = $selected_menu->menu()->pluck('name')->first();
                                                        
                                                        $selected_menu->price = $selected_menu->menu()->pluck('price')->first();
                                                    })->toArray();
        }
    }

    public function addDataToReservations_AdminView(&$currentReservations) {
        foreach ($currentReservations as $reservation) {
            $user = $reservation->user()->first();

            $reservation->user_id = $user->id_user;
            $reservation->userName = $user->name;
            $reservation->userSurname = $user->surname;
            $reservation->userEmail = $user->email;

            $reservation->pickedMenus = $reservation->selected_menus()
                                                    ->get(['id_menu', 'quantity'])
                                                    ->each(function ($selected_menu) {
                                                        $selected_menu->name = $selected_menu->menu()->pluck('name')->first();
                                                        
                                                        $selected_menu->price = $selected_menu->menu()->pluck('price')->first();
                                                    })->toArray();
        }
    }

    public function addDataToReviews(&$reviews) {
        Carbon::setLocale('sl');

        foreach ($reviews as $review) {
            $user = $review->user()->first();

            $review->name = $user->name;
            $review->surname = $user->surname;
            $review->profile_image = $this->USER_IMAGES_URL . $user->profile_image_path;
            $review->time_ago = $review->updated_at->diffForHumans();
        }

        return $reviews;
    }

    public function validateInteger(Request $request) {
        $message = "Request sent data must be number";

        if ($request->has('id_restaurant') && !is_numeric($request->id_restaurant)) return abort(403, "Id restaurant must be a number");
        if ($request->has('id_reservation') && !is_numeric($request->id_reservation)) return abort(403, "Id reservation must be a number");

        if ($request->has('commentOffset') && !is_numeric($request->commentOffset)) return abort(403, "commentOffset must be a number");
        if ($request->has('reservationOffset') && !is_numeric($request->reservationOffset)) return abort(403, "reservationOffset must be a number");
        if ($request->has('reviewOffset') && !is_numeric($request->reviewOffset)) return abort(403, "reviewOffset must be a number");
        if ($request->has('restaurantOffset') && !is_numeric($request->restaurantOffset)) return abort(403, "restaurantOffset must be a number");
        if ($request->has('categoryOffset') && !is_numeric($request->categoryOffset)) return abort(403, "categoryOffset must be a number");
    }
}
