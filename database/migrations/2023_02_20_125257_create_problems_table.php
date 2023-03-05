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
        Schema::create('problems', function (Blueprint $table) {
            $table->string('problem_id')->primary();
            $table->string('author_id');
            $table->string('title');
            $table->enum('level',['easy','medium','hard','veryhard'])->default('easy');
            $table->string('description');
            $table->integer('max_point')->default(100);
            $table->json('test')->default(json_encode(array(
                array(
                    "input" => "input_example",
                    "output" => "output_example"
                )
            )));
            $table->json('test_description')->default(json_encode(array(
                array(
                    "input" => "input_testcase_explaination",
                    "output" => "output",
                )
            )));
            $table->float('runtime', 30, 10)->default(0.1);
            $table->integer('memory')->default(1024);
            $table->enum('language_acception',['cpp','c','php','py','sql','cs','java','js','node'])->default('cpp');
            $table->string('test_file_path',500);
            $table->json('forbidden')->default(json_encode(array(
                "val_type" => array("%?g","double"),
                "function" => array("max","min"),
                "lib" => array("bits/stdc++.h")
            )));
            $table->json('score_calculator')->default(json_encode(array()));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('problems');
    }
};
