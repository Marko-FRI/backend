<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;

use App\Models\Table;

use App\Models\Favourite;
use App\Models\Restaurant;
use App\Models\Reservation;
use App\Models\Restaurant_has_image;

use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    //----------- login -----------
    public function login(Request $request) {
        $this->validateUserCredentialsForLogin($request);

        $user = User::where('email', $request->email)->first();
 
        if ($user && Hash::check($request->password, $user->password)) {
            $restaurant = Restaurant::where('id_user', $user->id_user)->first();

            $user->id_restaurant = $restaurant == null ? null : $restaurant->id_restaurant;

            $user->profile_image_path = $this->USER_IMAGES_URL . $user->profile_image_path;
            
            $token = $user->createToken('web-token')->plainTextToken;

            $message = "You have been succesfully logged in.";
        } else {
            $message = "Login failed - Please check your email and password again.";
            $token = "";
        }

        $response = [
            'message' => $message,
            'token' => $token,
            'userData' => $token != "" ? $user : null
        ];

        if ($token == "")
            return abort(401, $message);
        //    return Response::json($response, 401);
        
        //return Response::json($response, 200);
        return $response;
    }

    function validateUserCredentialsForLogin($request) {
        $rules = [
            'email' => ['required','email'],
            'password' => ['required']
        ];

        $errorMessages = [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email.',

            'password.required' => 'Password is required.'
        ];

        return $request->validate($rules, $errorMessages);
    }

    //----------- register -----------
    public function register(Request $request) {
        $this->validateUserCredentialsForRegister($request);
        
        try {
            $user = new User();
            $user->email = $request->email;
            $user->name = ucfirst(strtolower($request->name));
            $user->surname = ucfirst(strtolower($request->surname));
            $user->password = Hash::make($request->password);
            $user->profile_image_path = 'profile.jpg';
            $user->remember_token = Str::random(12);
            $user->save();

            $user->profile_image_path = $this->USER_IMAGES_URL . $user->profile_image_path;

            $message = "You have been succesfully registered.";
            $token = $user->createToken('web-token')->plainTextToken;
        } catch (\Illuminate\Database\QueryException $ex) {
            $message = $ex->getMessage();
            $token = "";
            $user = null;
        }

        $response = [
            'message' => $message,
            'token' => $token,
            'userData' => $user
        ];

        //return Response::json($response, 200);
        return $response;
    }

    function validateUserCredentialsForRegister(Request $request) {
        $rules = [
            'name' => ['required', 'min:3', 'max:100', 'regex:/^[a-zA-ZšđčćžŠĐČĆŽ]+$/'],
            'surname'=> ['required', 'min:3', 'max:100', 'regex:/^[a-zA-ZšđčćžŠĐČĆŽ]+$/'],
            'email' => ['required', 'email:rfc,dns', 'unique:users'],
            'password' => ['required_with:password_confirmation', 'min:6', 'confirmed','regex:/[a-z]/', 'regex:/[A-Z]/','regex:/[0-9]/','regex:/[@$!%*#?&_.-]/'],
            'password_confirmation' => ['same:password']
        ];

        $errorMessages = [
            'name.required' => 'Ime je obvezno.',
            'name.min' => 'Ime mora vsebovati vsaj 3 črke.',
            'name.max' => 'Ime lahko vsebuje največ 100 črk.',
            'name.regex' => 'Ime lahko vsebuje le črke.',

            'surname.required' => 'Priimek je obvezen.',
            'surname.min' => 'Priimek mora vsebovati vsaj 3 črke.',
            'surname.max' => 'Priimek lahko vsebuje največ 100 črk.',
            'surname.regex' => 'Priimek lahko vsebuje le črke.',
            
            'email.required' => 'Email je obvezen.',
            'email.email' => 'Prosimo vnesite pravilen email.',
            'email.unique' => 'Email je že v uporabi.',

            'password.required_with' => 'Geslo in potrditveno geslo sta obvezni.',
            'password.min' => 'Geslo mora vsebovati vsaj 6 znakov.',
            'password.confirmed' => 'Potrditveno geslo se ne ujema z geslom.',
            'password.regex' => 'Geslo mora vsebovati vsaj 1 malo črko, 1 veliko črko ter en poseben znak.',
            
            'password_confirmation.same' => 'Potrditveno geslo se ne ujema z geslom.'
        ];

        return $request->validate($rules, $errorMessages);
    }

    //----------- logout -----------
    public function logout(Request $request) {
        try {
            $request->user()->currentAccessToken()->delete();
            
            $message = "You have been succesfully logged out.";
        } catch (\Illuminate\Database\QueryException $ex) {
            $message = $ex->getMessage();
        }

        $response = [
            'message' => $message
        ];

        return $response;
    }

    public function getUserData(Request $request) {
        $id_user = auth('sanctum')->user()->id_user;

        $restaurants = Restaurant::select('restaurants.id_restaurant', 'restaurants.name', 'restaurants.address')
                                ->join('favourites', 'restaurants.id_restaurant', '=', 'favourites.id_restaurant')
                                ->where('favourites.id_user', $id_user)
                                ->orderBy('restaurants.name', 'asc');
                                //->get(['restaurants.id_restaurant', 'restaurants.name', 'restaurants.address']);

        $num_of_restaurants = $restaurants->count();
        
        $restaurants = $restaurants->paginate(9);
        
        foreach ($restaurants as $restaurant) {
            $image = $restaurant->images()->get()->first();
            
            $restaurant->image = $image != null ? $this->RESTAURANT_IMAGES_URL . $image->image_path : null;

            $restaurant->avg_rating = $restaurant->average_rating();
            
            $restaurant->is_open = $this->is_open($restaurant->schedule()->get());
        };

        $pastReservations = $this->getPastReservations($id_user, $request->reservationOffset);

        $numOfPastReservations = $this->getNumberOfPastReservations($id_user);

        $this->addDataToReservations_UserView($pastReservations);

        $activeReservations = $this->getActiveReservations($id_user, $request->reservationOffset);

        $numOfActiveReservations = $this->getNumberOfActiveReservations($id_user);

        if ($activeReservations!=null)
            $this->addDataToReservations_UserView($activeReservations);

        $response = [
            'restaurants' => $restaurants,
            'num_of_restaurants' => $num_of_restaurants,
            'pastReservations' => $pastReservations,
            'activeReservations' => $activeReservations,
            'numOfPastReservations' => $numOfPastReservations,
            'numOfActiveReservations' => $numOfActiveReservations
        ];

        return $response;
    }

    public function loadMoreActiveReservations(Request $request) {
        $activeReservations = $this->getActiveReservations(auth('sanctum')->user()->id_user, $request->reservationOffset);

        $this->addDataToReservations_UserView($activeReservations);

        $response = [
            'activeReservations' => $activeReservations
        ];

        return $response;
    }

    public function loadMorePastReservations(Request $request) {
        $pastReservations = $this->getPastReservations(auth('sanctum')->user()->id_user, $request->reservationOffset);

        $this->addDataToReservations_UserView($pastReservations);

        $response = [
            'pastReservations' => $pastReservations
        ];

        return $response;
    }

    public function checkChangeInPassword(Request $request) {
        $this->validatePasswordChangeCredentials($request);

        $user = auth('sanctum')->user();
        
        $isChange = true;
        
        if ($user && Hash::check($request->password, $user->password)) {
            $isChange = false;
        }

        $response = [
            'isChange' => $isChange
        ];

        return $response;
    }

    function validatePasswordChangeCredentials(Request $request) {
        $rules = [
            'password' => ['required_with:password_confirmation', 'min:6', 'confirmed','regex:/[a-z]/', 'regex:/[A-Z]/','regex:/[0-9]/','regex:/[@$!%*#?&_.-]/'],
            'password_confirmation' => ['same:password']
        ];

        $errorMessages = [
            'password.required_with' => 'Geslo in potrditveno geslo sta obvezni.',
            'password.min' => 'Geslo mora vsebovati vsaj 6 znakov.',
            'password.confirmed' => 'Potrditveno geslo se ne ujema z geslom.',
            'password.regex' => 'Geslo mora vsebovati vsaj 1 malo črko, 1 veliko črko ter en poseben znak.',
            
            'password_confirmation.same' => 'Potrditveno geslo se ne ujema z geslom.'
        ];

        return $request->validate($rules, $errorMessages);
    }

    public function editProfileImage(Request $request) {
        $this->validateNewImageCredentials($request);

        $user = auth('sanctum')->user();

        $imageName = $user->id_user . time().'.'.$request->file->extension();
        
        $request->file->move(public_path('images/user_images'), $imageName);

        $user->update(['profile_image_path' => $imageName]);

        $response =  [
            'imageName' => $this->USER_IMAGES_URL . $imageName
        ];

        return $response;
    }

    function validateNewImageCredentials(Request $request) {
        $rules = [
            'file' => ['image', 'mimes:jpeg,png,jpg,gif']
        ];

        $errorMessages = [
            'file.image' => 'Datoteka je lahko le slika.',
            'file.mimes' => 'Sprejeti formati so: jpeg, png, jpg and gif.'
        ];

        return $request->validate($rules, $errorMessages);
    }

    public function editProfile(Request $request) {
        $this->validateNewDataCredentials($request);

        $user = auth('sanctum')->user();
        
        if ($user && Hash::check($request->current_password, $user->password) ) {
            
            $user_db = User::where('id_user', $user->id_user)->first();

            $user_db->update(['name'=>$request->name, 'surname'=>$request->surname]);

            if ($request->password != null)
                $user_db->update(['password'=>Hash::make($request->password)]);

            $message = "User data updated.";
        } else {
            return abort(401, "Invalid password.");
        }

        $user = User::where('id_user', $user->id_user)->first();
        
        $user->profile_image_path = $this->USER_IMAGES_URL . $user->profile_image_path;

        $response = [
            'message' => $message,
            'userData' => $user
        ];

        return $response;
    }

    function validateNewDataCredentials(Request $request) {
        $rules = [
            'name' => ['required', 'min:3', 'max:100', 'regex:/^[a-zA-ZšđčćžŠĐČĆŽ]+$/'],
            'surname'=> ['required', 'min:3', 'max:100', 'regex:/^[a-zA-ZšđčćžŠĐČĆŽ]+$/'],
            'password' => ['nullable', 'min:6', 'regex:/[a-z]/', 'regex:/[A-Z]/','regex:/[0-9]/','regex:/[@$!%*#?&_.-]/']
        ];

        $errorMessages = [
            'name.required' => 'Ime je obvezno.',
            'name.min' => 'Ime mora vsebovati vsaj 3 črke.',
            'name.max' => 'Ime lahko vsebuje največ 100 črk.',
            'name.regex' => 'Ime lahko vsebuje le črke.',

            'surname.required' => 'Priimek je obvezen.',
            'surname.min' => 'Priimek mora vsebovati vsaj 3 črke.',
            'surname.max' => 'Priimek lahko vsebuje največ 100 črk.',
            'surname.regex' => 'Priimek lahko vsebuje le črke.',

            'password.min' => 'Geslo mora vsebovati vsaj 6 znakov.',
            'password.regex' => 'Geslo mora vsebovati vsaj 1 malo črko, 1 veliko črko ter en poseben znak.',
        ];

        return $request->validate($rules, $errorMessages);
    }

    function getPastReservations($id_user, $offset) {
        $reservations = Reservation::where('id_user', $id_user)
                                ->where('date_and_time_of_reservation', '<', $this->before)
                                ->orderBy('date_and_time_of_reservation', 'desc')
                                ->take($offset)
                                ->get();
        
        foreach($reservations as $reservation)
            $reservation->id_restaurant = $reservation->restaurant()->first()->id_restaurant;

        return $reservations->makeHidden(['id_table', 'updated_at', 'created_at']);
    }

    function getNumberOfPastReservations($id_user) {
        return Reservation::where('id_user', $id_user)
                            ->where('date_and_time_of_reservation', '<', $this->before)
                            ->count();
    }

    function getActiveReservations($id_user, $offset) {
        $reservations = Reservation::where('id_user', $id_user)
                                ->where('date_and_time_of_reservation', '>=', $this->before)
                                ->orderBy('date_and_time_of_reservation')
                                ->take($offset)
                                ->get();

        foreach($reservations as $reservation)
            $reservation->id_restaurant = $reservation->restaurant()->first()->id_restaurant;

        return $reservations->makeHidden(['id_table', 'updated_at', 'created_at']);
    }

    function getNumberOfActiveReservations($id_user) {
        return Reservation::where('id_user', $id_user)
                            ->where('date_and_time_of_reservation', '>=', $this->before)
                            ->count();
    }
}
