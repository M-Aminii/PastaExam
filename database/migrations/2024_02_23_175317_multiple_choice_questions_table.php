<?php

use App\Models\MultipleChoiceQuestion;
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
        Schema::create('multiple_choice_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('grade_level_id');
            $table->unsignedBigInteger('grade_id');
            $table->unsignedBigInteger('field_id')->nullable();
            $table->unsignedBigInteger('textbook_id');
            $table->unsignedBigInteger('topic_id');
            $table->string('source')->nullable();
            $table->enum('direction', MultipleChoiceQuestion::Direction)->nullable()->default(MultipleChoiceQuestion::Direction_Right);
            $table->enum('difficulty_level',MultipleChoiceQuestion::LEVEL);// سطح سختی
            $table->string('question_text');
            $table->string('option1');
            $table->string('option2');
            $table->string('option3');
            $table->string('option4');
            $table->string('correct_option');
            $table->string('Descriptive_answer')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();


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

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multiple_choice_questions');
    }
};
