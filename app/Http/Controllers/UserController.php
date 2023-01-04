<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\Restaurant_has_image;
use App\Models\Food;
use App\Models\Drink;

class UserController extends Controller
{
    //----------- login -----------
    public function login(Request $request) {
        //Log::info($request->input());
        $this->validateUserCredentialsForLogin($request);

        $user = User::where('email', $request->email)->first();
 
        if ($user && Hash::check($request->password, $user->password) ) {
            $message = "You have been succesfully logged in.";

            $url = URL::to('/') . '/images/user_images/';
            $user->profile_image_path = $url . $user->profile_image_path;

            $token = $user->createToken('web-token')->plainTextToken;
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

            $url = URL::to('/') . '/images/user_images/';
            $user->profile_image_path = $url . $user->profile_image_path;

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
            'name.required' => 'Name is required.',
            'name.min' => 'Name needs to contain atleast 3 characters.',
            'name.max' => 'Name can contain maximum 100 characters.',
            'name.regex' => 'Name can only contain letters.',

            'surname.required' => 'Surname is required.',
            'surname.min' => 'Surname needs to contain atleast 3 characters.',
            'surname.max' => 'Surname can contain maximum 100 characters.',
            'surname.regex' => 'Surname can only contain letters.',
            
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email.',
            'email.unique' => 'Email is already in use.',

            'password.required_with' => 'Password and password confirmation are required.',
            'password.min' => 'Password needs to contain atleast 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.regex' => 'Password must contain at least 1 lower case letter, 1 upper case letter and one special character.',

            'password_confirmation.same' => 'Password confirmation does not match password.'
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

    public function getData(Request $request) {
        
    }
}
