<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('admin/profile/Edit');
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $rules = [
            'name' => 'required|min:2|max:100',
            'email' => 'required|email|min:3|max:255',
        ];

        $request->validate($rules);
        $user = User::find(Auth::user()->id);
        $user->fill($request->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        $request->session()->flash('success', 'Profil berhasil diperbarui.');

        return back();
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => 'required|confirmed|min:5',
        ], [
            'current_password.required' => 'Kata sandi sekarang ini harus diisi.',
            'current_password.current_password' => 'Kata sandi yang anda masukkan salah.',
            'password.required' => 'Kata sandi baru ini harus diisi.',
            'password.confirmed' => 'Kata sandi yang anda konfirmasi salah.',
            'password.min' => 'Kata sandi minimal 5 karakter.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $request->session()->flash('success', 'Password berhasil diperbarui.');

        return back();
    }
}
