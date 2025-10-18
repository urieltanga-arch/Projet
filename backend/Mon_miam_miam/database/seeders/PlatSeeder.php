<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Plat;

class PlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plats =[
            [
                'name' => 'Ndole',
                'description' => 'Plat traditionnel camerounais à base de feuilles de ndole, de viande et de crevettes.',
                'price' => 1000,
                'category' => 'plat',
                'image_url' => 'https://afrocuisine.co/wp-content/uploads/2022/04/eru.jpg',

            ],
            [
                'name' => 'Poulet Rôti',
                'description' => 'Poulet rôti aux herbes avec pommes de terre et légumes de saison',
                'price' => 1200,
                'category' => 'plat',
                'image_url' => 'poulet_roti.jpg',

            ],
            [
                'name' => 'Eru',
                'description' => 'plat traditionnel',
                'price' => 1000,
                'category' => 'plat',
                'image_url' => 'https://afrocuisine.co/wp-content/uploads/2022/04/eru.jpg',

            ],


            //boissons
             [
                'name' => 'Jus planet',
                'description' => 'boisson gazeuse gout orange',
                'price' => 500,
                'category' => 'boisson',
                'image_url' => 'https://www.easy-market.net/wp-content/uploads/2021/09/jus_planet_cocktail.jpg',

            ],
            [
                'name' => 'Eau Minérale',
                'description' => 'Eau minérale naturelle 1.5l',
                'price' => 500,
                'category' => 'boisson',
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTkmFwupQ7NdInJxPXuMlkvJrRxGkyr6snNAg&s',

            ],


        ];
         DB::table('plats')->insert($plats);
        
        $this->command->info('Plats créés avec succès!');

    }
}
