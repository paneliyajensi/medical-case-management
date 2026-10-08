<?php

namespace Database\Seeders;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@medcase.local'],
            [
                'name' => 'Dr. Admin Case Manager',
                'password' => Hash::make('password123'),
                'role' => 'Admin',
            ]
        );

        // 2. Seed Viewer user
        $viewer = User::firstOrCreate(
            ['email' => 'viewer@medcase.local'],
            [
                'name' => 'Hospital Audit Viewer',
                'password' => Hash::make('password123'),
                'role' => 'Viewer',
            ]
        );

        // 3. Seed at least 20 dummy cases (we create 24 curated medical cases)
        $casesData = [
            [
                'case_number' => 'CASE-1001',
                'patient_reference' => 'PAT-8812',
                'surgeon_name' => 'Dr. Sarah Jenkins',
                'implant_type' => 'Titanium Hip Stem (Mod-4)',
                'status' => 'Planning',
                'priority' => 'High',
                'surgery_date' => now()->addDays(3)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1002',
                'patient_reference' => 'PAT-4421',
                'surgeon_name' => 'Dr. Michael Chen',
                'implant_type' => 'Ceramic Knee Prosthesis (CR-200)',
                'status' => 'In Review',
                'priority' => 'Medium',
                'surgery_date' => now()->addDays(7)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1003',
                'patient_reference' => 'PAT-9034',
                'surgeon_name' => 'Dr. Emily Rodriguez',
                'implant_type' => 'PEEK Spinal Interbody Cage',
                'status' => 'Approved',
                'priority' => 'High',
                'surgery_date' => now()->addDays(5)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1004',
                'patient_reference' => 'PAT-3129',
                'surgeon_name' => 'Dr. David Kim',
                'implant_type' => 'Bioresorbable Interference Screw',
                'status' => 'Completed',
                'priority' => 'Low',
                'surgery_date' => now()->subDays(4)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1005',
                'patient_reference' => 'PAT-6718',
                'surgeon_name' => 'Dr. Olivia Taylor',
                'implant_type' => 'Cranial Reconstruction Mesh',
                'status' => 'Planning',
                'priority' => 'High',
                'surgery_date' => now()->addDays(12)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1006',
                'patient_reference' => 'PAT-2415',
                'surgeon_name' => 'Dr. Robert Patel',
                'implant_type' => 'Acetabular Dual-Mobility Cup',
                'status' => 'In Review',
                'priority' => 'Medium',
                'surgery_date' => now()->addDays(14)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1007',
                'patient_reference' => 'PAT-5920',
                'surgeon_name' => 'Dr. Sarah Jenkins',
                'implant_type' => 'Reverse Shoulder Glenoid Plate',
                'status' => 'Approved',
                'priority' => 'Medium',
                'surgery_date' => now()->addDays(9)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1008',
                'patient_reference' => 'PAT-7703',
                'surgeon_name' => 'Dr. Marcus Vance',
                'implant_type' => 'Bipolar Femoral Head System',
                'status' => 'Draft',
                'priority' => 'Low',
                'surgery_date' => null,
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1009',
                'patient_reference' => 'PAT-1845',
                'surgeon_name' => 'Dr. Anita Sharma',
                'implant_type' => 'Distal Radius Locking Plate',
                'status' => 'Planning',
                'priority' => 'Medium',
                'surgery_date' => now()->addDays(18)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1010',
                'patient_reference' => 'PAT-8392',
                'surgeon_name' => 'Dr. Michael Chen',
                'implant_type' => 'Tibial Tubercle Osteotomy Wedge',
                'status' => 'Completed',
                'priority' => 'High',
                'surgery_date' => now()->subDays(10)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1011',
                'patient_reference' => 'PAT-9451',
                'surgeon_name' => 'Dr. Emily Rodriguez',
                'implant_type' => 'PEEK Spinal Interbody Cage',
                'status' => 'In Review',
                'priority' => 'High',
                'surgery_date' => now()->addDays(4)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1012',
                'patient_reference' => 'PAT-2093',
                'surgeon_name' => 'Dr. Thomas Wright',
                'implant_type' => 'Titanium Hip Stem (Mod-4)',
                'status' => 'Cancelled',
                'priority' => 'Low',
                'surgery_date' => null,
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1013',
                'patient_reference' => 'PAT-6381',
                'surgeon_name' => 'Dr. David Kim',
                'implant_type' => 'Ceramic Knee Prosthesis (CR-200)',
                'status' => 'Approved',
                'priority' => 'High',
                'surgery_date' => now()->addDays(6)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1014',
                'patient_reference' => 'PAT-7124',
                'surgeon_name' => 'Dr. Olivia Taylor',
                'implant_type' => 'Acetabular Dual-Mobility Cup',
                'status' => 'Planning',
                'priority' => 'Medium',
                'surgery_date' => now()->addDays(20)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1015',
                'patient_reference' => 'PAT-4809',
                'surgeon_name' => 'Dr. Robert Patel',
                'implant_type' => 'Bioresorbable Interference Screw',
                'status' => 'Completed',
                'priority' => 'Low',
                'surgery_date' => now()->subDays(1)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1016',
                'patient_reference' => 'PAT-3517',
                'surgeon_name' => 'Dr. Marcus Vance',
                'implant_type' => 'Cranial Reconstruction Mesh',
                'status' => 'Draft',
                'priority' => 'Medium',
                'surgery_date' => null,
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1017',
                'patient_reference' => 'PAT-5264',
                'surgeon_name' => 'Dr. Anita Sharma',
                'implant_type' => 'Reverse Shoulder Glenoid Plate',
                'status' => 'Planning',
                'priority' => 'High',
                'surgery_date' => now()->addDays(11)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1018',
                'patient_reference' => 'PAT-8902',
                'surgeon_name' => 'Dr. Sarah Jenkins',
                'implant_type' => 'Tibial Tubercle Osteotomy Wedge',
                'status' => 'In Review',
                'priority' => 'Medium',
                'surgery_date' => now()->addDays(16)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1019',
                'patient_reference' => 'PAT-1428',
                'surgeon_name' => 'Dr. Thomas Wright',
                'implant_type' => 'Titanium Hip Stem (Mod-4)',
                'status' => 'Approved',
                'priority' => 'High',
                'surgery_date' => now()->addDays(8)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1020',
                'patient_reference' => 'PAT-6750',
                'surgeon_name' => 'Dr. Elena Rostova',
                'implant_type' => 'Ceramic Knee Prosthesis (CR-200)',
                'status' => 'Completed',
                'priority' => 'Medium',
                'surgery_date' => now()->subDays(6)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1021',
                'patient_reference' => 'PAT-9204',
                'surgeon_name' => 'Dr. Michael Chen',
                'implant_type' => 'PEEK Spinal Interbody Cage',
                'status' => 'Planning',
                'priority' => 'High',
                'surgery_date' => now()->addDays(25)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1022',
                'patient_reference' => 'PAT-3841',
                'surgeon_name' => 'Dr. Emily Rodriguez',
                'implant_type' => 'Distal Radius Locking Plate',
                'status' => 'Approved',
                'priority' => 'Low',
                'surgery_date' => now()->addDays(2)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1023',
                'patient_reference' => 'PAT-7419',
                'surgeon_name' => 'Dr. David Kim',
                'implant_type' => 'Acetabular Dual-Mobility Cup',
                'status' => 'In Review',
                'priority' => 'Medium',
                'surgery_date' => now()->addDays(15)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'CASE-1024',
                'patient_reference' => 'PAT-5163',
                'surgeon_name' => 'Dr. Olivia Taylor',
                'implant_type' => 'Bipolar Femoral Head System',
                'status' => 'Completed',
                'priority' => 'High',
                'surgery_date' => now()->subDays(14)->toDateString(),
                'created_by' => $admin->id,
            ],
        ];

        foreach ($casesData as $data) {
            MedicalCase::updateOrCreate(
                ['case_number' => $data['case_number']],
                $data
            );
        }
    }
}
