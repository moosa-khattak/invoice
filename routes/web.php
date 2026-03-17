<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\RefundedController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware("auth")->group(function () {
    // Invoice Management
    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/', 'create')->name('invoice.create');
        Route::post('/invoice', 'store')->name('invoice.store');
        Route::get("/allinvoices", "index")->name("allinvoices");
        Route::get("/invoice/{id}", "show")->name("invoice.show");
        Route::get("/invoice/{id}/edit", "edit")->name("invoice.edit");
        Route::put('/invoice/{id}', 'update')->name('invoice.update');
        Route::get("/invoice/{id}/delete", "destroy")->name("invoice.delete");
        Route::get("/invoice/{id}/cash", "cashPayment")->name("invoice.cash");
        Route::post("/invoice/{id}/cash", "processCashPayment")->name("invoice.cash.process");
        Route::get("/invoice/{id}/history", "history")->name("invoice.history");
        Route::patch("/invoice/{id}/status", "updateStatus")->name("invoice.status.update");
    });

    // Payments
    Route::controller(StripeController::class)->group(function () {
        Route::get('/invoice/{id}/payment', 'payment')->name('invoice.payment');
        Route::post('/invoice/{id}/payment/process', 'processPayment')->name('invoice.payment.process');
    });

    Route::post("/invoice/{id}/refund",[RefundedController::class,"processRefund"] )->name("invoice.refund");

    // PDF Export
    Route::get("/invoice/{id}/pdf", [PDFController::class, "downloadPdf"])->name("invoice.pdf");

    // Auth
    Route::post("logout", [UserController::class, "logout"])->name("logout");
});

// Guest Routes
Route::controller(UserController::class)->group(function () {
    Route::view("register", "register")->name("register");
    Route::view("login", "login")->name("login");

    Route::post("/userregistersave", "register")->name("register.save");
    Route::post("loginMatch", "login")->name("login.save");

    // Social Authentication
    Route::get("googlelogin", "googlelogin")->name("login.google");
    Route::get("auth/google/callback", "googleCallback")->name("google.callback");
    Route::get("githublogin", "githublogin")->name("login.github");
    Route::get("auth/github/callback", "githubCallback")->name("github.callback");
});

// Fallback Route for 404 Page Not Found
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
