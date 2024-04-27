<p align="center">
  <img src="https://github.com/Reizfy/ae/blob/master/public/assets/img/kasir.png" alt="Logo-KRRB" style="width: 100px;">
</p>

# Tentang Aplikasi

Selamat datang di CashierApp, solusi terbaik untuk semua kebutuhan kasir Anda! Dirancang dengan sederhana dan efisien, CashierApp memberdayakan bisnis dari berbagai skala untuk menyederhanakan proses pembayaran, mengelola transaksi dengan lancar, dan meningkatkan kepuasan pelanggan.

# Fitur Utama

- **Manajemen Member:** Kelola informasi anggota dengan mudah untuk memperkuat hubungan dengan pelanggan setia.
- **Manajemen Petugas:** Tetapkan peran dan akses untuk setiap petugas kasir untuk mengatur dan memantau aktivitas staf dengan efisien.
- **Manajemen Produk:** Tambahkan, edit, atau hapus produk dengan cepat dan mudah, sertakan informasi seperti nama produk, harga, dan stok.
- **Manajemen Kategori:** Kategorikan produk Anda untuk mempermudah navigasi dan pencarian dalam aplikasi.
- **Laporan Stok Barang:** Pantau stok barang secara real-time dan terima pemberitahuan saat stok mendekati titik peringatan.
- **Laporan Penjualan:** Analisis kinerja penjualan dengan laporan penjualan yang komprehensif untuk mengidentifikasi tren dan strategi yang tepat.

- **Sistem Transaksi yang Fluid:** Proses transaksi dengan cepat dan lancar menggunakan sistem transaksi yang responsif dan intuitif.

# Instalasi

1. Unduh repositori CashierApp.
2. Pastikan PHP 8.2+ sudah terinstal dan konfigurasi XAMPP sudah disiapkan.
3. Jalankan `composer install` untuk menginstal dependensi PHP.
4. Jalankan `npm install` untuk menginstal dependensi JavaScript.
5. Salin file `.env.example` dan ubah namanya menjadi `.env`. Konfigurasikan koneksi basis data dan preferensi aplikasi lainnya di file `.env`.
6. Jalankan migrasi basis data dengan perintah `php artisan migrate` untuk menghasilkan tabel-tabel yang diperlukan.
7. Buka aplikasi dalam editor kode seperti Visual Studio Code.
8. Buka file seeder yang berisi data pengguna (misalnya `UsersTableSeeder.php`) dan ubah nilai-nilai sesuai preferensi Anda. Pastikan untuk menyimpan password dalam bentuk hash yang aman (misalnya, menggunakan `bcrypt()` jika menggunakan Laravel).
9. Jalankan perintah `php artisan db:seed --class=NamaSeeder` untuk menjalankan seeder dan memasukkan data yang telah Anda ubah ke dalam basis data.
10. Jalankan server lokal PHP dengan perintah `php artisan serve`.
11. Untuk pengembangan frontend, jalankan `npm run dev` untuk mengaktifkan mode pengembangan.

# Changelog

- **Version 1**

  1. Login Authentication
  2. CRUD Master Data (Produk, Member, Petugas, Kategori)

- **Version 2**

  1. Bug Fixing CRUD Master Data
  2. Transaction

- **Version 3**
  1. Bug Fixing Transaction and Finalization
  2. Stok Barang Report and Sales Report
  3. Bug Fixing Validation on CRUD Master Data
