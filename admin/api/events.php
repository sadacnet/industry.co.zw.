<?php
/**
 * Admin API: Events Management
 * GET /admin/api/events.php - List events
 * POST /admin/api/events.php - Create event
 * PUT /admin/api/events.php?id=1 - Update event
 * DELETE /admin/api/events.php?id=1 - Delete event
 */

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../../api/config/database.php';
require_once __DIR__ . '/../includes/auth-check.php';
requireAdminLogin();

try {
    $database = new Database();
    $db = $database->getConnection();

    // GET - List events
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $query = "SELECT * FROM events ORDER BY event_date DESC";
        
        // Pagination
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = isset($_GET['limit']) ? min(50, max(1, intval($_GET['limit']))) : 10;
        $offset = ($page - 1) * $limit;
        
        $countQuery = "SELECT COUNT(*) as total FROM events";
        $countStmt = $db->prepare($countQuery);
        $countStmt->execute();
        $totalCount = $countStmt->fetch()['total'];
        
        $query .= " LIMIT :limit OFFSET :offset";
        
        $stmt = $db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $events = $stmt->fetchAll();
        
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
    }

    // POST - Create event
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['title']) || !isset($input['event_date'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Title and event date are required"]);
            exit;
        }
        
        $query = "INSERT INTO events (title, organizer, event_date, end_date, location, description, poster) 
                  VALUES (:title, :organizer, :event_date, :end_date, :location, :description, :poster)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':title', $input['title']);
        $stmt->bindParam(':organizer', $input['organizer']);
        $stmt->bindParam(':event_date', $input['event_date']);
        $stmt->bindParam(':end_date', $input['end_date']);
        $stmt->bindParam(':location', $input['location']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->bindParam(':poster', $input['poster']);
        
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode([
                "status" => "success",
                "message" => "Event created successfully",
                "id" => $db->lastInsertId()
            ]);
        }
    }

    // PUT - Update event
    elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Event ID is required"]);
            exit;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $updates = [];
        $params = [':id' => $_GET['id']];
        
        $allowedFields = ['title', 'organizer', 'event_date', 'end_date', 'location', 'description', 'poster', 'is_active'];
        
        foreach ($allowedFields as $field) {
            if (isset($input[$field])) {
                $updates[] = "$field = :$field";
                $params[":$field"] = $input[$field];
            }
        }
        
        $query = "UPDATE events SET " . implode(', ', $updates) . " WHERE id = :id";
        
        $stmt = $db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Event updated successfully"]);
        }
    }

    // DELETE - Delete event
    elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Event ID is required"]);
            exit;
        }
        
        $query = "DELETE FROM events WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Event deleted successfully"]);
        }
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error"]);
}
?>