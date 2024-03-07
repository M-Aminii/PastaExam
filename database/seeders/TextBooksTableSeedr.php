<?php

namespace Database\Seeders;

use App\Models\Textbook;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TextBooksTableSeedr extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Textbook::count()) {
            Textbook::truncate();
        }

        $Textbooks = [
            ['name' => 'همه', 'field_id' => 1],
        ];

        foreach ($Textbooks as $Textbook) {
            Textbook::create($Textbook);
            $this->command->info('add Textbook to database');
        }
    }
}
