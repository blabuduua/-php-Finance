<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Expense;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Expense::factory()->count(300)->create();
    }
}
