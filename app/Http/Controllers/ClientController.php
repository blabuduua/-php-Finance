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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'fio' => 'required|string|max:255',
            'employee_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json( ['errors' => $validator->errors()->all()] );
        }

        $client = new Client;
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
        $response = $client->updateData( $id, $request->all() );

        $answer = $this->checkResponse($response, 'обновлён');

        return response()->json( $answer );
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
        $response = $сlient->deleteData($id);

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
