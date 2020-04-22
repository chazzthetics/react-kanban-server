<?php

use App\Label;
use Illuminate\Database\Seeder;

class LabelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $labels = ['blue', 'green', 'yellow', 'purple', 'red'];

        foreach ($labels as $label) {
            Label::create([
                'color' => $label,
            ]);
        }
    }
}
