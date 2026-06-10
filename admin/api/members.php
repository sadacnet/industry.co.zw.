<?php
/**
 * Admin API: Members Management
 * GET /admin/api/members.php - List all members
 * GET /admin/api/members.php?id=1 - Get single member
 * POST /admin/api/members.php - Create member
 * PUT /admin/api/members.php?id=1 - Update member
 * DELETE /admin/api/members.php?id=1 - Delete member
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

    // ========== GET ==========
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        
        // Single member
        if (isset($_GET['id'])) {
            $query = "SELECT c.*, i.name as industry_name, p.name as province_name 
                      FROM companies c
                      JOIN industries i ON c.industry_id = i.id
                      JOIN provinces p ON c.province_id = p.id
                      WHERE c.id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
            $stmt->execute();
            
            $member = $stmt->fetch();
            
            if ($member) {
                http_response_code(200);
                echo json_encode(["status" => "success", "data" => $member]);
            } else {
                http_response_code(404);
                echo json_encode(["status" => "error", "message" => "Member not found"]);
            }
        } 
        // List all members
        else {
            $whereConditions = [];
            $params = [];
            
            $query = "SELECT c.*, i.name as industry_name, p.name as province_name 
                      FROM companies c
                      JOIN industries i ON c.industry_id = i.id
                      JOIN provinces p ON c.province_id = p.id
                      WHERE 1=1";
            
            // Filter by industry
            if (isset($_GET['industry']) && !empty($_GET['industry'])) {
                $whereConditions[] = "c.industry_id = :industry_id";
                $params[':industry_id'] = $_GET['industry'];
            }
            
            // Filter by stakeholder
            if (isset($_GET['stakeholder']) && !empty($_GET['stakeholder'])) {
                if (strtolower($_GET['stakeholder']) === 'null' || strtolower($_GET['stakeholder']) === 'none') {
                    $whereConditions[] = "(c.stakeholder IS NULL OR c.stakeholder = '')";
                } else {
                    $whereConditions[] = "c.stakeholder = :stakeholder";
                    $params[':stakeholder'] = strtoupper($_GET['stakeholder']);
                }
            }
            
            // Filter by listing_type
            if (isset($_GET['listing_type']) && !empty($_GET['listing_type'])) {
                $whereConditions[] = "c.listing_type = :listing_type";
                $params[':listing_type'] = $_GET['listing_type'];
            }
            
            // Filter by showcase tier
            if (isset($_GET['showcase_tier']) && !empty($_GET['showcase_tier'])) {
                $whereConditions[] = "c.showcase_tier = :showcase_tier";
                $params[':showcase_tier'] = $_GET['showcase_tier'];
            }
            
            // Filter by showcase active
            if (isset($_GET['showcase_active'])) {
                $whereConditions[] = "c.showcase_active = :showcase_active";
                $params[':showcase_active'] = $_GET['showcase_active'];
            }
            
            // Filter by active status
            if (isset($_GET['is_active'])) {
                $whereConditions[] = "c.is_active = :is_active";
                $params[':is_active'] = $_GET['is_active'];
            }
            
            // Search
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $searchTerm = "%" . $_GET['search'] . "%";
                $whereConditions[] = "(c.name LIKE :search1 OR c.email LIKE :search2 OR c.phone LIKE :search3)";
                $params[':search1'] = $searchTerm;
                $params[':search2'] = $searchTerm;
                $params[':search3'] = $searchTerm;
            }
            
            if (!empty($whereConditions)) {
                $query .= " AND " . implode(" AND ", $whereConditions);
            }
            
            $query .= " ORDER BY c.name ASC";
            
            // Pagination
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $limit = isset($_GET['limit']) ? min(5000, max(1, intval($_GET['limit']))) : 2000;
            $offset = ($page - 1) * $limit;
            
            // Count total
            $countQuery = "SELECT COUNT(*) as total FROM companies c 
                           JOIN industries i ON c.industry_id = i.id 
                           JOIN provinces p ON c.province_id = p.id 
                           WHERE 1=1";
            if (!empty($whereConditions)) {
                $countQuery .= " AND " . implode(" AND ", $whereConditions);
            }
            
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
            
            $stmt = $db->prepare($query);
            foreach ($params as $key => $value) {
                if (is_int($value)) {
                    $stmt->bindValue($key, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $value, PDO::PARAM_STR);
                }
            }
            $stmt->execute();
            
            $members = $stmt->fetchAll();
            
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $members,
                "total" => count($members),
                "pagination" => [
                    "current_page" => $page,
                    "per_page" => $limit,
                    "total_items" => intval($totalCount),
                    "total_pages" => $totalCount > 0 ? ceil($totalCount / $limit) : 1
                ]
            ]);
        }
    }

    // ========== POST ==========
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $requiredFields = ['name', 'industry_id', 'province_id'];
        foreach ($requiredFields as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "$field is required"]);
                exit;
            }
        }
        
        $query = "INSERT INTO companies (name, industry_id, province_id, stakeholder, listing_type, phone, email, website, logo, description, showcase_tier, showcase_order, showcase_active, showcase_tagline, showcase_full_description) 
                  VALUES (:name, :industry_id, :province_id, :stakeholder, :listing_type, :phone, :email, :website, :logo, :description, :showcase_tier, :showcase_order, :showcase_active, :showcase_tagline, :showcase_full_description)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':industry_id', $input['industry_id']);
        $stmt->bindParam(':province_id', $input['province_id']);
        $stmt->bindParam(':stakeholder', $input['stakeholder']);
        $stmt->bindParam(':listing_type', $input['listing_type']);
        $stmt->bindParam(':phone', $input['phone']);
        $stmt->bindParam(':email', $input['email']);
        $stmt->bindParam(':website', $input['website']);
        $stmt->bindParam(':logo', $input['logo']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->bindParam(':showcase_tier', $input['showcase_tier']);
        $stmt->bindParam(':showcase_order', $input['showcase_order']);
        $stmt->bindParam(':showcase_active', $input['showcase_active']);
        $stmt->bindParam(':showcase_tagline', $input['showcase_tagline']);
        $stmt->bindParam(':showcase_full_description', $input['showcase_full_description']);
        
        if ($stmt->execute()) {
            $newId = $db->lastInsertId();
            http_response_code(201);
            echo json_encode(["status" => "success", "message" => "Member created", "id" => $newId]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Failed to create member"]);
        }
    }

    // ========== PUT ==========
    elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Member ID is required"]);
            exit;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $updates = [];
        $params = [':id' => $_GET['id']];
        
        $allowedFields = ['name', 'industry_id', 'province_id', 'stakeholder', 'listing_type', 'phone', 'email', 'website', 'logo', 'description', 'is_active', 'showcase_tier', 'showcase_order', 'showcase_active', 'showcase_tagline', 'showcase_full_description'];
        
        foreach ($allowedFields as $field) {
            if (isset($input[$field])) {
                $updates[] = "$field = :$field";
                $params[":$field"] = $input[$field];
            }
        }
        
        if (empty($updates)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "No fields to update"]);
            exit;
        }
        
        $query = "UPDATE companies SET " . implode(', ', $updates) . " WHERE id = :id";
        
        $stmt = $db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Member updated"]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Failed to update member"]);
        }
    }

    // ========== DELETE ==========
    elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Member ID is required"]);
            exit;
        }
        
        $query = "DELETE FROM companies WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Member deleted"]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Failed to delete member"]);
        }
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>