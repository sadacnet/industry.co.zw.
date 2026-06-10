<?php
/**
 * Public API: Companies
 * GET /api/public/companies.php - List companies with filters
 * GET /api/public/companies.php?industry=agriculture - Filter by industry
 * GET /api/public/companies.php?province=harare - Filter by province
 * GET /api/public/companies.php?stakeholder=CZI - Filter by stakeholder (CZI/CIFOZ)
 * GET /api/public/companies.php?stakeholder=null - Filter industry listings only (no stakeholder)
 * GET /api/public/companies.php?listing_type=industry - Filter by listing type
 * GET /api/public/companies.php?search=term - Search companies
 * GET /api/public/companies.php?page=1&limit=20 - Pagination
 * GET /api/public/companies.php?industry=agriculture&showcase=true - Get showcase companies only
 */

ini_set('display_errors', '0');
error_reporting(0);

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    $whereConditions = [];
    $params = [];
    
    $response = [];

    // Check if we need showcase companies only
    $showcaseOnly = isset($_GET['showcase']) && $_GET['showcase'] === 'true';
    $industrySlug = isset($_GET['industry']) ? $_GET['industry'] : '';

    if ($showcaseOnly && !empty($industrySlug)) {
        // GET SHOWCASE COMPANIES FOR THIS INDUSTRY
        $showcaseQuery = "SELECT 
                            c.*,
                            i.name as industry_name,
                            i.slug as industry_slug,
                            p.name as province_name,
                            p.slug as province_slug
                          FROM companies c
                          JOIN industries i ON c.industry_id = i.id
                          JOIN provinces p ON c.province_id = p.id
                          WHERE c.is_active = 1 
                            AND c.showcase_active = 1
                            AND i.slug = :industry_slug
                          ORDER BY FIELD(c.showcase_tier, 'platinum', 'gold', 'silver'), c.showcase_order ASC
                          LIMIT 30";
        
        $showcaseStmt = $db->prepare($showcaseQuery);
        $showcaseStmt->bindParam(':industry_slug', $industrySlug);
        $showcaseStmt->execute();
        $showcaseCompanies = $showcaseStmt->fetchAll(PDO::FETCH_ASSOC);
        
        $response['showcase'] = $showcaseCompanies;
    }

    // MAIN COMPANIES QUERY
    $query = "SELECT 
                c.*,
                i.name as industry_name,
                i.slug as industry_slug,
                p.name as province_name,
                p.slug as province_slug
              FROM companies c
              JOIN industries i ON c.industry_id = i.id
              JOIN provinces p ON c.province_id = p.id
              WHERE c.is_active = 1";

    // Filter by industry slug
    if (isset($_GET['industry']) && !empty($_GET['industry'])) {
        $whereConditions[] = "i.slug = :industry_slug";
        $params[':industry_slug'] = $_GET['industry'];
    }

    // Filter by province slug
    if (isset($_GET['province']) && !empty($_GET['province'])) {
        $whereConditions[] = "p.slug = :province_slug";
        $params[':province_slug'] = $_GET['province'];
    }

    // Filter by stakeholder (CZI, CIFOZ, or null for industry listings)
    if (isset($_GET['stakeholder']) && $_GET['stakeholder'] !== '') {
        $stakeholder = strtoupper($_GET['stakeholder']);
        
        // Handle null/empty stakeholder for industry.co.zw own listings
        if ($stakeholder === 'NULL' || $stakeholder === 'NONE') {
            $whereConditions[] = "(c.stakeholder IS NULL OR c.stakeholder = '')";
        } elseif (in_array($stakeholder, ['CZI', 'CIFOZ'])) {
            $whereConditions[] = "c.stakeholder = :stakeholder";
            $params[':stakeholder'] = $stakeholder;
        }
    }

    // Filter by listing_type (industry or partner)
    if (isset($_GET['listing_type']) && !empty($_GET['listing_type'])) {
        if ($_GET['listing_type'] === 'industry') {
            $whereConditions[] = "(c.stakeholder IS NULL OR c.stakeholder = '' OR c.listing_type = 'industry')";
        } elseif ($_GET['listing_type'] === 'partner') {
            $whereConditions[] = "c.stakeholder IN ('CZI', 'CIFOZ')";
        }
    }

    // Search functionality
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searchTerm = "%" . $_GET['search'] . "%";
        $whereConditions[] = "(c.name LIKE :search_name OR c.description LIKE :search_desc OR c.email LIKE :search_email)";
        $params[':search_name'] = $searchTerm;
        $params[':search_desc'] = $searchTerm;
        $params[':search_email'] = $searchTerm;
    }

    // Add WHERE conditions to query
    if (!empty($whereConditions)) {
        $query .= " AND " . implode(" AND ", $whereConditions);
    }

    // Count query
    $countQuery = "SELECT COUNT(*) as total FROM companies c 
                   JOIN industries i ON c.industry_id = i.id 
                   JOIN provinces p ON c.province_id = p.id 
                   WHERE c.is_active = 1";
    
    if (!empty($whereConditions)) {
        $countQuery .= " AND " . implode(" AND ", $whereConditions);
    }

    $countStmt = $db->prepare($countQuery);
    foreach ($params as $key => $value) {
        $countStmt->bindValue($key, $value);
    }
    $countStmt->execute();
    $totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Ordering - put showcase companies first if requested
    if (isset($_GET['industry']) && !empty($_GET['industry'])) {
        $query .= " ORDER BY CASE 
                        WHEN c.showcase_active = 1 THEN 0 
                        ELSE 1 
                    END, c.name ASC";
    } else {
        $query .= " ORDER BY c.name ASC";
    }

    // Pagination
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? min(5000, max(1, intval($_GET['limit']))) : 500;
    $offset = ($page - 1) * $limit;

    $query .= " LIMIT :limit OFFSET :offset";
    $params[':limit'] = $limit;
    $params[':offset'] = $offset;

    // Execute main query
    $stmt = $db->prepare($query);
    foreach ($params as $key => $value) {
        if (is_int($value)) {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
    }
    $stmt->execute();

    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Build response
    $response['status'] = 'success';
    $response['data'] = $companies;
    $response['pagination'] = [
        "current_page" => $page,
        "per_page" => $limit,
        "total_items" => intval($totalCount),
        "total_pages" => $totalCount > 0 ? ceil($totalCount / $limit) : 1
    ];

    http_response_code(200);
    echo json_encode($response);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error occurred"]);
}
?>