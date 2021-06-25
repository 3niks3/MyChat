<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoriesList = [
            'S' => 'Sports',
            'L'=> 'Live',
            'G' => 'Games'
        ];

        foreach($categoriesList as $label => $name)
        {
            \App\Models\Category::create([
                'name' => $name,
                'label' =>$label,
            ]);
        }

    }
}
