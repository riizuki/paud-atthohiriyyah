<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Information;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Users for each role
        $roles = ['admin', 'guru', 'orang-tua'];
        foreach ($roles as $role) {
            User::create([
                'name' => 'Test ' . ucfirst($role),
                'email' => $role . '@test.com',
                'password' => Hash::make('password123'),
                'role' => $role,
            ]);
        }

        // Gallery
        $galleryItems = [
            ['title' => 'Kegiatan Mewarnai Bersama', 'file' => 'img/Kelompok-Bermain.jpg'],
            ['title' => 'Eksplorasi Luar Ruangan', 'file' => 'img/Kurikulum.jpg'],
            ['title' => 'Momen Kebersamaan Siswa', 'file' => 'img/Banner01.jpeg'],
            ['title' => 'Fasilitas Belajar Nyaman', 'file' => 'img/Banner0.jpeg'],
        ];

        foreach ($galleryItems as $item) {
            Gallery::create([
                'title' => $item['title'],
                'file' => $item['file'],
                'description' => 'Momen berharga di PAUD ATTHOHIRIYYAH',
            ]);
        }

        // Articles
        $articles = [
            [
                'title' => 'Pentingnya Pendidikan Karakter Sejak Dini',
                'category' => 'Edukasi',
                'content' => 'Pendidikan karakter adalah usaha sadar dan terencana untuk membantu anak memahami, mempedulikan, dan bertindak berdasarkan nilai-nilai etis. Di PAUD ATTHOHIRIYYAH, kami mengedepankan nilai-nilai Islami sebagai fondasi utama.',
            ],
            [
                'title' => 'Tips Mendampingi Anak Belajar di Rumah',
                'category' => 'Parenting',
                'content' => 'Mendampingi anak belajar bukan berarti mengerjakan tugas mereka. Peran orang tua adalah menciptakan suasana yang nyaman dan memberikan motivasi agar anak semangat bereksplorasi.',
            ],
            [
                'title' => 'Manfaat Bermain Peran bagi Tumbuh Kembang Anak',
                'category' => 'Tips & Trik',
                'content' => 'Bermain peran membantu anak mengembangkan keterampilan sosial, bahasa, dan empati. Melalui imajinasi, anak belajar memahami berbagai perspektif kehidupan.',
            ],
            [
                'title' => 'Kreativitas Tanpa Batas dengan Alat Sederhana',
                'category' => 'Edukasi',
                'content' => 'Seringkali alat peraga terbaik ada di sekitar kita. Botol bekas, kardus, dan daun kering bisa menjadi media belajar yang sangat efektif untuk melatih motorik halus anak.',
            ],
        ];

        foreach ($articles as $art) {
            Article::create([
                'title' => $art['title'],
                'slug' => Str::slug($art['title']),
                'category' => $art['category'],
                'content' => $art['content'],
                'author' => 'Admin PAUD',
            ]);
        }

        // Information
        $infos = [
            [
                'title' => 'Pembukaan Pendaftaran Murid Baru 2026/2027',
                'content' => 'Kami membuka pendaftaran untuk Gelombang 1 mulai Januari hingga Maret 2026. Segera daftarkan ananda untuk mendapatkan kuota terbatas.',
            ],
            [
                'title' => 'Kegiatan Outbound Tahunan di Taman Wisata',
                'content' => 'Seluruh siswa akan mengikuti kegiatan outbound untuk melatih keberanian dan kemandirian pada hari Sabtu mendatang.',
            ],
        ];

        foreach ($infos as $info) {
            Information::create([
                'title' => $info['title'],
                'slug' => Str::slug($info['title']),
                'description' => $info['content'],
            ]);
        }
    }
}
