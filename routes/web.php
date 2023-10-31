<?php
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

Route::get('/', function () {
    return view('index');
});

Route::controller(DashboardController::class)->group(function(){
    Route::get('/troubleshooted','troubleshooted')->name('load_card');
    Route::get('/modal_content','modalcard')->name('load_modal_content');
    Route::get('/modal_fixed_table','modalfixedtable')->name('modal_fixed_table');
    Route::get('/modal_standby_table','modalstandbytable')->name('modal_standby_table');
    Route::get('/modal_breakdown_table','modalbreakdowntable')->name('modal_breakdown_table');
    Route::get('/modal_decommissioned_table','modaldecommissionedtable')->name('modal_decommissioned_table');
});

