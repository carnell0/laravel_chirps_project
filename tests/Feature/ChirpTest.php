<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChirpTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $user= User::factory()->unverified()->create();
        $response = $this->actingAs($user)->get('/chirps');

        $contents=(string) $this->view('chirps.index', [
            'chirps' => $user->chirps()->latest()->get(),
        ]);
        $contents->assertSee('chirps');

        $response->assertStatus(200);
    }
}