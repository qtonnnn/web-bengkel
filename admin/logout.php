<?php
// Include auth
require_once '../inc/auth.php';

// Logout admin
logoutAdmin();

// Redirect to login page
header('Location: login.php');
exit;
?>
