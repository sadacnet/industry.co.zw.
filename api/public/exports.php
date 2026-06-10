<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT * FROM exports WHERE is_active = 1";
    $params = [];

    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $query .= " AND category = :category";
        $params[':category'] = $_GET['category'];
    }

    $query .= " ORDER BY category ASC, product_name ASC";

    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? min(50, max(1, intval($_GET['limit']))) : 50;
    $offset = ($page - 1) * $limit;

    $countQuery = str_replace("SELECT *", "SELECT COUNT(*) as total", $query);
    $countStmt = $db->prepare($countQuery);
    foreach ($params as $key => $value) $countStmt->bindValue($key, $value);
    $countStmt->execute();
    $totalCount = $countStmt->fetch()['total'];

    $query .= " LIMIT :limit OFFSET :offset";
    $params[':limit'] = $limit;
    $params[':offset'] = $offset;

    $stmt = $db->prepare($query);
    foreach ($params as $key => $value) {
        if (is_int($value)) $stmt->bindValue($key, $value, PDO::PARAM_INT);
        else $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }
    $stmt->execute();
    $exports = $stmt->fetchAll();

    // Parse comma-separated fields
    foreach ($exports as &$item) {
        $item['exports_to'] = $item['exports_to'] ? explode(',', $item['exports_to']) : [];
        $item['certifications'] = $item['certifications'] ? explode(',', $item['certifications']) : [];
        $item['verified'] = (bool)$item['verified'];
    }

    $catQuery = "SELECT DISTINCT category FROM exports WHERE is_active = 1 ORDER BY category";
    $catStmt = $db->prepare($catQuery);
    $catStmt->execute();
    $categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => $exports,
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
    echo json_encode(["status" => "error", "message" => "Database error"]);
}
?>