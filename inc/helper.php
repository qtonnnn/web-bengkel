<?php
// Helper functions for Web Bengkel

// Function to sanitize input
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to format currency
function format_currency($amount) {
    return 'Rp ' . number_format($amount, 2, ',', '.');
}

// Function to format date
function format_date($date) {
    return date('d M Y', strtotime($date));
}

// Function to format datetime
function format_datetime($datetime) {
    return date('d M Y H:i', strtotime($datetime));
}

// Function to get status badge class
function get_status_class($status) {
    switch ($status) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'confirmed':
            return 'bg-blue-100 text-blue-800';
        case 'completed':
            return 'bg-green-100 text-green-800';
        case 'cancelled':
            return 'bg-red-100 text-red-800';
        case 'paid':
            return 'bg-green-100 text-green-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

// Function to check if user is admin (alias for auth function)
function is_logged_in() {
    return isset($_SESSION['admin_id']);
}

// Function to redirect
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// Function to display error message
function display_error($message) {
    return '<p class="text-red-500">' . sanitize($message) . '</p>';
}

// Function to display success message
function display_success($message) {
    return '<p class="text-green-500">' . sanitize($message) . '</p>';
}
?>
