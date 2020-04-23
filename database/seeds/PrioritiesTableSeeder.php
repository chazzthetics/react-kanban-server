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
        $priorities = ['low', 'lowest', 'medium', 'high', 'highest'];

        foreach ($priorities as $priority) {
            Priority::create([
                'name' => $priority,
            ]);
        }
    }
}
