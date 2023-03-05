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
        Schema::create('submissions', function (Blueprint $table) {
            $table->bigInteger('submission_id')->increment();
            $table->string('problem_id');
            $table->string('from_user');
            $table->string('language')->default('cpp');
            $table->integer('point')->default(0);
            $table->string('memory')->default('0KB');
            $table->double('time',8,5)->default(0);
            $table->enum('time',['wt','ac','wa','pa','ce','rte','tle','mle','uk'])->default('wt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
