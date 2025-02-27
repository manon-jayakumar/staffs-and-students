<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('home');
//})->name('home');
Route::get('/', [AuthController::class, 'homepage'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/test/{id}/description', [TestController::class, 'showTestDescription'])->name('test.test_description');
Route::get('/test/{id}/questions', [TestController::class, 'showQuestionsPage'])->name('test.questions');

Route::post('/check-answer', [TestController::class, 'checkAnswer']);


Route::get('/leave/request/form', [TestController::class, 'leaveRequestPage'])->name('leave.request');
Route::post('store/leave/request', [TestController::class, 'leaveRequestStore'])->name('leave.store');

Route::group(['middleware' => 'auth'], function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/test/create', [TestController::class, 'create'])->name('test.create');
    Route::post('/test/store', [TestController::class, 'store'])->name('test.store');

    Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions/store', [QuestionController::class, 'store'])->name('questions.store');

    Route::get('view/all/leave/requests', [TestController::class, 'viewAllLeaveRequests'])->name('view.all.leave.requests');
    Route::get('/leave-request/{id}', [TestController::class, 'viewLeaveRequest'])->name('view.leave.request');


});
