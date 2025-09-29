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
        Schema::create('activity_with_clients', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('customer_id')->constrained()->onDelete('cascade');
                $table->foreignId('job_id')->nullable()->constrained('customer_jobs')->onDelete('cascade');
                $table->enum('type', ['call', 'email', 'meeting', 'note']);
                $table->text('content')->nullable();
                $table->timestamp('activity_date');
                $table->softDeletes();
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_with_clients');
    }
};
