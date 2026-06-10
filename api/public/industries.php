<?php
/**
 * Public API: Industries
 * GET /api/public/industries.php - List all industries
 * GET /api/public/industries.php?slug=agriculture - Get single industry
 */

// Disable PHP error display for production
ini_set('display_errors', '0');
error_reporting(0);

// Set headers
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Include database
require_once __DIR__ . '/../config/database.php';

try {
    // Get database connection
    $database = new Database();
    $db = $database->getConnection();

    // Check if a specific industry is requested
    if (isset($_GET['slug'])) {
        // Get single industry
        $slug = $_GET['slug'];

        // Prepare query
        $query = "SELECT * FROM industries WHERE slug = :slug LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();

        $industry = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($industry) {
            // Get companies count for this industry
            $countQuery = "SELECT COUNT(*) as company_count FROM companies WHERE industry_id = :industry_id AND is_active = 1";
            $countStmt = $db->prepare($countQuery);
            $countStmt->bindParam(':industry_id', $industry['id'], PDO::PARAM_INT);
            $countStmt->execute();
            $countResult = $countStmt->fetch(PDO::FETCH_ASSOC);
            $industry['company_count'] = $countResult ? (int)$countResult['company_count'] : 0;

            // Return success response
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $industry
            ]);
        } else {
            // Industry not found
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Industry not found"
            ]);
        }
    } else {
        // Get all industries
        $query = "SELECT i.*, 
                  (SELECT COUNT(*) FROM companies WHERE industry_id = i.id AND is_active = 1) as company_count 
                  FROM industries i 
                  ORDER BY i.display_order ASC";

        $stmt = $db->prepare($query);
        $stmt->execute();

        $industries = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return success response
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "data" => $industries,
            "total" => count($industries)
        ]);
    }
} catch (PDOException $e) {
    // Handle database errors gracefully
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>