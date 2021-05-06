<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Список всех Заказов
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json( Order::all() );
    }

    /**
     * C - Создать Заказ
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Order $order)
    {
        $validator = \Validator::make($request->all(), [
            'sum' => 'required|integer',
            'employee_id' => 'required|integer',
            'purchase_at' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json( ['errors' => $validator->errors()->all()] );
        }

        $order->storeData( $request->all() );

        return response()->json( ['success'=>'Заказ успешно добавлен'] );
    }

    /**
     * R - Показать Заказ
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json( Order::find($id) );
    }

    /**
     * U - Обновить Заказ
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		$validator = \Validator::make($request->all(), [
            'sum' => 'required|integer',
            'employee_id' => 'required|integer',
            'purchase_at' => 'required|date',
        ]);
        
        if ($validator->fails()) {
            return response()->json( ['errors' => $validator->errors()->all()] );
        }

        $order = new Order;
        $order->updateData( $id, $request->all() );

        return response()->json( ['success'=>'Заказ успешно обновлён'] );
    }

    /**
     * D - Удалить Заказ
     *
     * @param  \App\Models\Order int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$order = new Order;
        $order->deleteData($id);

        return response()->json( ['success'=>'Заказ успешно удалён'] );
    }
}
