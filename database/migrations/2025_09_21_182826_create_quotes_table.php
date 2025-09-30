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
            Schema::create('quotes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('client_id')->constrained('customers')->onDelete('cascade'); // from customers
                $table->foreignId('job_id')->nullable()->constrained('customer_jobs'); // from customer_jobs
                $table->string('quote_number')->unique();
                $table->string('title');
                $table->text('description')->nullable();
                $table->json('business_snapshot')->nullable();
                $table->json('client_snapshot')->nullable();
                $table->decimal('subtotal', 15, 2)->default(0);
                $table->decimal('tax', 15, 2)->default(0);
                $table->decimal('discount', 15, 2)->default(0);
                $table->decimal('total', 15, 2)->default(0);
                $table->string('currency', 10)->default('USD');
                $table->foreignId('business_id')->constrained()->onDelete('cascade');
                $table->text('terms')->nullable();
                $table->enum('status', ['draft', 'sent', 'accepted', 'rejected', 'expired'])->default('draft');
                $table->date('due_date')->nullable();
                $table->string('pdf_path')
                    ->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
