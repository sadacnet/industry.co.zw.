<?php
/**
 * Admin Authentication Check
 * Include this file in all admin pages to verify login
 */

require_once __DIR__ . '/../../api/config/database.php';

/**
 * Include this file in all admin pages to verify login
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if admin is logged in
 * @return bool
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Require admin login - redirect if not logged in
 */
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        // If this is an API request, return JSON error
        if (strpos($_SERVER['REQUEST_URI'], '/admin/api/') !== false) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode([
                "status" => "error",
                "message" => "Authentication required. Please login."
            ]);
            exit;
        } else {
            // Otherwise redirect to login page
            header('Location: ' . SITE_ROOT . '/admin/login.php');
            exit;
        }
    }
}

/**
 * Get current admin user info
 * @return array|null
 */
function getCurrentAdmin() {
    if (!isAdminLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['admin_id'],
        'username' => $_SESSION['admin_username'],
        'email' => $_SESSION['admin_email']
    ];
}

/**
 * Logout admin
 */
function logoutAdmin() {
    // Unset all session variables
    $_SESSION = [];
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login
    header('Location: ' . SITE_ROOT . '/admin/login.php');
    exit;
}
?>