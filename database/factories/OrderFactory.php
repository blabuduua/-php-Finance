<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sum' => $this->faker->numberBetween(100, 3000),
            'purchase_at' => $this->faker->dateTimeBetween('-6 month', 'now'),
            'client_id' => Client::all()->random()->id,
        ];
    }
}
