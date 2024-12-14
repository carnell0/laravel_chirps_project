<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Chirp;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_un_utilisateur_ne_peut_pas_modifier_le_chirp_d_un_autre()
    {
        $utilisateur1 = User::factory()->create();
        $utilisateur2 = User::factory()->create();

        $chirp = Chirp::factory()->create(['user_id' => $utilisateur1->id]);

        $this->actingAs($utilisateur2);

        $reponse = $this->patch("/chirps/{$chirp->id}", [
            'message' => 'Message modifié par un autre utilisateur',
        ]);

        $reponse->assertStatus(403); // Vérifie que l'utilisateur reçoit une erreur d'autorisation.
        $this->assertDatabaseHas('chirps', [
            'id' => $chirp->id,
            'message' => $chirp->message, // Le message d'origine reste inchangé.
        ]);
    }

    public function test_un_utilisateur_ne_peut_pas_supprimer_le_chirp_d_un_autre()
    {
        $utilisateur1 = User::factory()->create();
        $utilisateur2 = User::factory()->create();

        $chirp = Chirp::factory()->create(['user_id' => $utilisateur1->id]);

        $this->actingAs($utilisateur2);

        $reponse = $this->delete("/chirps/{$chirp->id}");

        $reponse->assertStatus(403); // Vérifie que l'utilisateur reçoit une erreur d'autorisation.
        $this->assertDatabaseHas('chirps', [
            'id' => $chirp->id, // Le "chirp" existe toujours dans la base de données.
        ]);
    }
}