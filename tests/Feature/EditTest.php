<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Chirp;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_un_utilisateur_peut_modifier_son_chirp()
{
 $utilisateur = User::factory()->create();
 $chirp = Chirp::factory()->create(['user_id' => $utilisateur->id]);
 $this->actingAs($utilisateur);
 $reponse = $this->put("/chirps/{$chirp->id}", [
 'message' => 'Chirp modifié'
 ]);
 $reponse->assertStatus(200);
 // Vérifie si le chirp existe dans la base de donnée.
 $this->assertDatabaseHas('chirps', [
 'id' => $chirp->id,
 'message' => 'Chirp modifié',
 ]);
}

}