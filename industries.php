<?php
$pageTitle = "Browse Industries - Zimbabwe Business Directory";
$pageDescription = "Browse all industrial sectors and find companies in Zimbabwe";
$selectedIndustry = isset($_GET['slug']) ? $_GET['slug'] : null;
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

require_once __DIR__ . '/includes/head.php';

// Database connection
try {
    require_once __DIR__ . '/api/config/database.php';
    $database = new Database();
    $db = $database->getConnection();
} catch(Exception $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get all industries for sidebar with counts
$stmt = $db->prepare("
    SELECT 
        i.id, 
        i.slug, 
        i.name, 
        i.icon, 
        i.description,
        COUNT(DISTINCT c.id) as total,
        COUNT(DISTINCT c.province_id) as provinces_count,
        (SELECT COUNT(*) FROM industry_showcase WHERE industry_slug = i.slug AND is_active = 1) as showcase_count
    FROM industries i
    LEFT JOIN companies c ON i.id = c.industry_id AND c.is_active = 1
    GROUP BY i.id
    ORDER BY total DESC
");
$stmt->execute();
$industries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get companies for selected industry
$companies = [];
$selectedIndustryName = '';
$selectedIndustryDesc = '';
$industryStats = [];

if ($selectedIndustry) {
    $stmt = $db->prepare("
        SELECT 
            i.*,
            COUNT(DISTINCT c.id) as total_companies,
            COUNT(DISTINCT c.province_id) as provinces_covered,
            (SELECT COUNT(*) FROM industry_showcase WHERE industry_slug = i.slug AND is_active = 1) as showcase_count
        FROM industries i
        LEFT JOIN companies c ON i.id = c.industry_id AND c.is_active = 1
        WHERE i.slug = :slug
        GROUP BY i.id
    ");
    $stmt->execute(['slug' => $selectedIndustry]);
    $industryStats = $stmt->fetch(PDO::FETCH_ASSOC);
    $selectedIndustryName = $industryStats['name'] ?? '';
    $selectedIndustryDesc = $industryStats['description'] ?? '';
    $showcaseCount = $industryStats['showcase_count'] ?? 0;
    
    // Get top provinces only
    $stmt = $db->prepare("
        SELECT p.name, COUNT(DISTINCT c.id) as total
        FROM companies c
        JOIN provinces p ON c.province_id = p.id
        JOIN industries i ON c.industry_id = i.id
        WHERE i.slug = :slug AND c.is_active = 1
        GROUP BY p.id
        ORDER BY total DESC
        LIMIT 5
    ");
    $stmt->execute(['slug' => $selectedIndustry]);
    $topProvinces = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get companies
    $sql = "
        SELECT DISTINCT c.*, i.name as industry_name, p.name as province_name,
               c.stakeholder as stakeholder
        FROM companies c
        JOIN industries i ON c.industry_id = i.id
        LEFT JOIN provinces p ON c.province_id = p.id
        WHERE i.slug = :slug AND c.is_active = 1
    ";
    
    if ($searchQuery) {
        $sql .= " AND (c.name LIKE :search OR c.description LIKE :search)";
    }
    
    $sql .= " ORDER BY c.name";
    
    $stmt = $db->prepare($sql);
    $params = ['slug' => $selectedIndustry];
    if ($searchQuery) {
        $params['search'] = "%$searchQuery%";
    }
    $stmt->execute($params);
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCompanyUrl($stakeholder, $companyId, $companyName) {
    if ($stakeholder === 'CZI') {
        return "stakeholder.php?org=CZI&section=directory&company={$companyId}#company-{$companyId}";
    } elseif ($stakeholder === 'CIFOZ') {
        return "stakeholder.php?org=CIFOZ&section=directory&company={$companyId}#company-{$companyId}";
    } else {
        return "company.php?id={$companyId}";
    }
}
?>
<style>
    body.index-page { padding-top: 70px; }
    
    .industries-hero {
        background: linear-gradient(135deg, #1a3a5c, #0f1f33);
        color: #fff;
        padding: 50px 0 40px;
        text-align: center;
        margin-top: 0;
    }
    .industries-hero h1 { 
        font-size: 32px; 
        font-weight: 700; 
        margin-bottom: 12px; 
        color: #ffffff; 
    }
    .industries-hero p { 
        font-size: 16px; 
        opacity: 0.9; 
        max-width: 600px; 
        margin: 0 auto; 
        color: #ffffff;
    }
    
    .industries-main {
        background: #f5f7fa;
        padding: 40px 0;
        min-height: 500px;
    }
    .industries-container {
        display: flex;
        gap: 30px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    /* Sidebar */
    .industries-sidebar {
        flex: 0 0 280px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        height: fit-content;
        position: sticky;
        top: 90px;
    }
    .industries-sidebar h3 {
        padding: 18px 20px;
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #1a3a5c;
        border-bottom: 1px solid #eaeaea;
    }
    .industry-list {
        list-style: none;
        margin: 0;
        padding: 0;
        max-height: 60vh;
        overflow-y: auto;
    }
    .industry-list li { border-bottom: 1px solid #f0f0f0; }
    .industry-list a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        color: #444;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.2s;
    }
    .industry-list a:hover { background: #f0f4f9; color: #1a3a5c; }
    .industry-list a.active {
        background: #e8f0fe;
        color: #1a3a5c;
        font-weight: 600;
        border-right: 3px solid #1a3a5c;
    }
    .industry-list .industry-name {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .industry-list i { width: 24px; font-size: 16px; color: #1a3a5c; }
    .industry-list .count {
        background: #f0f0f0;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 11px;
        color: #666;
    }
    .industry-list a.active .count { background: #1a3a5c; color: #fff; }
    
    /* Content Area */
    .industries-content { flex: 1; min-width: 0; }
    
    /* Industry Stats Cards - Only 2 cards now */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 25px;
    }
    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    .stat-card i { font-size: 32px; color: #1a3a5c; margin-bottom: 10px; display: block; }
    .stat-card .stat-value { font-size: 32px; font-weight: 700; color: #1a3a5c; }
    .stat-card .stat-label { font-size: 13px; color: #666; margin-top: 5px; }
    
    /* Top Locations Card */
    .location-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    .location-card h4 {
        font-size: 14px;
        font-weight: 700;
        color: #1a3a5c;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 2px solid #e8f0fe;
        padding-bottom: 10px;
    }
    .province-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .province-list li {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 13px;
    }
    .province-list li:last-child { border-bottom: none; }
    .province-count { background: #e8f0fe; padding: 2px 8px; border-radius: 20px; font-size: 11px; color: #1a3a5c; }
    
    /* Showcase Banner - Clean and Professional */
    .showcase-banner {
        background: linear-gradient(135deg, #1a3a5c, #2c4a6e);
        border-radius: 12px;
        padding: 25px 30px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .showcase-banner:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    .showcase-banner-content {
        flex: 1;
    }
    .showcase-banner-content h3 {
        color: #fff;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .showcase-banner-content p {
        color: rgba(255,255,255,0.8);
        font-size: 13px;
    }
    .showcase-banner .featured-count {
        background: rgba(255,255,255,0.2);
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 13px;
        color: #fff;
        display: inline-block;
        margin-bottom: 10px;
    }
    .showcase-banner .btn-showcase {
        background: #fff;
        color: #1a3a5c;
        padding: 10px 25px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .showcase-banner .btn-showcase:hover {
        transform: scale(1.02);
        background: #f5f5f5;
    }
    
    /* View Toggle - Clean */
    .view-toggle {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        background: #fff;
        padding: 5px;
        border-radius: 50px;
        width: fit-content;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    .view-toggle .btn {
        padding: 10px 25px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-directory {
        background: #1a3a5c;
        color: #fff;
    }
    .btn-directory:hover {
        background: #0f1f33;
    }
    .btn-showcase-toggle {
        background: #e8f0fe;
        color: #1a3a5c;
    }
    .btn-showcase-toggle:hover {
        background: #d0dbe8;
    }
    
    /* Search Bar */
    .search-bar {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        background: #fff;
        padding: 15px 20px;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    .search-bar input {
        flex: 1;
        padding: 12px 16px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
    }
    .search-bar input:focus { outline: none; border-color: #1a3a5c; box-shadow: 0 0 0 3px rgba(26,58,92,0.1); }
    .search-bar button {
        padding: 12px 28px;
        background: #1a3a5c;
        color: #fff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .search-bar button:hover { background: #0f1f33; }
    
    /* Companies List */
    .companies-list {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    .company-item {
        display: flex;
        align-items: center;
        padding: 18px 20px;
        border-bottom: 1px solid #f0f0f0;
        text-decoration: none;
        transition: all 0.2s;
    }
    .company-item:last-child { border-bottom: none; }
    .company-item:hover { background: #f8f9fa; }
    .company-logo {
        width: 60px;
        height: 60px;
        object-fit: contain;
        margin-right: 18px;
        background: #f8f8f8;
        border-radius: 8px;
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .company-logo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    .company-logo .no-logo-icon {
        font-size: 32px;
        color: #ccc;
    }
    .company-details { flex: 1; }
    .company-name { font-size: 16px; font-weight: 700; color: #1a3a5c; margin-bottom: 6px; }
    .company-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 6px;
    }
    .company-meta span {
        font-size: 12px;
        color: #666;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .company-meta i { font-size: 12px; color: #1a3a5c; }
    .company-badge {
        font-size: 10px;
        padding: 2px 10px;
        border-radius: 20px;
        background: #e8f0fe;
        color: #1a3a5c;
        display: inline-block;
    }
    
    /* Industry Cards Grid */
    .industry-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
    }
    .industry-card {
        background: #fff;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s;
        border: 1px solid #eaeaea;
        overflow: hidden;
    }
    .industry-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    .industry-card-header {
        background: linear-gradient(135deg, #1a3a5c, #0f1f33);
        padding: 25px;
        text-align: center;
        position: relative;
    }
    .industry-card-header i {
        font-size: 48px;
        color: rgba(255,255,255,0.3);
    }
    .showcase-badge-card {
        position: absolute;
        top: 12px;
        right: 12px;
        background: #f08a1e;
        color: #fff;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
    }
    .industry-card-content {
        padding: 20px;
    }
    .industry-card h3 {
        font-size: 18px;
        font-weight: 700;
        color: #1a3a5c;
        margin-bottom: 8px;
    }
    .industry-card p {
        font-size: 13px;
        color: #666;
        margin-bottom: 15px;
        line-height: 1.5;
    }
    .industry-stats {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }
    .industry-stats span {
        font-size: 12px;
        color: #666;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .card-actions {
        display: flex;
        border-top: 1px solid #f0f0f0;
    }
    .action-area {
        flex: 1;
        text-align: center;
        padding: 12px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }
    .action-area:first-child { border-right: 1px solid #f0f0f0; }
    .action-area.directory { color: #1a3a5c; background: #fff; }
    .action-area.directory:hover { background: #e8f0fe; }
    .action-area.showcase { color: #f08a1e; background: #fff; }
    .action-area.showcase:hover { background: #fff8f0; }
    
    .no-results {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 12px;
    }
    
    /* Simple CTA at bottom */
    .simple-cta {
        background: #e8f0fe;
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        margin-top: 30px;
    }
    .simple-cta p {
        color: #1a3a5c;
        margin-bottom: 10px;
    }
    .simple-cta a {
        color: #1a3a5c;
        font-weight: 600;
        text-decoration: none;
    }
    .simple-cta a:hover { text-decoration: underline; }

    @media (max-width: 992px) {
        .industries-container { flex-direction: column; }
        .industries-sidebar {
            flex: auto;
            position: static;
            max-height: 400px;
            overflow-y: auto;
        }
        .industry-cards-grid { grid-template-columns: 1fr; }
        .showcase-banner { flex-direction: column; text-align: center; }
    }
    
    @media (max-width: 768px) {
        .company-item { flex-direction: column; text-align: center; }
        .company-logo { margin-right: 0; margin-bottom: 12px; }
        .company-meta { justify-content: center; }
        .stats-grid { grid-template-columns: 1fr; }
        .view-toggle { width: 100%; justify-content: center; }
        .search-bar { flex-direction: column; }
    }
</style>
</head>

<body class="index-page">

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<main class="main">

<section class="industries-hero">
    <div class="container">
        <h1><i class="bi bi-grid-3x3-gap-fill"></i> Industries in Zimbabwe</h1>
        <p>Browse companies by industry sector and connect with business partners across Zimbabwe</p>
    </div>
</section>

<section class="industries-main">
    <div class="industries-container">
        
        <!-- Sidebar -->
        <div class="industries-sidebar">
            <h3><i class="bi bi-folder2"></i> All Industries</h3>
            <ul class="industry-list">
                <?php foreach ($industries as $industry): ?>
                <li>
                    <a href="industries.php?slug=<?php echo $industry['slug']; ?>" class="<?php echo $selectedIndustry == $industry['slug'] ? 'active' : ''; ?>">
                        <span class="industry-name">
                            <i class="bi <?php echo $industry['icon'] ?: 'bi-building'; ?>"></i>
                            <span><?php echo $industry['name']; ?></span>
                        </span>
                        <span class="count"><?php echo $industry['total']; ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <!-- Content Area -->
        <div class="industries-content">
            
            <?php if ($selectedIndustry && $industryStats): ?>
            
            <!-- Clean Showcase Banner -->
            <?php if ($showcaseCount > 0): ?>
            <div class="showcase-banner" onclick="window.location.href='industry.php?slug=<?php echo $selectedIndustry; ?>&tab=showcase'">
                <div class="showcase-banner-content">
                    <div class="featured-count">
                        <i class="bi bi-star-fill"></i> <?php echo $showcaseCount; ?> Featured Companies
                    </div>
                    <h3>Premium Showcase</h3>
                    <p>Discover the best <?php echo htmlspecialchars($selectedIndustryName); ?> companies</p>
                </div>
                <span class="btn-showcase">
                    View Featured <i class="bi bi-arrow-right"></i>
                </span>
            </div>
            <?php endif; ?>
            
            <!-- Stats Cards - Only Total Companies and Provinces -->
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="bi bi-building"></i>
                    <div class="stat-value"><?php echo $industryStats['total_companies']; ?></div>
                    <div class="stat-label">Total Companies</div>
                </div>
                <div class="stat-card">
                    <i class="bi bi-geo-alt"></i>
                    <div class="stat-value"><?php echo $industryStats['provinces_covered']; ?></div>
                    <div class="stat-label">Provinces</div>
                </div>
            </div>
            
            <!-- Top Locations Card Only -->
            <?php if (!empty($topProvinces)): ?>
            <div class="location-card">
                <h4><i class="bi bi-map"></i> Top Locations</h4>
                <ul class="province-list">
                    <?php foreach ($topProvinces as $province): ?>
                    <li>
                        <span><?php echo htmlspecialchars($province['name']); ?></span>
                        <span class="province-count"><?php echo $province['total']; ?> companies</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <!-- View Toggle -->
            <div class="view-toggle">
                <a href="industries.php?slug=<?php echo $selectedIndustry; ?>" class="btn btn-directory">
                    <i class="bi bi-list-ul"></i> Directory View
                </a>
                <a href="<?= SITE_ROOT ?>/industry.php?slug=<?php echo $selectedIndustry; ?>&tab=showcase" class="btn btn-showcase-toggle">
                    <i class="bi bi-star-fill"></i> Showcase View
                </a>
            </div>
            
            <!-- Search Bar -->
            <div class="search-bar">
                <input type="text" id="companySearch" placeholder="Search companies in <?php echo htmlspecialchars($selectedIndustryName); ?>..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button onclick="searchCompanies()"><i class="bi bi-search"></i> Search</button>
            </div>
            
            <!-- Companies List -->
            <?php if (!empty($companies)): ?>
            <div class="companies-list" id="companiesList">
                <?php foreach ($companies as $company): ?>
                <a href="<?php echo getCompanyUrl($company['stakeholder'], $company['id'], $company['name']); ?>" class="company-item">
                    <div class="company-logo">
                        <?php if ($company['logo']): ?>
                        <img src="<?= SITE_ROOT ?>/<?php echo $company['logo']; ?>" alt="<?php echo htmlspecialchars($company['name']); ?>" onerror="this.parentElement.innerHTML='<i class=\'bi bi-building no-logo-icon\'></i>'">
                        <?php else: ?>
                        <i class="bi bi-building no-logo-icon"></i>
                        <?php endif; ?>
                    </div>
                    <div class="company-details">
                        <div class="company-name"><?php echo htmlspecialchars($company['name']); ?></div>
                        <div class="company-meta">
                            <span><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($company['province_name'] ?: 'Zimbabwe'); ?></span>
                            <?php if ($company['phone']): ?>
                            <span><i class="bi bi-telephone"></i> <?php echo htmlspecialchars($company['phone']); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($company['stakeholder']): ?>
                        <div class="company-badge"><?php echo $company['stakeholder']; ?> Member</div>
                        <?php endif; ?>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="no-results">
                <i class="bi bi-building"></i>
                <h4>No companies found</h4>
                <p>Try adjusting your search or browse other industries</p>
            </div>
            <?php endif; ?>
            
            <?php else: ?>
            <!-- Industry Cards Grid -->
            <div class="industry-cards-grid">
                <?php foreach ($industries as $industry): 
                    $hasShowcase = $industry['showcase_count'] > 0;
                ?>
                <div class="industry-card">
                    <div class="industry-card-header">
                        <i class="bi <?php echo $industry['icon'] ?: 'bi-building'; ?>"></i>
                        <?php if ($hasShowcase): ?>
                        <div class="showcase-badge-card">
                            <i class="bi bi-star-fill"></i> <?php echo $industry['showcase_count']; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="industry-card-content">
                        <h3><?php echo htmlspecialchars($industry['name']); ?></h3>
                        <?php if ($industry['description']): ?>
                        <p><?php echo htmlspecialchars(substr($industry['description'], 0, 100)); ?>...</p>
                        <?php endif; ?>
                        <div class="industry-stats">
                            <span><i class="bi bi-building"></i> <?php echo $industry['total']; ?> companies</span>
                        </div>
                    </div>
                    <div class="card-actions">
                        <a href="industries.php?slug=<?php echo $industry['slug']; ?>" class="action-area directory">
                            <i class="bi bi-list-ul"></i> View All
                        </a>
                        <?php if ($hasShowcase): ?>
                        <a href="<?= SITE_ROOT ?>/industry.php?slug=<?php echo $industry['slug']; ?>&tab=showcase" class="action-area showcase">
                            <i class="bi bi-star-fill"></i> View Featured
                        </a>
                        <?php else: ?>
                        <a href="advertise.php?industry=<?php echo $industry['slug']; ?>" class="action-area showcase">
                            <i class="bi bi-megaphone"></i> Advertise
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Simple CTA -->
            <div class="simple-cta">
                <p><i class="bi bi-megaphone"></i> Want to be featured?</p>
                <a href="<?= SITE_ROOT ?>/contact.php">Contact us to advertise →</a>
            </div>
            <?php endif; ?>
            
        </div>
    </div>
</section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
function searchCompanies() {
    let searchTerm = document.getElementById('companySearch').value;
    let currentUrl = new URL(window.location.href);
    if (searchTerm) {
        currentUrl.searchParams.set('search', searchTerm);
    } else {
        currentUrl.searchParams.delete('search');
    }
    window.location.href = currentUrl.toString();
}
</script>

</body>
</html>