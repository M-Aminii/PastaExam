<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UsersTableSeeder::class);
        $this->call(GradeLevelsTableSeeder::class);
        $this->call(GradesTableSeeder::class);
        $this->call(FieldsTableSeeder::class);
        $this->call(TextBooksTableSeedr::class);
        $this->call(TopicsTableSeedr::class);
        $this->call(ProvincesTableSeeder::class);
        $this->call(CityTableSeeder::class);

    }
}
