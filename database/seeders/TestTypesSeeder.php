<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TestType;

class TestTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $testTypes = [
            [
                'name' => 'Oficiales superiores armas',
                'state' => true,
            ],
            [
                'name' => 'Oficiales subalternos armas',
                'state' => true,
            ],
            [
                'name' => 'Suboficiales de servicios',
                'state' => true,
            ],
            [
                'name' => 'Suboficiales de armas',
                'state' => true,
            ],
            [
                'name' => 'Simulacros aleatorios',
                'state' => true,
            ]
        ];

        foreach ($testTypes as $testType) {
            TestType::create($testType);
        }
    }
}
