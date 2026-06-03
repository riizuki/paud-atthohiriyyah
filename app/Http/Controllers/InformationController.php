<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Information;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InformationController extends Controller
{
    public function show($slug)
    {
        $info = Information::where('slug', $slug)->firstOrFail();
        return response()->json(['success' => true, 'item' => $info]);
    }

    public function index()
    {
        $info = Information::latest()->get()->map(function($a) {
            $a->isOwner = Auth::check() && (Auth::id() == $a->user_id || Auth::user()->role == 'admin');
            return $a;
        });
        return response()->json(['success' => true, 'list' => $info]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category' => 'nullable',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('information', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $data['author'] = Auth::user()->name ?: Auth::user()->email;
        $data['user_id'] = Auth::id();

        $item = Information::create($data);
        return response()->json(['success' => true, 'item' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = Information::findOrFail($id);
        if (Auth::id() != $item->user_id && Auth::user()->role != 'admin') {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $item->update($request->only(['title', 'description', 'category']));
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('information', 'public');
            $item->update(['image' => 'storage/' . $path]);
        }

        return response()->json(['success' => true, 'item' => $item]);
    }

    public function destroy($id)
    {
        $item = Information::findOrFail($id);
        if (Auth::id() != $item->user_id && Auth::user()->role != 'admin') {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }
        $item->delete();
        return response()->json(['success' => true]);
    }
}
