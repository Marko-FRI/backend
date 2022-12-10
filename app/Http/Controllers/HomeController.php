<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Guard;
use Laravel\Sanctum\PersonalAccessToken;

use App\Models\Restaurant;
use App\Models\Favourite;
use App\Models\Category;

class HomeController extends Controller
{
    public function index() {
        $restaurants = Restaurant::all();

        foreach ($restaurants as $restaurant) {
            $restaurant->rating = $restaurant->average_rating();
            $restaurant->images = $restaurant->images()->get()->toArray();
            
            $restaurant->is_open = $this->is_open($restaurant->schedule()->get());

            $restaurant->is_favourited = $this->is_favourited(Auth::guard('sanctum')->user()->id_user, $restaurant->id_restaurant);
        }

        $categories = Category::all();

        $response = [
            'restaurants' => $restaurants,
            'categories' => $categories
        ];
        
        return $response;
    }

    function is_open($schedule) {
        $dayOfTheWeek = Carbon::now()->dayOfWeek;

        $current_time = Carbon::now()->format('H:i:s');

        foreach ($schedule as $day) {
            if ($this->get_day_index($day->day) == $dayOfTheWeek && $current_time >= $day->start_of_shift && $current_time <= $day->end_of_shift && $day->note == NULL)
                return true;
        }
        return false;
    }

    function get_day_index($day) {
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

    function is_favourited($id_user, $id_restaurant) {
        if (Favourite::where('id_user', $id_user)->where('id_restaurant', $id_restaurant)->first())
            return true;
        return false;
    }
}
