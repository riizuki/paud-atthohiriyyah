<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function show($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        return response()->json(['success' => true, 'item' => $article]);
    }

    public function index()
    {
        $articles = Article::latest()->get()->map(function($a) {
            $a->isOwner = Auth::check() && (Auth::id() == $a->user_id || Auth::user()->role == 'admin');
            return $a;
        });
        return response()->json(['success' => true, 'list' => $articles]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category' => 'nullable',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $data['author'] = Auth::user()->name ?: Auth::user()->email;
        $data['user_id'] = Auth::id();

        $article = Article::create($data);
        return response()->json(['success' => true, 'item' => $article]);
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        if (Auth::id() != $article->user_id && Auth::user()->role != 'admin') {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $article->update($request->only(['title', 'content', 'category']));
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $article->update(['image' => 'storage/' . $path]);
        }

        return response()->json(['success' => true, 'item' => $article]);
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        if (Auth::id() != $article->user_id && Auth::user()->role != 'admin') {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }
        $article->delete();
        return response()->json(['success' => true]);
    }
}
