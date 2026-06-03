<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Gallery;
use App\Models\PPDB;
use App\Models\Article;
use App\Models\Information;
use App\Models\Agenda;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class LegacyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $basePath = base_path('legacy-node/backend/data');

        // 1. Users from db.json
        if (File::exists("$basePath/db.json")) {
            $dbData = json_decode(File::get("$basePath/db.json"), true);
            if (isset($dbData['users'])) {
                foreach ($dbData['users'] as $u) {
                    User::updateOrCreate(
                        ['email' => $u['email']],
                        [
                            'account_id' => $u['account_id'] ?? null,
                            'name' => $u['name'] ?? explode('@', $u['email'])[0],
                            'password' => $u['password'], // Already hashed from node
                            'role' => $u['role'] ?? 'user',
                            'avatar' => $u['avatar'] ?? null,
                        ]
                    );
                }
            }
        }
        
        // Ensure admin@paud.id exists if not in db.json
        User::updateOrCreate(
            ['email' => 'admin@paud.id'],
            [
                'name' => 'Admin PAUD',
                'password' => Hash::make('adminpaud'),
                'role' => 'admin',
            ]
        );

        // 2. Gallery
        if (File::exists("$basePath/gallery.json")) {
            $gallery = json_decode(File::get("$basePath/gallery.json"), true);
            foreach ($gallery as $item) {
                Gallery::create([
                    'title' => $item['title'] ?? null,
                    'description' => $item['description'] ?? null,
                    'file' => $item['file'] ?? '',
                    'uploaded_by' => $item['uploaded_by'] ?? null,
                    'created_at' => isset($item['time']) ? date('Y-m-d H:i:s', strtotime($item['time'])) : now(),
                ]);
            }
        }

        // 3. PPDB
        if (File::exists("$basePath/ppdb.json")) {
            $ppdb = json_decode(File::get("$basePath/ppdb.json"), true);
            foreach ($ppdb as $item) {
                PPDB::create([
                    'jenis_pendaftaran' => $item['jenis_pendaftaran'] ?? null,
                    'jalur_pendaftaran' => $item['jalur_pendaftaran'] ?? null,
                    'nik' => $item['nik'],
                    'sekolah_asal' => $item['sekolah_asal'] ?? null,
                    'nama_lengkap' => $item['nama_lengkap'],
                    'nisn' => $item['nisn'] ?? null,
                    'jenis_kelamin' => $item['jenis_kelamin'] ?? null,
                    'tempat_lahir' => $item['tempat_lahir'] ?? null,
                    'tanggal_lahir' => isset($item['tanggal_lahir']) && $item['tanggal_lahir'] != '-' ? $item['tanggal_lahir'] : null,
                    'agama' => $item['agama'] ?? null,
                    'kewarganegaraan' => $item['kewarganegaraan'] ?? null,
                    'alamat_jalan' => $item['alamat_jalan'] ?? null,
                    'desa_kelurahan' => $item['desa_kelurahan'] ?? null,
                    'kecamatan' => $item['kecamatan'] ?? null,
                    'kabupaten_kota' => $item['kabupaten_kota'] ?? null,
                    'kode_pos' => $item['kode_pos'] ?? null,
                    'tempat_tinggal' => $item['tempat_tinggal'] ?? null,
                    'anak_ke' => is_numeric($item['anak_ke'] ?? null) ? $item['anak_ke'] : null,
                    'jumlah_saudara' => is_numeric($item['jumlah_saudara'] ?? null) ? $item['jumlah_saudara'] : null,
                    'memiliki_kip' => $item['memiliki_kip'] ?? null,
                    'akan_menerima_kip' => $item['akan_menerima_kip'] ?? null,
                    'nama_ayah' => $item['nama_ayah'] ?? null,
                    'nik_ayah' => $item['nik_ayah'] ?? null,
                    'tempat_lahir_ayah' => $item['tempat_lahir_ayah'] ?? null,
                    'tanggal_lahir_ayah' => isset($item['tanggal_lahir_ayah']) && $item['tanggal_lahir_ayah'] != '-' ? $item['tanggal_lahir_ayah'] : null,
                    'pendidikan_ayah' => $item['pendidikan_ayah'] ?? null,
                    'pekerjaan_ayah' => $item['pekerjaan_ayah'] ?? null,
                    'penghasilan_ayah' => $item['penghasilan_ayah'] ?? null,
                    'nama_ibu' => $item['nama_ibu'] ?? null,
                    'nik_ibu' => $item['nik_ibu'] ?? null,
                    'tempat_lahir_ibu' => $item['tempat_lahir_ibu'] ?? null,
                    'tanggal_lahir_ibu' => isset($item['tanggal_lahir_ibu']) && $item['tanggal_lahir_ibu'] != '-' ? $item['tanggal_lahir_ibu'] : null,
                    'pendidikan_ibu' => $item['pendidikan_ibu'] ?? null,
                    'pekerjaan_ibu' => $item['pekerjaan_ibu'] ?? null,
                    'penghasilan_ibu' => $item['penghasilan_ibu'] ?? null,
                    'nomor_hp' => $item['nomor_hp'] ?? null,
                    'email' => $item['email'] ?? null,
                    'pas_foto' => $item['pas_foto'] ?? null,
                    'kartu_keluarga' => $item['kartu_keluarga'] ?? null,
                    'akta_lahir' => $item['akta_lahir'] ?? null,
                    'kip' => $item['kip'] ?? null,
                    'bukti_pembayaran' => $item['bukti_pembayaran'] ?? null,
                    'created_at' => isset($item['waktu']) ? date('Y-m-d H:i:s', strtotime($item['waktu'])) : now(),
                ]);
            }
        }

        // 4. Articles
        if (File::exists("$basePath/articles.json")) {
            $articles = json_decode(File::get("$basePath/articles.json"), true);
            foreach ($articles as $item) {
                Article::create([
                    'title' => $item['title'],
                    'content' => $item['content'] ?? $item['description'] ?? '',
                    'category' => $item['category'] ?? 'Umum',
                    'image' => $item['image'] ?? null,
                    'author' => $item['author'] ?? null,
                    'user_id' => $item['userId'] ?? null,
                    'created_at' => isset($item['time']) ? date('Y-m-d H:i:s', strtotime($item['time'])) : now(),
                ]);
            }
        }

        // 5. Information
        if (File::exists("$basePath/information.json")) {
            $info = json_decode(File::get("$basePath/information.json"), true);
            foreach ($info as $item) {
                Information::create([
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'category' => $item['category'] ?? 'Umum',
                    'image' => $item['image'] ?? null,
                    'author' => $item['author'] ?? null,
                    'user_id' => $item['userId'] ?? null,
                    'created_at' => isset($item['time']) ? date('Y-m-d H:i:s', strtotime($item['time'])) : now(),
                ]);
            }
        }

        // 6. Agenda
        if (File::exists("$basePath/agenda.json")) {
            $agenda = json_decode(File::get("$basePath/agenda.json"), true);
            foreach ($agenda as $item) {
                Agenda::create([
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'start_date' => isset($item['startDate']) ? date('Y-m-d H:i:s', strtotime($item['startDate'])) : now(),
                    'end_date' => isset($item['endDate']) ? date('Y-m-d H:i:s', strtotime($item['endDate'])) : now(),
                    'location' => $item['location'] ?? null,
                    'author' => $item['author'] ?? null,
                    'user_id' => $item['userId'] ?? null,
                    'created_at' => isset($item['createdAt']) ? date('Y-m-d H:i:s', strtotime($item['createdAt'])) : now(),
                ]);
            }
        }
    }
}
