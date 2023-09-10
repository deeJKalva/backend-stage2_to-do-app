<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controller\TaskController;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::prefix('task')->group(function() {
    Route::get('/tasks', [TaskController::class, 'showTasks']);
    Route::post('/tasks', [TaskController::class, 'createTask']);
    Route::patch('/tasks', [TaskController::class, 'updateTask']);

    // NOTE: lanjutkan tugas assignment di routing baru dibawah ini
    Route::delete('/tasks', [TaskController::class, 'deleteTask']);
    Route::patch('/assign', [TaskController::class, 'assignTask']);
    Route::delete('/assign', [TaskController::class, 'unassignTask']);
    Route::patch('/subtasks', [TaskController::class, 'createSubtask']);
    Route::delete('/subtasks', [TaskController::class, 'deleteSubtask']);
});