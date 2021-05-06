<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'expenses';
    protected $guarded = array();


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
