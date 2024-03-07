<?php

namespace Database\Seeders;

use App\Models\GradeLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (GradeLevel::count()) {
            GradeLevel::truncate();
        }

        $gradelevels = [
           'همه', 'دبستان', 'متوسطه اول', 'متوسطه دوم - نظری', 'متوسطه دوم - فنی', 'متوسطه دوم - کاردانش'
        ];

        foreach ($gradelevels as $gradelevel) {
            GradeLevel::create([
                'name' => $gradelevel,
            ]);
            $this->command->info('add grade_levels to database');
        }
    }
}
