<?php
$org = isset($_GET['org']) ? strtoupper($_GET['org']) : 'CZI';
$section = isset($_GET['section']) ? $_GET['section'] : 'directory';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$targetCompanyId = isset($_GET['company']) ? intval($_GET['company']) : null;

if (!in_array($org, ['CZI', 'CIFOZ'])) { 
    $org = 'CZI'; 
}

if (!in_array($section, ['directory', 'events', 'networking'])) { 
    $section = 'directory'; 
}

$pageTitle = $org . " - " . ucfirst($section);

// CZI Brand Colors (Blue theme)
if ($org === 'CZI') {
    $orgFullName = 'Confederation of Zimbabwe Industries';
    $orgDesc = 'Representing Zimbabwe\'s industrial sector';
    $orgColor = '#003366';
    $orgColorLight = '#e8f0fe';
    $orgColorDark = '#002244';
    $orgColorBg = '#e8f0fe';
    $orgIcon = 'bi-building';
    $orgStakeholder = 'CZI';
} else {
    // CIFOZ Brand Colors (Deep Navy/Purple-Blue #28256a)
    $orgFullName = 'Construction Industry Federation of Zimbabwe';
    $orgDesc = 'Leading Zimbabwe\'s construction industry';
    $orgColor = '#28256a';
    $orgColorLight = '#8b88c4';
    $orgColorDark = '#1a1845';
    $orgColorBg = '#eeedf5';
    $orgIcon = 'bi-cone-striped';
    $orgStakeholder = 'CIFOZ';
}

require_once __DIR__ . '/includes/head.php';

// Fetch categories for CIFOZ filter sidebar
$cifozCategories = [];
$categoryDisplayNames = [];
$categorySlugToSearchMap = [];

