<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id');
            $table->text('questions_data')->nullable();
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('exam_id')
                ->references('id')
                ->on('exam_headers')
                ->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
