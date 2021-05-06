<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Расчёт заработной платы сотрудника за выбранный месяц
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

        // Фиксированная ставка $500
        $total = 500;

        $employee = new Employee;

        // Считаем 3% от всех заказов клиентов этого сотрудника, за выбранный месяц
        $total = $employee->month_orders($request->id, $date, $total);

        // Узнаём, является ли этот сотрудник лучшим за выбранный месяц и назначаем бонус $200
        $total = $employee->month_employees($request->id, $date, $total);

        // Узнаём, есть ли у сторудника больше 30 постоянных клиентов, которые совершили более 2-х покупок и назначаем квартальный бонус $300
        $total = $employee->regular_clients($request->id, $date, $total);


        return response()->json( ['employeePayment' => $total] );
    }

    /**
     * Расчёт дохода компании за выбранный период. 
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

        // Считаем доход компании за выбранный период
        $total = $employee->between_month_orders($from_date, $to_date);


        return response()->json( ['companyIncome' => $total] );
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
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Employee $employee)
    {
        $validator = \Validator::make($request->all(), [
            'fio' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json( ['errors' => $validator->errors()->all()] );
        }

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
        $employee->updateData( $id, $request->all() );

        return response()->json( ['success' => 'Сотрудник успешно обновлён'] );
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
        $employee->deleteData($id);

        return response()->json( ['success' => 'Сотрудник успешно удалён'] );
    }
}
