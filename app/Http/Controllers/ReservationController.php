<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

use Carbon\Carbon;

use App\Models\Restaurant;
use App\Models\Table;
use App\Models\Reservation;
use App\Models\Selected_menu;

class ReservationController extends Controller
{
    public function index(Request $request) {
        $this->validateReservationCredentials($request);
        $this->validateAdditionalReservationCredentials($request);

        $available_tables = $this->getAvailableTables($request);
        $is_available=false;

        $selected_table = NULL;
        foreach ($available_tables as $table) {
            if ($request->numPersons<=$table->number_of_seats) {
                $selected_table = $table;
                break;
            }
        }

        if ($selected_table!=NULL) {
            $reservation = new Reservation;
            $reservation->id_user = auth('sanctum')->user()->id_user;
            $reservation->id_table = $table->id_table;
            $reservation->number_of_personel = $request->numPersons;
            $reservation->date_and_time_of_reservation = $request->dateTime;
            $reservation->note = $request->note;
            
            $this->validateMenuCredentials($request);

            foreach ($request->pickedMenus as $menu) {
                $selected_menu = new Selected_menu;
                
                $selected_menu->id_reservation = $reservation->id_reservation;
                $selected_menu->id_menu = $menu['id_menu'];
                $selected_menu->quantity = $menu['quantity'];
                $selected_menu->save();
            }

            $reservation->save();

            $is_available=true;
            $message = "You have successfuly reserved a table.";
        } else {
            $message = "There are no seats available at that time for that amount of personel.";
        }

        $response = [
            'message' => $message
        ];

        if (!$is_available)
            return abort(401, $message);

        return $response;
    }

    public function checkAvaliability(Request $request) {
        $this->validateReservationCredentials($request);
        
        $available_tables = $this->getAvailableTables($request);

        $is_available=false;
        foreach ($available_tables as $table) {
            if ($request->numPersons<=$table->number_of_seats){
                $is_available=true;
                break;
            }
        }

        if ($is_available) $message = "You have successfuly reserved a table.";
        else $message = "There are no seats available at that time for that amount of personel.";

        $response = [
            'message' => $message,
            'available' => $is_available,
            //'available_tables' => $available_tables
        ];

        if (!$is_available)
            return abort(401, $message);
        
        return $response;
    }

    function validateReservationCredentials(Request $request) {
        $max_seats = Restaurant::where('id_restaurant', $request->id_restaurant)->first()->tables()->max('number_of_seats');

        $after = Carbon::createFromFormat('Y-m-d H:i:s', now())->addDays(1);
        $before = Carbon::createFromFormat('Y-m-d H:i:s', now())->addDays(14);
        
        $rules = [
            'dateTime' => ['required', 'date_format:Y-m-d H:i:s', "after_or_equal:$after", "before_or_equal:$before"],
            'numPersons' => ['required', 'integer', 'min:1', "max:$max_seats"]
        ];

        $errorMessages = [
            'dateTime.required' => 'DateTime is required.',
            'dateTime.date_format' => 'DateTime must be in correct format.',
            'dateTime.after_or_equal' => 'Can reservate after 1 day from now.',
            'dateTime.before_or_equal' => 'Can reservate before 14 days from now.',

            'numPersons.required' => 'Number of persons is required.',
            'numPersons.integer' => 'Number of persons must be a number.',
            'numPersons.min' => 'Number of persons must be at least 0.',
            'numPersons.max' => "Number of persons is at most $max_seats.",
        ];

        return $request->validate($rules, $errorMessages);
    }

    public function validateAdditionalReservationCredentials(Request $request) {
        $rules = [
            'note' => ['max:512']
        ];

        $errorMessages = [
            'note.max' => 'Note can contain a most 512 characters.',
        ];

        return $request->validate($rules, $errorMessages);
    }

    function validateMenuCredentials(Request $request) {
        $numPersons = $request->numPersons;
        
        $rules = [
            'pickedMenus' => ['array'],
            'pickedMenus.*.quantity' => ['integer', "max:$numPersons"]
        ];

        $errorMessages = [
            'pickedMenus.*.quantity.integer' => 'Quantity must be integer.',
            'pickedMenus.*.quantity.max' => "Quantity must be at most $numPersons."
        ];

        return $request->validate($rules, $errorMessages);
    }

    function getAvailableTables(Request $request) {       
        $prev = Carbon::createFromFormat('Y-m-d H:i:s', $request->dateTime)->subHours(2);
        $next = Carbon::createFromFormat('Y-m-d H:i:s', $request->dateTime)->addHours(2);

        $used_tables = Reservation::join('tables', 'reservations.id_table', '=', 'tables.id_table')
                                ->where('id_restaurant', $request->id_restaurant)
                                ->where('date_and_time_of_reservation', '>=', $prev)
                                ->where('date_and_time_of_reservation', '<=', $next)
                                ->pluck('reservations.id_table');

        return Table::where('id_restaurant', $request->id_restaurant)->whereNotIn('id_table', $used_tables)->orderBy('number_of_seats')->get(['id_table', 'number_of_seats', 'description']);
    }
}
