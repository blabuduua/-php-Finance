<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $guarded = array();

    /**
    * Get the expenses for the employee.
    */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
    * Get the clients for the employee.
    */
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

 	public function storeData($input)
    {
    	return static::create($input);
    }

    public function updateData($id, $input)
    {
        return static::find($id)->update($input);
    }

    public function deleteData($id)
    {
        return static::find($id)->delete();
    }
}
