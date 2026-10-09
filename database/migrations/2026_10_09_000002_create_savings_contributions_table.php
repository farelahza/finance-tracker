<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('savings_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('savings_goal_id')->constrained('savings_goals')->cascadeOnDelete();
            $table->unsignedBigInteger('amount');          // Rupiah, tanpa desimal
            $table->date('saved_at');
            $table->string('note')->nullable();
            $table->timestamps();

            $table->index(['savings_goal_id', 'saved_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('savings_contributions');
    }
};