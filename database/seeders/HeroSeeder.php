<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Hero;
use Illuminate\Support\Str;

class HeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Pastikan User Admin tersedia
        $user = User::firstOrCreate(
            ['email' => 'admin@pahlawan.test'],
            [
                'name' => 'Admin Historical',
                'password' => bcrypt('password'),
            ]
        );

        // 2. Data Pahlawan dengan tambahan kolom quotes
        $heroes = [
            [
                'user_id'    => $user->id,
                'name'       => 'Ir. Soekarno',
                'birth_date' => '1901-06-06',
                'hometown'   => 'Surabaya',
                'category'   => 'Proclamator',
                'image_path' => 'dien.png',
                'quotes'     => 'Bangsa yang besar adalah bangsa yang menghormati jasa pahlawannya.',
                'bio_id'     => 'Presiden pertama Republik Indonesia dan proklamator kemerdekaan yang memimpin bangsa keluar dari penjajahan.',
                'bio_en'     => 'The first President of the Republic of Indonesia and the proclaimer of independence.',
            ],
            [
                'user_id'    => $user->id,
                'name'       => 'Mohammad Hatta',
                'birth_date' => '1902-08-12',
                'hometown'   => 'Bukittinggi',
                'category'   => 'Proclamator',
                'image_path' => 'sudirman.png',
                'quotes'     => 'Kurang cerdas dapat diperbaiki dengan belajar, kurang cakap dapat dibentuk dengan pengalaman. Namun tidak jujur sulit diperbaiki.',
                'bio_id'     => 'Wakil Presiden pertama Indonesia, pejuang kemerdekaan, dan dikenal sebagai Bapak Koperasi Indonesia.',
                'bio_en'     => 'The first Vice President of Indonesia and the proclaimer of independence.',
            ],
            [
                'user_id'    => $user->id,
                'name'       => 'Raden Ajeng Kartini',
                'birth_date' => '1879-04-21',
                'hometown'   => 'Jepara',
                'category'   => 'National Hero',
                'image_path' => 'dien.png',
                'quotes'     => 'Habis Gelap Terbitlah Terang.',
                'bio_id'     => 'Pelopor kebangkitan perempuan pribumi dan pejuang hak asasi wanita di masa kolonial Belanda.',
                'bio_en'     => 'A pioneer in the emancipation of Indonesian women and a women\'s rights activist.',
            ],
            [
                'user_id'    => $user->id,
                'name'       => 'Jenderal Sudirman',
                'birth_date' => '1916-01-24',
                'hometown'   => 'Purbalingga',
                'category'   => 'Military Leader',
                'image_path' => 'sudirman.png',
                'quotes'     => 'Percaya dan teguh pada pendirian, serta jangan sekali-kali mengkhianati perjuangan bangsa.',
                'bio_id'     => 'Panglima Besar TNI pertama yang memimpin perang gerilya mempertahankan kemerdekaan meski dalam kondisi sakit.',
                'bio_en'     => 'The first Commander-in-Chief of the Indonesian National Armed Forces.',
            ],
            [
                'user_id'    => $user->id,
                'name'       => 'Cut Nyak Dien',
                'birth_date' => '1848-02-11',
                'hometown'   => 'Aceh Besar',
                'category'   => 'National Hero',
                'image_path' => 'dien.png',
                'quotes'     => 'Dalam menghadapi musuh, tak ada yang lebih ampuh daripada kesabaran.',
                'bio_id'     => 'Pejuang wanita legendaris dari Aceh yang memimpin perlawanan gerilya melawan penjajah Belanda dalam perang Aceh.',
                'bio_en'     => 'A female warrior from Aceh who fought courageously against Dutch colonizers.',
            ],
            [
                'user_id'    => $user->id,
                'name'       => 'Ki Hajar Dewantara',
                'birth_date' => '1889-05-02',
                'hometown'   => 'Yogyakarta',
                'category'   => 'Education Pioneer',
                'image_path' => 'dien.png',
                'quotes'     => 'Ing Ngarsa Sung Tulada, Ing Madya Mangun Karsa, Tut Wuri Handayani.',
                'bio_id'     => 'Bapak Pendidikan Nasional Indonesia dan pendiri Taman Siswa yang memperjuangkan pendidikan bagi rakyat jelata.',
                'bio_en'     => 'The father of Indonesian national education.',
            ],
        ];

        foreach ($heroes as $heroData) {
            // PAKSA SLUG DI SINI AGAR TIDAK ERROR 1364
            $heroData['slug'] = Str::slug($heroData['name']);

            Hero::create($heroData);
        }
    }
}
