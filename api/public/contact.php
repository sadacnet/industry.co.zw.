<?php
/**
 * Public API: Contact Form
 * POST /api/public/contact.php
 * Body: JSON {name, email, phone, subject, message, recaptcha_token}
 */

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Only POST method is allowed"
    ]);
    exit;
}

require_once __DIR__ . '/../config/database.php';

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (!isset($input['name']) || empty(trim($input['name']))) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Name is required"
        ]);
        exit;
    }

    if (!isset($input['email']) || empty(trim($input['email']))) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Email is required"
        ]);
        exit;
    }

    if (!isset($input['message']) || empty(trim($input['message']))) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Message is required"
        ]);
        exit;
    }

    // Validate email format
    if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Invalid email format"
        ]);
        exit;
    }

    // Sanitize inputs
    $name = htmlspecialchars(strip_tags(trim($input['name'])));
    $email = htmlspecialchars(strip_tags(trim($input['email'])));
    $phone = isset($input['phone']) ? htmlspecialchars(strip_tags(trim($input['phone']))) : null;
    $subject = isset($input['subject']) ? htmlspecialchars(strip_tags(trim($input['subject']))) : null;
    $message = htmlspecialchars(strip_tags(trim($input['message'])));
    $recaptchaScore = isset($input['recaptcha_score']) ? floatval($input['recaptcha_score']) : null;

    // Get database connection
    $database = new Database();
    $db = $database->getConnection();

    // Insert enquiry
    $query = "INSERT INTO contact_enquiries (name, email, phone, subject, message, recaptcha_score) 
              VALUES (:name, :email, :phone, :subject, :message, :recaptcha_score)";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':subject', $subject);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':recaptcha_score', $recaptchaScore);
    
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode([
            "status" => "success",
            "message" => "Thank you for your message. We will get back to you soon.",
            "enquiry_id" => $db->lastInsertId()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Failed to send message. Please try again."
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Failed to send message"
    ]);
}
?>