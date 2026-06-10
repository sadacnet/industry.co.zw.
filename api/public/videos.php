<?php
/**
 * Public API: Videos
 * GET /api/public/videos.php - List videos
 * GET /api/public/videos.php?category=events - Filter by category
 */

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    // Base query
    $query = "SELECT * FROM videos";
    $params = [];

    // Filter by category
    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $query .= " WHERE category = :category";
        $params[':category'] = $_GET['category'];
    }

    // Order by newest first
    $query .= " ORDER BY created_at DESC";

    // Pagination
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? min(50, max(1, intval($_GET['limit']))) : 9;
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
    
    $videos = $stmt->fetchAll();

    // Get categories
    $catQuery = "SELECT DISTINCT category FROM videos ORDER BY category";
    $catStmt = $db->prepare($catQuery);
    $catStmt->execute();
    $categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => $videos,
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
        "message" => "Database error occurred"
    ]);
}
?>