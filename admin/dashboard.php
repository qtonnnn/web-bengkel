<?php
session_start();
// Include database connection
require_once '../inc/koneksi.php';
// Include auth functions (assuming auth.php has login check)
require_once '../inc/auth.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Fetch dashboard data
// Total customers
$stmt = $pdo->query("SELECT COUNT(*) as total FROM pelanggan");
$totalPelanggan = $stmt->fetch()['total'];

// Total bookings
$stmt = $pdo->query("SELECT COUNT(*) as total FROM booking");
$totalBooking = $stmt->fetch()['total'];

// Total payments
$stmt = $pdo->query("SELECT SUM(jumlah) as total FROM pembayaran WHERE status = 'paid'");
$totalPembayaran = $stmt->fetch()['total'];

// Recent bookings
$stmt = $pdo->query("SELECT b.id, p.nama as pelanggan, l.nama as layanan, b.tanggal, b.status FROM booking b JOIN pelanggan p ON b.pelanggan_id = p.id JOIN layanan l ON b.layanan_id = l.id ORDER BY b.created_at DESC LIMIT 5");
$recentBookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard - Web Bengkel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold">Admin Dashboard</h1>
            <nav>
                <ul class="flex space-x-6 text-lg font-medium">
                    <li><a href="dashboard.php" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
                    <li><a href="layanan.php" class="text-blue-600 hover:text-blue-800">Layanan</a></li>
                    <li><a href="pelanggan.php" class="text-blue-600 hover:text-blue-800">Pelanggan</a></li>
                    <li><a href="booking.php" class="text-blue-600 hover:text-blue-800">Booking</a></li>
                    <li><a href="pembayaran.php" class="text-blue-600 hover:text-blue-800">Pembayaran</a></li>
                    <li><a href="logout.php" class="text-blue-600 hover:text-blue-800">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-10">
        <section>
            <h2 class="text-2xl font-semibold mb-6">Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <h3 class="text-lg font-semibold mb-2">Total Customers</h3>
                    <p class="text-3xl font-bold"><?php echo $totalPelanggan; ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <h3 class="text-lg font-semibold mb-2">Total Bookings</h3>
                    <p class="text-3xl font-bold"><?php echo $totalBooking; ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <h3 class="text-lg font-semibold mb-2">Total Payments</h3>
                    <p class="text-3xl font-bold text-blue-600">Rp <?php echo number_format($totalPembayaran, 2, ',', '.'); ?></p>
                </div>
            </div>
        </section>

        <section class="mt-10">
            <h2 class="text-2xl font-semibold mb-6">Recent Bookings</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($recentBookings as $booking): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $booking['id']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($booking['pelanggan']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($booking['layanan']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $booking['tanggal']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $booking['status']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="bg-white shadow mt-20">
        <div class="container mx-auto px-4 py-6 text-center text-gray-600">
            &copy; <?php echo date('Y'); ?> Web Bengkel Admin.
        </div>
    </footer>
</body>
</html>
