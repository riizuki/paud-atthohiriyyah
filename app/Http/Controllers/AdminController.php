<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PPDB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function usersPage()
    {
        $users = User::all();
        return view('dashboard.admin.users', compact('users'));
    }

    public function articlesPage()
    {
        $articles = \App\Models\Article::latest()->get();
        return view('dashboard.admin.articles', compact('articles'));
    }

    public function galleryPage()
    {
        $gallery = \App\Models\Gallery::latest()->get();
        return view('dashboard.admin.gallery', compact('gallery'));
    }

    public function agendaPage()
    {
        $agendas = \App\Models\Agenda::latest()->get();
        return view('dashboard.admin.agenda', compact('agendas'));
    }

    public function ppdbPage()
    {
        $ppdb = \App\Models\PPDB::latest()->get();
        $settings = \Illuminate\Support\Facades\DB::table('settings')->pluck('value', 'key');
        return view('dashboard.admin.ppdb', compact('ppdb', 'settings'));
    }

    public function updateSettings(Request $request)
    {
        \Illuminate\Support\Facades\DB::table('settings')->updateOrInsert(
            ['key' => $request->key],
            ['value' => $request->value]
        );
        return response()->json(['success' => true]);
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,guru,orang-tua'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json(['success' => true]);
    }

    public function convertToStudent($ppdbId)
    {
        $ppdb = PPDB::findOrFail($ppdbId);

        if ($ppdb->status !== 'verified') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pendaftar dengan status "verified" yang bisa dikonversi menjadi siswa.'
            ], 400);
        }

        // Check if user already exists
        $existingUser = User::where('email', $ppdb->email)->orWhere('account_id', $ppdb->nik)->first();
        if ($existingUser) {
            return response()->json([
                'success' => false,
                'message' => 'User dengan email atau NIK ini sudah ada.'
            ], 400);
        }

        $password = Str::random(8);
        $user = User::create([
            'name' => $ppdb->nama_lengkap,
            'email' => $ppdb->email ?: $ppdb->nik . '@paud.local',
            'password' => Hash::make($password),
            'role' => 'orang-tua', // Changed from siswa
            'account_id' => $ppdb->nik,
            'avatar' => $ppdb->pas_foto,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil dikonversi!',
            'user' => $user,
            'temporary_password' => $password
        ]);
    }

    public function stats()
    {
        return response()->json([
            'total_users' => User::count(),
            'total_students' => User::where('role', 'siswa')->count(),
            'total_teachers' => User::where('role', 'guru')->count(),
            'total_ppdb' => PPDB::count(),
            'pending_ppdb' => PPDB::where('status', 'pending')->count(),
        ]);
    }

    public function users()
    {
        return response()->json(User::all(['id', 'email', 'role', 'name']));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => true]);
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update(['role' => $request->role]);
        return response()->json(['success' => true]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'email', 'role']));
        return response()->json(['success' => true]);
    }

    public function addGuru(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email|unique:users',
        ]);

        $accountId = 'G' . now()->getTimestamp();
        $password = Str::random(8);
        $email = $request->email ?: $accountId . '@paud.local';

        $user = User::create([
            'account_id' => $accountId,
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'guru',
        ]);

        return response()->json([
            'success' => true,
            'account_id' => $accountId,
            'password' => $password
        ]);
    }
}
