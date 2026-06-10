<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/../../api/config/database.php';
require_once __DIR__ . '/../includes/auth-check.php';
requireAdminLogin();

try {
    $database = new Database();
    $db = $database->getConnection();

    // GET - List all ads
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $query = "SELECT * FROM advertisements ORDER BY stakeholder ASC, type ASC, display_order ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $ads = $stmt->fetchAll();
        http_response_code(200);
        echo json_encode(["status" => "success", "data" => $ads]);
    }

    // POST - Create ad
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $query = "INSERT INTO advertisements (stakeholder, type, title, file_path, file_type, link_url, display_order, is_active) 
                  VALUES (:stakeholder, :type, :title, :file_path, :file_type, :link_url, :display_order, :is_active)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':stakeholder', $input['stakeholder']);
        $stmt->bindParam(':type', $input['type']);
        $stmt->bindParam(':title', $input['title']);
        $stmt->bindParam(':file_path', $input['file_path']);
        $stmt->bindParam(':file_type', $input['file_type']);
        $stmt->bindParam(':link_url', $input['link_url']);
        $stmt->bindParam(':display_order', $input['display_order']);
        $stmt->bindParam(':is_active', $input['is_active']);
        
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["status" => "success", "message" => "Ad created", "id" => $db->lastInsertId()]);
        }
    }

    // PUT - Update ad
    elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $input = json_decode(file_get_contents('php://input'), true);
        $updates = [];
        $params = [':id' => $_GET['id']];
        
        $fields = ['stakeholder', 'type', 'title', 'file_path', 'file_type', 'link_url', 'display_order', 'is_active'];
        foreach ($fields as $field) {
            if (isset($input[$field])) {
                $updates[] = "$field = :$field";
                $params[":$field"] = $input[$field];
            }
        }
        
        if (empty($updates)) { http_response_code(400); echo json_encode(["status" => "error", "message" => "No fields"]); exit; }
        
        $query = "UPDATE advertisements SET " . implode(', ', $updates) . " WHERE id = :id";
        $stmt = $db->prepare($query);
        foreach ($params as $key => $value) $stmt->bindValue($key, $value);
        if ($stmt->execute()) { echo json_encode(["status" => "success", "message" => "Updated"]); }
    }

    // DELETE - Delete ad
    elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $query = "DELETE FROM advertisements WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        if ($stmt->execute()) { echo json_encode(["status" => "success", "message" => "Deleted"]); }
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error"]);
}
?>