<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'username' => 'kasir',
            'password' => Hash::make('kasir123'),
            'role' => 'kasir',
        ]);

        DB::table("kategori_menu")->insert([
            'nama_kategori_menu' => 'Kopi',
            'created_at' => now(),
        ]);
        DB::table("kategori_menu")->insert([
            'nama_kategori_menu' => 'Minuman',
            'created_at' => now(),
        ]);
        

        DB::table("menu")->insert([
            'id_kategori_menu' => '1',
            'nama_menu' => 'Coffee Latte',
            'deskripsi_menu' => 'Espresso dengan susu, harmoni kopi kaya dan kelembutan susu dalam setiap tegukan.',
            'harga_menu' => '17000',
            'url_gambar' => 'https://static.republika.co.id/uploads/member/images/news/qy3rhdqb8s.jpg',
        ]);
        DB::table("menu")->insert([
            'id_kategori_menu' => '1',
            'nama_menu' => 'Javanese',
            'deskripsi_menu' => 'Nikmati sensasi kopi Javanese yang memikat, sebuah perpaduan sempurna dari cita rasa kopi premium dan kehangatan budaya Jawa. Setiap tegukan adalah petualangan rasa yang membawa Anda ke keindahan pulau Jawa. Rasakan keajaiban kopi Javanese hari ini, karena kelezatan yang sejati tidak mengenal batasan.',
            'harga_menu' => '18000',
            'url_gambar' => 'https://www.crazymasalafood.com/wp-content/images/ginger-milk-tea.png',
        ]);
        DB::table("menu")->insert([
            'id_kategori_menu' => '1',
            'nama_menu' => 'Cappuccino',
            'deskripsi_menu' => 'Nikmati kehangatan dan kenikmatan yang tak tertandingi dengan Cappuccino kami! Kopi Cappuccino yang lezat dengan rasa kopi yang kuat dan kelembutan susu, disajikan dengan cinta untuk memulai harimu dengan semangat. Temukan kesejukan dalam setiap tegukan. Cappuccino, rasa yang tiada tandingannya untuk para pencinta kopi sejati. Segera kunjungi kami dan rasakan sensasi kopi Cappuccino yang tak terlupakan!.',
            'harga_menu' => '17000',
            'url_gambar' => 'https://asset.kompas.com/crops/bxbnsuDpvDCY8FfyiWFxZ2oOa3w=/0x0:1000x667/750x500/data/photo/2023/02/14/63ead9a1e8eaf.jpeg',
        ]);
        DB::table("menu")->insert([
            'id_kategori_menu' => '2',
            'nama_menu' => 'Vanilla Latte',
            'deskripsi_menu' => 'Nikmati sensasi lembut dan manis yang tak tertandingi dengan Minuman Vanilla Latte kami! Setiap tegukan penuh dengan harmoni rasa kopi yang kaya dan vanila yang memikat, sehingga Anda dapat merasakan kelezatan dalam setiap gigitan. Pesan sekarang dan biarkan Minuman Vanilla Latte kami memanjakan lidah Anda dengan kesempurnaan rasa yang tak terlupakan.',
            'harga_menu' => '16000',
            'url_gambar' => 'https://asset-2.tstatic.net/travel/foto/bank/images/ilustrasi-minuman-vanilla-latte.jpg',
        ]);
        DB::table("menu")->insert([
            'id_kategori_menu' => '2',
            'nama_menu' => 'Lemon Tea',
            'deskripsi_menu' => 'Lemon Tea.',
            'harga_menu' => '15000',
            'url_gambar' => 'https://asset-2.tstatic.net/travel/foto/bank/images/ilustrasi-minuman-vanilla-latte.jpg',
        ]);
    }
}

									

