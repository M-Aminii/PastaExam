<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Field::count()) {
            Field::truncate();
        }

        $fields = [
            ['name' => 'همه', 'grade_level_id' => 1],
            ['name' => 'علوم ریاضی', 'grade_level_id' => 3],
            ['name' => 'علوم تجربی', 'grade_level_id' => 3],
            ['name' => 'ادبیات و علوم انسانی', 'grade_level_id' => 3],
            ['name' => 'علوم و معارف اسلامی', 'grade_level_id' => 3],

        ];

        foreach ($fields as $field) {
            Field::create($field);
            $this->command->info('add Field to database');
        }
    }
}
