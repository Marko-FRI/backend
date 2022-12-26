<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

use Carbon\Carbon;

use App\Models\Restaurant;
use App\Models\Per_day_schedule;
use App\Models\Review;
use App\Models\Menu;
use App\Models\Menu_has_food;
use App\Models\Restaurant_has_food;
use App\Models\Food;
use App\Models\Food_has_alergen;
use App\Models\Alergen;

class RestaurantController extends Controller
{
    public function get_single_restaurant(Request $request) {
        $restaurant = Restaurant::find($request->id);

        $num_of_ratings = Review::where('id_restaurant', $request->id)->count();

        $num_of_reviews = $restaurant->reviews()->count();
        $reviews = $restaurant->reviews()
                              ->select('id_user', 'id_review', 'comment', 'rating', 'updated_at')
                              ->orderBy('updated_at', 'desc')->paginate(4);
        
        $url = URL::to('/') . '/images/profile_images/';
        
        Carbon::setLocale('sl');
        foreach ($reviews as $review) {
            $user = $review->user();

            $review->name = $user->pluck('name')[0];
            $review->surname = $user->pluck('surname')[0];
            $review->profile_image = $url . $user->pluck('profile_image_path')[0];
            $review->time_ago = $review->updated_at->diffForHumans();
        }
        
        $url = URL::to('/') . '/images/restaurant_images/';

        $i=0;
        $images = [];
        foreach ($restaurant->images()->get(['image_path'])->toArray() as $image) {
            $images[$i] = $url . $image['image_path'];
            $i++;
        }
        $restaurant->images = $images;
        $restaurant->rating = $restaurant->average_rating();

        $num_of_menus = $restaurant->menus()->count();
        $menus = $restaurant->menus()->select('id_menu', 'name', 'image_path', 'price', 'description', 'discount')->orderBy('price', 'desc')->paginate(6);
        $schedule = $restaurant->schedule()->get(['start_of_shift', 'end_of_shift', 'day', 'note', ]);

        for ($i=0; $i < 7; $i++) { 
            if(!isset($schedule[$i])) {
                $schedule[$i] = "/";
            }
        }

        $url = URL::to('/') . '/images/menu_images/';

        foreach ($menus as $menu) {
            $menu->image_path = $url . $menu->image_path;
            $alergens = Alergen::join('food_has_alergens', 'alergens.id_alergen', '=', 'food_has_alergens.id_alergen')
                            ->join('food', 'food_has_alergens.id_food', '=', 'food.id_food')
                            ->join('restaurant_has_food', 'food.id_food', '=', 'restaurant_has_food.id_food')
                            ->join('menu_has_food', 'restaurant_has_food.id_restaurant_has_food', '=', 'menu_has_food.id_restaurant_has_food')
                            ->where('menu_has_food.id_menu', $menu->id_menu)->get(['alergens.name'])->toArray();

            $alergens_new = [];
            $i=0;
            foreach ($alergens as $alergen) {
                $alergens_new[$i] = $alergen['name'];
                $i++;
            }
            
            $menu->alergens = $alergens_new;
        }

        $response = [
            'restaurant_data' => $restaurant, //->get(['id_restaurant', 'id_user', 'name', 'address', 'description', 'email', 'phone_number', 'facebook_link', 'instagram_link', 'twitter_link']),
            'menus' => $menus,
            'schedule' => $schedule,
            'reviews' => $reviews,
            'numMenus' => $num_of_menus,
            'numRatings' => $num_of_ratings,
            'numReviews' => $num_of_reviews
        ];

        return $response;
    }
}
