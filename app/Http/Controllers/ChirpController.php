<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\Chirp;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {

            $chirps = Chirp::with('user')
            ->where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->get();

            return view('chirps.index', [
                'chirps' => $chirps,
            ]);
        // return view('chirps.index', [
        //     'chirps' => Chirp::with('user')->latest()->get(),
        // ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        if ($request->user()->chirps()->count() >= 10) {
            return response()->json(['error' => 'Vous ne pouvez pas créer plus de 10 chirps.'], 403);
        }
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $chirp = $request->user()->chirps()->create($validated);

        //return redirect(route('chirps.index'));

        return response()->json($chirp, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp): View
    {
        Gate::authorize('update', $chirp);

        return view('chirps.edit', [
            'chirp' => $chirp,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp): JsonResponse
    {
        Gate::authorize('update', $chirp);

        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $chirp->update($validated);

        //return redirect(route('chirps.index'));
        return response()->json(['message' => 'Chirp updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp): RedirectResponse
    {
        Gate::authorize('delete', $chirp);

        $chirp->delete();

        return redirect(route('chirps.index'));
        //return response()->json(['message' => 'Chirp deleted'], 200);
    }

    public function like(Chirp $chirp)
{
    $user = auth()->user();

    // Vérifie si l'utilisateur a déjà liké ce chirp
    if ($chirp->likes()->where('user_id', $user->id)->exists()) {
        return response()->json(['error' => 'Already liked'], 400);
    }

    // Ajoute le like
    $chirp->likes()->create(['user_id' => $user->id]);

    return response()->json(['message' => 'Liked successfully'], 200);
}

}
