<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $activities = [
            'Yoga',
            'Swimming',
            'Boxing',
            'Weightlifting',
            'Pilates',
            'Cardio',
        ];

        foreach ($activities as $activity) {
            DB::table('activities')->updateOrInsert(
                ['activity_name' => $activity],
                ['activity_name' => $activity]
            );
        }
    }
}
