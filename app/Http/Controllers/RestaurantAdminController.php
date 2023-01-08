<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Restaurant;
use App\Models\Reservation;
use App\Models\Table;

class RestaurantAdminController extends Controller
{
    public function index(Request $request) {
        $this->validateInteger($request);

        $id_restaurant = $request->id_restaurant;

        $tables = Table::where('id_restaurant', $id_restaurant)->pluck('id_table')->toArray();

        $restaurant = Restaurant::where('id_restaurant', $id_restaurant)->first();

        $restaurantName = $restaurant->name;
        $restaurantUser_id = $restaurant->id_user;
        
        $pastReservations = $this->getPastReservations($tables, $request->reservationOffset);
        $numOfPastReservations = $this->getNumberOfPastReservations($tables);

        $this->addDataToReservations_AdminView($pastReservations);

        $activeReservations = $this->getActiveReservations($tables, $request->reservationOffset);
        $numOfActiveReservations = $this->getNumberOfActiveReservations($tables);

        $this->addDataToReservations_AdminView($activeReservations);     

        $response = [
            'restaurant_header_image' => $this->PLACEHOLDER_IMAGES_URL . 'restaurant_header_picture.png',
            'restaurantName' => $restaurantName,
            'user_id' => $restaurantUser_id,
            'pastReservations' => $pastReservations,
            'activeReservations' => $activeReservations,
            'numOfPastReservations' => $numOfPastReservations,
            'numOfActiveReservations' => $numOfActiveReservations,
        ];

        return $response;
    }

    public function moreAdminActiveReservations(Request $request) {
        $this->validateInteger($request);

        $tables = Table::where('id_restaurant', $request->id_restaurant)->pluck('id_table')->toArray();

        $activeReservations = $this->getActiveReservations($tables, $request->reservationOffset);

        $this->addDataToReservations_AdminView($activeReservations);

        $response = [
            'activeReservations' => $activeReservations
        ];

        return $response;
    }

    public function moreAdminPastReservations(Request $request) {
        $this->validateInteger($request);

        $tables = Table::where('id_restaurant', $request->id_restaurant)->pluck('id_table')->toArray();

        $pastReservations = $this->getPastReservations($tables, $request->reservationOffset);

        $this->addDataToReservations_AdminView($pastReservations);

        $response = [
            'pastReservations' => $pastReservations
        ];

        return $response;
    }

    public function deleteAdminReservation(Request $request) {
        $this->validateInteger($request);

        Reservation::where('id_reservation', $request->id_reservation)->delete();

        $tables = Table::where('id_restaurant', $request->id_restaurant)->pluck('id_table')->toArray();

        $activeReservations = $this->getActiveReservations($tables, $request->reservationOffset);

        $numOfActiveReservations = $this->getNumberOfActiveReservations($tables);

        $this->addDataToReservations_AdminView($activeReservations);

        $response = [
            'numOfActiveReservations' => $numOfActiveReservations,
            'activeReservations' => $activeReservations
        ];

        return $response;
    }

    function getPastReservations($tables, $offset) {
        return Reservation::whereIn('id_table', $tables)
                            ->where('date_and_time_of_reservation', '<', $this->before)
                            ->orderBy('date_and_time_of_reservation', 'desc')
                            ->take($offset)
                            ->get(['id_user', 'id_reservation', 'number_of_personel', 'date_and_time_of_reservation', 'note']);
    }

    function getNumberOfPastReservations($tables) {
        return Reservation::whereIn('id_table', $tables)
                            ->where('date_and_time_of_reservation', '<', $this->before)
                            ->count();
    }

    function getActiveReservations($tables, $offset) {
        return Reservation::whereIn('id_table', $tables)
                            ->where('date_and_time_of_reservation', '>=', $this->before)
                            ->orderBy('date_and_time_of_reservation')
                            ->take($offset)
                            ->get(['id_user', 'id_reservation', 'number_of_personel', 'date_and_time_of_reservation', 'note']);
    }

    function getNumberOfActiveReservations($tables) {
        return Reservation::whereIn('id_table', $tables)
                            ->where('date_and_time_of_reservation', '>=', $this->before)
                            ->count();
    }
}
