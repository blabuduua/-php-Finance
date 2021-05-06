<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Expense::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'sum' => $this->faker->numberBetween(1, 1000),
            'purchase_at' => $this->faker->dateTimeBetween('-6 month', 'now'),
            'employee_id' => Employee::all()->random()->id,
        ];
    }
}