if ($org === 'CIFOZ') {
    try {
        require_once __DIR__ . '/api/config/database.php';
        $database = new Database();
        $db = $database->getConnection();
        
        // Get all categories for display name mapping
        $stmt = $db->query("SELECT slug, name FROM cifoz_categories");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categoryDisplayNames[$row['slug']] = $row['name'];
            
            // Create search mapping
            $searchTerm = '';
            switch($row['slug']) {
                case 'gc-building': $searchTerm = 'building'; break;
                case 'gc-category-a': $searchTerm = 'category-a'; break;
                case 'gc-category-b': $searchTerm = 'category-b'; break;
                case 'gc-category-c': $searchTerm = 'category-c'; break;
                case 'gc-category-d': $searchTerm = 'category-d'; break;
                case 'gc-category-e': $searchTerm = 'category-e'; break;
                case 'gc-category-f': $searchTerm = 'category-f'; break;
                case 'gc-category-g': $searchTerm = 'category-g'; break;
                case 'gc-civil-engineering': $searchTerm = 'civil-engineering'; break;
                case 'sc-electrical': $searchTerm = 'electrical'; break;
                case 'sc-electro-mechanical': $searchTerm = 'electro-mechanical-engineers'; break;
                case 'sc-joinery-shopfitting': $searchTerm = 'joinery-and-shopfitting'; break;
                case 'sc-plumbing': $searchTerm = 'plumbingdrain-laying-and-sheeting'; break;
                case 'sc-roofing': $searchTerm = 'roof-slatingtiling-and-sheeting'; break;
                case 'sc-scaffolding': $searchTerm = 'scaffolding-and-formwork-specialists'; break;
                case 'sc-painting-decorating': $searchTerm = 'painting-and-decoratingsign-writting'; break;
                case 'sc-fencing-precast': $searchTerm = 'fencing-precast-walling-and-structures'; break;
                case 'sc-acoustic': $searchTerm = 'acoustic-engineering'; break;
                case 'sc-fire-protection': $searchTerm = 'fire-protection-and-sprinkler-engineers'; break;
                case 'sc-excavation-earthmoving': $searchTerm = 'excavation-and-earth-moving-road-works-etc'; break;
                case 'sc-flooring-waterproofing': $searchTerm = 'patent-flooring-and-floor-layersroofwater-proofing-and-tanking'; break;
                case 'sc-art-metal': $searchTerm = 'art-metal-workaluminium-and-steel-window-specialist'; break;
                case 'sc-suppliers-hires': $searchTerm = 'suppliers-and-hires-of-earth-moving-equipment'; break;
                case 'sc-boreholes': $searchTerm = 'boreholes-and-allied-services'; break;
                case 'sc-security-systems': $searchTerm = 'burglarfire-detection-alarm-systems'; break;
                case 'sc-tiling-marble': $searchTerm = 'wall-tilingmosaics-and-marble-workersterrazzo-specialistsreconstruction'; break;
                case 'sc-pest-control': $searchTerm = 'fumigation-and-pest-control'; break;
                case 'sc-structural-steel': $searchTerm = 'structural-engineerssteel-reinforcing-engineers'; break;
                default: $searchTerm = strtolower($row['name']);
            }
            $categorySlugToSearchMap[$row['slug']] = $searchTerm;
        }
        
        // Get parent categories with counts
        $stmt = $db->prepare("
            SELECT 
                cat.id,
                cat.slug,
                cat.name,
                cat.level,
                COUNT(DISTINCT cc.company_id) as total
            FROM cifoz_categories cat
            LEFT JOIN company_cifoz_categories cc ON cat.id = cc.category_id
            LEFT JOIN companies c ON cc.company_id = c.id AND c.is_active = 1 AND c.stakeholder = 'CIFOZ'
            WHERE cat.parent_id IS NULL
            GROUP BY cat.id
            ORDER BY cat.display_order
        ");
        $stmt->execute();
        $parentCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get sub-categories for each parent
        foreach ($parentCategories as &$parent) {
            $stmt = $db->prepare("
                SELECT 
                    cat.id,
                    cat.slug,
                    cat.name,
                    COUNT(DISTINCT cc.company_id) as total
                FROM cifoz_categories cat
                LEFT JOIN company_cifoz_categories cc ON cat.id = cc.category_id
                LEFT JOIN companies c ON cc.company_id = c.id AND c.is_active = 1 AND c.stakeholder = 'CIFOZ'
                WHERE cat.parent_id = :parent_id
                GROUP BY cat.id
                ORDER BY cat.display_order
            ");
            $stmt->execute(['parent_id' => $parent['id']]);
            $parent['children'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        $cifozCategories = $parentCategories;
    } catch(PDOException $e) {
        // Silent fail - categories not available
    }
}
?>
<style>
    body.index-page { padding-top: 85px; }
    
    .breadcrumb-wrapper {
        background: #f8f9fa;
        padding: 12px 20px;
        border-bottom: 1px solid #e0e0e0;
    }
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin: 0;
        padding: 0;
        list-style: none;
        font-size: 13px;
    }
    .breadcrumb li {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .breadcrumb li:not(:last-child):after {
        content: "›";
        color: #999;
        font-size: 16px;
    }
    .breadcrumb a {
        color: <?php echo $orgColor; ?>;
        text-decoration: none;
    }
    .breadcrumb a:hover {
        color: <?php echo $orgColorDark; ?>;
        text-decoration: underline;
    }
    .breadcrumb .current {
        color: #666;
        font-weight: 500;
    }

    .stakeholder-banner {
        background: linear-gradient(135deg, <?php echo $orgColor; ?>, <?php echo $orgColorDark; ?>);
        color: #fff; padding: 40px 0 30px; text-align: center; margin-top: 0;
    }
    .stakeholder-banner h1 { color: #fff; font-size: 28px; font-weight: 700; margin-bottom: 8px; }
    .stakeholder-banner p { color: rgba(255,255,255,0.85); margin: 0 auto; font-size: 14px; max-width: 500px; }
    
    .section-nav {
        background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        position: sticky; top: 85px; z-index: 99;
    }
    .section-nav .container { display: flex; gap: 0; }
    .section-nav a {
        display: inline-block; padding: 14px 20px; font-weight: 600;
        color: #555; text-decoration: none; border-bottom: 3px solid transparent;
        transition: all 0.2s; font-size: 13px;
    }
    .section-nav a.active { color: <?php echo $orgColor; ?>; border-bottom-color: <?php echo $orgColor; ?>; }
    .section-nav a:hover { color: <?php echo $orgColor; ?>; background: <?php echo $orgColorBg; ?>; }
    
    .directory-layout {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }
    .category-sidebar {
        flex: 0 0 300px;
        background: #fff;
        border-radius: 12px;
        padding: 0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        height: fit-content;
        position: sticky;
        top: 145px;
        overflow: hidden;
    }
    .category-sidebar h3 {
        padding: 18px 20px;
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: <?php echo $orgColor; ?>;
        background: <?php echo $orgColorBg; ?>;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 10px;
        justify-content: space-between;
    }
    .category-sidebar h3 .collapse-all {
        font-size: 11px;
        background: rgba(0,0,0,0.05);
        padding: 4px 10px;
        border-radius: 20px;
        cursor: pointer;
        font-weight: normal;
    }
    .category-sidebar h3 .collapse-all:hover {
        background: rgba(0,0,0,0.1);
    }
    .category-list {
        list-style: none;
        margin: 0;
        padding: 0;
        max-height: 70vh;
        overflow-y: auto;
    }
    .category-list::-webkit-scrollbar {
        width: 4px;
    }
    .category-list::-webkit-scrollbar-track {
        background: #f0f0f0;
    }
    .category-list::-webkit-scrollbar-thumb {
        background: <?php echo $orgColor; ?>;
        border-radius: 4px;
    }
    .category-list > li {
        margin: 0;
        padding: 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .category-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 20px;
        color: #444;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.2s;
        background: #fff;
    }
    .category-link:hover {
        background: <?php echo $orgColorBg; ?>;
        color: <?php echo $orgColor; ?>;
    }
    .category-link.active {
        background: <?php echo $orgColorBg; ?>;
        color: <?php echo $orgColor; ?>;
        font-weight: 600;
        border-right: 3px solid <?php echo $orgColor; ?>;
    }
    .category-link .count {
        background: #f0f0f0;
        padding: 2px 10px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        color: <?php echo $orgColor; ?>;
        min-width: 45px;
        text-align: center;
    }
    .category-link.active .count {
        background: <?php echo $orgColor; ?>;
        color: #fff;
    }
    
    .parent-category-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f0f0f0;
    }
    .parent-category-row .category-link {
        flex: 1;
        border: none;
    }
    .toggle-btn {
        padding: 12px 15px;
        cursor: pointer;
        color: #999;
        transition: all 0.2s;
    }
    .toggle-btn:hover {
        color: <?php echo $orgColor; ?>;
        background: <?php echo $orgColorBg; ?>;
    }
    
    .sub-category-list {
        list-style: none;
        margin: 0;
        padding: 0;
        background: #fafafa;
        display: none;
    }
    .sub-category-list.open {
        display: block;
    }
    .sub-category-list li {
        border-top: 1px solid #f0f0f0;
    }
    .sub-category-list a {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px 10px 40px;
        font-size: 13px;
        color: #555;
        text-decoration: none;
        transition: all 0.2s;
        background: #fafafa;
    }
    .sub-category-list a:hover {
        background: <?php echo $orgColorBg; ?>;
        color: <?php echo $orgColor; ?>;
        padding-left: 45px;
    }
    .sub-category-list a.active {
        background: <?php echo $orgColorBg; ?>;
        color: <?php echo $orgColor; ?>;
        font-weight: 500;
        border-right: 3px solid <?php echo $orgColor; ?>;
    }
    .sub-category-list .count {
        background: #e8e8e8;
        padding: 2px 8px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 500;
        color: <?php echo $orgColor; ?>;
        min-width: 35px;
        text-align: center;
    }
    
    .directory-content {
        flex: 1;
        min-width: 0;
    }
    
    .directory-toolbar {
        background: #fff;
        padding: 15px 20px;
        margin-bottom: 20px;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .directory-toolbar .result-count {
        font-size: 14px;
        color: #666;
    }
    .directory-toolbar .result-count strong {
        color: <?php echo $orgColor; ?>;
        font-size: 20px;
        font-weight: 700;
    }
    .toolbar-controls {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }
    .toolbar-controls input {
        padding: 10px 16px;
        border: 1px solid #e0e0e0;
        border-radius: 30px;
        font-size: 13px;
        min-width: 220px;
        transition: all 0.2s;
        background: #fff;
    }
    .toolbar-controls input:focus {
        outline: none;
        border-color: <?php echo $orgColor; ?>;
        box-shadow: 0 0 0 3px rgba(40, 37, 106, 0.1);
    }
    .toolbar-controls select {
        padding: 10px 16px;
        border: 1px solid #e0e0e0;
        border-radius: 30px;
        font-size: 13px;
        background: #fff;
        cursor: pointer;
    }
    .sort-select {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sort-select label {
        font-size: 13px;
        color: #666;
    }
    
    .sabai-directory-listings {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .sabai-entity {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        gap: 20px;
        transition: all 0.2s;
        align-items: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        border: 1px solid #f0f0f0;
        scroll-margin-top: 100px;
    }
    .sabai-entity:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
        border-color: <?php echo $orgColorLight; ?>;
    }
    
    /* Highlight animation for company when clicked from industries page */
    .company-highlight {
        background-color: <?php echo $orgColorBg; ?> !important;
        border: 2px solid <?php echo $orgColor; ?> !important;
        border-radius: 12px !important;
        transition: all 0.3s ease;
        animation: pulse 0.5s ease-in-out;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }
    
    .sabai-entity-image {
        flex: 0 0 180px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .sabai-entity-image img {
        width: 180px;
        height: 130px;
        border-radius: 8px;
        object-fit: contain;
        border: 1px solid #eee;
        background: #fafafa;
        padding: 10px;
    }
    .sabai-entity-image .no-image {
        width: 180px;
        height: 130px;
        background: #f8f8f8;
        border: 1px solid #eee;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ccc;
        font-size: 40px;
    }
    
    .sabai-entity-body {
        flex: 1;
        min-width: 0;
    }
    .sabai-entity-title {
        margin-bottom: 8px;
    }
    .sabai-entity-title a {
        color: <?php echo $orgColor; ?>;
        text-decoration: none;
        font-size: 18px;
        font-weight: 700;
    }
    .sabai-entity-title a:hover {
        text-decoration: underline;
    }
    
    .sabai-entity-category {
        margin-bottom: 12px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .category-badge {
        color: <?php echo $orgColor; ?>;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: <?php echo $orgColorBg; ?>;
        padding: 4px 12px;
        border-radius: 30px;
        text-decoration: none;
    }
    .category-badge i {
        font-size: 11px;
    }
    
    .sabai-entity-contact {
        font-size: 13px;
    }
    .sabai-entity-contact > div {
        margin-bottom: 5px;
        color: #555;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sabai-entity-contact i {
        width: 18px;
        color: <?php echo $orgColor; ?>;
        font-size: 13px;
    }
    .sabai-entity-contact a {
        color: <?php echo $orgColor; ?>;
        text-decoration: none;
    }
    .sabai-entity-contact a:hover {
        text-decoration: underline;
    }
    
    .sabai-entity-location {
        font-size: 12px;
        color: #999;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px solid #f0f0f0;
    }
    .sabai-entity-location i {
        margin-right: 5px;
        color: <?php echo $orgColor; ?>;
        font-size: 12px;
    }
    
    .sabai-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-top: 30px;
        padding: 15px 0;
        flex-wrap: wrap;
    }
    .sabai-pagination a, .sabai-pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        background: #fff;
        border: 1px solid #e0e0e0;
        text-decoration: none;
        color: #555;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .sabai-pagination a:hover {
        background: <?php echo $orgColorBg; ?>;
        border-color: <?php echo $orgColor; ?>;
        color: <?php echo $orgColor; ?>;
    }
    .sabai-pagination .active {
        background: <?php echo $orgColor; ?>;
        color: #fff;
        border-color: <?php echo $orgColor; ?>;
    }
    .sabai-pagination .disabled {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }
    .sabai-pagination .page-dots {
        border: none;
        background: transparent;
        cursor: default;
    }
    
    .event-item {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        margin-bottom: 15px;
        border-left: 4px solid <?php echo $orgColor; ?>;
        transition: all 0.2s;
    }
    .event-item:hover {
        transform: translateX(3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
        background: #fff;
        border-radius: 12px;
    }
    .empty-state i {
        font-size: 48px;
        display: block;
        margin-bottom: 15px;
        opacity: 0.3;
    }

    .section-heading {
        margin-bottom: 20px;
        font-weight: 700;
        color: <?php echo $orgColor; ?>;
        border-left: 4px solid <?php echo $orgColor; ?>;
        padding-left: 15px;
    }
    
    .clear-filter {
        display: inline-block;
        margin-left: 10px;
        font-size: 12px;
        color: <?php echo $orgColor; ?>;
        text-decoration: none;
    }
    .clear-filter:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        body.index-page { padding-top: 70px; }
        .section-nav { top: 70px; }
        .stakeholder-banner { padding: 30px 0 20px; }
        .stakeholder-banner h1 { font-size: 22px; }
        .sabai-entity { flex-direction: column; text-align: center; }
        .sabai-entity-image { flex: 0 0 auto; }
        .sabai-entity-image img, .sabai-entity-image .no-image { width: 150px; height: 110px; }
        .sabai-entity-contact > div { justify-content: center; }
        .directory-toolbar { flex-direction: column; }
        .toolbar-controls { width: 100%; flex-direction: column; }
        .toolbar-controls input { width: 100%; min-width: auto; }
        .toolbar-controls select { width: 100%; }
        .sort-select { width: 100%; justify-content: space-between; }
        .section-nav a { padding: 10px 8px; font-size: 11px; }
        .category-sidebar {
            flex: 0 0 100%;
            position: static;
            margin-bottom: 20px;
        }
        .directory-layout {
            flex-direction: column;
        }
    }
</style>
</head>

<body class="index-page">

  <?php require_once __DIR__ . '/includes/navbar.php'; ?>

  <main class="main">

    <div class="breadcrumb-wrapper">
        <ul class="breadcrumb">
            <li><a href="<?= SITE_ROOT ?>/index.php"><i class="bi bi-house-door"></i> Home</a></li>
            <li><a href="<?= SITE_ROOT ?>/stakeholder.php?org=<?php echo $org; ?>&section=directory"><?php echo $org; ?></a></li>
            <?php if ($category): ?>
            <li><a href="<?= SITE_ROOT ?>/stakeholder.php?org=<?php echo $org; ?>&section=directory">Directory</a></li>
            <li><span class="current"><?php echo ucfirst(str_replace('-', ' ', $category)); ?></span></li>
            <?php else: ?>
            <li><span class="current"><?php echo ucfirst($section); ?></span></li>
            <?php endif; ?>
        </ul>
    </div>

    <section class="stakeholder-banner">
      <div class="container">
        <h1><i class="bi <?php echo $orgIcon; ?>"></i> <?php echo $orgFullName; ?></h1>
        <p><?php echo $orgDesc; ?></p>
      </div>
    </section>

    <div class="section-nav">
      <div class="container">
        <a href="?org=<?php echo $org; ?>&section=directory" class="<?php echo $section == 'directory' ? 'active' : ''; ?>"><i class="bi bi-people"></i> Directory</a>
        <a href="?org=<?php echo $org; ?>&section=events" class="<?php echo $section == 'events' ? 'active' : ''; ?>"><i class="bi bi-calendar-event"></i> Events</a>
        <a href="?org=<?php echo $org; ?>&section=networking" class="<?php echo $section == 'networking' ? 'active' : ''; ?>"><i class="bi bi-diagram-3"></i> Networking</a>
      </div>
    </div>

    <section style="padding:30px 0;background:#f4f6f8;">
      <div class="container">

        <?php if ($section == 'directory'): ?>
        
        <?php if ($org === 'CIFOZ' && !empty($cifozCategories)): ?>
        <div class="directory-layout">
            <div class="category-sidebar">
                <h3>
                    <span><i class="bi bi-grid-3x3-gap-fill"></i> Categories</span>
                    <span class="collapse-all" onclick="toggleAllSubmenus()">Collapse All</span>
                </h3>
                <ul class="category-list" id="categoryList">
                    <li>
                        <a href="?org=CIFOZ&section=directory" class="category-link <?php echo !$category ? 'active' : ''; ?>">
                            <span>All Listings</span>
                            <span class="count">All</span>
                        </a>
                    </li>
                    <?php foreach ($cifozCategories as $cat): ?>
                    <li>
                        <?php if (!empty($cat['children'])): ?>
                            <div class="parent-category-row">
                                <a href="?org=CIFOZ&section=directory&category=<?php echo $cat['slug']; ?>" class="category-link <?php echo $category == $cat['slug'] ? 'active' : ''; ?>">
                                    <span><?php echo $cat['name']; ?></span>
                                    <span class="count"><?php echo $cat['total']; ?></span>
                                </a>
                                <span class="toggle-btn" data-parent="<?php echo $cat['slug']; ?>">
                                    <i class="bi bi-chevron-down toggle-icon"></i>
                                </span>
                            </div>
                            <ul class="sub-category-list" id="submenu-<?php echo $cat['slug']; ?>">
                                <?php foreach ($cat['children'] as $child): ?>
                                <li>
                                    <a href="?org=CIFOZ&section=directory&category=<?php echo $child['slug']; ?>" class="<?php echo $category == $child['slug'] ? 'active' : ''; ?>">
                                        <span><?php echo $child['name']; ?></span>
                                        <span class="count"><?php echo $child['total']; ?></span>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <a href="?org=CIFOZ&section=directory&category=<?php echo $cat['slug']; ?>" class="category-link <?php echo $category == $cat['slug'] ? 'active' : ''; ?>">
                                <span><?php echo $cat['name']; ?></span>
                                <span class="count"><?php echo $cat['total']; ?></span>
                            </a>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="directory-content">
                <div class="directory-toolbar">
                    <div class="result-count">
                        <strong id="memberCount">0</strong> members
                        <?php if ($category): ?>
                        <a href="?org=CIFOZ&section=directory" class="clear-filter"><i class="bi bi-x-circle"></i> Clear filter</a>
                        <?php endif; ?>
                    </div>
                    <div class="toolbar-controls">
                        <input type="text" id="memberSearch" placeholder="Search members...">
                        <select id="industryFilter"><option value="">All Industries</option></select>
                        <div class="sort-select">
                            <label>Sort by:</label>
                            <select id="sortBy">
                                <option value="name_asc">Name A-Z</option>
                                <option value="name_desc">Name Z-A</option>
                                <option value="newest">Newest First</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="sabai-directory-listings" id="membersList">
                    <div class="text-center py-5"><div class="spinner-border" style="color:<?php echo $orgColor; ?>;"></div></div>
                </div>
                <div class="sabai-pagination" id="pagination"></div>
            </div>
        </div>
        
        <script>
        // Store open state in localStorage
        function saveOpenMenus() {
            const openMenus = [];
            document.querySelectorAll('.sub-category-list.open').forEach(menu => {
                openMenus.push(menu.id.replace('submenu-', ''));
            });
            localStorage.setItem('cifoz_open_menus', JSON.stringify(openMenus));
        }
        
        function loadOpenMenus() {
            const saved = localStorage.getItem('cifoz_open_menus');
            if (saved) {
                const openMenus = JSON.parse(saved);
                openMenus.forEach(slug => {
                    const submenu = document.getElementById('submenu-' + slug);
                    const icon = document.querySelector(`.toggle-btn[data-parent="${slug}"] .toggle-icon`);
                    if (submenu && icon) {
                        submenu.classList.add('open');
                        icon.classList.remove('bi-chevron-down');
                        icon.classList.add('bi-chevron-up');
                    }
                });
            }
        }
        
        function toggleAllSubmenus() {
            const allSubmenus = document.querySelectorAll('.sub-category-list');
            const allOpen = Array.from(allSubmenus).every(menu => menu.classList.contains('open'));
            
            allSubmenus.forEach(submenu => {
                const slug = submenu.id.replace('submenu-', '');
                const icon = document.querySelector(`.toggle-btn[data-parent="${slug}"] .toggle-icon`);
                if (allOpen) {
                    submenu.classList.remove('open');
                    if (icon) {
                        icon.classList.remove('bi-chevron-up');
                        icon.classList.add('bi-chevron-down');
                    }
                } else {
                    submenu.classList.add('open');
                    if (icon) {
                        icon.classList.remove('bi-chevron-down');
                        icon.classList.add('bi-chevron-up');
                    }
                }
            });
            saveOpenMenus();
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtns = document.querySelectorAll('.toggle-btn');
            
            // Load saved open menus
            loadOpenMenus();
            
            // Check if any sub-category is active and open its parent
            const activeSub = document.querySelector('.sub-category-list a.active');
            if (activeSub) {
                const parentList = activeSub.closest('.sub-category-list');
                if (parentList && !parentList.classList.contains('open')) {
                    parentList.classList.add('open');
                    const parentId = parentList.id.replace('submenu-', '');
                    const toggleIcon = document.querySelector(`.toggle-btn[data-parent="${parentId}"] .toggle-icon`);
                    if (toggleIcon) {
                        toggleIcon.classList.remove('bi-chevron-down');
                        toggleIcon.classList.add('bi-chevron-up');
                    }
                    saveOpenMenus();
                }
            }
            
            toggleBtns.forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const parentSlug = this.getAttribute('data-parent');
                    const submenu = document.getElementById('submenu-' + parentSlug);
                    const icon = this.querySelector('.toggle-icon');
                    
                    if (submenu) {
                        submenu.classList.toggle('open');
                        if (submenu.classList.contains('open')) {
                            icon.classList.remove('bi-chevron-down');
                            icon.classList.add('bi-chevron-up');
                        } else {
                            icon.classList.remove('bi-chevron-up');
                            icon.classList.add('bi-chevron-down');
                        }
                        saveOpenMenus();
                    }
                });
            });
        });
        </script>
        
        <?php else: ?>
        <!-- CZI Layout -->
        <div class="directory-toolbar">
            <div class="result-count"><strong id="memberCount">0</strong> members</div>
            <div class="toolbar-controls">
                <input type="text" id="memberSearch" placeholder="Search members...">
                <select id="industryFilter"><option value="">All Industries</option></select>
                <div class="sort-select">
                    <label>Sort by:</label>
                    <select id="sortBy">
                        <option value="name_asc">Name A-Z</option>
                        <option value="name_desc">Name Z-A</option>
                        <option value="newest">Newest First</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="sabai-directory-listings" id="membersList">
            <div class="text-center py-5"><div class="spinner-border" style="color:<?php echo $orgColor; ?>;"></div></div>
        </div>
        <div class="sabai-pagination" id="pagination"></div>
        <?php endif; ?>
        
        <?php endif; ?>

        <?php if ($section == 'events'): ?>
        <h4 class="section-heading"><i class="bi bi-calendar-event"></i> <?php echo $org; ?> Events</h4>
        <div id="eventsList"><div class="text-center py-5"><div class="spinner-border" style="color:<?php echo $orgColor; ?>;"></div></div></div>
        <?php endif; ?>

        <?php if ($section == 'networking'): ?>
        <h4 class="section-heading"><i class="bi bi-diagram-3"></i> <?php echo $org; ?> Networking</h4>
        <div class="row g-4">
          <div class="col-lg-6">
            <div class="card h-100" style="border:1px solid #e0e0e0;border-radius:12px;padding:24px;border-top:4px solid <?php echo $orgColor; ?>;">
              <h5 style="color:<?php echo $orgColor; ?>;"><i class="bi bi-info-circle"></i> About <?php echo $org; ?></h5>
              <p style="color:#666;font-size:14px;line-height:1.6;">Connect with <?php echo $org; ?> members across Zimbabwe's <?php echo $org === 'CZI' ? 'industrial' : 'construction'; ?> sector.</p>
              <hr>
              <p style="font-size:13px;margin:0;"><i class="bi bi-envelope"></i> info@<?php echo strtolower($org); ?>.co.zw</p>
              <p style="font-size:13px;margin:0;"><i class="bi bi-telephone"></i> +263 242 123456</p>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card h-100" style="border:1px solid #e0e0e0;border-radius:12px;padding:24px;border-top:4px solid <?php echo $orgColor; ?>;">
              <h5 style="color:<?php echo $orgColor; ?>;"><i class="bi bi-people"></i> Member Connection</h5>
              <p style="color:#666;font-size:14px;line-height:1.6;">Browse the directory to find business partners and networking opportunities.</p>
              <a href="?org=<?php echo $org; ?>&section=directory" class="btn btn-sm mt-2" style="background:<?php echo $orgColor; ?>;color:#fff;border:none;border-radius:30px;padding:10px 20px;">Browse Directory →</a>
            </div>
          </div>
        </div>
        <?php endif; ?>

      </div>
    </section>

  </main>

  <?php require_once __DIR__ . '/includes/footer.php'; ?>

  <script>
    const org = '<?php echo $org; ?>';
    const section = '<?php echo $section; ?>';
    const categorySlug = '<?php echo $category; ?>';
    const orgColor = '<?php echo $orgColor; ?>';
    const targetCompanyId = <?php echo $targetCompanyId ? $targetCompanyId : 'null'; ?>;
    const API = '<?= SITE_ROOT ?>/api/public';
    const categorySearchMap = <?php echo json_encode($categorySlugToSearchMap); ?>;
    const categoryDisplayNames = <?php echo json_encode($categoryDisplayNames); ?>;
    let allMembers = [];
    let filteredMembers = [];
    let currentPage = 1;
    let targetCompanyFound = false;
    let targetCompanyPage = 1;
    const perPage = 10;

    function getCompanyCategories(categoryRaw) {
        if (!categoryRaw) return [];
        
        const categories = [];
        const categoryMap = {
            'building': 'Building',
            'category-a': 'Category A',
            'category-b': 'Category B',
            'category-c': 'Category C',
            'category-d': 'Category D',
            'category-e': 'Category E',
            'category-f': 'Category F',
            'category-g': 'Category G',
            'civil-engineering': 'Civil Engineering',
            'electrical': 'Electrical',
            'electro-mechanical-engineers': 'Electro-Mechanical Engineers',
            'joinery-and-shopfitting': 'Joinery and Shopfitting',
            'plumbingdrain-laying-and-sheeting': 'Plumbing',
            'roof-slatingtiling-and-sheeting': 'Roofing',
            'scaffolding-and-formwork-specialists': 'Scaffolding',
            'painting-and-decoratingsign-writting': 'Painting & Decorating',
            'fencing-precast-walling-and-structures': 'Fencing',
            'acoustic-engineering': 'Acoustic Engineering',
            'fire-protection-and-sprinkler-engineers': 'Fire Protection',
            'excavation-and-earth-moving-road-works-etc': 'Excavation & Earth Moving',
            'patent-flooring-and-floor-layersroofwater-proofing-and-tanking': 'Flooring & Waterproofing',
            'art-metal-workaluminium-and-steel-window-specialist': 'Aluminium & Steel Windows',
            'suppliers-and-hires-of-earth-moving-equipment': 'Earth Moving Equipment',
            'boreholes-and-allied-services': 'Boreholes',
            'burglarfire-detection-alarm-systems': 'Security Systems',
            'wall-tilingmosaics-and-marble-workersterrazzo-specialistsreconstruction': 'Tiling & Marble',
            'fumigation-and-pest-control': 'Pest Control',
            'structural-engineerssteel-reinforcing-engineers': 'Structural Engineering'
        };
        
        const parts = categoryRaw.split(';');
        for (const part of parts) {
            const trimmed = part.trim().toLowerCase();
            if (categoryMap[trimmed]) {
                categories.push(categoryMap[trimmed]);
            }
        }
        
        return categories;
    }

    // Function to find which page contains the target company
    function findTargetCompanyPage(companies, targetId) {
        for (let i = 0; i < companies.length; i++) {
            if (companies[i].id === targetId) {
                return Math.floor(i / perPage) + 1;
            }
        }
        return null;
    }

    // Function to scroll to and highlight a specific company
    function scrollToCompany(companyId) {
        if (!companyId) return false;
        
        let companyElement = document.getElementById('company-' + companyId);
        
        if (companyElement) {
            companyElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            companyElement.classList.add('company-highlight');
            setTimeout(function() {
                companyElement.classList.remove('company-highlight');
            }, 3000);
            return true;
        }
        return false;
    }

    document.addEventListener('DOMContentLoaded', function() {
      
      if (section === 'directory') {
        let url = API + '/companies.php?stakeholder=' + org + '&limit=2000';
        
        fetch(url)
          .then(r => r.json())
          .then(d => {
            if (d.status === 'success' && d.data && d.data.length > 0) {
              allMembers = d.data;
              
              // If there's a target company, find which page it's on
              if (targetCompanyId) {
                const targetIndex = allMembers.findIndex(m => m.id === targetCompanyId);
                if (targetIndex !== -1) {
                  targetCompanyPage = Math.floor(targetIndex / perPage) + 1;
                  currentPage = targetCompanyPage;
                  targetCompanyFound = true;
                  console.log('Target company found on page:', targetCompanyPage);
                } else {
                  console.log('Target company not found in loaded data');
                }
              }
              
              let industries = [...new Set(allMembers.map(m => m.industry_name).filter(i => i))].sort();
              let select = document.getElementById('industryFilter');
              if (select) {
                  select.innerHTML = '<option value="">All Industries</option>';
                  industries.forEach(ind => { select.innerHTML += `<option value="${ind}">${ind}</option>`; });
              }
              
              applyFilters();
              
              const searchInput = document.getElementById('memberSearch');
              if (searchInput) searchInput.addEventListener('keyup', function() { applyFilters(); });
              
              const industryFilter = document.getElementById('industryFilter');
              if (industryFilter) industryFilter.addEventListener('change', function() { applyFilters(); });
              
              const sortBy = document.getElementById('sortBy');
              if (sortBy) sortBy.addEventListener('change', function() { applyFilters(); });
            } else {
              document.getElementById('membersList').innerHTML = '<div class="empty-state"><i class="bi bi-people"></i><h4>No members found</h4><p>Try adjusting your filters</p></div>';
              document.getElementById('memberCount').textContent = '0';
              if (document.getElementById('pagination')) document.getElementById('pagination').innerHTML = '';
            }
          })
          .catch(err => {
            console.error(err);
            document.getElementById('membersList').innerHTML = '<div class="empty-state"><i class="bi bi-exclamation-triangle"></i><h4>Error loading members</h4><p>Please try again later</p></div>';
          });
      }

      if (section === 'events') {
        fetch(API + '/events.php?organizer=' + org).then(r => r.json()).then(d => {
          const c = document.getElementById('eventsList');
          if (d.status === 'success' && d.data && d.data.length > 0) {
            c.innerHTML = d.data.map(e => { 
              const ed = new Date(e.event_date); 
              return `<div class="event-item">
                <div class="row align-items-center">
                  <div class="col-md-2 text-center mb-2 mb-md-0">
                    <div style="font-size:28px;font-weight:700;color:${orgColor};">${ed.getDate()}</div>
                    <div style="font-size:13px;font-weight:600;">${ed.toLocaleString('default',{month:'short'})}</div>
                  </div>
                  <div class="col-md-10">
                    <h5 style="color:${orgColor};margin-bottom:5px;">${e.title}</h5>
                    <p style="color:#666;margin:0;font-size:13px;"><i class="bi bi-geo-alt" style="color:${orgColor};"></i> ${e.location || 'TBA'}</p>
                    ${e.description ? '<p style="color:#888;font-size:13px;margin-top:8px;">' + e.description.substring(0, 150) + (e.description.length > 150 ? '...' : '') + '</p>' : ''}
                  </div>
                </div>
              </div>`; 
            }).join('');
          } else { 
            c.innerHTML = '<div class="empty-state"><i class="bi bi-calendar-event"></i><h4>No events scheduled</h4><p>Check back later for updates</p></div>'; 
          }
        });
      }
    });
    
    function applyFilters() {
        let search = document.getElementById('memberSearch') ? document.getElementById('memberSearch').value.toLowerCase() : '';
        let industry = document.getElementById('industryFilter') ? document.getElementById('industryFilter').value : '';
        let sortBy = document.getElementById('sortBy') ? document.getElementById('sortBy').value : 'newest';
        
        let workingMembers = [...allMembers];
        
        if (categorySlug && org === 'CIFOZ') {
            if (categorySlug === 'general-contractors') {
                workingMembers = workingMembers.filter(m => m.cifoz_member_type === 'General Contractors');
            } else if (categorySlug === 'sub-contractors') {
                workingMembers = workingMembers.filter(m => m.cifoz_member_type === 'Sub-Contractors');
            } else if (categorySlug === 'associate-members') {
                workingMembers = workingMembers.filter(m => m.cifoz_member_type === 'Associate Members');
            } else if (categorySlug === 'dormant-members') {
                workingMembers = workingMembers.filter(m => m.cifoz_member_type === 'Dormant Members');
            } else if (categorySlug === 'honourary') {
                workingMembers = workingMembers.filter(m => m.cifoz_member_type === 'Honourary');
            } else if (categorySlug === 'emergent-contractors') {
                workingMembers = workingMembers.filter(m => m.cifoz_member_type === 'Emergent Contractors');
            } else {
                const searchTerm = categorySearchMap[categorySlug];
                if (searchTerm) {
                    workingMembers = workingMembers.filter(m => {
                        return m.cifoz_category_raw && m.cifoz_category_raw.toLowerCase().includes(searchTerm);
                    });
                }
            }
        }
        
        if (search) {
            workingMembers = workingMembers.filter(m => {
                return (m.name && m.name.toLowerCase().includes(search)) || 
                       (m.industry_name && m.industry_name.toLowerCase().includes(search)) || 
                       (m.province_name && m.province_name.toLowerCase().includes(search));
            });
        }
        
        if (industry) {
            workingMembers = workingMembers.filter(m => m.industry_name === industry);
        }
        
        workingMembers.sort((a, b) => {
            switch(sortBy) {
                case 'name_asc':
                    return (a.name || '').localeCompare(b.name || '');
                case 'name_desc':
                    return (b.name || '').localeCompare(a.name || '');
                case 'newest':
                default:
                    return new Date(b.created_at || 0) - new Date(a.created_at || 0);
            }
        });
        
        filteredMembers = workingMembers;
        document.getElementById('memberCount').textContent = filteredMembers.length;
        
        // If we have a target company, use its pre-calculated page
        if (targetCompanyId && targetCompanyFound && targetCompanyPage <= Math.ceil(filteredMembers.length / perPage)) {
            currentPage = targetCompanyPage;
        } else {
            currentPage = 1;
        }
        
        showPage();
    }

    function showPage() {
      let totalPages = Math.ceil(filteredMembers.length / perPage);
      let start = (currentPage - 1) * perPage;
      let pageMembers = filteredMembers.slice(start, start + perPage);
      
      let container = document.getElementById('membersList');
      if (pageMembers.length === 0) {
        container.innerHTML = '<div class="empty-state"><i class="bi bi-search"></i><h4>No members match</h4><p>Try different search terms or clear filters</p></div>';
        if (document.getElementById('pagination')) document.getElementById('pagination').innerHTML = '';
        return;
      }
      
      container.innerHTML = pageMembers.map((m, index) => {
          const categories = getCompanyCategories(m.cifoz_category_raw);
          const memberTypeSlug = m.cifoz_member_type ? m.cifoz_member_type.toLowerCase().replace(/ /g, '-') : '';
          
          return `
          <div class="sabai-entity" id="company-${m.id}" data-company-id="${m.id}">
            <div class="sabai-entity-image">
              ${m.logo ? `<img src="<?= SITE_ROOT ?>/${m.logo}" alt="${m.name.replace(/"/g, '&quot;')}" onerror="this.parentElement.innerHTML='<div class=\\'no-image\\'><i class=\\'bi bi-building\\'></i></div>'">` : `<div class="no-image"><i class="bi bi-building"></i></div>`}
            </div>
            <div class="sabai-entity-body">
              <div class="sabai-entity-title"><a href="<?= SITE_ROOT ?>/company.php?id=${m.id}">${m.name}</a></div>
              <div class="sabai-entity-category">
                ${m.industry_name ? `<a href="?org=${org}&section=directory&industry=${encodeURIComponent(m.industry_name)}" class="category-badge"><i class="bi bi-folder"></i> ${m.industry_name}</a>` : ''}
                ${m.cifoz_member_type && org === 'CIFOZ' ? `<a href="?org=CIFOZ&section=directory&category=${memberTypeSlug}" class="category-badge"><i class="bi bi-tag"></i> ${m.cifoz_member_type}</a>` : ''}
                ${categories.map(cat => `<a href="?org=CIFOZ&section=directory&category=${cat.toLowerCase().replace(/ /g, '-')}" class="category-badge"><i class="bi bi-bookmark"></i> ${cat}</a>`).join('')}
              </div>
              <div class="sabai-entity-contact">
                ${m.phone ? `<div><i class="bi bi-telephone-fill"></i> ${m.phone}</div>` : ''}
                ${m.email ? `<div><i class="bi bi-envelope-fill"></i> <a href="mailto:${m.email}">${m.email}</a></div>` : ''}
                ${m.website ? `<div><i class="bi bi-globe2"></i> <a href="https://${m.website}" target="_blank">${m.website}</a></div>` : ''}
              </div>
              <div class="sabai-entity-location">
                <i class="bi bi-geo-alt-fill"></i> ${m.province_name || 'Zimbabwe'}
                ${m.description ? ' · ' + m.description.substring(0, 80) + (m.description.length > 80 ? '...' : '') : ''}
              </div>
            </div>
          </div>
        `;
      }).join('');
      
      let pag = document.getElementById('pagination');
      if (totalPages <= 1) { 
        pag.innerHTML = ''; 
      } else {
        let html = '';
        html += `<a class="${currentPage === 1 ? 'disabled' : ''}" onclick="if(currentPage>1){currentPage--;showPage();}"><i class="bi bi-chevron-left"></i></a>`;
        
        let maxVisible = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
        let endPage = Math.min(totalPages, startPage + maxVisible - 1);
        
        if (startPage > 1) {
          html += `<a onclick="currentPage=1;showPage();">1</a>`;
          if (startPage > 2) html += `<span class="page-dots">...</span>`;
        }
        
        for (let i = startPage; i <= endPage; i++) {
          html += `<a class="${i === currentPage ? 'active' : ''}" onclick="currentPage=${i};showPage();">${i}</a>`;
        }
        
        if (endPage < totalPages) {
          if (endPage < totalPages - 1) html += `<span class="page-dots">...</span>`;
          html += `<a onclick="currentPage=${totalPages};showPage();">${totalPages}</a>`;
        }
        
        html += `<a class="${currentPage === totalPages ? 'disabled' : ''}" onclick="if(currentPage<totalPages){currentPage++;showPage();}"><i class="bi bi-chevron-right"></i></a>`;
        pag.innerHTML = html;
      }
      
      // After rendering, scroll to the target company if we're on the correct page
      if (targetCompanyId && targetCompanyFound && currentPage === targetCompanyPage) {
        setTimeout(function() {
            scrollToCompany(targetCompanyId);
            // Clear the target so it doesn't keep trying
            targetCompanyFound = false;
        }, 500);
      }
      
      window.scrollTo({top: 400, behavior: 'smooth'});
    }
  </script>

</body>
</html>