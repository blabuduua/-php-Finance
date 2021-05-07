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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'sum' => 'required|integer',
            'client_id' => 'required|integer',
            'purchase_at' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json( ['errors' => $validator->errors()->all()] );
        }

        $order = new Order;
        $order->storeData( $request->all() );

        return response()->json( ['success' => 'Заказ успешно добавлен'] );
    }


    /**
     * R - Показать Заказ
     *
     * @param  \App\Models\Order int $id
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
            'client_id' => 'required|integer',
            'purchase_at' => 'required|date',
        ]);
        
        if ($validator->fails()) {
            return response()->json( ['errors' => $validator->errors()->all()] );
        }

        $order = new Order;
        $response = $order->updateData( $id, $request->all() );

        $answer = $this->checkResponse($response, 'обновлён');

        return response()->json( $answer );
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
        $response = $order->deleteData($id);

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
            return ['success' => 'Заказ успешно ' . $process];
        }else{
            return ['error' => 'Заказ не найден'];
        }
    }
}
