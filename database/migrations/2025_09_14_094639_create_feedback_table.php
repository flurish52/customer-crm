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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->index();
            $table->json('modules')->nullable();
            $table->json('features')->nullable();
            $table->string('frequency')->nullable();
            $table->tinyInteger('navigation')->nullable();
            $table->tinyInteger('speed')->nullable();
            $table->text('confusing')->nullable();
            $table->text('missing_features')->nullable();
            $table->tinyInteger('satisfaction')->nullable();
            $table->tinyInteger('recommend')->nullable();
            $table->text('suggestions')->nullable();
            $table->string('business_type')->nullable();
            $table->string('team_size')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
