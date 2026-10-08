<?php

namespace Tests\Feature;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CaseManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Requirement 1: Valid login succeeds.
     */
    public function test_valid_login_succeeds(): void
    {
        $user = User::factory()->create([
            'email' => 'doctor@medcase.local',
            'password' => Hash::make('secret123'),
            'role' => 'Admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'doctor@medcase.local',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Requirement 2: Invalid login is rejected.
     */
    public function test_invalid_login_is_rejected(): void
    {
        $user = User::factory()->create([
            'email' => 'doctor@medcase.local',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'doctor@medcase.local',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Requirement 3: A guest cannot open case pages or APIs (401 / redirect).
     */
    public function test_guest_cannot_open_case_pages_or_apis(): void
    {
        // Web pages redirect to /login
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/cases')->assertRedirect('/login');
        $this->get('/cases/create')->assertRedirect('/login');

        // APIs return 401 Unauthorized
        $this->getJson('/api/cases')->assertStatus(401);
        $this->postJson('/api/cases', [])->assertStatus(401);
        $this->getJson('/api/cases/1')->assertStatus(401);
    }

    /**
     * Requirement 4: Admin can create a case.
     */
    public function test_admin_can_create_a_case(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);

        // Web form submission
        $caseData = [
            'case_number' => 'CASE-TEST-001',
            'patient_reference' => 'PAT-1234',
            'surgeon_name' => 'Dr. Sarah Jenkins',
            'implant_type' => 'Titanium Hip Stem (Mod-4)',
            'status' => 'Planning',
            'priority' => 'High',
            'surgery_date' => '2026-11-20',
        ];

        $response = $this->actingAs($admin)->post('/cases', $caseData);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('cases', [
            'case_number' => 'CASE-TEST-001',
            'patient_reference' => 'PAT-1234',
            'created_by' => $admin->id,
        ]);

        // API submission with Sanctum
        Sanctum::actingAs($admin);

        $apiCaseData = [
            'case_number' => 'CASE-TEST-API-002',
            'patient_reference' => 'PAT-5678',
            'surgeon_name' => 'Dr. Michael Chen',
            'implant_type' => 'Ceramic Knee Prosthesis',
            'status' => 'In Review',
            'priority' => 'Medium',
            'surgery_date' => '2026-12-05',
        ];

        $apiResponse = $this->postJson('/api/cases', $apiCaseData);
        $apiResponse->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Case created successfully.',
            ]);
    }

    /**
     * Requirement 5: Creating a case with a duplicate Case Number fails (422).
     */
    public function test_creating_case_with_duplicate_case_number_fails(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);

        MedicalCase::factory()->create([
            'case_number' => 'CASE-DUPLICATE-999',
            'created_by' => $admin->id,
        ]);

        // Web attempt
        $duplicateData = [
            'case_number' => 'CASE-DUPLICATE-999',
            'patient_reference' => 'PAT-9999',
            'surgeon_name' => 'Dr. Emily Rodriguez',
            'implant_type' => 'PEEK Spinal Interbody Cage',
            'status' => 'Planning',
            'priority' => 'Low',
        ];

        $response = $this->actingAs($admin)->post('/cases', $duplicateData);
        $response->assertSessionHasErrors('case_number');

        // API attempt returns 422 Unprocessable Entity
        Sanctum::actingAs($admin);

        $apiResponse = $this->postJson('/api/cases', $duplicateData);
        $apiResponse->assertStatus(422)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonValidationErrors('case_number');
    }

    /**
     * Requirement 6: Viewer cannot delete a case (403).
     */
    public function test_viewer_cannot_delete_a_case(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $viewer = User::factory()->create(['role' => 'Viewer']);

        $case = MedicalCase::factory()->create([
            'case_number' => 'CASE-RESTRICTED-100',
            'created_by' => $admin->id,
        ]);

        // Web delete attempt by Viewer must be blocked with 403
        $response = $this->actingAs($viewer)->delete("/cases/{$case->id}");
        $response->assertStatus(403);

        // Case must still exist in database
        $this->assertDatabaseHas('cases', [
            'id' => $case->id,
            'case_number' => 'CASE-RESTRICTED-100',
        ]);

        // API delete attempt by Viewer must also be blocked with 403
        Sanctum::actingAs($viewer);
        $apiResponse = $this->deleteJson("/api/cases/{$case->id}");
        $apiResponse->assertStatus(403)
            ->assertJson([
                'success' => false,
            ]);

        // Case must still exist in database
        $this->assertDatabaseHas('cases', [
            'id' => $case->id,
        ]);
    }

    /**
     * Test API authentication token generation and revocation.
     */
    public function test_api_login_and_logout_flow(): void
    {
        $user = User::factory()->create([
            'email' => 'api.admin@medcase.local',
            'password' => Hash::make('tokenpass123'),
            'role' => 'Admin',
        ]);

        // Login to get token
        $loginRes = $this->postJson('/api/login', [
            'email' => 'api.admin@medcase.local',
            'password' => 'tokenpass123',
        ]);

        $loginRes->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Authenticated successfully.',
            ])
            ->assertJsonStructure([
                'data' => ['token', 'token_type', 'user' => ['id', 'name', 'email', 'role']],
            ]);

        $token = $loginRes->json('data.token');

        // Logout using Bearer token
        $logoutRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $logoutRes->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Token revoked successfully.',
            ]);
    }

    /**
     * Test admin updating a case and unique rule ignores current case.
     */
    public function test_admin_can_update_case_and_self_unique_is_ignored(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);

        $case = MedicalCase::factory()->create([
            'case_number' => 'CASE-EDIT-001',
            'status' => 'Planning',
            'created_by' => $admin->id,
        ]);

        $updateData = [
            'case_number' => 'CASE-EDIT-001', // Keeps same case number
            'patient_reference' => 'PAT-7777',
            'surgeon_name' => 'Dr. Olivia Taylor',
            'implant_type' => 'Cranial Reconstruction Mesh',
            'status' => 'Approved',
            'priority' => 'High',
            'surgery_date' => '2026-12-10',
        ];

        // Web update
        $res = $this->actingAs($admin)->put("/cases/{$case->id}", $updateData);
        $res->assertSessionHasNoErrors();
        $this->assertDatabaseHas('cases', [
            'id' => $case->id,
            'status' => 'Approved',
            'surgeon_name' => 'Dr. Olivia Taylor',
        ]);

        // API update
        Sanctum::actingAs($admin);
        $updateData['status'] = 'Completed';
        $apiRes = $this->putJson("/api/cases/{$case->id}", $updateData);
        $apiRes->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Case updated successfully.',
            ]);
        $this->assertDatabaseHas('cases', [
            'id' => $case->id,
            'status' => 'Completed',
        ]);
    }

    /**
     * Test API returns 404 for non-existent case.
     */
    public function test_api_returns_404_when_case_not_found(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        Sanctum::actingAs($admin);

        $res = $this->getJson('/api/cases/99999');
        $res->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Case not found.',
            ]);
    }

    /**
     * Test admin can delete a case.
     */
    public function test_admin_can_delete_a_case(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $case = MedicalCase::factory()->create(['created_by' => $admin->id]);

        $res = $this->actingAs($admin)->delete("/cases/{$case->id}");
        $res->assertRedirect('/cases');
        $this->assertDatabaseMissing('cases', ['id' => $case->id]);
    }
}

