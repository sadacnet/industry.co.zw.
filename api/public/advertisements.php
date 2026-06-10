<?php
/**
 * Public API: Advertisements
 * GET /api/public/advertisements.php?stakeholder=CZI&type=banner
 * Required: stakeholder (CZI or CIFOZ)
 * Optional: type (logo, banner, flyer, poster)
 */

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    // Validate stakeholder parameter (required)
    if (!isset($_GET['stakeholder']) || empty($_GET['stakeholder'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Stakeholder parameter is required (CZI or CIFOZ)"
        ]);
        exit;
    }

    $stakeholder = strtoupper($_GET['stakeholder']);
    
    // Validate stakeholder value
    if (!in_array($stakeholder, ['CZI', 'CIFOZ'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Stakeholder must be CZI or CIFOZ"
        ]);
        exit;
    }

    // Base query
    $query = "SELECT * FROM advertisements WHERE stakeholder = :stakeholder AND is_active = 1";
    $params = [':stakeholder' => $stakeholder];

    // Filter by type (optional)
    $validTypes = ['logo', 'banner', 'flyer', 'poster'];
    if (isset($_GET['type']) && !empty($_GET['type'])) {
        $type = strtolower($_GET['type']);
        if (in_array($type, $validTypes)) {
            $query .= " AND type = :type";
            $params[':type'] = $type;
        }
    }

    // Order by display order
    $query .= " ORDER BY display_order ASC, created_at DESC";

    // Execute query
    $stmt = $db->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    
    $advertisements = $stmt->fetchAll();

    // Update view count for these ads
    if (!empty($advertisements)) {
        $updateStmt = $db->prepare("UPDATE advertisements SET views = views + 1 WHERE id = :id");
        foreach ($advertisements as $ad) {
            $updateStmt->bindValue(':id', $ad['id'], PDO::PARAM_INT);
            $updateStmt->execute();
        }
    }

    // Group by type for easier frontend use
    $grouped = [];
    foreach ($advertisements as $ad) {
        $grouped[$ad['type']][] = $ad;
    }

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => $advertisements,
        "grouped" => $grouped,
        "total" => count($advertisements)
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database error occurred"
    ]);
}
?>