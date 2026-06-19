<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::query()
                ->withCount(['comments', 'posts'])
                ->latest()
                ->paginate(20),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'is_admin' => ['nullable', 'boolean'],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
        ], [
            'username.regex' => 'Le pseudo ne peut contenir que des lettres minuscules, des chiffres et des underscores.',
        ]);

        $data['username'] = strtolower($data['username']);

        if ($user->id === $request->user()->id && ! ($data['is_admin'] ?? false)) {
            return back()->withErrors(['is_admin' => 'Tu ne peux pas retirer ton propre accès admin.']);
        }

        $user->update([
            'est_administrateur' => (bool) ($data['is_admin'] ?? false),
            'username' => $data['username'],
        ]);

        return back()->with('status', 'Utilisateur mis à jour.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['user' => 'Tu ne peux pas supprimer ton propre compte ici.']);
        }

        $user->delete();

        return to_route('admin.users.index')->with('status', 'Utilisateur supprimé.');
    }
}
