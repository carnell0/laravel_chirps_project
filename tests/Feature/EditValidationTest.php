<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Chirp;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditValidationTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    public function test_un_contenu_vide_n_est_pas_accepte_lors_de_la_mise_a_jour()
    {
        $utilisateur = User::factory()->create();
        $chirp = Chirp::factory()->create(['user_id' => $utilisateur->id]);

        $this->actingAs($utilisateur);

        $reponse = $this->patch("/chirps/{$chirp->id}", [
            'message' => '',
        ]);

        $this->assertDatabaseHas('chirps', [
            'id' => $chirp->id,
            'message' => $chirp->message,
        ]);
    }

    public function test_un_contenu_trop_long_n_est_pas_accepte_lors_de_la_mise_a_jour()
    {
        $utilisateur = User::factory()->create();
        $chirp = Chirp::factory()->create(['user_id' => $utilisateur->id]);

        $this->actingAs($utilisateur);

        $reponse = $this->patch("/chirps/{$chirp->id}", [
            'message' => str_repeat('a', 256),
        ]);

        $reponse->assertSessionHasErrors('message');
        $this->assertDatabaseHas('chirps', [
            'id' => $chirp->id,
            'message' => $chirp->message,
        ]);
    }
}