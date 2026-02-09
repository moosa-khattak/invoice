<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('from');
            $table->string('bill_to');
            $table->string('ship_to')->nullable();
            $table->date('date');
            $table->date('due_date');
            $table->string('payment_terms');
            $table->string('po_number')->nullable();
            $table->string('logo_path')->nullable();
            $table->json('header_columns')->nullable(); // Stores custom column definitions
            $table->json('items')->nullable();          // Stores the rows data
            $table->text('notes')->nullable();
            $table->text('terms')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('shipping', 15, 2)->default(0);
            $table->decimal('discount_rate', 5, 2)->default(0); // Percentage
            $table->decimal('discount', 15, 2)->default(0);      // Calculated Amount
            $table->decimal('tax_rate', 5, 2)->default(0);      // Percentage
            $table->decimal('tax', 15, 2)->default(0);           // Calculated Amount
            $table->decimal('total', 15, 2)->default(0);
            $table->string('currency')->default('USD');
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('balance_due', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
