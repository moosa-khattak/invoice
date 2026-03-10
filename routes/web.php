<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UserController;

Route::middleware("auth")->group(function () {

    Route::controller(InvoiceController::class)->group(function () {

        Route::get('/', 'create')->name('invoice.create');
        Route::post('/invoice', 'store')->name('invoice.store');
        Route::get("/allinvoices", "index")->name("allinvoices");
        Route::get("/invoice/{id}", "show")->name("invoice.show");
        Route::get("/invoice/{id}/edit", "edit")->name("invoice.edit");
        Route::put('/invoice/{id}', 'update')->name('invoice.update');
        Route::get("/invoice/{id}/delete", "destroy")->name("invoice.delete");
        Route::post("/invoice/{id}/mark-as-paid", "markAsPaid")->name("invoice.mark_as_paid");
    });

    Route::controller(StripeController::class)->group(function () {
        // Payment process
        Route::get('/invoice/{id}/payment', 'payment')->name('invoice.payment');

        Route::post('/invoice/{id}/payment/process', 'processPayment')->name('invoice.payment.process');
    });




    // for downlode pdf 
    Route::get("/invoice/{id}/pdf", [PDFController::class, "downloadPdf"])->name("invoice.pdf");


    Route::controller(UserController::class)->group(function () {

        Route::post("/userregistersave",  "register")->name("register.save");
        Route::post("loginMatch",  "login")->name("login.save");

        // logout 
        Route::post("logout",  "logout")->name("logout");

        // google login 
        Route::get("googlelogin",  "googlelogin")->name("login.google");
        Route::get("auth/google/callback",  "googleCallback")->name("google.callback");

        // github login 
        Route::get("githublogin",  "githublogin")->name("login.github");

        Route::get("auth/github/callback",  "githubCallback")->name("github.callback");
    });

    Route::view("register", "register")->name("register");
    Route::view("login", "login")->name("login");
});
