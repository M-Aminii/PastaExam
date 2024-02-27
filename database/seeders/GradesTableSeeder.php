<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Grade::count()) {
            Grade::truncate();
        }

        $grades = [
            ['name' => 'همه','grade_level_id' => 1],
            ['name' => 'اول','grade_level_id' => 2],
            ['name' => 'دوم','grade_level_id' => 2 ],
            ['name' => 'سوم','grade_level_id' => 2],
            ['name' => 'چهارم', 'grade_level_id' => 2],
            ['name' => 'پنجم', 'grade_level_id' => 2],
            ['name' => 'ششم', 'grade_level_id' => 2],
            ['name' => 'هفتم', 'grade_level_id' => 3],
            ['name' => 'هشتم', 'grade_level_id' => 3],
            ['name' => 'نهم', 'grade_level_id' => 3],
            ['name' => 'دهم', 'grade_level_id' => 4],
            ['name' => 'یازدهم', 'grade_level_id' => 4],
            ['name' => 'دوازدهم', 'grade_level_id' => 4],
            ['name' => 'کنکور سراسری', 'grade_level_id' => 4],
            ['name' => 'دهم', 'grade_level_id' => 5],
            ['name' => 'یازدهم', 'grade_level_id' => 5],
            ['name' => 'دوازدهم', 'grade_level_id' => 5],
            ['name' => 'کنکور کاردانی', 'grade_level_id' => 5],
            ['name' => 'دهم', 'grade_level_id' => 6],
            ['name' => 'یازدهم', 'grade_level_id' => 6],
            ['name' => 'دوازدهم', 'grade_level_id' => 6],
            ['name' => 'کنکور کاردانی', 'grade_level_id' => 6],


        ];

        foreach ($grades as $grade) {
            Grade::create($grade);
            $this->command->info('add ' . $grade['name'] . ' grade_levels');
        }
    }
}
