<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $guarded = array();


    /**
    * Список расходов у сотрудника
    */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }


    /**
    * Список клиентов у сотрудника
    */
    public function clients()
    {
        return $this->hasMany(Client::class);
    }


    // Рассчёт 3% от всех заказов клиентов этого сотрудника, за выбранный месяц
    public function monthOrders($employee_id, $date, $total)
    {
        $month_orders = static::with([
            'expenses',
            'clients.orders' => function ($query) use ($date) {
                $query->whereYear('purchase_at', $date->year)->whereMonth('purchase_at', $date->month);
            }
        ])->whereHas('clients.orders', function ($query) use ($date) {
            return $query->whereYear('purchase_at', $date->year)->whereMonth('purchase_at', $date->month);
        })->find($employee_id);


        if($month_orders !== null){
            foreach ($month_orders->clients as $client) {
                // Считаем % от суммы всех продаж по этому клиенту
                $total = $total + $client->orders->sum('sum') * 0.03;
            }
        }


        return $total;
    }


    // Узнаём, является ли этот сотрудник лучшим за выбранный месяц и назначаем бонус $200
    public function monthEmployees($employee_id, $date, $total)
    {
        $month_employees = static::with([
            'expenses',
            'clients.orders' => function ($query) use ($date) {
                $query->whereYear('purchase_at', $date->year)->whereMonth('purchase_at', $date->month);
            }
        ])->whereHas('clients.orders', function ($query) use ($date) {
            return $query->whereYear('purchase_at', $date->year)->whereMonth('purchase_at', $date->month);
        })->get();


        if(!$month_employees->isEmpty()){
            $employee_rating = [];

            foreach ($month_employees as $employee) {
                // Стартовая сумма продаж
                $sum = 0;

                foreach ($employee->clients as $client) {
                    // Суммируем сумму всех продаж по этому клиенту
                    $sum = $sum + $client->orders->sum('sum');
                }

                // Записываем результат в массив id => sum
                $employee_rating[$employee->id] = $sum;
            }

            arsort($employee_rating);

            // Если этот сотрудник на вершине массива, он лучший за этот месяц, назначаем бонус $200
            if(array_key_first($employee_rating) == $employee_id){
                $total = $total + 200;
            }
        }


        return $total;
    }


    // Узнаём, есть ли у сторудника больше 30 постоянных клиентов, которые совершили более 2-х покупок и назначаем квартальный бонус $300
    public function regularClients($employee_id, $date, $total)
    {
        $regular_clients = static::with([
            'expenses',
            'clients' => function ($query) {
                $query->withCount('orders');
            }
        ])->find($employee_id);


        // Если это квартал
        if ($date->month % 3 === 0 && $regular_clients !== null) {
            // Если количество постоянных клиентов больше 30, назначаем бонус $300
            if(count($regular_clients->clients->where('orders_count', '>', 1)) > 30){
                $total = $total + 300;
            }
        }


        return $total;
    }


    // Рассчёт доходов компании за выбранный период
    public function betweenMonthOrders($from_date, $to_date)
    {
        // Итоговая сумма доходов
        $total = 0;

        $between_month_orders = static::with([
            'clients.orders' => function ($query) use ($from_date, $to_date) {
                $query->whereBetween('purchase_at', [$from_date, $to_date]);
            }
        ])->whereHas('clients.orders', function ($query) use ($from_date, $to_date) {
            return $query->whereBetween('purchase_at', [$from_date, $to_date]);
        })->get();


        if(!$between_month_orders->isEmpty()){
            foreach ($between_month_orders as $employee) {
                foreach ($employee->clients as $client) {
                    // Считаем сумму всех продаж по этому сотруднику
                    $total = $total + $client->orders->sum('sum');
                }
            }
        }


        return $total;
    }


    // Рассчёт расходов компании за выбранный период
    public function betweenMonthConsumption($from_date, $to_date)
    {
        // Итоговая сумма расходов
        $total = 0;

        $between_month_сonsumption = static::with([
            'expenses' => function ($query) use ($from_date, $to_date) {
                $query->whereBetween('purchase_at', [$from_date, $to_date]);
            }
        ])->whereHas('expenses', function ($query) use ($from_date, $to_date) {
            return $query->whereBetween('purchase_at', [$from_date, $to_date]);
        })->get();


        if(!$between_month_сonsumption->isEmpty()){
            foreach ($between_month_сonsumption as $employee) {
                // Считаем сумму всех расходов по этому сотруднику
                $total = $total + $employee->expenses->sum('sum');
            }
        }


        return $total;
    }


    // Рассчёт прибыли компании за выбранный период
    public function betweenMonthProfit($from_date, $to_date)
    {
        // Итоговая сумма прибыли. Доход минус Расход
        return $this->betweenMonthOrders($from_date, $to_date) - $this->betweenMonthConsumption($from_date, $to_date);
    }


 	public function storeData($input)
    {
    	return static::create($input);
    }


    public function updateData($id, $input)
    {
        $data = static::find($id);

        if($data !== null){
            return $data->update($input);
        }
        
        return false;
    }


    public function deleteData($id)
    {   
        $data = static::find($id);

        if($data !== null){
            return $data->delete();
        }
        
        return false;
    }
}
