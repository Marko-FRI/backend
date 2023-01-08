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
        $this->validateInteger($request);

        $restaurant = Restaurant::where('id_restaurant', $request->id_restaurant)->first();

        $num_of_ratings = Review::where('id_restaurant', $request->id_restaurant)->count();

        $num_of_reviews = $restaurant->reviews()->count();
        $reviews = $restaurant->reviews()
                              ->select('id_user', 'id_restaurant', 'comment', 'rating', 'updated_at')
                              ->orderBy('updated_at', 'desc')->take(4)->get();
        
        Carbon::setLocale('sl');
        foreach ($reviews as $review) {
            $user = $review->user();

            $review->name = $user->pluck('name')[0];
            $review->surname = $user->pluck('surname')[0];
            $review->profile_image = $this->USER_IMAGES_URL . $user->pluck('profile_image_path')[0];
            $review->time_ago = $review->updated_at->diffForHumans();
        }

        $i=0;
        $images = [];
        foreach ($restaurant->images()->get(['image_path'])->toArray() as $image) {
            $images[$i] = $this->RESTAURANT_IMAGES_URL . $image['image_path'];
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

        foreach ($menus as $menu) {
            $menu->image_path = $this->MENU_IMAGES_URL . $menu->image_path;
            $alergens = $menu->alergens()->get();

            $alergens_new = [];
            $i=0;
            foreach ($alergens as $alergen) {
                $alergens_new[$i] = $alergen->alergen()->get(['name'])->pluck('name')[0];
                $i++;
            }
            
            $menu->alergens = $alergens_new;
        }

        $restaurant_header_image = $this->PLACEHOLDER_IMAGES_URL . 'restaurant_header_picture.png';

        $response = [
            'restaurant_data' => $restaurant,
            'menus' => $menus,
            'schedule' => $schedule,
            'reviews' => $reviews,
            'numMenus' => $num_of_menus,
            'numRatings' => $num_of_ratings,
            'numReviews' => $num_of_reviews,
            'restaurant_header_image' => $restaurant_header_image,
            'twitter_icon' => $this->APP_IMAGES_URL. 'twitter_green.png',
            'instagram_icon' => $this->APP_IMAGES_URL. 'instagram_green.png'
        ];

        return $response;
    }
}
