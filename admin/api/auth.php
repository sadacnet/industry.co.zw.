<?php
/**
 * Admin API: Authentication
 * POST /admin/api/auth.php - Login
 * DELETE /admin/api/auth.php - Logout
 */

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../../api/config/database.php';
require_once __DIR__ . '/../includes/auth-check.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $database = new Database();
    $db = $database->getConnection();

    // Handle LOGIN
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        // Validate input
        if (!isset($input['username']) || empty($input['username'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Username is required"]);
            exit;
        }

        if (!isset($input['password']) || empty($input['password'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Password is required"]);
            exit;
        }

        // Get user from database
        $query = "SELECT id, username, password_hash, email FROM admin_users WHERE username = :username LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $input['username']);
        $stmt->execute();
        
        $user = $stmt->fetch();

        // Verify user exists
        if (!$user) {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "Invalid username or password"]);
            exit;
        }

        // Verify password
        if (!password_verify($input['password'], $user['password_hash'])) {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "Invalid username or password"]);
            exit;
        }

        // Login successful - set session
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_email'] = $user['email'];

        // Update last login
        $updateQuery = "UPDATE admin_users SET last_login = NOW() WHERE id = :id";
        $updateStmt = $db->prepare($updateQuery);
        $updateStmt->bindParam(':id', $user['id']);
        $updateStmt->execute();

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Login successful",
            "data" => [
                "id" => $user['id'],
                "username" => $user['username'],
                "email" => $user['email']
            ]
        ]);
    }

    // Handle LOGOUT
    elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        logoutAdmin();
    }

    else {
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Server error"]);
}
?>