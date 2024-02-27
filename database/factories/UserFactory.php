<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'role' => User::ROLE_STUDENT,
            'mobile' => '+989'.$this->faker->randomNumber(4) . $this->faker->randomNumber(5),
            'email'=>$this->faker->unique()->safeEmail,
            'name'=>$this->faker->name,
            'password'=> '$2y$10$yHOVJzIYR5NRsj1JVFrKIuJ8X4JZHlW7Y7QAgRPpnd4MEp9uglwHK', // 123456
            'avatar'=> null,
        ];
    }
}
