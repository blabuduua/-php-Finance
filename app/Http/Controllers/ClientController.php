<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Список всех Клиентов
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json( Client::all() );
    }

    /**
     * C - Создать Клиента
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Client $client)
    {
        $validator = \Validator::make($request->all(), [
            'fio' => 'required|string|max:255',
            'employee_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json( ['errors' => $validator->errors()->all()] );
        }

        $client->storeData( $request->all() );

        return response()->json( ['success' => 'Клиент успешно добавлен'] );
    }

    /**
     * R - Показать Клиента
     *
     * @param  \App\Models\Client int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json( Client::find($id) );
    }

    /**
     * U - Обновить Клиента
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'fio' => 'required|string|max:255',
            'employee_id' => 'required|integer',
        ]);
        
        if ($validator->fails()) {
            return response()->json( ['errors' => $validator->errors()->all()] );
        }

        $client = new Client;
        $client->updateData( $id, $request->all() );

        return response()->json( ['success' => 'Клиент успешно обновлён'] );
    }

    /**
     * D - Удалить Клиента
     *
     * @param  \App\Models\Client int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $сlient = new Client;
        $сlient->deleteData($id);

        return response()->json( ['success' => 'Клиент успешно удалён'] );
    }
}
