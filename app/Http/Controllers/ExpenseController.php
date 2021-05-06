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
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Expense $expense)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'sum' => 'required|integer',
            'employee_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json( ['errors' => $validator->errors()->all()] );
        }

        $expense->storeData( $request->all() );

        return response()->json( ['success'=>'Расход успешно добавлен'] );
    }

    /**
     * R - Показать Расход
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * U - Обновить Расход
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * D - Удалить Расход
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
