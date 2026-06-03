<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PPDB;
use Illuminate\Support\Facades\Storage;

class PPDBController extends Controller
{
    public function create()
    {
        $status = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'pmb_status')->value('value');
        if ($status != 1) {
            return view('pages.ppdb_closed');
        }
        return view('pages.ppdb');
    }

    public function index()
    {
        return response()->json(PPDB::latest()->get());
    }

    public function submit(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'jenis_pendaftaran' => 'required',
                'jalur_pendaftaran' => 'required',
                'nik' => 'required|unique:ppdbs,nik',
                'nama_lengkap' => 'required',
                'email' => 'nullable|email',
                'pas_foto' => 'required|image|mimes:jpeg,png,jpg|max:512',
                'kartu_keluarga' => 'required|file|mimes:jpeg,png,jpg,pdf|max:512',
                'akta_lahir' => 'required|file|mimes:jpeg,png,jpg,pdf|max:512',
                'kip' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:512',
                'bukti_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:512',
            ], [
                'nik.unique' => 'NIK ini sudah terdaftar dalam sistem.',
                'max' => 'Ukuran file maksimal 0.5MB (512KB).',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            $data = $request->except(['pas_foto', 'kartu_keluarga', 'akta_lahir', 'kip', 'bukti_pembayaran']);

            // Handle file uploads
            $files = ['pas_foto', 'kartu_keluarga', 'akta_lahir', 'kip', 'bukti_pembayaran'];
            foreach ($files as $fileField) {
                if ($request->hasFile($fileField)) {
                    $path = $request->file($fileField)->store('ppdb/' . $request->nik, 'public');
                    $data[$fileField] = 'storage/' . $path;
                }
            }

            $ppdb = PPDB::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil dikirim! Silakan tunggu konfirmasi dari pihak sekolah.',
                'data' => $ppdb
            ]);

        } catch (\Exception $e) {
            \Log::error('PPDB Submit Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
    public function update(Request $request)
    {
        if ($request->has('id')) {
            $ppdb = PPDB::findOrFail($request->id);
            $ppdb->update(['status' => $request->status]);
            return response()->json(['success' => true]);
        }
        
        $ppdb = PPDB::where('nik', $request->nik)->firstOrFail();
        $ppdb->update($request->updates);
        return response()->json(['success' => true, 'entry' => $ppdb]);
    }

    public function destroy($id)
    {
        $ppdb = PPDB::findOrFail($id);
        $ppdb->delete();
        return response()->json(['success' => true]);
    }
}
