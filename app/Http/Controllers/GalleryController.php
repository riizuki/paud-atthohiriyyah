<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'list' => Gallery::latest()->get()
        ]);
    }

    public function upload(Request $request)
    {
        try {
            \Log::info('Upload Request Started');

            $validator = \Validator::make($request->all(), [
                'file' => 'required|image|max:5120',
                'title' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                \Log::error('Validation failed:', ['errors' => $validator->errors()]);
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
            }

            if (!$request->hasFile('file')) {
                \Log::error('No file found in request');
                return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
            }

            $file = $request->file('file');

            \Log::info('File Details:', [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'error' => $file->getError()
            ]);

            if (!$file->isValid()) {
                \Log::error('File invalid:', ['error' => $file->getErrorMessage()]);
                return response()->json(['success' => false, 'message' => 'File tidak valid: ' . $file->getErrorMessage()], 400);
            }

            $directory = public_path('uploads/gallery');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($directory, $filename);

            $item = Gallery::create([
                'file' => 'uploads/gallery/' . $filename,
                'title' => $request->title,
                'description' => $request->description ?? $request->title,
                'uploaded_by' => Auth::user()->name ?? Auth::user()->email,
            ]);

            \Log::info('Gallery item created successfully');
            return response()->json(['success' => true, 'item' => $item]);

        } catch (\Exception $e) {
            \Log::error('Upload error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $item = Gallery::findOrFail($id);
        $item->update($request->only(['title', 'description']));
        return response()->json(['success' => true, 'item' => $item]);
    }

    public function delete($id)
    {
        $item = Gallery::findOrFail($id);
        // Delete file from storage if needed
        $item->delete();
        return response()->json(['success' => true]);
    }
}
