<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\PPDB;

class BasicFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_is_accessible()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_ppdb_submission_validation()
    {
        $response = $this->withoutMiddleware()->post('/ppdb/submit', [
            'nama_lengkap' => 'Test User',
            // Missing NIK
        ]);

        $response->assertStatus(302); // Redirect back due to validation error
    }

    public function test_admin_stats_requires_auth()
    {
        $response = $this->get('/admin/stats');
        $response->assertStatus(302); // Redirect to login
    }

    public function test_admin_stats_requires_admin_role()
    {
        $user = User::factory()->create(['role' => 'user']);
        
        $response = $this->actingAs($user)->get('/admin/stats');
        $response->assertStatus(403);
    }

    public function test_admin_stats_is_accessible_to_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/admin/stats');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'total_users',
            'total_students',
            'total_teachers',
            'total_ppdb',
            'pending_ppdb'
        ]);
    }
}
