<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Article;
use App\Models\PPDB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'admin':
                $stats = [
                    'total_users' => User::count(),
                    'total_articles' => Article::count(),
                    'total_ppdb' => PPDB::count(),
                ];
                return view('dashboard.admin', compact('user', 'stats'));
            case 'guru':
                return view('dashboard.guru', compact('user'));
            case 'orang-tua':
                return view('dashboard.orang_tua', compact('user'));
            default:
                Auth::logout();
                return redirect('/login')->with('error', 'Role tidak dikenali.');
        }
    }
}
