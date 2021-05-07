<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Expense;

class ExpenseController extends Controller
{
    /**
     * Список всех Расходов
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json( Expense::all() );
    }


    /**
     * C - Создать Расход
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'sum' => 'required|integer',
            'employee_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json( ['errors' => $validator->errors()->all()] );
        }

        $expense = new Expense;
        $expense->storeData( $request->all() );

        return response()->json( ['success' => 'Расход успешно добавлен'] );
    }


    /**
     * R - Показать Расход
     *
     * @param  \App\Models\Expense int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json( Expense::find($id) );
    }


    /**
     * U - Обновить Расход
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'sum' => 'required|integer',
            'employee_id' => 'required|integer',
        ]);
        
        if ($validator->fails()) {
            return response()->json( ['errors' => $validator->errors()->all()] );
        }

        $expense = new Expense;
        $response = $expense->updateData( $id, $request->all() );

        $answer = $this->checkResponse($response, 'обновлён');

        return response()->json( $answer );
    }


    /**
     * D - Удалить Расход
     *
     * @param  \App\Models\Expense int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expense = new Expense;
        $response = $expense->deleteData($id);

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
            return ['success' => 'Расход успешно ' . $process];
        }else{
            return ['error' => 'Расход не найден'];
        }
    }
}
