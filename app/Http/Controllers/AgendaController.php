<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agenda;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function index()
    {
        $agendas = Agenda::latest()->get()->map(function($a) {
            $a->isOwner = Auth::check() && (Auth::id() == $a->user_id || Auth::user()->role == 'admin');
            return $a;
        });
        return response()->json(['success' => true, 'list' => $agendas]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'date' => 'required|date',
            'time' => 'nullable',
            'location' => 'nullable',
        ]);

        $dateTime = $request->date . ($request->time ? ' ' . $request->time : ' 00:00:00');

        $agenda = Agenda::create([
            'title' => $request->title,
            'description' => $request->title, // fallback
            'start_date' => $dateTime,
            'end_date' => $dateTime,
            'location' => $request->location,
            'author' => Auth::user()->name ?: Auth::user()->email,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['success' => true, 'item' => $agenda]);
    }

    public function update(Request $request, $id)
    {
        $agenda = Agenda::findOrFail($id);
        if (Auth::id() != $agenda->user_id && Auth::user()->role != 'admin') {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $agenda->update($request->only(['title', 'description', 'start_date', 'end_date', 'location']));
        return response()->json(['success' => true, 'item' => $agenda]);
    }

    public function destroy($id)
    {
        $agenda = Agenda::findOrFail($id);
        if (Auth::id() != $agenda->user_id && Auth::user()->role != 'admin') {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }
        $agenda->delete();
        return response()->json(['success' => true]);
    }
}
