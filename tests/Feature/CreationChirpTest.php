<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreationChirpTest extends TestCase

{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_un_utilisateur_peut_creer_un_chirp()
{
 // Simuler un utilisateur connecté
 $utilisateur = User::factory()->create();
 $this->actingAs($utilisateur);
 // Envoyer une requête POST pour créer un chirp
 $reponse = $this->post('/chirps', [
 'message' => 'Mon premier chirp !'
 ]);
 // Vérifier que le chirp a été ajouté à la base de données
 $reponse->assertStatus(201);
 $this->assertDatabaseHas('chirps', [
 'message' => 'Mon premier chirp !',
 'user_id' => $utilisateur->id,
 ]);
}
}