<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

use App\Models\Restaurant;
use App\Models\Favourite;
use App\Models\Category;
use App\Models\Review;

class HomeController extends Controller
{
    public function index(Request $request) {
        $this->validateInteger($request);

        $reviews = $this->getReviews($request->reviewOffset);

        $restaurants = Restaurant::select('restaurants.id_restaurant', 'restaurants.name', 'restaurants.description')
                                ->leftJoin('reviews', 'reviews.id_restaurant', '=', 'restaurants.id_restaurant')
                                ->distinct()
                                ->withAvg('reviews as avg_rating', 'rating')
                                ->orderByRaw('avg_rating desc nulls last')
                                ->take($request->restaurantOffset)
                                ->get();

        foreach ($restaurants as $restaurant) {
            $restaurant_image = $restaurant->images()->first();

            $restaurant->restaurant_image_path = $restaurant_image == null ? null : $this->RESTAURANT_IMAGES_URL . $restaurant_image->image_path;
        }

        $categories = Category::inRandomOrder()->take($request->categoryOffset)->get();
        
        foreach ($categories as $category) {            
            $category->restaurants = $category->restaurants()->get(['restaurants.id_restaurant', 'restaurants.name', 'restaurants.address'])->unique('id_restaurant')->take($request->categoryRestaurantOffset);

            foreach ($category->restaurants as $restaurant) {
                $restaurant_image = $restaurant->images()->first();

                $restaurant->image = $restaurant_image == null ? null : $this->RESTAURANT_IMAGES_URL . $restaurant_image->image_path;

                $restaurant->is_open = $this->is_open($restaurant->schedule()->get());

                $restaurant->avg_rating = $restaurant->average_rating();
            }
        }

        $response = [
            'reviews' => $reviews,
            'restaurants' => $restaurants,
            'categories' => $categories
        ];

        return $response;
    }

    function moreReviews(Request $request) {
        $response = [
            'reviews' => $this->getReviews($request->reviewOffset)
        ];

        return $response;
    }

    function getReviews($offset) {
        $reviews = Review::select('*')->distinct('id_restaurant')->get()->sortByDesc('updated_at')->take($offset)->makeHidden(['created_at', 'updated_at']);
        //$reviews = Review::distinct('id_restaurant')->orderBy('updated_at', 'desc')->get()->unique('id_restaurant')->take($offset);
        /*$newReviews = [];

        foreach ($reviews as $review) {
            $newReviews[] = $review;
        }*/
        //$reviews = Review::all();
        //dd($reviews);
        foreach ($reviews as $review) {
            $user = $review->user()->first();

            $review->user_name = $user->name;
            $review->user_image_path = $this->USER_IMAGES_URL . $user->profile_image_path;

            $restaurant = $review->restaurant()->first();

            $review->restaurant_name = $restaurant->name;

            $restaurant_image = $restaurant->images()->first();

            $review->restaurant_image_path = $restaurant_image == null ? null : $this->RESTAURANT_IMAGES_URL . $restaurant_image->image_path;
        }

        return array_values($reviews->toArray());
    }

    public function restaurantsFirstLoad() {
        $restaurants = Restaurant::select('restaurants.id_restaurant', 'restaurants.name', 'restaurants.address')
                                    ->leftJoin('reviews', 'reviews.id_restaurant', '=', 'restaurants.id_restaurant')
                                    ->distinct()
                                    ->withAvg('reviews as avg_rating', 'rating')
                                    ->orderByRaw('avg_rating desc nulls last');
        
        $num_of_restaurants = $restaurants->get()->count();

        $restaurants = $restaurants->paginate(9);

        /*$num_of_restaurants = Restaurant::select('restaurants.id_restaurant', 'restaurants.name', 'restaurants.address')
                                        ->leftJoin('reviews', 'reviews.id_restaurant', '=', 'restaurants.id_restaurant')
                                        ->distinct()->get()->count();*/

        foreach ($restaurants as $restaurant) {
            $restaurant_image = $restaurant->images()->first();

            $restaurant->image = $restaurant_image == null ? null : $this->RESTAURANT_IMAGES_URL . $restaurant_image->image_path;
            
            $restaurant->is_open = $this->is_open($restaurant->schedule()->get());

            //$restaurant->is_favourited = $this->is_favourited(Auth::guard('sanctum')->user()->id_user, $restaurant->id_restaurant);
        }

        $categories = Category::select('id_category', 'name')->get();

        $response = [
            'restaurants' => $restaurants,
            'categories' => $categories,
            'num_of_restaurants' => $num_of_restaurants
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

        /*$num_of_restaurants = Restaurant::select('restaurants.id_restaurant', 'restaurants.name', 'restaurants.address')
                                        ->leftJoin('reviews', 'reviews.id_restaurant', '=', 'restaurants.id_restaurant')
                                        ->join('menus', 'menus.id_restaurant', '=', 'restaurants.id_restaurant')
                                        ->distinct()->get()->count();*/

        $message = "Success.";

        if (isset($data['search'])) $restaurants = $restaurants->where('restaurants.name', 'ilike', '%'. $data['search'] .'%');

        if (isset($data['pickedCategories'])) $restaurants->whereIn('menus.id_category', $data['pickedCategories']);

        if (isset($data['sortBy'])) {
            if (!in_array($data['sortBy'], ['Ime', 'Ocena'])) {
                $message = "Unvalid credentials.";
            } else {
                if ($data['sortBy'] == 'Ime') $restaurants = $restaurants->orderBy('restaurants.name', 'asc');
                else if ($data['sortBy'] == 'Ocena') $restaurants = $restaurants->orderByRaw('avg_rating desc nulls last');
            }
        }

        $num_of_restaurants = $restaurants->get()->count();

        $restaurants = $restaurants->paginate(9);

        foreach ($restaurants as $restaurant) {
            $restaurant_image = $restaurant->images()->first();

            $restaurant->image = $restaurant_image == null ? null : $this->RESTAURANT_IMAGES_URL . $restaurant_image->image_path;
            
            $restaurant->is_open = $this->is_open($restaurant->schedule()->get());

            //$restaurant->is_favourited = $this->is_favourited(auth('sanctum')->user()->id_user, $restaurant->id_restaurant);
        }

        $response = [
            'message' => $message,
            'restaurants' => $restaurants,
            'num_of_restaurants' => $num_of_restaurants
        ];

        return $response;
    }
}
