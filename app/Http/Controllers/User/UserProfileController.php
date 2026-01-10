<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password; // Tambahkan ini di atas

class UserProfileController extends Controller
{
    // Mengarah ke URL: /operator/profile/index
    public function index()
    {
        return view('user.profile.index');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->mixedCase()       // Harus ada huruf besar & kecil
                    ->symbols()         // Harus ada simbol (!@#$%)
                    ->numbers()         // Harus ada angka (opsional, tapi disarankan)
            ],
        ], [
            'new_password.mixed' => 'Password harus mengandung huruf besar dan kecil.',
            'new_password.symbols' => 'Password harus mengandung karakter simbol (!@#$%^&*_).',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('user.profile.index')->with('success', 'Profil berhasil diperbarui!');
    }
}