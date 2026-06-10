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

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $query = "SELECT * FROM tenders ORDER BY closing_date ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $tenders = $stmt->fetchAll();
        foreach ($tenders as &$t) {
            $t['status'] = (strtotime($t['closing_date']) >= time()) ? 'active' : 'expired';
        }
        http_response_code(200);
        echo json_encode(["status" => "success", "data" => $tenders]);
    }

    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $query = "INSERT INTO tenders (title, tender_number, description, issuing_organization, category, budget, location, contact_email, contact_phone, submission_requirements, eligibility_criteria, closing_date, bid_opening_date, document_url, document_url2, document_url3, is_active) 
                  VALUES (:title, :tender_number, :description, :issuing_organization, :category, :budget, :location, :contact_email, :contact_phone, :submission_requirements, :eligibility_criteria, :closing_date, :bid_opening_date, :document_url, :document_url2, :document_url3, :is_active)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':title', $input['title']);
        $stmt->bindParam(':tender_number', $input['tender_number']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->bindParam(':issuing_organization', $input['issuing_organization']);
        $stmt->bindParam(':category', $input['category']);
        $stmt->bindParam(':budget', $input['budget']);
        $stmt->bindParam(':location', $input['location']);
        $stmt->bindParam(':contact_email', $input['contact_email']);
        $stmt->bindParam(':contact_phone', $input['contact_phone']);
        $stmt->bindParam(':submission_requirements', $input['submission_requirements']);
        $stmt->bindParam(':eligibility_criteria', $input['eligibility_criteria']);
        $stmt->bindParam(':closing_date', $input['closing_date']);
        $stmt->bindParam(':bid_opening_date', $input['bid_opening_date']);
        $stmt->bindParam(':document_url', $input['document_url']);
        $stmt->bindParam(':document_url2', $input['document_url2']);
        $stmt->bindParam(':document_url3', $input['document_url3']);
        $stmt->bindParam(':is_active', $input['is_active']);
        
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["status" => "success", "message" => "Tender created", "id" => $db->lastInsertId()]);
        }
    }

    elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $input = json_decode(file_get_contents('php://input'), true);
        $updates = [];
        $params = [':id' => $_GET['id']];
        
        $fields = ['title', 'tender_number', 'description', 'issuing_organization', 'category', 'budget', 'location', 'contact_email', 'contact_phone', 'submission_requirements', 'eligibility_criteria', 'closing_date', 'bid_opening_date', 'document_url', 'document_url2', 'document_url3', 'is_active'];
        
        foreach ($fields as $field) {
            if (isset($input[$field])) {
                $updates[] = "$field = :$field";
                $params[":$field"] = $input[$field];
            }
        }
        
        if (empty($updates)) { http_response_code(400); echo json_encode(["status" => "error", "message" => "No fields"]); exit; }
        
        $query = "UPDATE tenders SET " . implode(', ', $updates) . " WHERE id = :id";
        $stmt = $db->prepare($query);
        foreach ($params as $key => $value) $stmt->bindValue($key, $value);
        if ($stmt->execute()) { echo json_encode(["status" => "success", "message" => "Tender updated"]); }
    }

    elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $query = "DELETE FROM tenders WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        if ($stmt->execute()) { echo json_encode(["status" => "success", "message" => "Tender deleted"]); }
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error"]);
}
?>