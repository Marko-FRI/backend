<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

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
        $restaurants = Restaurant::select('restaurants.id_restaurant', 'restaurants.name', 'restaurants.address')
                                    ->leftJoin('reviews', 'reviews.id_restaurant', '=', 'restaurants.id_restaurant')
                                    ->distinct()
                                    ->withAvg('reviews as avg_rating', 'rating')
                                    ->orderByRaw('avg_rating desc nulls last')
                                    ->paginate(9);

        $url = URL::to('/') . '/images/restaurant_images/';

        foreach ($restaurants as $restaurant) {
            $image =$restaurant->images()->get()->first();

            $restaurant->image = $image != null ? $url . $image->image_path : null;
            
            $restaurant->is_open = $this->is_open($restaurant->schedule()->get());

            //$restaurant->is_favourited = $this->is_favourited(Auth::guard('sanctum')->user()->id_user, $restaurant->id_restaurant);
        }

        $categories = Category::select('id_category', 'name')->get();

        $response = [
            'restaurants' => $restaurants,
            'categories' => $categories,
            'num_of_restaurants' => $restaurants->count()
        ];
        
        return $response;
    }

    function filtered(Request $request) {
        $data = $request->all();

        $restaurants = Restaurant::select('restaurants.id_restaurant', 'restaurants.name', 'restaurants.address')
                                    ->leftJoin('reviews', 'reviews.id_restaurant', '=', 'restaurants.id_restaurant')
                                    ->join('menus', 'menus.id_restaurant', '=', 'restaurants.id_restaurant')
                                    ->distinct()
                                    ->withAvg('reviews as avg_rating', 'rating');

        $message = "Success.";

        $url = URL::to('/') . '/images/restaurant_images/';

        if (isset($data['search'])) $restaurants = $restaurants->where('name', 'ilike', '%'. $data['search'] .'%');

        if (isset($data['pickedCategories'])) $restaurants->whereIn('menus.id_category', $data['pickedCategories']);

        if (isset($data['sortBy'])) {
            if (!in_array($data['sortBy'], ['Ime', 'Ocena'])) {
                $message = "Unvalid credentials.";
            } else {
                if ($data['sortBy'] == 'Ime') $restaurants = $restaurants->orderBy('name', 'asc');
                else if ($data['sortBy'] == 'Ocena') $restaurants = $restaurants->orderByRaw('avg_rating desc nulls last');
            }
        }

        $restaurants = $restaurants->paginate(9);

        foreach ($restaurants as $restaurant) {
            $image = $restaurant->images()->get()->first();

            $restaurant->image = $image != null ? $url . $image->image_path : null;
            
            $restaurant->is_open = $this->is_open($restaurant->schedule()->get());

            //$restaurant->is_favourited = $this->is_favourited(Auth::guard('sanctum')->user()->id_user, $restaurant->id_restaurant);
        }

        $response = [
            'message' => $message,
            'restaurants' => $restaurants,
            'num_of_restaurants' => $restaurants->count()
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
