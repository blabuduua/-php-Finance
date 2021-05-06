<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Employee;

class EmployeeController extends Controller
{
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

        return response()->json( ['success'=>'Сотрудник успешно добавлен'] );
    }

    /**
     * R - Показать Сотрудника
     *
     * @param  int  $id
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

        return response()->json( ['success'=>'Сотрудник успешно обновлён'] );
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

        return response()->json( ['success'=>'Сотрудник успешно удалён'] );
    }
}
