<?php
/**
 * Public API: Tenders
 * Returns ALL tender fields
 */
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    // Get ALL columns
    $query = "SELECT * FROM tenders WHERE is_active = 1";
    $params = [];

    // Filter active (not expired)
    if (isset($_GET['active']) && $_GET['active'] == '1') {
        $query .= " AND closing_date >= CURDATE()";
    }

    // Filter by category
    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $query .= " AND category = :category";
        $params[':category'] = $_GET['category'];
    }

    // Order by closing date (closest first)
    $query .= " ORDER BY closing_date ASC";

    // Pagination
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? min(50, max(1, intval($_GET['limit']))) : 50;
    $offset = ($page - 1) * $limit;

    // Get total count
    $countQuery = str_replace("SELECT *", "SELECT COUNT(*) as total", $query);
    $countStmt = $db->prepare($countQuery);
    foreach ($params as $key => $value) {
        $countStmt->bindValue($key, $value);
    }
    $countStmt->execute();
    $totalCount = $countStmt->fetch()['total'];

    // Add limit
    $query .= " LIMIT :limit OFFSET :offset";
    $params[':limit'] = $limit;
    $params[':offset'] = $offset;

    // Execute
    $stmt = $db->prepare($query);
    foreach ($params as $key => $value) {
        if (is_int($value)) {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
    }
    $stmt->execute();
    
    $tenders = $stmt->fetchAll();

    // Add calculated fields
    foreach ($tenders as &$tender) {
        $closingDate = new DateTime($tender['closing_date']);
        $today = new DateTime();
        $interval = $today->diff($closingDate);
        $tender['days_remaining'] = $closingDate >= $today ? $interval->days : -$interval->days;
        $tender['is_expired'] = $closingDate < $today;
        
        // Ensure all fields exist (even if null)
        $tender['tender_number'] = $tender['tender_number'] ?? null;
        $tender['issuing_organization'] = $tender['issuing_organization'] ?? null;
        $tender['category'] = $tender['category'] ?? null;
        $tender['budget'] = $tender['budget'] ?? null;
        $tender['location'] = $tender['location'] ?? null;
        $tender['contact_email'] = $tender['contact_email'] ?? null;
        $tender['contact_phone'] = $tender['contact_phone'] ?? null;
        $tender['submission_requirements'] = $tender['submission_requirements'] ?? null;
        $tender['eligibility_criteria'] = $tender['eligibility_criteria'] ?? null;
        $tender['bid_opening_date'] = $tender['bid_opening_date'] ?? null;
        $tender['document_url'] = $tender['document_url'] ?? null;
        $tender['document_url2'] = $tender['document_url2'] ?? null;
        $tender['document_url3'] = $tender['document_url3'] ?? null;
    }

    // Get unique categories for filter
    $catQuery = "SELECT DISTINCT category FROM tenders WHERE is_active = 1 AND category IS NOT NULL AND category != '' ORDER BY category";
    $catStmt = $db->prepare($catQuery);
    $catStmt->execute();
    $categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => $tenders,
        "categories" => $categories,
        "pagination" => [
            "current_page" => $page,
            "per_page" => $limit,
            "total_items" => intval($totalCount),
            "total_pages" => ceil($totalCount / $limit)
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>