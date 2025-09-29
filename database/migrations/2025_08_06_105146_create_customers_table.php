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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('company');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['user_id', 'email', 'deleted_at']);
            $table->unique(['user_id', 'phone', 'deleted_at']);

            // Optional indexes for faster lookup
            $table->index(['user_id', 'email', 'deleted_at']);
            $table->index(['user_id', 'phone', 'deleted_at']);
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
