<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Chirp;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LimitChirpTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_un_utilisateur_ne_peut_pas_creer_plus_de_10_chirps()
    {
        //$utilisateur = User::factory()->create();
        $utilisateur = User::factory()->create([
            'name' => 'Nom de l\'utilisateur',  // Ajoutez le champ 'name'
            'email' => 'utilisateur@example.com',
            'password' => bcrypt('password'),
        ]);
        Chirp::factory()->count(10)->create(['user_id' => $utilisateur->id]);

        $this->actingAs($utilisateur);

        $reponse = $this->post('/chirps', [
            'message' => 'Ceci est un 11ᵉ chirp.',
        ]);

        $reponse->assertStatus(403); // Vérifie qu'une erreur est retournée
        $this->assertDatabaseCount('chirps', 10); // Assure que le total reste à 10
    }
}