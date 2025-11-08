<?php
session_start();

// Function to check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Function to login admin
function loginAdmin($username, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, password FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_id'] = $user['id'];
        return true;
    }
    return false;
}

// Function to logout admin
function logoutAdmin() {
    session_destroy();
}
?>
