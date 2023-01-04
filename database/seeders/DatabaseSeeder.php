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
use App\Models\Menu;
use App\Models\Selected_menu;
use App\Models\Alergen;
use App\Models\Menu_has_alergen;
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

        // generacija alergenov
        Alergen::factory(10)->create();

        //generacija kategorij
        $categories = ['Hitra hrana', 'Jedi z žara', 'Morske jedi', 'Veganske jedi', 'Solatne jedi'];
        for ($i=0; $i < count($categories); $i++) { 
            Category::factory(1)->create([
                'name' => $categories[$i]
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
            for ($i=0; $i < rand(5,7); $i++) { 
                Per_day_schedule::factory(1)->create([
                    'id_restaurant' => $restaurant->id_restaurant,
                    'day' => $days[$i],
                ]);
            }

            //generacija miz
            Table::factory(rand(1,2))->create([
                'id_restaurant' => $restaurant->id_restaurant
            ]);

            //generacija restavracija ima menije
            Menu::factory(rand(8,15))->create([
                'id_restaurant' => $restaurant->id_restaurant,
                'id_category' => Category::all()->random()->id_category
            ]);

            //generacija rezervacij
            Reservation::factory(rand(0,5))->create([
                //generacija rezerviranuh tabel
                'id_table' => Table::factory()->create([
                    'id_restaurant' => $restaurant->id_restaurant
                ]),
                'number_of_personel' => rand(1,20)
            ])->each(function ($reservation) use ($restaurant) {
                //generacija izbranih menijev v rezervaciji za vsako osebo
                Selected_menu::factory($reservation->number_of_personel)->create([
                    'id_reservation' => $reservation->id_reservation,
                    'id_menu' => Menu::where('id_restaurant', $restaurant->id_restaurant)->inRandomOrder()->first()->id_menu
                ]);
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
        for ($i=0; $i < rand(20,30); $i++) {
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

        //generacija meni ima alergene
        for ($i=0; $i < 20; $i++) { 
            Menu_has_alergen::firstOrCreate([
                'id_menu' => Menu::all()->random()->id_menu,
                'id_alergen' => Alergen::all()->random()->id_alergen
            ]);
        }
    }
}
