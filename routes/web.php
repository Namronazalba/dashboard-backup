<?php
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('index');
// });

Route::controller(DashboardController::class)->group(function(){
    Route::get('/','dashboard')->name('dashboard')->middleware('auth');
    Route::get('/troubleshooted','troubleshooted')->name('load_card');
    Route::get('/modal_content','modalcard')->name('load_modal_content');
    Route::get('/modal_fixed_table','modalfixedtable')->name('modal_fixed_table');
    Route::get('/modal_standby_table','modalstandbytable')->name('modal_standby_table');
    Route::get('/modal_breakdown_table','modalbreakdowntable')->name('modal_breakdown_table');
    Route::get('/modal_decommissioned_table','modaldecommissionedtable')->name('modal_decommissioned_table');
});

//standard routing
// Route::get('/index', [UserController::class, 'index'])->name('index')->middleware('auth');
// Route::get('/login',[UserController::class,'login'])->name('login')->middleware('guest');
// Route::get('/register',[UserController::class,'register'])->name('register')->middleware('guest');
// Route::post('/loginProcess',[UserController::class,'loginPost'])->name('login.post');
// Route::post('/registerProcess',[UserController::class,'registerPost'])->name('register.post');
// Route::post('/logout',[UserController::class,'logout']);

// optimized routing
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [UserController::class, 'login'])->name('login');
    Route::get('/register', [UserController::class, 'register'])->name('register');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user_index', [UserController::class, 'user_index'])->name('index');
    Route::post('/logout', [UserController::class, 'logout']);
});
Route::post('/registerProcess', [UserController::class, 'registerPost'])->name('register.post');
Route::post('/loginProcess', [UserController::class, 'loginPost'])->name('login.post');
