<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [UserController::class,'index'])->name('index');
Route::post('/login', [UserController::class,'adminLogin'])->name('admin-login');
Route::get('/login', [UserController::class,'adminLoginForm'])->name('admin-login-form');
Route::get('/logout', [UserController::class,'adminLogout'])->name('admin-logout');

Route::name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class,'dashboard'])->name('dashboard');

    //    =================================================== Boat Type Start==============================================
    Route::get('/boatTypes', [BoatTypeController::class,'index'])->name('boatTypes.index');
    Route::post('/boatTypes', [BoatTypeController::class,'store'])->name('boatTypes.store');
    Route::delete('/boatTypes/{boatType}', [BoatTypeController::class,'destroy'])->name('boatTypes.destroy');
    Route::get('/boatTypes/{boatType}/edit', [BoatTypeController::class,'edit'])->name('boatTypes.edit');
    Route::get('/boatTypes/create', [BoatTypeController::class,'create'])->name('boatTypes.create');
    Route::put('/boatTypes/{boatType}', [BoatTypeController::class,'update'])->name('boatTypes.update');
    //    =================================================== Boat Type end =================================================


    //    =================================================== Boat Start==============================================
    Route::post('/update-boat', [BoatController::class,'updateBoat'])->name('update-boat');
    Route::get('/remove-boat-info', [BoatController::class,'removeBoatInfo'])->name('remove-boat-info');
    Route::get('/approve-boat', [BoatController::class,'approveBoat'])->name('approve-boat');
    Route::get('/boat-default-image', [BoatController::class,'defualtBoatImage'])->name('boat-default-image');
    
    Route::get('/boat/{type}', [BoatController::class,'index'])->name('boats.index');
    Route::get('/boat/{uuid}/show', [BoatController::class,'show'])->name('boats.show');
    Route::put('/boat/{type}', [BoatController::class,'update'])->name('boats.update');
    Route::get('/boat/booking/{uuid}/show', [BoatController::class,'boatBookingList'])->name('boatBooking.show');
    Route::post('/boat/booking/withdraw', [BoatController::class,'boatBookingWithdraw'])->name('boatBooking.withdraw');

    //    =================================================== Boat Start==============================================




    //    =================================================== Booking Start =================================================

    Route::get('/bookings/{type}', [BookingController::class,'index'])->name('bookings.index');
    Route::get('/bookings/{uuid}/edit/{type}', [BookingController::class,'edit'])->name('bookings.edit');

    //    =================================================== Booking End =================================================

    //    =================================================== Boat Owner Start =================================================
    Route::get('/boatOwners/{type?}', [BoatOwnerController::class,'index'])->name('boatOwners.index');
    Route::get('/boatOwners/{uuid}/boats', [BoatOwnerController::class,'boats'])->name('boatOwners.boats');
    Route::get('/boatOwners/{uuid}/available-transactions', [WithdrawController::class,'availeableTransactions'])->name('boatOwners.availableTransactions');
    Route::post('/boatOwners/save-available-transaction', [WithdrawController::class,'saveAvaileableTransactions'])->name('boatOwners.saveAvaileableTransactions');
    Route::get('/transferred/payment/detail/{uuid}', [WithdrawController::class,'transferPaymentDetail'])->name('transferPayments.details');
    //    =================================================== Boat Owner End =================================================

    //    =================================================== Customer Start =================================================
    Route::get('/customers/{type?}', [CustomerController::class, 'index'])->name('customers.index');
    //    =================================================== Customer End =================================================

    //    =================================================== Customer Start =================================================
    Route::get('/posts/{type}', [BoatPostController::class, 'index'])->name('posts.index');
    //    Route::get('/posts/reported', [BoatPostController::class, 'reported'])->name('posts.reported');
    Route::put('/posts/reported/{reported_post_uuid}', [BoatPostController::class, 'update'])->name('posts.update');
    //    =================================================== Customer End =================================================

    //    =================================================== Revenue Start =================================================
    Route::get('/revenues/earning', [AccountController::class, 'earning'])->name('revenues.earning');

    //    =================================================== Revenue End =================================================



    //    =================================================== Customer Start =================================================
    Route::get('/messageCodes', [UserController::class,'messageCodes'])->name('messageCodes');
    Route::get('/settings', [SettingConroller::class, 'index'])->name('settings.index');
    Route::get('/settings/create', [SettingConroller::class, 'create'])->name('settings.create');
    Route::post('/settings/store', [SettingConroller::class, 'store'])->name('settings.store');
    Route::get('/settings/edit/{uuid}', [SettingConroller::class, 'edit'])->name('settings.edit');
    Route::post('/settings/update/{uuid}', [SettingConroller::class, 'update'])->name('settings.update');
    //    =================================================== Customer End =================================================

    //    =================================================== Bookings and Transactions =================================================
    Route::get('/owner/booking/list/{uuid}/{type}', [BookingController::class, 'ownerBoatBookings'])->name('ownerBoatBookings.all');
    //    =================================================== Bookings and Transactions =================================================

});


Route::get('install-app', function(){
    return view('install_app');
});

Route::post('/bounce-and-complaints', [UserController::class,'bounceAndComplaints'])->name('bounce-and-complaints');
