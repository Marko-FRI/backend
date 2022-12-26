<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\Restaurant_image;
use App\Models\Review;
use App\Models\Favourite;
use App\Models\Per_day_schedule;
use App\Models\Table;
use App\Models\Reservation;
use App\Models\Reservation_has_table;
use App\Models\Menu;
use App\Models\Food;
use App\Models\Restaurant_has_food;
use App\Models\Menu_has_food;
use App\Models\Drink;
use App\Models\Volume;
use App\Models\Drink_has_volume;
use App\Models\Selected_menu;
use App\Models\Alergen;
use App\Models\Food_has_alergen;
use App\Models\Restaurant_has_drink;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //generacija uporabnikov
        User::factory(15)->create();

        //generacija hrane
        Food::factory(30)->create();

        //generacija alergenov
        Alergen::factory(8)->create();

        //generacija hrana ima alergene
        for ($i=0; $i < 10; $i++) { 
            Food_has_alergen::firstOrCreate([
                'id_food' => Food::all()->random()->id_food,
                'id_alergen' => Alergen::all()->random()->id_alergen
            ]);
        }

        //generacija kategorij
        $categories = ['Hitra hrana', 'Jedi z žara', 'Morske jedi', 'Veganske jedi', 'Solatne jedi'];
        for ($i=0; $i < count($categories); $i++) { 
            Category::factory(1)->create([
                'name' => $categories[$i]
            ]);
        }

        //generacija volumnov
        $volumes = [200, 350, 500, 750, 1000];
        for ($i=0; $i < count($volumes); $i++) { 
            Volume::create([
                'value' => $volumes[$i]
            ]);
        }

        //generacija pijač
        Drink::factory(20)->create();

        //generacija pijača ima volumne 
        for ($i=0; $i < 30; $i++) {              
            do {
                $id_volume = Volume::all()->random()->id_volume;
                $id_drink = Drink::all()->random()->id_drink;

                $get_some = Drink_has_volume::where('id_volume', $id_volume)->where('id_drink', $id_drink)->count();
            } while ($get_some>0);

            Drink_has_volume::factory(1)->create([
                'id_drink' => $id_drink,
                'id_volume' => $id_volume
            ]);
        }

        //generacija restavracij
        Restaurant::factory(25)->create()->each(function ($restaurant) {
            //generacija slik
            Restaurant_image::factory(rand(0,5))->create([
                'id_restaurant' => $restaurant->id_restaurant
            ]);

            //generacija urnika dela
            $days = ['Ponedeljek', 'Torek', 'Sreda', 'Četrtek', 'Petek', 'Sobota', 'Nedelja'];
            $r = rand(5,7);
            for ($i=0; $i < $r; $i++) { 
                Per_day_schedule::factory(1)->create([
                    'id_restaurant' => $restaurant->id_restaurant,
                    'day' => $days[$i],
                ]);
            }

            //generacija miz
            Table::factory(rand(4,10))->create([
                'id_restaurant' => $restaurant->id_restaurant
            ]);

            for ($i=0; $i < rand(10,15); $i++) {
                //generacija restavracija ima hrano
                do {
                    $id_food = Food::all()->random()->id_food;
                    $get_some = Restaurant_has_food::where('id_restaurant', $restaurant->id_restaurant)->where('id_food', $id_food)->count();
                } while ($get_some>0);

                Restaurant_has_food::create([
                    'id_restaurant' => $restaurant->id_restaurant,
                    'id_food' => $id_food
                ]);

                //generacija restavracija ima pijačo
                do {
                    $id_drink_has_volume = Drink_has_volume::all()->random()->id_drink_has_volume;

                    $get_some = Restaurant_has_drink::where('id_restaurant', $restaurant->id_restaurant)->where('id_drink_has_volume', $id_drink_has_volume)->count();
                } while ($get_some>0);

                Restaurant_has_drink::create([
                    'id_restaurant' => $restaurant->id_restaurant,
                    'id_drink_has_volume' => $id_drink_has_volume
                ]);
            }

            //generacija restavracija ima menuje
            Menu::factory(rand(10,15))->create([
                'id_restaurant' => $restaurant->id_restaurant,
                'id_category' => Category::all()->random()->id_category
            ])->each(function ($menu) use($restaurant) {
                //generacija menu ima hrano
                    for ($i=0; $i < rand(3,4); $i++) {
                        do {
                            $id_restaurant_has_food = Restaurant_has_food::inRandomOrder()->where('id_restaurant', $restaurant->id_restaurant)->pluck('id_restaurant_has_food')->first();
                            
                            $get_some = Menu_has_food::where('id_menu', $menu->id_menu)->where('id_restaurant_has_food', )->count();
                        } while ($get_some>0);

                        Menu_has_food::firstOrCreate([
                            'id_menu' => $menu->id_menu,
                            'id_restaurant_has_food' => $id_restaurant_has_food
                        ]);
                    }
            });
        });

        //generacija mnenj
        for ($i=0; $i < rand(40,55); $i++) {
            do {
                $id_restaurant = Restaurant::all()->random()->id_restaurant;
                $id_user = User::all()->random()->id_user;

                $get_some = Review::where('id_restaurant', $id_restaurant)->where('id_user', $id_user)->count();
            } while ($get_some>0);
            
            Review::factory(1)->create([
                'id_restaurant' => $id_restaurant,
                'id_user' => $id_user
            ]);
        }

        //generacija najljubših
        for ($i=0; $i < rand(15,25); $i++) {
            do {
                $id_restaurant = Restaurant::all()->random()->id_restaurant;
                $id_user = User::all()->random()->id_user;

                $get_some = Favourite::where('id_restaurant', $id_restaurant)->where('id_user', $id_user)->count();
            } while ($get_some>0);
            
            Favourite::create([
                'id_restaurant' => $id_restaurant,
                'id_user' => $id_user
            ]);
        }

        //generacija rezervacij
        Reservation::factory(20)->create()->each(function ($reservation) {
            //generacija izbranih menijev v rezervaciji
            Selected_menu::factory($reservation->number_of_personel)->create([
                'id_reservation' => $reservation->id_reservation,
                'id_menu' => Menu::all()->random()->id_menu
            ]);

            //generacija rezervacija ima mize
            Reservation_has_table::factory(rand(1,2))->create([
                'id_reservation' => $reservation->id_reservation
            ]);
        });
    }
}
