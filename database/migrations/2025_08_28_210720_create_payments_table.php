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
        Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
                $table->foreignId('job_id')->nullable()->constrained('customer_jobs')->onDelete('set null');
                $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
                $table->decimal('original_amount', 15, 2);
                $table->decimal('amount_in_business_currency', 15, 2);
                $table->string('method', 50);
                $table->date('paid_at');
                $table->text('notes')->nullable();
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
