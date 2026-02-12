<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InvoiceController;
Route::controller(InvoiceController::class)->group(function(){

    Route::get('/', 'create')->name('invoice.create');
    Route::post('/invoice', 'store')->name('invoice.store');
    Route::get("/allinvoices" , "index")->name("allinvoices");
    Route::get("/invoice/{id}", "show")->name("invoice.show");
    Route::get("/invoice/{id}/edit", "edit")->name("invoice.edit");
    Route::put('/invoice/{id}', 'update')->name('invoice.update');
    Route::get("/invoice/{id}/delete", "destroy")->name("invoice.delete");
    // for downlode pdf 
    Route::get("/invoice/{id}/pdf", "downloadPdf")->name("invoice.pdf");
});
