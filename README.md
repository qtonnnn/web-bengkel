# Web Bengkel

Sistem manajemen bengkel berbasis web yang komprehensif dibangun dengan PHP, dirancang untuk merampingkan layanan perbaikan kendaraan, pemesanan pelanggan, dan operasi administratif.

## Fitur

### Fitur Publik
- **Beranda**: Gambaran umum layanan, tentang kami, testimoni, dan informasi kontak
- **Pemesanan Layanan**: Sistem pemesanan online untuk pelanggan menjadwalkan perbaikan kendaraan
- **Daftar Layanan**: Menampilkan layanan yang tersedia dengan deskripsi dan harga

### Panel Admin
- **Dashboard**: Gambaran umum total pelanggan, pemesanan, dan pembayaran
- **Manajemen Layanan**: Tambah, edit, dan kelola layanan bengkel
- **Manajemen Pelanggan**: Lihat dan kelola informasi pelanggan
- **Manajemen Pemesanan**: Tangani permintaan pemesanan dan pembaruan status
- **Pelacakan Pembayaran**: Pantau status pembayaran dan transaksi
- **Autentikasi**: Sistem login admin yang aman

## Teknologi yang Digunakan

- **Backend**: PHP 7+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, Tailwind CSS
- **JavaScript**: Vanilla JS untuk interaksi dasar
- **Abstraksi Database**: PDO untuk operasi database yang aman

## Instalasi

1. **Prasyarat**
   - PHP 7.0 atau lebih tinggi
   - MySQL 5.7 atau lebih tinggi
   - Server web (Apache/Nginx) atau XAMPP/WAMP

2. **Pengaturan**
   ```bash
   # Kloning repositori
   git clone https://github.com/yourusername/web-bengkel.git
   cd web-bengkel

   # Salin ke direktori server web (misalnya, htdocs untuk XAMPP)
   cp -r . /path/to/web/server/
   ```

3. **Konfigurasi Database**
   - Buat database MySQL bernama `webbengkel`
   - Impor skema dari `sql/create_tables.sql`
   - Perbarui kredensial database di `inc/koneksi.php`

4. **Pengaturan Admin**
   - Akses panel admin di `admin/login.php`
   - Buat pengguna admin dengan menyisipkan langsung ke tabel `admin_users`:
     ```sql
     INSERT INTO admin_users (username, password) VALUES ('admin', '$2y$10$hashed_password');
     ```
     Gunakan `password_hash('your_password', PASSWORD_DEFAULT)` PHP untuk menghasilkan hash.

## Penggunaan

### Akses Publik
- Kunjungi beranda di `public/index.php`
- Jelajahi layanan dan pesan janji temu melalui `public/booking.php`

### Akses Admin
- Login di `admin/login.php`
- Kelola semua aspek bengkel dari dashboard

## Skema Database

Sistem menggunakan tabel utama berikut:
- `admin_users`: Autentikasi admin
- `layanan`: Layanan yang tersedia
- `pelanggan`: Informasi pelanggan
- `booking`: Pemesanan layanan
- `pembayaran`: Catatan pembayaran

## Fitur Keamanan

- Hashing kata sandi menggunakan fungsi bawaan PHP
- Autentikasi berbasis sesi
- Sanitasi dan validasi input
- Pernyataan PDO yang disiapkan untuk mencegah injeksi SQL

## Berkontribusi

1. Fork repositori
2. Buat cabang fitur (`git checkout -b feature/AmazingFeature`)
3. Komit perubahan Anda (`git commit -m 'Tambahkan beberapa AmazingFeature'`)
4. Push ke cabang (`git push origin feature/AmazingFeature`)
5. Buka Pull Request

## Lisensi

Proyek ini dilisensikan di bawah Lisensi MIT - lihat file [LICENSE](LICENSE) untuk detail.

## Kontak

Untuk pertanyaan atau dukungan, silakan hubungi tim pengembang.
