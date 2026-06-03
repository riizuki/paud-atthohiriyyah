<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\Information;
use App\Models\Article;
use App\Models\Agenda;
use App\Models\User;
use App\Models\PPDB;

class PublicPageController extends Controller
{
    public function index()
    {
        $gallery = Gallery::latest()->limit(3)->get(); // Fewer for brief view
        $information = Information::latest()->limit(2)->get(); // Fewer for brief view
        $agendas = Agenda::latest()->limit(2)->get();
        
        $stats = [
            'teachers' => 6,
            'students' => 33,
            'rombel' => 2,
        ];

        return view('pages.index', compact('gallery', 'information', 'agendas', 'stats'));
    }

    public function profil()
    {
        return view('pages.profil');
    }

    public function galeri()
    {
        $gallery = Gallery::latest()->paginate(12);
        return view('pages.galeri', compact('gallery'));
    }

    public function media()
    {
        $articles = Article::latest()->paginate(6);
        return view('pages.media', compact('articles'));
    }

    public function informasi()
    {
        $information = Information::latest()->paginate(6);
        return view('pages.informasi', compact('information'));
    }

    public function kontak()
    {
        return view('pages.kontak');
    }

    public function sambutan()
    {
        return view('pages.sambutan');
    }
}
