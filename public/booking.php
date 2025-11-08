<?php
// Include database connection
require_once '../inc/koneksi.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $layanan_id = $_POST['layanan_id'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $catatan = trim($_POST['catatan']);

    // Insert customer if not exists
    $stmt = $pdo->prepare("INSERT INTO pelanggan (nama, email, telepon, alamat) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
    $stmt->execute([$nama, $email, $telepon, $alamat]);
    $pelanggan_id = $pdo->lastInsertId();

    // Insert booking
    $stmt = $pdo->prepare("INSERT INTO booking (pelanggan_id, layanan_id, tanggal, waktu, catatan) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$pelanggan_id, $layanan_id, $tanggal, $waktu, $catatan]);

    $success = "Booking submitted successfully!";
}

// Fetch services for dropdown
$stmt = $pdo->query("SELECT id, nama FROM layanan ORDER BY nama");
$layanan = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Booking - Web Bengkel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold">Web Bengkel</h1>
            <nav>
                <ul class="flex space-x-6 text-lg font-medium">
                    <li><a href="index.php" class="text-blue-600 hover:text-blue-800">Home</a></li>
                    <li><a href="booking.php" class="text-blue-600 hover:text-blue-800">Booking</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-10">
        <section class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
            <h2 class="text-2xl font-semibold mb-6">Book a Service</h2>
            <?php if (isset($success)): ?>
                <p class="text-green-500 mb-4"><?php echo $success; ?></p>
            <?php endif; ?>
            <form method="post" action="" class="space-y-4">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Name:</label>
                    <input type="text" id="nama" name="nama" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                    <input type="email" id="email" name="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <div>
                    <label for="telepon" class="block text-sm font-medium text-gray-700">Phone:</label>
                    <input type="text" id="telepon" name="telepon" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Address:</label>
                    <textarea id="alamat" name="alamat" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div>
                    <label for="layanan_id" class="block text-sm font-medium text-gray-700">Service:</label>
                    <select id="layanan_id" name="layanan_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select a service</option>
                        <?php foreach ($layanan as $l): ?>
                            <option value="<?php echo $l['id']; ?>"><?php echo htmlspecialchars($l['nama']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Date:</label>
                    <input type="date" id="tanggal" name="tanggal" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <div>
                    <label for="waktu" class="block text-sm font-medium text-gray-700">Time:</label>
                    <input type="time" id="waktu" name="waktu" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700">Notes:</label>
                    <textarea id="catatan" name="catatan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Submit Booking</button>
            </form>
        </section>
    </main>

    <footer class="bg-white shadow mt-20">
        <div class="container mx-auto px-4 py-6 text-center text-gray-600">
            &copy; <?php echo date('Y'); ?> Web Bengkel. All rights reserved.
        </div>
    </footer>
</body>
</html>
