<?php
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

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $query = "SELECT * FROM exports ORDER BY category ASC, product_name ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $products = $stmt->fetchAll();
        
        // Parse exports_to and certifications for frontend
        foreach ($products as &$p) {
            $p['exports_to'] = $p['exports_to'] ? explode(',', $p['exports_to']) : [];
            $p['certifications'] = $p['certifications'] ? explode(',', $p['certifications']) : [];
        }
        
        http_response_code(200);
        echo json_encode(["status" => "success", "data" => $products]);
    }

    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['product_name']) || empty($input['product_name'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Product name is required"]);
            exit;
        }
        
        $query = "INSERT INTO exports (product_name, category, description, specs, price, moq, company, rating, reviews, exports_to, certifications, verified, image, is_active) 
                  VALUES (:product_name, :category, :description, :specs, :price, :moq, :company, :rating, :reviews, :exports_to, :certifications, :verified, :image, :is_active)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':product_name', $input['product_name']);
        $stmt->bindParam(':category', $input['category']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->bindParam(':specs', $input['specs']);
        $stmt->bindParam(':price', $input['price']);
        $stmt->bindParam(':moq', $input['moq']);
        $stmt->bindParam(':company', $input['company']);
        $stmt->bindParam(':rating', $input['rating']);
        $stmt->bindParam(':reviews', $input['reviews']);
        // Store arrays as comma-separated strings
        $exportsTo = is_array($input['exports_to']) ? implode(',', $input['exports_to']) : $input['exports_to'];
        $certifications = is_array($input['certifications']) ? implode(',', $input['certifications']) : $input['certifications'];
        $stmt->bindParam(':exports_to', $exportsTo);
        $stmt->bindParam(':certifications', $certifications);
        $stmt->bindParam(':verified', $input['verified']);
        $stmt->bindParam(':image', $input['image']);
        $stmt->bindParam(':is_active', $input['is_active']);
        
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["status" => "success", "message" => "Product created", "id" => $db->lastInsertId()]);
        }
    }

    elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Product ID required"]);
            exit;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $updates = [];
        $params = [':id' => $_GET['id']];
        
        $fields = ['product_name', 'category', 'description', 'specs', 'price', 'moq', 'company', 'rating', 'reviews', 'verified', 'image', 'is_active'];
        
        foreach ($fields as $field) {
            if (isset($input[$field])) {
                $updates[] = "$field = :$field";
                $params[":$field"] = $input[$field];
            }
        }
        
        // Handle array fields
        if (isset($input['exports_to'])) {
            $updates[] = "exports_to = :exports_to";
            $params[':exports_to'] = is_array($input['exports_to']) ? implode(',', $input['exports_to']) : $input['exports_to'];
        }
        if (isset($input['certifications'])) {
            $updates[] = "certifications = :certifications";
            $params[':certifications'] = is_array($input['certifications']) ? implode(',', $input['certifications']) : $input['certifications'];
        }
        
        if (empty($updates)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "No fields to update"]);
            exit;
        }
        
        $query = "UPDATE exports SET " . implode(', ', $updates) . " WHERE id = :id";
        $stmt = $db->prepare($query);
        foreach ($params as $key => $value) $stmt->bindValue($key, $value);
        
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Product updated"]);
        }
    }

    elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Product ID required"]);
            exit;
        }
        $query = "DELETE FROM exports WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Product deleted"]);
        }
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>