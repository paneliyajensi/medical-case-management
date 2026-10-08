<?php

namespace Database\Factories;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalCase>
 */
class MedicalCaseFactory extends Factory
{
    protected $model = MedicalCase::class;

    protected static int $caseCounter = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $surgeons = [
            'Dr. Sarah Jenkins',
            'Dr. Michael Chen',
            'Dr. Emily Rodriguez',
            'Dr. David Kim',
            'Dr. Olivia Taylor',
            'Dr. Robert Patel',
            'Dr. Marcus Vance',
            'Dr. Anita Sharma',
            'Dr. Thomas Wright',
            'Dr. Elena Rostova',
        ];

        $implants = [
            'Titanium Hip Stem (Mod-4)',
            'Ceramic Knee Prosthesis (CR-200)',
            'PEEK Spinal Interbody Cage',
            'Bioresorbable Interference Screw',
            'Cranial Reconstruction Mesh',
            'Acetabular Dual-Mobility Cup',
            'Reverse Shoulder Glenoid Plate',
            'Bipolar Femoral Head System',
            'Distal Radius Locking Plate',
            'Tibial Tubercle Osteotomy Wedge',
        ];

        $caseNumber = sprintf('CASE-%04d', static::$caseCounter++);

        return [
            'case_number' => $caseNumber,
            'patient_reference' => 'PAT-' . fake()->numberBetween(1000, 9999),
            'surgeon_name' => fake()->randomElement($surgeons),
            'implant_type' => fake()->randomElement($implants),
            'status' => fake()->randomElement(MedicalCase::STATUSES),
            'priority' => fake()->randomElement(MedicalCase::PRIORITIES),
            'surgery_date' => fake()->optional(0.8)->dateTimeBetween('-10 days', '+45 days')?->format('Y-m-d'),
            'created_by' => User::factory(),
        ];
    }
}
