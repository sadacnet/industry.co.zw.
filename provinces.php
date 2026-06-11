<?php
$pageTitle = "Provinces - industry.co.zw";
$pageDescription = "Explore businesses and opportunities across all provinces of Zimbabwe";
require_once __DIR__ . '/includes/head.php';

// Province data with actual company counts (in production, fetch from database)
$provinces = [
    ['slug' => 'harare', 'name' => 'Harare', 'icon' => 'bi-building', 'description' => 'Capital city with diverse business opportunities, financial services hub, and growing tech sector', 'company_count' => 0],
    ['slug' => 'bulawayo', 'name' => 'Bulawayo', 'icon' => 'bi-gear', 'description' => 'Industrial hub with strong manufacturing base, cultural tourism, and educational institutions', 'company_count' => 0],
    ['slug' => 'manicaland', 'name' => 'Manicaland', 'icon' => 'bi-tree', 'description' => 'Agricultural heartland with timber, tea, coffee production, and tourism potential', 'company_count' => 0],
    ['slug' => 'mashonaland-central', 'name' => 'Mashonaland Central', 'icon' => 'bi-minecart-loaded', 'description' => 'Mining region with agricultural potential, tobacco farming, and mineral deposits', 'company_count' => 0],
    ['slug' => 'mashonaland-east', 'name' => 'Mashonaland East', 'icon' => 'bi-leaf', 'description' => 'Agricultural production, horticulture, and proximity to Harare markets', 'company_count' => 0],
    ['slug' => 'mashonaland-west', 'name' => 'Mashonaland West', 'icon' => 'bi-water', 'description' => 'Tourism attractions, Lake Kariba, mining operations, and commercial farming', 'company_count' => 0],
    ['slug' => 'masvingo', 'name' => 'Masvingo', 'icon' => 'bi-bank', 'description' => 'Great Zimbabwe heritage site, agriculture, and growing industrial base', 'company_count' => 0],
    ['slug' => 'matabeleland-north', 'name' => 'Matabeleland North', 'icon' => 'bi-compass', 'description' => 'Victoria Falls tourism, wildlife conservation, coal mining, and timber', 'company_count' => 0],
    ['slug' => 'matabeleland-south', 'name' => 'Matabeleland South', 'icon' => 'bi-truck', 'description' => 'Ranching, mining, border trade with South Africa and Botswana', 'company_count' => 0],
    ['slug' => 'midlands', 'name' => 'Midlands', 'icon' => 'bi-geo-alt', 'description' => 'Central location advantage, mining, manufacturing, and educational institutions', 'company_count' => 0]
];

