<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

use Carbon\Carbon;

use App\Models\Restaurant;
use App\Models\Per_day_schedule;
use App\Models\Review;
use App\Models\Menu;
use App\Models\Favourite;
use App\Models\Menu_has_alergen;
use App\Models\Alergen;

class RestaurantController extends Controller
{
    public function index(Request $request) {
        $restaurant = Restaurant::find($request->id);

        $num_of_ratings = Review::where('id_restaurant', $request->id)->count();

        $num_of_reviews = $restaurant->reviews()->count();
        $reviews = $restaurant->reviews()
                              ->select('id_user', 'id_restaurant', 'comment', 'rating', 'updated_at')
                              ->orderBy('updated_at', 'desc')->take(4)->get();
        
        $url = URL::to('/') . '/images/user_images/';
        
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

        if (auth('sanctum')->user() != null) {
            $current_user_id = auth('sanctum')->user()->id_user;

            $restaurant->isFavourited = $this->is_favourited($current_user_id, $restaurant->id_restaurant);

            $review = Review::where('id_restaurant', $restaurant->id_restaurant)->where('id_user', $current_user_id)->get(['rating', 'comment']);
            
            $restaurant->userReview = isset($review[0]) ? $review[0] : NULL;
        }

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
            $alergens = $menu->alergens()->get();

            $alergens_new = [];
            $i=0;
            foreach ($alergens as $alergen) {
                $alergens_new[$i] = $alergen->alergen()->get(['name'])->pluck('name')[0];
                $i++;
            }
            
            $menu->alergens = $alergens_new;
        }

        $response = [
            'restaurant_data' => $restaurant,
            'menus' => $menus,
            'schedule' => $schedule,
            'reviews' => $reviews,
            'numMenus' => $num_of_menus,
            'numRatings' => $num_of_ratings,
            'numReviews' => $num_of_reviews
        ];

        return $response;
    }

    function is_favourited($id_user, $id_restaurant) {
        if (Favourite::where('id_user', $id_user)->where('id_restaurant', $id_restaurant)->first())
            return true;
        return false;
    }
}
