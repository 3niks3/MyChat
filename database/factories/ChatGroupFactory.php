<?php

namespace Database\Factories;

use App\Models\ChatGroup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ChatGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChatGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $reg_date = $this->faker->dateTimeBetween('-1 years');
        $type = ['public', 'private'];
        $key = array_rand($type);
        $type = $type[$key];

        $categories = \App\Models\Category::pluck('id');

        return [
            'name' => $this->faker->unique()->word(),
            'category' => $categories->random(),
            'type' => $type,
            'join_token' => ($type == 'private')?Str::random('15'):null,
            'created_at' => $reg_date,
            'updated_at' => $reg_date,
        ];
    }
}
