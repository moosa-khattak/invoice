<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_meta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->string('from')->nullable();
            $table->string('bill_to')->nullable();
            $table->string('ship_to')->nullable();
            $table->string('po_number')->nullable();
            $table->string('logo_path')->nullable();
            $table->text('notes')->nullable();
            $table->text('terms')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_meta');
    }
};
