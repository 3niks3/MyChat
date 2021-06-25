<?php

namespace Database\Factories;

use App\Models\ChatMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatMessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChatMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $reg_date = $this->faker->dateTimeBetween('-1 years');
        $users_id = \App\Models\User::pluck('id')->random();

        return [
            'user_id' => $users_id,
            'chat_group_id' => \App\Models\ChatGroup::factory(),
            'content' => $this->faker->sentence(rand(1,20)),
            'type' => 'message',
            'created_at' => $reg_date,
            'updated_at' => $reg_date,
        ];
    }
}
