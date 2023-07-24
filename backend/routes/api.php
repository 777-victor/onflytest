<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [UserController::class, 'register']);
Route::post("/login", [AuthController::class, 'login']);

Route::middleware('auth:sanctum')
    // ->controller(ExpenseController::class)
    ->group(
        function () {

            /* Expenses Requests */
            Route::get("/expenses", [ExpenseController::class, 'index']);
            Route::get("/expenses/{id}", [ExpenseController::class, 'show']);
            Route::post("/expenses", [ExpenseController::class, 'store']);
            Route::put("/expenses/{id}", [ExpenseController::class, 'update']);
            Route::delete("/expenses/{id}", [ExpenseController::class, 'destroy']);

            Route::post("/logout", [AuthController::class, 'logout']);
        }
    );
