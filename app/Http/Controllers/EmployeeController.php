<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Рассчёт заработной платы сотрудника за выбранный месяц
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function employeePayment(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required|integer',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json( ['errors' => $validator->errors()->all()] );
        }

        $date = Carbon::createFromFormat('Y-m-d', $request->date, 'Europe/Kiev'); 
        $toDayMonth = Carbon::now();

        if($date->month > $toDayMonth->month && $date->year >= $toDayMonth->year){
            return response()->json( ['error' => 'Заказов не найдено'] );
        }

        // Фиксированная ставка $500
        $total = 500;

        $employee = Employee::find($request->id);

        if($employee !== null){
            // Считаем 3% от всех заказов клиентов этого сотрудника, за выбранный месяц
            $total = $employee->monthOrders($request->id, $date, $total);

            // Узнаём, является ли этот сотрудник лучшим за выбранный месяц и назначаем бонус $200
            $total = $employee->monthEmployees($request->id, $date, $total);

            // Узнаём, есть ли у сторудника больше 30 постоянных клиентов, которые совершили более 2-х покупок и назначаем квартальный бонус $300
            $total = $employee->regularClients($request->id, $date, $total);


            return response()->json( ['employeePayment' => $total] );
        }else{
            return response()->json( ['error' => 'Сотрудник не найден'] );
        }
    }


    /**
     * Рассчёт доходов компании за выбранный период. 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function companyIncome(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json( ['errors' => $validator->errors()->all()] );
        }

        $from_date = Carbon::createFromFormat('Y-m-d', $request->from_date, 'Europe/Kiev')->startOfMonth();
        $to_date = Carbon::createFromFormat('Y-m-d', $request->to_date, 'Europe/Kiev')->endOfMonth(); 

        $employee = new Employee;

        // Рассчёт доходов компании за выбранный период
        $total = $employee->betweenMonthOrders($from_date, $to_date);


        return response()->json( ['companyIncome' => $total] );
    }


    /**
     * Рассчёт расходов компании за выбранный период. 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function companyConsumption(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json( ['errors' => $validator->errors()->all()] );
        }

        $from_date = Carbon::createFromFormat('Y-m-d', $request->from_date, 'Europe/Kiev')->startOfMonth();
        $to_date = Carbon::createFromFormat('Y-m-d', $request->to_date, 'Europe/Kiev')->endOfMonth(); 

        $employee = new Employee;

        // Рассчёт расходов компании за выбранный период
        $total = $employee->betweenMonthConsumption($from_date, $to_date);


        return response()->json( ['companyConsumption' => $total] );
    }


    /**
     * Рассчёт прибыли компании за выбранный период. 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function companyProfit(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json( ['errors' => $validator->errors()->all()] );
        }

        $from_date = Carbon::createFromFormat('Y-m-d', $request->from_date, 'Europe/Kiev')->startOfMonth();
        $to_date = Carbon::createFromFormat('Y-m-d', $request->to_date, 'Europe/Kiev')->endOfMonth(); 

        $employee = new Employee;

        // Рассчёт прибыли компании за выбранный период
        $total = $employee->betweenMonthProfit($from_date, $to_date);


        return response()->json( ['companyProfit' => $total] );
    }


    /**
     * Список всех Сотрудников
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json( Employee::all() );
    }


    /**
     * C - Создать Сотрудника
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'fio' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json( ['errors' => $validator->errors()->all()] );
        }

        $employee = new Employee;
        $employee->storeData( $request->all() );

        return response()->json( ['success' => 'Сотрудник успешно добавлен'] );
    }


    /**
     * R - Показать Сотрудника
     *
     * @param  \App\Models\Employee int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json( Employee::find($id) );
    }


    /**
     * U - Обновить Сотрудника
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'fio' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return response()->json( ['errors' => $validator->errors()->all()] );
        }

        $employee = new Employee;
        $response = $employee->updateData( $id, $request->all() );

        $answer = $this->checkResponse($response, 'обновлён');

        return response()->json( $answer );
    }


    /**
     * D - Удалить Сотрудника
     *
     * @param  \App\Models\Employee int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = new Employee;
        $response = $employee->deleteData($id);

        $answer = $this->checkResponse($response, 'удалён');

        return response()->json( $answer );
    }


    /**
     * Проверка ответа на обновление или удаление
     *
     * @param  $response boolean
     * @param  $process string
     * @return array
     */
    public function checkResponse($response, $process)
    {
        if($response){
            return ['success' => 'Сотрудник успешно ' . $process];
        }else{
            return ['error' => 'Сотрудник не найден'];
        }
    }
}
