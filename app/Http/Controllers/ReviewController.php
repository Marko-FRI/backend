<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

use Carbon\Carbon;

use App\Models\Restaurant;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index(Request $request) {
        $this->validateReviewCredentials($request);

        $id_user = auth('sanctum')->user()->id_user;

        $existing = Review::where('id_user', $id_user)->where('id_restaurant', $request->id_restaurant)->first();

        try {
            if ($existing) {
                Review::where('id_restaurant', $request->id_restaurant)
                        ->where('id_user', $id_user)
                        ->update(['comment' => $request->comment, 'rating' => $request->rating]);
            
                //$message = "You have already submitted a review.";
                //return abort(401, $message);

                $message = "You have updated your review";
            } else {
                $review = new Review;
                $review->rating = $request->rating;
                $review->comment = $request->comment;
                $review->id_restaurant = $request->id_restaurant;
                $review->id_user = $id_user;
                $review->save();
                
                $message = "You have successfuly submitetd a review.";
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $message = $ex->getMessage();
        }

        $restaurant = Restaurant::where('id_restaurant', $request->id_restaurant)->first();

        $rating = $restaurant->average_rating();

        $numReviews = Review::where('id_restaurant', $request->id_restaurant)->count();

        $reviews = $restaurant->reviews()
                              ->select('id_user', 'id_restaurant', 'comment', 'rating', 'updated_at')
                              ->orderBy('updated_at', 'desc')->take($request->commentOffset)->get();

        Carbon::setLocale('sl');
        foreach ($reviews as $review) {
            $user = $review->user();

            $review->name = $user->pluck('name')[0];
            $review->surname = $user->pluck('surname')[0];
            $review->profile_image = $this->USER_IMAGES_URL . $user->pluck('profile_image_path')[0];
            $review->time_ago = $review->updated_at->diffForHumans();
        }

        $response = [
            'message' => $message,
            'rating' => $rating,
            'numReviews' => $numReviews,
            'reviews' => $reviews
        ];

        return $response;
    }

    function validateReviewCredentials(Request $request) {
        $rules = [
            'rating' => ['required', 'min:1', 'max:5', 'regex:/[1-5]/'],
            'comment' => ['required', 'max:1024']
        ];

        $errorMessages = [
            'rating.required' => 'Ocena je obvezna.',
            'rating.min' => 'Ocena je lahko najmanj 1.',
            'rating.max' => 'Ocena je lahko največ 5.',
            'rating.regex' => 'Ocena je lahko vključno med 1 in 5.',

            'comment.required' => 'Komentar je obvezen.',
            'comment.max' => 'Komentar je presegel 1024 znakov.'
        ];

        return $request->validate($rules, $errorMessages);
    }

    function moreReviews(Request $request) {
        $restaurant = Restaurant::where('id_restaurant', $request->id_restaurant)->first();

        $reviews = $restaurant->reviews()
                              ->select('id_user', 'id_review', 'comment', 'rating', 'updated_at')
                              ->orderBy('updated_at', 'desc')->take($request->commentOffset)->get();

        Carbon::setLocale('sl');
        foreach ($reviews as $review) {
            $user = $review->user();

            $review->name = $user->pluck('name')[0];
            $review->surname = $user->pluck('surname')[0];
            $review->profile_image = $this->USER_IMAGES_URL . $user->pluck('profile_image_path')[0];
            $review->time_ago = $review->updated_at->diffForHumans();
        }

        $response = [
            'reviews' => $reviews
        ];

        return $response;
    }
}
