<?php
session_start();
// Include database connection and auth
require_once '../inc/koneksi.php';
require_once '../inc/auth.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE booking SET status = ? WHERE id = ?");
    $stmt->execute([$status, $booking_id]);
    $message = "Booking status updated successfully.";
}

// Fetch all bookings with customer and service info
$stmt = $pdo->query("SELECT b.id, p.nama as pelanggan, l.nama as layanan, b.tanggal, b.waktu, b.status, b.catatan FROM booking b JOIN pelanggan p ON b.pelanggan_id = p.id JOIN layanan l ON b.layanan_id = l.id ORDER BY b.created_at DESC");
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Bookings - Web Bengkel Admin</title>
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
            <h2 class="text-2xl font-semibold mb-6">Manage Bookings</h2>
            <?php if (isset($message)): ?>
                <p class="text-green-500 mb-4"><?php echo $message; ?></p>
            <?php endif; ?>
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $booking['id']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($booking['pelanggan']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($booking['layanan']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $booking['tanggal']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $booking['waktu']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php 
                                    if ($booking['status'] == 'pending') echo 'bg-yellow-100 text-yellow-800';
                                    elseif ($booking['status'] == 'confirmed') echo 'bg-blue-100 text-blue-800';
                                    elseif ($booking['status'] == 'completed') echo 'bg-green-100 text-green-800';
                                    else echo 'bg-red-100 text-red-800';
                                    ?>">
                                    <?php echo ucfirst($booking['status']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($booking['catatan']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form method="post" action="" class="inline">
                                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>" />
                                    <select name="status" class="border border-gray-300 rounded px-2 py-1">
                                        <option value="pending" <?php if ($booking['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                        <option value="confirmed" <?php if ($booking['status'] == 'confirmed') echo 'selected'; ?>>Confirmed</option>
                                        <option value="completed" <?php if ($booking['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                                        <option value="cancelled" <?php if ($booking['status'] == 'cancelled') echo 'selected'; ?>>Cancelled</option>
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
