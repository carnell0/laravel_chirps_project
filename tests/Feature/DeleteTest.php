<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Chirp;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_un_utilisateur_peut_supprimer_son_chirp
()
{
 $utilisateur = User::factory()->create();
 $chirp = Chirp::factory()->create(['user_id' => $utilisateur->id]);
 $this->actingAs($utilisateur);
 $reponse = $this->delete("/chirps/{$chirp->id}");
 //$reponse->assertStatus(200);
 $reponse->assertStatus(302);
    $reponse->assertRedirect(route('chirps.index'));

 $this->assertDatabaseMissing('chirps', [
 'id' => $chirp->id,
 ]);
}
}