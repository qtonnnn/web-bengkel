<?php
// File: inc/koneksi.php

$host     = "localhost";   // biasanya "localhost"
$user     = "root";        // default user XAMPP/MAMP
$pass     = "";            // password MySQL kamu (kosong di XAMPP default)
$db       = "webbengkel";  // nama database yang sudah kamu buat

// Buat koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Set charset biar aman untuk UTF-8
$conn->set_charset("utf8mb4");
?>
