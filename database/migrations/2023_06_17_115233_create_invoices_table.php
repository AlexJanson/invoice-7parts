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
            $table->string('invoice_number', 10)->unique();
            $table->date('invoice_date');
            $table->date('due_date');
            $table->string('reference', 25)->nullable();
            $table->string('term')->nullable();
            $table->enum('status', ['DRAFT', 'SENT', 'COMPLETED']);
            $table->enum('payment_status', ['UNPAID', 'PARTIALLY PAID', 'PAID']);
            $table->enum('discount_type', ['PERCENTAGE', 'FIXED']);
            $table->unsignedBigInteger('discount');
            $table->text('comments')->nullable();
            $table->foreignId('customer_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('contact_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->softDeletes();
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
