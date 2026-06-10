<?php
/**
 * Public API: Provinces
 * GET /api/public/provinces.php - List all provinces
 * GET /api/public/provinces.php?slug=harare - Get single province
 */

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

    // Check if a specific province is requested
    if (isset($_GET['slug'])) {
        // Get single province
        $slug = $_GET['slug'];
        
        $query = "SELECT * FROM provinces WHERE slug = :slug LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        
        $province = $stmt->fetch();
        
        if ($province) {
            // Get companies count for this province
            $countQuery = "SELECT COUNT(*) as company_count 
                          FROM companies 
                          WHERE province_id = :province_id AND is_active = 1";
            $countStmt = $db->prepare($countQuery);
            $countStmt->bindParam(':province_id', $province['id']);
            $countStmt->execute();
            $countResult = $countStmt->fetch();
            
            $province['company_count'] = $countResult['company_count'];
            
            // Get industries in this province
            $industriesQuery = "SELECT DISTINCT i.id, i.name, i.slug,
                               (SELECT COUNT(*) FROM companies WHERE industry_id = i.id AND province_id = :province_id AND is_active = 1) as count
                               FROM industries i
                               JOIN companies c ON i.id = c.industry_id
                               WHERE c.province_id = :province_id2 AND c.is_active = 1
                               ORDER BY count DESC";
            $industriesStmt = $db->prepare($industriesQuery);
            $industriesStmt->bindParam(':province_id', $province['id']);
            $industriesStmt->bindParam(':province_id2', $province['id']);
            $industriesStmt->execute();
            
            $province['industries'] = $industriesStmt->fetchAll();
            
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $province
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Province not found"
            ]);
        }
        
    } else {
        // Get all provinces
        $query = "SELECT p.*, 
                  (SELECT COUNT(*) FROM companies WHERE province_id = p.id AND is_active = 1) as company_count 
                  FROM provinces p 
                  ORDER BY p.display_order ASC";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $provinces = $stmt->fetchAll();
        
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "data" => $provinces,
            "total" => count($provinces)
        ]);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database error occurred"
    ]);
}
?>