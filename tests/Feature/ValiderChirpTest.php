<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ValiderChirpTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_un_chirp_ne_peut_pas_avoir_un_contenu_vide()
        {
        $utilisateur = User::factory()->create();
        $this->actingAs($utilisateur);
        $reponse = $this->post('/chirps', [
        'message' => ''
        ]);
        $reponse->assertSessionHasErrors(['message' => 'The message field is required.']);
        }
        public function test_un_chirp_ne_peut_pas_depasse_255_caracteres()
        {
        $utilisateur = User::factory()->create();
        $this->actingAs($utilisateur);
        $reponse = $this->post('/chirps', [
        'message' => str_repeat('a', 256)
        ]);
        $reponse->assertSessionHasErrors(['message' => 'The message field must not be greater than 255 characters.']);
    }

}