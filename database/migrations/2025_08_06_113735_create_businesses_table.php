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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('business_name');
            $table->string('business_email');
            $table->string('business_phone')->nullable();
            $table->text('business_address')->nullable();
            $table->string('business_website')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('tax_id')->nullable();
            $table->json('settings')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Composite unique indexes per user for active businesses
            $table->unique(['user_id', 'business_email', 'deleted_at']);
            $table->unique(['user_id', 'business_phone', 'deleted_at']);

            // Optional indexes for faster lookup
            $table->index(['user_id', 'business_email', 'deleted_at']);
            $table->index(['user_id', 'business_phone', 'deleted_at']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
