<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            'Swimming Pool',
            'Sauna',
            'Parking',
            'Showers',
            'Lockers',
            'Free Weights Area',
            'Women-Only Section',
            'Jacuzzi',
        ];

        foreach ($facilities as $facility) {
            DB::table('facilities')->updateOrInsert(
                ['facility_name' => $facility],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
