<?php
session_start();
// Include database connection, auth, and helper
require_once '../inc/koneksi.php';
require_once '../inc/auth.php';
require_once '../inc/helper.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Handle add payment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_payment'])) {
    $booking_id = $_POST['booking_id'];
    $jumlah = $_POST['jumlah'];
    $metode = $_POST['metode'];
    $stmt = $pdo->prepare("INSERT INTO pembayaran (booking_id, jumlah, metode) VALUES (?, ?, ?)");
    $stmt->execute([$booking_id, $jumlah, $metode]);
    $message = "Payment added successfully.";
}

// Handle update payment status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE pembayaran SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    $message = "Payment status updated successfully.";
}

// Fetch all payments with booking and customer info
$stmt = $pdo->query("SELECT p.id, p.booking_id, p.jumlah, p.metode, p.tanggal_pembayaran, p.status, b.tanggal as booking_date, pel.nama as pelanggan FROM pembayaran p JOIN booking b ON p.booking_id = b.id JOIN pelanggan pel ON b.pelanggan_id = pel.id ORDER BY p.tanggal_pembayaran DESC");
$pembayaran = $stmt->fetchAll();

// Fetch bookings without payment for dropdown
$stmt = $pdo->query("SELECT b.id, pel.nama as pelanggan, l.nama as layanan FROM booking b JOIN pelanggan pel ON b.pelanggan_id = pel.id JOIN layanan l ON b.layanan_id = l.id WHERE b.id NOT IN (SELECT booking_id FROM pembayaran) ORDER BY b.created_at DESC");
$bookings_without_payment = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Payments - Web Bengkel Admin</title>
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
            <h2 class="text-2xl font-semibold mb-6">Manage Payments</h2>
            <?php if (isset($message)): ?>
                <p class="text-green-500 mb-4"><?php echo $message; ?></p>
            <?php endif; ?>

            <!-- Add Payment Form -->
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h3 class="text-lg font-semibold mb-4">Add New Payment</h3>
                <form method="post" action="" class="space-y-4">
                    <div>
                        <label for="booking_id" class="block text-sm font-medium text-gray-700">Booking:</label>
                        <select id="booking_id" name="booking_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select a booking</option>
                            <?php foreach ($bookings_without_payment as $b): ?>
                                <option value="<?php echo $b['id']; ?>"><?php echo htmlspecialchars($b['pelanggan'] . ' - ' . $b['layanan']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="jumlah" class="block text-sm font-medium text-gray-700">Amount:</label>
                        <input type="number" step="0.01" id="jumlah" name="jumlah" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                    <div>
                        <label for="metode" class="block text-sm font-medium text-gray-700">Payment Method:</label>
                        <select id="metode" name="metode" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="cash">Cash</option>
                            <option value="transfer">Transfer</option>
                            <option value="credit_card">Credit Card</option>
                        </select>
                    </div>
                    <button type="submit" name="add_payment" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Add Payment</button>
                </form>
            </div>

            <!-- Payments List -->
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($pembayaran as $p): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $p['id']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $p['booking_id']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($p['pelanggan']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo format_currency($p['jumlah']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo ucfirst($p['metode']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo format_date($p['tanggal_pembayaran']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo get_status_class($p['status']); ?>">
                                    <?php echo ucfirst($p['status']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form method="post" action="" class="inline">
                                    <input type="hidden" name="id" value="<?php echo $p['id']; ?>" />
                                    <select name="status" class="border border-gray-300 rounded px-2 py-1">
                                        <option value="pending" <?php if ($p['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                        <option value="paid" <?php if ($p['status'] == 'paid') echo 'selected'; ?>>Paid</option>
                                        <option value="failed" <?php if ($p['status'] == 'failed') echo 'selected'; ?>>Failed</option>
                                    </select>
                                    <button type="submit" name="update_status" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Update</button>
                                </form>
                            </td>
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
