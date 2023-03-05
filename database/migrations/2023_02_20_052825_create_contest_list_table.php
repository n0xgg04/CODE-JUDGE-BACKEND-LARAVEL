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
        Schema::create('contest_list', function (Blueprint $table) {
            $table->string('contest_id')->primary();
            $table->string('name');
            $table->enum('type',['oi','acm']);
            $table->string('language')->default('{"cpp","c"}');
            $table->datetime('start_at')->default(now());
            $table->datetime('end_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contest_list');
    }
};
