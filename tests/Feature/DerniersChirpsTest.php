<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Chirp;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DerniersChirpsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_seuls_les_chirps_recents_sont_affiches()
{
    $utilisateur = User::factory()->create();

    // Création d'un chirp vieux de 10 jours
    Chirp::factory()->create(['user_id' => $utilisateur->id, 'created_at' => now()->subDays(10)]);

    // Création d'un chirp vieux de 3 jours
    Chirp::factory()->create(['user_id' => $utilisateur->id, 'created_at' => now()->subDays(3)]);

    $this->actingAs($utilisateur);

    $reponse = $this->get('/chirps');

    // Vérifie que le chirp créé il y a 3 jours apparaît
    $reponse->assertSee('3 days ago');



}

}