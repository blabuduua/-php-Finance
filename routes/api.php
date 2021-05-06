<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ExpenseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// POST
Route::post( 'employeePayment', [EmployeeController::class, 'employeePayment'] )->name('employeePayment');

Route::post( 'companyIncome', [EmployeeController::class, 'companyIncome'] )->name('companyIncome');

Route::post( 'companyConsumption', [EmployeeController::class, 'companyConsumption'] )->name('companyConsumption');

Route::post( 'companyProfit', [EmployeeController::class, 'companyProfit'] )->name('companyProfit');

// RESOURCES
Route::resource('employees', EmployeeController::class);

Route::resource('clients', ClientController::class);

Route::resource('orders', OrderController::class);

Route::resource('expenses', ExpenseController::class);