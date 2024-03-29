<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\User;
use App\Models\Thread;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThreadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Thread::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'channel_id' => Channel::factory()->create()->id,
            'title' => $this->faker->sentence(),
            'body' => $this->faker->paragraph()
        ];
    }
}
