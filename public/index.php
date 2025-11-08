<?php
// Include database connection
require_once '../inc/koneksi.php';
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Web Bengkel - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold">Welcome to Web Bengkel</h1>
            <nav>
                <ul class="flex space-x-6 text-lg font-medium">
                    <li><a href="#hero" class="text-blue-600 hover:text-blue-800">Home</a></li>
                    <li><a href="#about" class="text-blue-600 hover:text-blue-800">Tentang</a></li>
                    <li><a href="#services" class="text-blue-600 hover:text-blue-800">Layanan</a></li>
                    <li><a href="#contact" class="text-blue-600 hover:text-blue-800">Kontak</a></li>
                    <li><a href="booking.php" class="text-blue-600 hover:text-blue-800">Booking</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-10">
        <!-- Hero Section -->
        <section id="hero" class="bg-blue-600 text-white rounded-lg p-10 text-center mb-10">
            <h2 class="text-4xl font-bold mb-4">Selamat Datang di Web Bengkel</h2>
            <p class="text-xl mb-6">Layanan perbaikan kendaraan terbaik dengan mekanik berpengalaman. Pesan sekarang dan dapatkan servis berkualitas!</p>
            <a href="booking.php" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100">Pesan Sekarang</a>
        </section>

        <!-- About Us Section -->
        <section id="about" class="mb-10">
            <h2 class="text-3xl font-semibold mb-6 text-center">Tentang Kami</h2>
            <div class="bg-white p-6 rounded-lg shadow">
                <p class="text-lg">Web Bengkel adalah bengkel terpercaya yang telah melayani pelanggan sejak 2010. Kami berkomitmen untuk memberikan layanan perbaikan kendaraan yang cepat, aman, dan terjangkau. Dengan tim mekanik profesional dan peralatan modern, kami siap membantu Anda menjaga kendaraan Anda dalam kondisi prima.</p>
            </div>
        </section>

        <!-- Our Services Section -->
        <section id="services" class="mb-10">
            <h2 class="text-3xl font-semibold mb-6 text-center">Layanan Kami</h2>
            <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                // Fetch layanan (services) from database
                $stmt = $pdo->query("SELECT id, nama, deskripsi, harga FROM layanan ORDER BY created_at DESC");
                while ($row = $stmt->fetch()) {
                    echo '<li class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow">';
                    echo '<div class="text-4xl mb-4">🔧</div>'; // Placeholder icon
                    echo '<h3 class="text-xl font-bold mb-2">' . htmlspecialchars($row['nama']) . '</h3>';
                    echo '<p class="mb-2">' . nl2br(htmlspecialchars($row['deskripsi'])) . '</p>';
                    echo '<p class="font-semibold text-blue-600">Harga: Rp ' . number_format($row['harga'], 2, ",", ".") . '</p>';
                    echo '</li>';
                }
                ?>
            </ul>
        </section>

        <!-- Why Choose Us Section -->
        <section id="why-choose" class="mb-10">
            <h2 class="text-3xl font-semibold mb-6 text-center">Mengapa Memilih Kami?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <div class="text-4xl mb-4">👨‍🔧</div>
                    <h3 class="text-xl font-bold mb-2">Mekanik Berpengalaman</h3>
                    <p>Tim ahli dengan pengalaman bertahun-tahun dalam perbaikan kendaraan.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <div class="text-4xl mb-4">⚡</div>
                    <h3 class="text-xl font-bold mb-2">Servis Cepat</h3>
                    <p>Layanan perbaikan yang efisien tanpa mengorbankan kualitas.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <div class="text-4xl mb-4">💰</div>
                    <h3 class="text-xl font-bold mb-2">Harga Terjangkau</h3>
                    <p>Tarif kompetitif dengan transparansi dalam setiap layanan.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <div class="text-4xl mb-4">🔒</div>
                    <h3 class="text-xl font-bold mb-2">Garansi</h3>
                    <p>Jaminan kepuasan pelanggan dengan garansi pada setiap servis.</p>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="mb-10">
            <h2 class="text-3xl font-semibold mb-6 text-center">Testimoni Pelanggan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="mb-4">"Servis sangat memuaskan! Mekaniknya profesional dan harga terjangkau."</p>
                    <p class="font-semibold">- Ahmad S.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="mb-4">"Kendaraan saya kembali seperti baru. Terima kasih Web Bengkel!"</p>
                    <p class="font-semibold">- Siti R.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="mb-4">"Pelayanan cepat dan ramah. Akan datang lagi untuk servis rutin."</p>
                    <p class="font-semibold">- Budi K.</p>
                </div>
            </div>
        </section>

        <!-- Gallery Section -->
        <section id="gallery" class="mb-10">
            <h2 class="text-3xl font-semibold mb-6 text-center">Galeri Kerja Kami</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <img src="https://via.placeholder.com/300x200?text=Workshop+1" alt="Workshop Image 1" class="rounded-lg shadow">
                <img src="https://via.placeholder.com/300x200?text=Workshop+2" alt="Workshop Image 2" class="rounded-lg shadow">
                <img src="https://via.placeholder.com/300x200?text=Workshop+3" alt="Workshop Image 3" class="rounded-lg shadow">
                <img src="https://via.placeholder.com/300x200?text=Workshop+4" alt="Workshop Image 4" class="rounded-lg shadow">
            </div>
        </section>

        <!-- Contact Us Section -->
        <section id="contact" class="mb-10">
            <h2 class="text-3xl font-semibold mb-6 text-center">Hubungi Kami</h2>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-xl font-bold mb-4">Informasi Kontak</h3>
                        <p class="mb-2"><strong>Alamat:</strong> Jl. Raya Bengkel No. 123, Jakarta</p>
                        <p class="mb-2"><strong>Telepon:</strong> (021) 123-4567</p>
                        <p class="mb-2"><strong>Email:</strong> info@webbengkel.com</p>
                        <p class="mb-2"><strong>Jam Operasional:</strong> Senin - Sabtu, 08:00 - 17:00</p>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-4">Kirim Pesan</h3>
                        <p>Butuh bantuan? Hubungi kami untuk konsultasi gratis.</p>
                        <a href="booking.php" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 mt-4 inline-block">Pesan Konsultasi</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-white shadow mt-20">
        <div class="container mx-auto px-4 py-6 text-center text-gray-600">
            &copy; <?php echo date('Y'); ?> Web Bengkel. All rights reserved.
        </div>
    </footer>
</body>
</html>
