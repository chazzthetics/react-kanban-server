<?php

use App\Priority;
use Illuminate\Database\Seeder;

class PrioritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $priorities = [
            [
                'color' => 'green.300',
                'name' => 'lowest',
            ],
            [
                'color' => 'green.500',
                'name' => 'low',
            ],
            [
                'color' => 'yellow.400',
                'name' => 'medium',
            ],
            [
                'color' => 'orange.400',
                'name' => 'high',
            ],
            [
                'color' => 'red.400',
                'name' => 'highest',
            ],
        ];

        foreach ($priorities as $priority) {
            Priority::create([
                'color' => $priority['color'],
                'name' => $priority['name'],
            ]);
        }
    }
}
