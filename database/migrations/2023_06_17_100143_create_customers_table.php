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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('slug', 50);
            $table->string('email', 50);
            $table->string('phone', 25);
            $table->string('tax', 20);
            $table->string('kvk', 20)->nullable();
            $table->foreignId('shipping_address')->constrained('addresses')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('billing_address')->constrained('addresses')->cascadeOnUpdate()->restrictOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
