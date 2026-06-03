<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\Information;

class PublicController extends Controller
{
    public function index()
    {
        $gallery = Gallery::latest()->get();
        $information = Information::latest()->get();
        return view('pages.index', compact('gallery', 'information'));
    }
}
