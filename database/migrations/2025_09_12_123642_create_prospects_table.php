<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // owner
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('source')->nullable();
            $table->enum('status', ['new', 'contacted', 'follow_up', 'converted', 'lost'])->default('new');
            $table->softDeletes();
            $table->timestamps();

            // Composite unique indexes per user for active prospects
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
        Schema::dropIfExists('prospects');
    }
};
