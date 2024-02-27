<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopicsTableSeedr extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Topic::count()) {
            Topic::truncate();
        }

        $Topics = [
            ['name' => 'همه', 'textbook_id' => 1],
        ];

        foreach ($Topics as $Topic) {
            Topic::create($Topic);
            $this->command->info('add ' . $Topic['name']);
        }
    }
}
