<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Chirp;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChirpLikeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_un_utilisateur_peut_liker_un_chirp()
{
    //$utilisateur = User::factory()->create();
    $utilisateur = User::factory()->create([
        'name' => 'user',  // Ajoutez le champ 'name'
        'email' => 'utilisateur@example.com',
        'password' => bcrypt('password'),
    ]);
    $chirp = Chirp::factory()->create();

    $this->actingAs($utilisateur);

    $reponse = $this->post("/chirps/{$chirp->id}/like");

    $reponse->assertStatus(200);
    $this->assertDatabaseHas('chirp_likes', [
        'chirp_id' => $chirp->id,
        'user_id' => $utilisateur->id,
    ]);
}

public function test_un_utilisateur_ne_peut_pas_liker_un_chirp_deux_fois()
{
    $utilisateur = User::factory()->create();


    $chirp = Chirp::factory()->create();

    $chirp->likes()->attach($utilisateur);

    $this->actingAs($utilisateur);

    $reponse = $this->post("/chirps/{$chirp->id}/like");

    $reponse->assertStatus(403);
    $this->assertDatabaseCount('chirp_likes', 1);
}

}