// Fetch actual company counts directly from database (more efficient than internal API call)
try {
    $db = (new Database())->getConnection();
    $countQuery = "SELECT p.slug, COUNT(c.id) as count
                   FROM provinces p
                   LEFT JOIN companies c ON p.id = c.province_id AND c.is_active = 1
                   GROUP BY p.id";
    $countStmt = $db->query($countQuery);
    $counts = $countStmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // Update province counts
    foreach ($provinces as &$province) {
        if (isset($counts[$province['slug']])) {
            $province['company_count'] = (int)$counts[$province['slug']];
        }
    }
} catch (Exception $e) {
    // Fallback to 0 if database fails
}
?>
<style>
    body.index-page { padding-top: 85px; }
    
    /* BREADCRUMB STYLES */
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
        color: #2e7d32;
        text-decoration: none;
        transition: color 0.2s;
    }
    .breadcrumb a:hover {
        color: #1b5e20;
        text-decoration: underline;
    }
    .breadcrumb .current {
        color: #666;
        font-weight: 500;
    }
    .breadcrumb i {
        font-size: 14px;
        color: #2e7d32;
    }

    /* Page Title */
    .page-title {
        background: linear-gradient(135deg, #1a5e2a, #0d3b1a);
        padding: 50px 0 40px;
        text-align: center;
        color: #fff;
    }
    .page-title h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #fff;
    }
    .page-title p {
        color: rgba(255,255,255,0.85);
        font-size: 16px;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Stats Bar */
    .stats-bar {
        background: #fff;
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        border: 1px solid #f0f0f0;
    }
    .stats-bar .total-count {
        font-size: 14px;
        color: #555;
    }
    .stats-bar .total-count strong {
        color: #2e7d32;
        font-size: 18px;
        font-weight: 700;
    }
    .stats-bar .search-box {
        display: flex;
        gap: 10px;
    }
    .stats-bar .search-box input {
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 25px;
        font-size: 13px;
        width: 250px;
        outline: none;
        transition: all 0.2s;
    }
    .stats-bar .search-box input:focus {
        border-color: #2e7d32;
        box-shadow: 0 0 0 2px rgba(46,125,50,0.1);
    }
    .stats-bar .search-box button {
        background: transparent;
        border: 1px solid #2e7d32;
        color: #2e7d32;
        padding: 8px 18px;
        border-radius: 25px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .stats-bar .search-box button:hover {
        background: #2e7d32;
        color: #fff;
    }

    /* Province Cards Grid */
    .provinces-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
    }
    
    .province-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
        position: relative;
    }
    .province-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.12);
    }
    
    .province-header {
        background: linear-gradient(135deg, #1a5e2a, #0d3b1a);
        padding: 25px 20px;
        text-align: center;
        position: relative;
    }
    .province-icon {
        font-size: 48px;
        color: rgba(255,255,255,0.3);
        margin-bottom: 10px;
    }
    .province-name {
        font-size: 22px;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }
    .province-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255,255,255,0.2);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: #fff;
    }
    
    .province-body {
        padding: 20px;
    }
    .province-description {
        font-size: 13px;
        color: #666;
        line-height: 1.5;
        margin-bottom: 15px;
    }
    .province-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 12px;
        border-top: 1px solid #f0f0f0;
    }
    .company-count {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #2e7d32;
        font-weight: 600;
    }
    .company-count i {
        font-size: 16px;
    }
    .view-link {
        color: #2e7d32;
        font-size: 13px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
    }
    .province-card:hover .view-link {
        gap: 8px;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 16px;
    }
    .empty-state i {
        font-size: 64px;
        color: #ccc;
        margin-bottom: 20px;
    }
    .empty-state h4 {
        font-size: 20px;
        color: #666;
        margin-bottom: 10px;
    }
    .empty-state p {
        color: #999;
        font-size: 14px;
    }

    /* Call to Action */
    .cta-section {
        background: linear-gradient(135deg, #1a5e2a, #0d3b1a);
        padding: 50px 0;
        margin-top: 40px;
        text-align: center;
        border-radius: 20px;
    }
    .cta-section h3 {
        color: #fff;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .cta-section p {
        color: rgba(255,255,255,0.85);
        font-size: 14px;
        margin-bottom: 20px;
    }
    .cta-btn {
        display: inline-block;
        background: #FFD700;
        color: #1a2c3e;
        padding: 12px 30px;
        border-radius: 40px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s;
    }
    .cta-btn:hover {
        background: #FFC107;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    @media (max-width: 768px) {
        body.index-page { padding-top: 70px; }
        .page-title { padding: 30px 0 20px; }
        .page-title h1 { font-size: 24px; }
        .provinces-grid { grid-template-columns: 1fr; gap: 20px; }
        .stats-bar { flex-direction: column; align-items: stretch; }
        .stats-bar .search-box { width: 100%; }
        .stats-bar .search-box input { flex: 1; width: auto; }
        .cta-section { margin-top: 30px; padding: 35px 20px; }
        .cta-section h3 { font-size: 20px; }
    }
</style>
</head>

<body class="index-page">

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<main class="main">

    <!-- BREADCRUMB NAVIGATION -->
    <div class="breadcrumb-wrapper">
        <ul class="breadcrumb">
            <li><a href="<?= SITE_ROOT ?>/index.php"><i class="bi bi-house-door"></i> Home</a></li>
            <li><span class="current">Provinces</span></li>
        </ul>
    </div>

    <!-- PAGE TITLE -->
    <section class="page-title">
        <div class="container">
            <h1>Provinces of Zimbabwe</h1>
            <p>Explore businesses and opportunities across all provinces of Zimbabwe</p>
        </div>
    </section>

    <section style="padding: 40px 0 60px; background: #f5f5f5;">
        <div class="container">

            <!-- STATS BAR -->
            <div class="stats-bar">
                <div class="total-count">
                    <i class="bi bi-pin-map-fill"></i> <strong id="totalProvinces"><?php echo count($provinces); ?></strong> provinces
                </div>
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search province..." autocomplete="off">
                    <button id="resetBtn"><i class="bi bi-arrow-repeat"></i> Reset</button>
                </div>
            </div>

            <!-- PROVINCES GRID -->
            <div class="provinces-grid" id="provincesContainer">
                <?php foreach ($provinces as $province): ?>
                <a href="<?= SITE_ROOT ?>/province.php?slug=<?php echo $province['slug']; ?>" class="province-card" data-name="<?php echo strtolower($province['name']); ?>">
                    <div class="province-header">
                        <div class="province-icon"><i class="bi <?php echo $province['icon']; ?>"></i></div>
                        <h3 class="province-name"><?php echo $province['name']; ?></h3>
                        <div class="province-badge">
                            <i class="bi bi-building"></i> <?php echo $province['company_count']; ?> Companies
                        </div>
                    </div>
                    <div class="province-body">
                        <p class="province-description"><?php echo $province['description']; ?></p>
                        <div class="province-stats">
                            <span class="company-count">
                                <i class="bi bi-briefcase"></i> <?php echo $province['company_count']; ?> registered businesses
                            </span>
                            <span class="view-link">
                                View Details <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

    <!-- CALL TO ACTION SECTION -->
    <div class="container" style="margin-bottom: -30px;">
        <div class="cta-section">
            <h3>List Your Company in Your Province</h3>
            <p>Get your business listed in your province and connect with customers, suppliers, and partners across Zimbabwe.</p>
            <a href="<?= SITE_ROOT ?>/contact.php" class="cta-btn">Add Your Business →</a>
        </div>
    </div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    const searchInput = document.getElementById('searchInput');
    const resetBtn = document.getElementById('resetBtn');
    const provinceCards = document.querySelectorAll('.province-card');

    function filterProvinces() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        provinceCards.forEach(card => {
            const provinceName = card.getAttribute('data-name');
            if (searchTerm === '' || provinceName.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function resetFilters() {
        searchInput.value = '';
        provinceCards.forEach(card => {
            card.style.display = 'block';
        });
    }

    searchInput.addEventListener('keyup', filterProvinces);
    resetBtn.addEventListener('click', resetFilters);
</script>

</body>
</html>