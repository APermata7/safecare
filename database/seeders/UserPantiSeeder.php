<?php

namespace Database\Seeders;

use App\Models\PantiAsuhan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserPantiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat folder storage jika belum ada
        Storage::disk('public')->makeDirectory('avatars');
        Storage::disk('public')->makeDirectory('panti_images');
        Storage::disk('public')->makeDirectory('documents');

        // Buat user donatur dengan avatar
        User::create([
            'name' => 'Donatur Test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('rahasia1'),
            'role' => 'donatur',
            'phone' => '08123456781',
            'avatar' => $this->storeAvatar('dummy_files/avatars/donatur1.jpg', 'donatur1.jpg')
        ]);

        User::create([
            'name' => 'Lidwina Donatur',
            'email' => 'lidwina@gmail.com',
            'password' => Hash::make('rahasia1'),
            'role' => 'donatur',
            'phone' => '08123456782',
            'avatar' => $this->storeAvatar('dummy_files/avatars/donatur2.jpg', 'donatur2.jpg')
        ]);

        // Buat user panti asuhan 1
        $userPanti = User::create([
            'name' => 'Pengurus Panti',
            'email' => 'panti@gmail.com',
            'password' => Hash::make('rahasia1'),
            'role' => 'panti',
            'phone' => '08123456783',
            'avatar' => $this->storeAvatar('dummy_files/avatars/pengurus.jpg', 'pengurus.jpg')
        ]);

        // Buat data panti asuhan 1
        PantiAsuhan::create([
            'user_id' => $userPanti->id,
            'nama_panti' => 'Panti Asuhan Lidwina',
            'alamat' => 'Jl. Kebon Jeruk No. 123, Jakarta Barat',
            'deskripsi' => 'Panti asuhan untuk anak yatim dan dhuafa',
            'foto_profil' => $this->storePantiImage('dummy_files/panti_images/panti1.jpg', 'panti1.jpg'),
            'dokumen_verifikasi' => $this->storeDocument('dummy_files/documents/verifikasi.pdf', 'verifikasi.pdf'),
            'status_verifikasi' => 'verified',
            'nomor_rekening' => '1234567890',
            'bank' => 'BCA',
            'kontak' => '08123456789 / argenta@gmail.com'
        ]);

        // Buat user panti asuhan 2
        $userPanti = User::create([
            'name' => 'Hayao Miyazaki',
            'email' => 'hayao@gmail.com',
            'password' => Hash::make('rahasia1'),
            'role' => 'panti',
            'phone' => '08123456789',
            'avatar' => $this->storeAvatar('dummy_files/avatars/hayao.png', 'hayao.png')
        ]);

        // Buat data panti asuhan 2
        PantiAsuhan::create([
            'user_id' => $userPanti->id,
            'nama_panti' => 'Panti Asuhan Hayao',
            'alamat' => 'Jl. H. Djuanda No. 255, Jawa Barat',
            'deskripsi' => 'Panti asuhan anak yatim piatu',
            'foto_profil' => $this->storePantiImage('dummy_files/panti_images/panti2.jpg', 'panti2.jpg'),
            'dokumen_verifikasi' => $this->storeDocument('dummy_files/documents/portfolio1.pdf', 'portfolio1.pdf'),
            'status_verifikasi' => 'verified',
            'nomor_rekening' => '9876543321',
            'bank' => 'BRI',
            'kontak' => '08123456789 / hayao@gmail.com'
        ]);

        // Buat user panti asuhan 3
        $userPanti = User::create([
            'name' => 'Minecraft Steve',
            'email' => 'steve@gmail.com',
            'password' => Hash::make('rahasia1'),
            'role' => 'panti',
            'phone' => '0812234556789',
            'avatar' => $this->storeAvatar('dummy_files/avatars/steve.png', 'steve.png')
        ]);

        // Buat data panti asuhan 3
        PantiAsuhan::create([
            'user_id' => $userPanti->id,
            'nama_panti' => 'Panti Asuhan Steve',
            'alamat' => 'Jl. Veteran No. 555, Jawa Timur',
            'deskripsi' => 'Panti asuhan anak piatu dan yatim',
            'foto_profil' => $this->storePantiImage('dummy_files/panti_images/panti3.jpg', 'panti3.jpg'),
            'dokumen_verifikasi' => $this->storeDocument('dummy_files/documents/portfolio2.pdf', 'portfolio2.pdf'),
            'status_verifikasi' => 'verified',
            'nomor_rekening' => '987654332111',
            'bank' => 'BNI',
            'kontak' => '0812234556789 / steve@gmail.com'
        ]);

        // Buat user admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('rahasia1'),
            'role' => 'admin',
            'phone' => '08123456780',
            'avatar' => $this->storeAvatar('dummy_files/avatars/admin.jpg', 'admin.jpg')
        ]);

        $this->command->info('Seeder berhasil dijalankan!');
        $this->command->info('Email: test@gmail.com     | Password: rahasia1 (Donatur)');
        $this->command->info('Email: lidwina@gmail.com  | Password: rahasia1 (Donatur)');
        $this->command->info('Email: panti@gmail.com    | Password: rahasia1 (Panti)');
        $this->command->info('Email: hayao@gmail.com    | Password: rahasia1 (Panti)');
        $this->command->info('Email: steve@gmail.com    | Password: rahasia1 (Panti)');
        $this->command->info('Email: admin@gmail.com    | Password: rahasia1 (Admin)');
    }

    /**
     * Store avatar image to storage and return the path
     */
    private function storeAvatar(string $sourcePath, string $filename): ?string
    {
        $fullSourcePath = database_path('seeders/'.$sourcePath);
        
        if (!file_exists($fullSourcePath)) {
            $this->command->error("File avatar tidak ditemukan: {$fullSourcePath}");
            return null;
        }

        $storagePath = 'avatars/'.$filename;
        
        Storage::disk('public')->put(
            $storagePath,
            file_get_contents($fullSourcePath)
        );

        return $storagePath;
    }

    /**
     * Store panti image to storage and return the path
     */
    private function storePantiImage(string $sourcePath, string $filename): ?string
    {
        $fullSourcePath = database_path('seeders/'.$sourcePath);
        
        if (!file_exists($fullSourcePath)) {
            $this->command->error("File panti tidak ditemukan: {$fullSourcePath}");
            return null;
        }

        $storagePath = 'panti_images/'.$filename;
        
        Storage::disk('public')->put(
            $storagePath,
            file_get_contents($fullSourcePath)
        );

        return $storagePath;
    }

    /**
     * Store document to storage and return the path
     */
    private function storeDocument(string $sourcePath, string $filename): ?string
    {
        $fullSourcePath = database_path('seeders/'.$sourcePath);
        
        if (!file_exists($fullSourcePath)) {
            $this->command->error("File dokumen tidak ditemukan: {$fullSourcePath}");
            return null;
        }

        $storagePath = 'documents/'.$filename;
        
        Storage::disk('public')->put(
            $storagePath,
            file_get_contents($fullSourcePath)
        );

        return $storagePath;
    }
}