<?php

use App\Models\Exam;
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
        Schema::create('exams', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('grade_level_id'); // کلید خارجی به جدول مقاطع تحصیلی
            $table->unsignedBigInteger('grade_id');
            $table->unsignedBigInteger('field_id')->nullable();
            $table->unsignedBigInteger('textbook_id');
            $table->unsignedBigInteger('topic_id');

            $table->unsignedBigInteger('province_id')->nullable(); // کلید خارجی به جدول استان‌ها
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('school_id')->nullable(); // کلید خارجی به جدول مدارس
            $table->string('title');
            $table->boolean('negative_mark')->default(false);// نمره منفی
            $table->dateTime('date_time')->nullable(); // زمان برگزاری


            $table->enum('difficulty_level',Exam::LEVEL);// سطح سختی

            $table->integer('duration_minutes'); // مدت آزمون به دقیقه
            $table->enum('exam_type', ['test', 'Descriptive']); // نوع آزمون
            $table->string('year')->nullable(); // سال برگزاری
            $table->string('month')->nullable(); // ماه برگزاری
            $table->timestamps();

            // تعریف کلید‌های خارجی
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('grade_level_id')
                ->references('id')
                ->on('grade_levels')
                ->onDelete('cascade');

            $table->foreign('grade_id')
                ->references('id')
                ->on('grades')
                ->onDelete('cascade');

            $table->foreign('field_id')
                ->references('id')
                ->on('fields')
                ->onDelete('cascade');

            $table->foreign('textbook_id')
                ->references('id')
                ->on('textbooks')
                ->onDelete('cascade');

            $table->foreign('topic_id')
                ->references('id')
                ->on('topics')
                ->onDelete('cascade');

            $table->foreign('province_id')
                ->references('id')
                ->on('provinces')
                ->onDelete('cascade');

            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->onDelete('cascade');

            $table->foreign('school_id')
                ->references('id')
                ->on('schools')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
