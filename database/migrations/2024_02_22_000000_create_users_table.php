<?php

use App\Models\User;
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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mobile', 13)->unique()->nullable();
            $table->string('email', 100)->unique()->nullable();
            $table->string('username', 100)->unique()->nullable();
            $table->string('name', 100)->nullable();
            $table->string('family', 100)->nullable();
            $table->enum('gender', User::GENDERS)->default(User::GENDER_MAN);
            $table->date('birthdate')->nullable();
            $table->unsignedBigInteger('grade_level_id')->nullable();
            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('password', 100)->nullable();
            $table->enum('role', User::ROLES)->default(User::ROLE_STUDENT);
            $table->string('avatar', 100)->nullable();
            $table->string('about_me', 250)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('grade_level_id')
                ->references('id')
                ->on('grade_levels')
                ->onDelete('cascade');

            $table->foreign('province_id')
                ->references('id')
                ->on('provinces')
                ->onDelete('cascade');

            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
