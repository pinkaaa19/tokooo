<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Validasi Input
        $request->validate([
            'name'          => 'required|string|max:255',
            'phone_number'  => 'nullable|string|max:15',
            'gender'        => 'nullable|string',
            'birth_date'    => 'nullable|date',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 2. Update Data Teks
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->gender = $request->gender;
        $user->birth_date = $request->birth_date;

        // 3. Update Foto Profil
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            // Simpan foto baru
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $user->profile_photo = $path;
        }

        $user->save();

        // 4. Return JSON untuk diterima oleh JavaScript (AJAX)
        return response()->json([
            'status' => 'success',
            'message' => 'Profil Aldy Art berhasil diperbarui!',
            'new_photo_url' => asset('storage/' . $user->profile_photo),
            'new_name' => $user->name
        ]);
    }
}