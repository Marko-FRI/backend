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
            $reservation->save();

            $this->validateMenuCredentials($request);

            foreach ($request->pickedMenus as $menu) {
                $selected_menu = new Selected_menu;
                $selected_menu->id_reservation = $reservation->id_reservation;
                $selected_menu->id_menu = $menu['id_menu'];
                $selected_menu->quantity = $menu['quantity'];
                $selected_menu->save();
            }

            $is_available=true;
            $message = "You have successfuly reserved a table.";
        } else {
            $message = "There are no seats available at that time for that amount of personel.";
        }

        if (!$is_available)
            return abort(401, $message);
        
        $response = [
            'message' => $message
        ];

        return $response;
    }

    public function checkAvaliability(Request $request) {
        $this->validateReservationCredentials($request);
        
        $available_tables = $this->getAvailableTables($request);

        $is_available=false;
        foreach ($available_tables as $table) {
            if ($request->numPersons<=$table->number_of_seats && $this->availableWithinSchedule($request)) {
                $is_available=true;
                break;
            }
        }

        if ($is_available) $message = "Seats are available for reservation.";
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
        $this->validateInteger($request);

        $max_seats = Restaurant::where('id_restaurant', $request->id_restaurant)->first()->tables()->max('number_of_seats');

        $after = Carbon::createFromFormat('Y-m-d H:i:s', now())->addHours(12);
        $before = Carbon::createFromFormat('Y-m-d H:i:s', now())->addDays(14);
        
        $rules = [
            'dateTime' => ['required', 'date_format:Y-m-d H:i:s', "after_or_equal:$after", "before_or_equal:$before"],
            'numPersons' => ['required', 'integer', 'min:1', "max:$max_seats"]
        ];

        $errorMessages = [
            'dateTime.required' => 'Datum in čas sta obvezna.',
            'dateTime.date_format' => 'Datum in čas morata biti v pravilnem formatu (Y-m-d H:i:s).',
            'dateTime.after_or_equal' => 'Rezervira se lahko najhitreje 12 ur od sedaj.',
            'dateTime.before_or_equal' => 'Rezervira se lahko največ 14 dni vnaprej.',

            'numPersons.required' => 'Število oseb je obvezno.',
            'numPersons.integer' => 'Število oseb mora biti število.',
            'numPersons.min' => 'Število oseb je lahko najmanj 0 (brez oseb).',
            'numPersons.max' => "Število oseb je lahko največ $max_seats.",
        ];

        return $request->validate($rules, $errorMessages);
    }

    public function validateAdditionalReservationCredentials(Request $request) {
        $rules = [
            'note' => ['max:512']
        ];

        $errorMessages = [
            'note.max' => 'Opomba ima lahko največ 512 znakov.',
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
            'pickedMenus.*.quantity.integer' => 'Kvantiteta mora biti število.',
            'pickedMenus.*.quantity.max' => "Kvantiteta je lahko največ $numPersons."
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

    function availableWithinSchedule(Request $request) {
        $this->validateInteger($request);

        $restaurant = Restaurant::where('id_restaurant', $request->id_restaurant)->first();

        $reservationDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $request->dateTime);
        
        $dayOfTheWeek = $reservationDateTime->dayOfWeek;

        $schedule = $restaurant->schedule()->get();

        if (isset($schedule[$dayOfTheWeek-1])) {
            $start_of_shift = Carbon::createFromFormat('Y-m-d H:i:s', explode(" ", $request->dateTime)[0] . $schedule[$dayOfTheWeek-1]->start_of_shift);
            $end_of_shift = Carbon::createFromFormat('Y-m-d H:i:s', explode(" ", $request->dateTime)[0] . $schedule[$dayOfTheWeek-1]->end_of_shift)->subHours(2);
            
            if ($reservationDateTime->gte($start_of_shift) && $reservationDateTime->lte($end_of_shift))
                return true;
        }
        return false;
    }

    public function deleteReservation(Request $request) {
        $this->validateInteger($request);

        Reservation::where('id_reservation', $request->id_reservation)->delete();

        $id_user = auth('sanctum')->user()->id_user;

        $activeReservations = $this->getActiveReservations($id_user, $request->reservationOffset);

        $numOfActiveReservations = $this->getNumberOfActiveReservations($id_user);

        $this->addDataToReservations_UserView($activeReservations);

        $response = [
            'activeReservations' => $activeReservations,
            'numOfActiveReservations' => $numOfActiveReservations
        ];

        return $response;
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
