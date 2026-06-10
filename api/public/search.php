<?php
/**
 * Public API: Global Search
 * GET /api/public/search.php?q=searchterm&type=all
 * Types: all, companies, events, tenders, exports
 */

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/../config/database.php';

try {
    // Validate search query
    if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Search query parameter 'q' is required"
        ]);
        exit;
    }

    $database = new Database();
    $db = $database->getConnection();

    $searchTerm = "%" . trim($_GET['q']) . "%";
    $type = isset($_GET['type']) ? $_GET['type'] : 'all';
    
    $results = [];

    // Search Companies
    if ($type === 'all' || $type === 'companies') {
        $query = "SELECT 
                    c.id, c.name, c.description, c.logo, c.stakeholder,
                    i.name as industry_name, i.slug as industry_slug,
                    p.name as province_name, p.slug as province_slug,
                    'company' as result_type
                  FROM companies c
                  JOIN industries i ON c.industry_id = i.id
                  JOIN provinces p ON c.province_id = p.id
                  WHERE c.is_active = 1 
                  AND (c.name LIKE :search1 
                       OR c.description LIKE :search2 
                       OR i.name LIKE :search3)
                  ORDER BY c.name ASC
                  LIMIT 5";
        
        $stmt = $db->prepare($query);
        $stmt->bindValue(':search1', $searchTerm);
        $stmt->bindValue(':search2', $searchTerm);
        $stmt->bindValue(':search3', $searchTerm);
        $stmt->execute();
        $results['companies'] = $stmt->fetchAll();
    }

    // Search Events
    if ($type === 'all' || $type === 'events') {
        $query = "SELECT 
                    id, title, description, event_date, location, organizer,
                    'event' as result_type
                  FROM events 
                  WHERE is_active = 1 
                  AND (title LIKE :search1 
                       OR description LIKE :search2 
                       OR location LIKE :search3)
                  ORDER BY event_date ASC
                  LIMIT 5";
        
        $stmt = $db->prepare($query);
        $stmt->bindValue(':search1', $searchTerm);
        $stmt->bindValue(':search2', $searchTerm);
        $stmt->bindValue(':search3', $searchTerm);
        $stmt->execute();
        $results['events'] = $stmt->fetchAll();
    }

    // Search Tenders
    if ($type === 'all' || $type === 'tenders') {
        $query = "SELECT 
                    id, title, description, closing_date,
                    'tender' as result_type
                  FROM tenders 
                  WHERE is_active = 1 
                  AND (title LIKE :search1 
                       OR description LIKE :search2)
                  ORDER BY closing_date ASC
                  LIMIT 5";
        
        $stmt = $db->prepare($query);
        $stmt->bindValue(':search1', $searchTerm);
        $stmt->bindValue(':search2', $searchTerm);
        $stmt->execute();
        $results['tenders'] = $stmt->fetchAll();
    }

    // Search Exports
    if ($type === 'all' || $type === 'exports') {
        $query = "SELECT 
                    id, product_name, description, category, image,
                    'export' as result_type
                  FROM exports 
                  WHERE is_active = 1 
                  AND (product_name LIKE :search1 
                       OR description LIKE :search2 
                       OR category LIKE :search3)
                  ORDER BY product_name ASC
                  LIMIT 5";
        
        $stmt = $db->prepare($query);
        $stmt->bindValue(':search1', $searchTerm);
        $stmt->bindValue(':search2', $searchTerm);
        $stmt->bindValue(':search3', $searchTerm);
        $stmt->execute();
        $results['exports'] = $stmt->fetchAll();
    }

    // Calculate totals
    $totalResults = 0;
    foreach ($results as $category => $items) {
        $totalResults += count($items);
    }

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "query" => trim($_GET['q']),
        "total_results" => $totalResults,
        "data" => $results
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Search failed"
    ]);
}
?>