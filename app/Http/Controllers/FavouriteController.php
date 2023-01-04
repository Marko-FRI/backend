<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Favourite;

class FavouriteController extends Controller
{
    public function index(Request $request) {
        if ($request->isFavourited) {
            Favourite::where('id_restaurant', $request->id_restaurant)->where('id_user', auth('sanctum')->user()->id_user)->delete();
        } else {
            $favourite = new Favourite;

            $favourite->id_user = auth('sanctum')->user()->id_user;
            $favourite->id_restaurant = $request->id_restaurant;

            $favourite->save();
        }

        return true;
    }    
}
