<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InvoiceController;

Route::get('/', [InvoiceController::class, 'create'])->name('invoice.create');
Route::post('/invoice', [InvoiceController::class, 'store'])->name('invoice.store');

Route::get("/allinvoices" , [InvoiceController::class, "index"])->name("allinvoices");
Route::get("/invoice/{id}", [InvoiceController::class, "show"])->name("invoice.show");
Route::get("/invoice/{id}/delete", [InvoiceController::class, "destroy"])->name("invoice.delete");
