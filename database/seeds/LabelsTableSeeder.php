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
        $labels = ['green', 'yellow', 'orange', 'red', 'purple', 'blue'];

        foreach ($labels as $label) {
            Label::create([
                'color' => $label,
            ]);
        }
    }
}
