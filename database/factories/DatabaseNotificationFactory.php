<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DatabaseNotificationFactory extends Factory
{
    protected $model = \Illuminate\Notifications\DatabaseNotification::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'type' => 'App\Notifications\ThreadWasUpdated',
            'notifiable_id' => function () {
                return 1;
            },
            'notifiable_type' => 'App\Models\User',
            'data' => ['foo' => 'bar']
        ];
    }
}
