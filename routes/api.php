<?php

use App\Http\Controllers\PassportAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);


Route::group(['middleware' => ['auth:api', 'IsAdmin']], function () {
    Route::post('task_add', [\App\Http\Controllers\TaskController::class, 'add']);
    Route::post('task_delete/{id}', [\App\Http\Controllers\TaskController::class, 'delete']);
    Route::post('task_users/{id}', [\App\Http\Controllers\TaskController::class, 'task_users']);

    Route::post('state_add', [\App\Http\Controllers\StateController::class, 'add']);
    Route::post('priority_add', [\App\Http\Controllers\PriorityController::class, 'add']);

    Route::post('state_delete/{id}', [\App\Http\Controllers\StateController::class, 'delete']);
    Route::post('priority_delete/{id}', [\App\Http\Controllers\PriorityController::class, 'delete']);

    Route::post('task_state/{task_id}/{state_id}', [\App\Http\Controllers\TaskController::class, 'task_state']);
    Route::post('task_priority/{task_id}/{priority_id}', [\App\Http\Controllers\TaskController::class, 'task_priority']);
});

Route::middleware(['auth:api'])->group(function () {
    Route::get('task/{id}', [\App\Http\Controllers\TaskController::class, 'task']);
    Route::get('tasks', [\App\Http\Controllers\TaskController::class, 'tasks']);

    Route::get('state/{state_id}', [\App\Http\Controllers\StateController::class, 'tasks']);
    Route::get('priority/{priority_id}', [\App\Http\Controllers\PriorityController::class, 'tasks']);

    Route::get('user_tasks/{id}', [\App\Http\Controllers\TaskController::class, 'user_tasks']);
});
