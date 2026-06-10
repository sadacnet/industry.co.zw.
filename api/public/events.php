<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT * FROM events WHERE is_active = 1";
    $params = [];

    if (isset($_GET['organizer']) && !empty($_GET['organizer'])) {
        $organizer = strtoupper($_GET['organizer']);
        if (in_array($organizer, ['CZI', 'CIFOZ'])) {
            $query .= " AND organizer = :organizer";
            $params[':organizer'] = $organizer;
        }
    }

    if (isset($_GET['upcoming']) && $_GET['upcoming'] === 'true') {
        $query .= " AND (event_date >= CURDATE() OR end_date >= CURDATE())";
    }

    $query .= " ORDER BY event_date ASC";

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
    
    $events = $stmt->fetchAll();

    foreach ($events as &$event) {
        $eventDate = new DateTime($event['event_date']);
        $today = new DateTime();
        $interval = $today->diff($eventDate);
        $event['days_until'] = $eventDate >= $today ? $interval->days : -$interval->days;
        $event['is_upcoming'] = $eventDate >= $today;
    }

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => $events,
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