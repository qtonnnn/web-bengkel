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

// Handle add service
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_service'])) {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga = $_POST['harga'];
    $stmt = $pdo->prepare("INSERT INTO layanan (nama, deskripsi, harga) VALUES (?, ?, ?)");
    $stmt->execute([$nama, $deskripsi, $harga]);
    $message = "Service added successfully.";
}

// Handle update service
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_service'])) {
    $id = $_POST['id'];
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga = $_POST['harga'];
    $stmt = $pdo->prepare("UPDATE layanan SET nama = ?, deskripsi = ?, harga = ? WHERE id = ?");
    $stmt->execute([$nama, $deskripsi, $harga, $id]);
    $message = "Service updated successfully.";
}

// Handle delete service
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM layanan WHERE id = ?");
    $stmt->execute([$id]);
    $message = "Service deleted successfully.";
}

// Fetch all services
$stmt = $pdo->query("SELECT * FROM layanan ORDER BY created_at DESC");
$layanan = $stmt->fetchAll();

// Get service for editing
$edit_service = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM layanan WHERE id = ?");
    $stmt->execute([$id]);
    $edit_service = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Services - Web Bengkel Admin</title>
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
            <h2 class="text-2xl font-semibold mb-6">Manage Services</h2>
            <?php if (isset($message)): ?>
                <p class="text-green-500 mb-4"><?php echo $message; ?></p>
            <?php endif; ?>

            <!-- Add/Edit Form -->
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h3 class="text-lg font-semibold mb-4"><?php echo $edit_service ? 'Edit Service' : 'Add New Service'; ?></h3>
                <form method="post" action="" class="space-y-4">
                    <?php if ($edit_service): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_service['id']; ?>" />
                    <?php endif; ?>
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Name:</label>
                        <input type="text" id="nama" name="nama" required value="<?php echo $edit_service ? htmlspecialchars($edit_service['nama']) : ''; ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">Description:</label>
                        <textarea id="deskripsi" name="deskripsi" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"><?php echo $edit_service ? htmlspecialchars($edit_service['deskripsi']) : ''; ?></textarea>
                    </div>
                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-700">Price:</label>
                        <input type="number" step="0.01" id="harga" name="harga" required value="<?php echo $edit_service ? $edit_service['harga'] : ''; ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                    <button type="submit" name="<?php echo $edit_service ? 'update_service' : 'add_service'; ?>" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"><?php echo $edit_service ? 'Update Service' : 'Add Service'; ?></button>
                    <?php if ($edit_service): ?>
                        <a href="layanan.php" class="ml-4 text-gray-600 hover:text-gray-800">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Services List -->
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($layanan as $l): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $l['id']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($l['nama']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($l['deskripsi']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">Rp <?php echo number_format($l['harga'], 2, ',', '.'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="layanan.php?edit=<?php echo $l['id']; ?>" class="text-blue-600 hover:text-blue-800">Edit</a>
                                <a href="layanan.php?delete=<?php echo $l['id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:text-red-800 ml-4">Delete</a>
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
