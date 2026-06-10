<?php
/**
 * Admin API: Dashboard Statistics
 * GET /admin/api/dashboard.php
 */

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');

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
    
    $stats = [];
    
    // Total companies
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM companies");
    $stmt->execute();
    $stats['total_companies'] = $stmt->fetch()['total'];
    
    // Active companies
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM companies WHERE is_active = 1");
    $stmt->execute();
    $stats['active_companies'] = $stmt->fetch()['total'];
    
    // Companies by stakeholder
    $stmt = $db->prepare("SELECT stakeholder, COUNT(*) as count FROM companies WHERE stakeholder IS NOT NULL GROUP BY stakeholder");
    $stmt->execute();
    $stats['stakeholders'] = $stmt->fetchAll();
    
    // Total events
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM events");
    $stmt->execute();
    $stats['total_events'] = $stmt->fetch()['total'];
    
    // Upcoming events
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM events WHERE event_date >= CURDATE()");
    $stmt->execute();
    $stats['upcoming_events'] = $stmt->fetch()['total'];
    
    // Active tenders
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM tenders WHERE is_active = 1 AND closing_date >= CURDATE()");
    $stmt->execute();
    $stats['active_tenders'] = $stmt->fetch()['total'];
    
    // Total advertisements
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM advertisements");
    $stmt->execute();
    $stats['total_advertisements'] = $stmt->fetch()['total'];
    
    // Recent contact enquiries
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM contact_enquiries WHERE is_read = 0");
    $stmt->execute();
    $stats['unread_messages'] = $stmt->fetch()['total'];
    
    // Companies by industry
    $stmt = $db->prepare("
        SELECT i.name, COUNT(c.id) as count 
        FROM industries i 
        LEFT JOIN companies c ON i.id = c.industry_id 
        GROUP BY i.id 
        ORDER BY count DESC
    ");
    $stmt->execute();
    $stats['companies_by_industry'] = $stmt->fetchAll();
    
    http_response_code(200);
    echo json_encode(["status" => "success", "data" => $stats]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error"]);
}
?>
