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

// Handle add customer
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_customer'])) {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $stmt = $pdo->prepare("INSERT INTO pelanggan (nama, email, telepon, alamat) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nama, $email, $telepon, $alamat]);
    $message = "Customer added successfully.";
}

// Handle update customer
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_customer'])) {
    $id = $_POST['id'];
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $stmt = $pdo->prepare("UPDATE pelanggan SET nama = ?, email = ?, telepon = ?, alamat = ? WHERE id = ?");
    $stmt->execute([$nama, $email, $telepon, $alamat, $id]);
    $message = "Customer updated successfully.";
}

// Handle delete customer
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM pelanggan WHERE id = ?");
    $stmt->execute([$id]);
    $message = "Customer deleted successfully.";
}

// Fetch all customers
$stmt = $pdo->query("SELECT * FROM pelanggan ORDER BY created_at DESC");
$pelanggan = $stmt->fetchAll();

// Get customer for editing
$edit_customer = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM pelanggan WHERE id = ?");
    $stmt->execute([$id]);
    $edit_customer = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Customers - Web Bengkel Admin</title>
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
            <h2 class="text-2xl font-semibold mb-6">Manage Customers</h2>
            <?php if (isset($message)): ?>
                <p class="text-green-500 mb-4"><?php echo $message; ?></p>
            <?php endif; ?>

            <!-- Add/Edit Form -->
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h3 class="text-lg font-semibold mb-4"><?php echo $edit_customer ? 'Edit Customer' : 'Add New Customer'; ?></h3>
                <form method="post" action="" class="space-y-4">
                    <?php if ($edit_customer): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_customer['id']; ?>" />
                    <?php endif; ?>
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Name:</label>
                        <input type="text" id="nama" name="nama" required value="<?php echo $edit_customer ? htmlspecialchars($edit_customer['nama']) : ''; ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                        <input type="email" id="email" name="email" required value="<?php echo $edit_customer ? htmlspecialchars($edit_customer['email']) : ''; ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                    <div>
                        <label for="telepon" class="block text-sm font-medium text-gray-700">Phone:</label>
                        <input type="text" id="telepon" name="telepon" value="<?php echo $edit_customer ? htmlspecialchars($edit_customer['telepon']) : ''; ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Address:</label>
                        <textarea id="alamat" name="alamat" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"><?php echo $edit_customer ? htmlspecialchars($edit_customer['alamat']) : ''; ?></textarea>
                    </div>
                    <button type="submit" name="<?php echo $edit_customer ? 'update_customer' : 'add_customer'; ?>" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"><?php echo $edit_customer ? 'Update Customer' : 'Add Customer'; ?></button>
                    <?php if ($edit_customer): ?>
                        <a href="pelanggan.php" class="ml-4 text-gray-600 hover:text-gray-800">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Customers List -->
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($pelanggan as $p): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $p['id']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($p['nama']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($p['email']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($p['telepon']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($p['alamat']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="pelanggan.php?edit=<?php echo $p['id']; ?>" class="text-blue-600 hover:text-blue-800">Edit</a>
                                <a href="pelanggan.php?delete=<?php echo $p['id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:text-red-800 ml-4">Delete</a>
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
