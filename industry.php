<?php
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
if (empty($slug)) { header('Location: industries.php'); exit; }

$pageTitle = "Find Suppliers - industry.co.zw";
require_once __DIR__ . '/includes/head.php';

// Helper function to get industry display name
function getIndustryName($slug) {
    $industries = [
        'auto' => 'Auto',
        'accommodation' => 'Accommodation',
        'agriculture' => 'Agriculture',
        'banking-finance' => 'Banking & Finance',
        'construction' => 'Construction',
        'education' => 'Education',
        'energy-power' => 'Energy & Power',
        'healthcare' => 'Healthcare',
        'manufacturing' => 'Manufacturing',
        'mining' => 'Mining',
        'technology-ict' => 'Technology & ICT',
        'tourism-hospitality' => 'Tourism & Hospitality',
        'transport-logistics' => 'Transport & Logistics'
    ];
    return $industries[$slug] ?? ucfirst($slug);
}

// Helper function to format website URL correctly
function formatWebsiteUrl($url) {
    if (empty($url)) return '';
    $url = trim($url);
    $displayUrl = preg_replace('#^https?://#', '', $url);
    $hrefUrl = 'https://' . $displayUrl;
    return ['href' => $hrefUrl, 'display' => $displayUrl];
}

// Get industry display name for breadcrumb
$industryName = getIndustryName($slug);

// Mock data for showcase companies
$showcaseCompanies = [
    [
        'id' => 1,
        'name' => 'First Plastics Private Limited',
        'logo' => null,
        'description' => 'First Plastics delivers high-quality plastic bottles and containers. With over 20 years of experience, we are Zimbabwe\'s leading manufacturer of premium plastic bottles, containers, and packaging solutions. Our state-of-the-art facility in Msasa produces over 5 million units monthly, serving clients across beverage, pharmaceutical, and household sectors.',
        'website' => 'firstplastics.co.zw',
        'phone' => '+263 77 123 4567',
        'email' => 'info@firstplastics.co.zw',
        'province_name' => 'Harare',
        'tier' => 'platinum',
        'landing_page' => '/company/platinum/first-plastics',
    ],
    [
        'id' => 2,
        'name' => 'Proplastics Limited',
        'logo' => null,
        'description' => 'Proplastics is Zimbabwe\'s leading manufacturer of PVC pipes and fittings for water, sewerage, irrigation, and mining applications. Established in 1972, we have built a reputation for durability, innovation, and customer service. Our products meet international standards and are trusted by major infrastructure projects across Southern Africa.',
        'website' => 'proplastics.co.zw',
        'phone' => '+263 78 987 6543',
        'email' => 'sales@proplastics.co.zw',
        'province_name' => 'Harare',
        'tier' => 'gold',
        'landing_page' => '/company/gold/proplastics',
    ],
    [
        'id' => 3,
        'name' => 'Eligo Plastics',
        'logo' => null,
        'description' => 'Eligo Plastics specializes in custom plastic molding and packaging solutions for the food and beverage industry. Our expertise includes injection molding, blow molding, and custom design services. We work closely with clients to develop bespoke packaging that enhances brand identity.',
        'website' => 'eligoplastics.co.zw',
        'phone' => '+263 77 555 1234',
        'email' => 'info@eligoplastics.co.zw',
        'province_name' => 'Harare',
        'tier' => 'silver',
        'landing_page' => '/company.php?id=3',
    ],
    [
        'id' => 4,
        'name' => 'Panna Trading',
        'logo' => null,
        'description' => 'Panna Trading is a leading distributor of plastic raw materials, industrial packaging, and agricultural films. We supply recycled plastic pellets, LDPE films, and industrial bags to manufacturers across Zimbabwe. Our commitment to sustainability and quality has made us a preferred partner.',
        'website' => 'pannatrading.co.zw',
        'phone' => '+263 77 444 5678',
        'email' => 'sales@pannatrading.co.zw',
        'province_name' => 'Harare',
        'tier' => 'silver',
        'landing_page' => '/company.php?id=4',
    ]
];

// Mock news data
$industryNews = [
    [
        'date' => '3',
        'month' => 'Jun',
        'title' => 'First Plastics Announces $2M Expansion to New Facility',
        'summary' => 'First Plastics Private Limited has announced a $2 million expansion to a new manufacturing facility in Ruwa, creating 50 new jobs and increasing production capacity.'
    ],
    [
        'date' => '28',
        'month' => 'May',
        'title' => 'Proplastics Reports Strong Half-Year Results',
        'summary' => 'Proplastics Limited reported a 15% increase in revenue for the first half of 2026, driven by strong demand for PVC pipes in mining and infrastructure projects.'
    ],
    [
        'date' => '15',
        'month' => 'May',
        'title' => 'Government Announces Plastic Recycling Incentive Program',
        'summary' => 'The Zimbabwean government has launched a new incentive program for plastic recycling companies, offering tax breaks and grants for sustainable practices.'
    ]
];
?>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    body.index-page { padding-top: 85px; }
    main.main { background: #f5f5f5; }

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

    .category-bar {
        background: #fff; 
        border-bottom: 1px solid #e0e0e0; 
        padding: 0;
        position: sticky; 
        top: 85px;
        z-index: 98; 
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .category-bar-inner { display: flex; align-items: center; max-width: 100%; overflow: hidden; }
    .category-bar-title { font-size: 15px; font-weight: 700; color: #2e7d32; padding: 14px 18px; white-space: nowrap; min-width: 180px; }
    .category-tabs-wrapper { display: flex; align-items: center; flex: 1; overflow: hidden; }
    .category-tabs { display: flex; align-items: center; overflow-x: auto; scroll-behavior: smooth; scrollbar-width: none; -ms-overflow-style: none; padding: 0 8px; gap: 0; }
    .category-tabs::-webkit-scrollbar { display: none; }
    .category-tab { display: flex; align-items: center; gap: 6px; padding: 14px 16px; font-size: 13px; font-weight: 500; color: #555; cursor: pointer; white-space: nowrap; border-bottom: 3px solid transparent; transition: all 0.2s; text-decoration: none; }
    .category-tab:hover { color: #2e7d32; }
    .category-tab.active { color: #2e7d32; border-bottom: 3px solid #2e7d32; font-weight: 700; }
    .category-tab i { font-size: 18px; }
    .scroll-arrow { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #999; background: #fff; border: none; font-size: 18px; flex-shrink: 0; z-index: 2; }
    .scroll-arrow:hover { color: #2e7d32; }

    /* Page Tabs */
    .page-tabs {
        background: #fff;
        border-bottom: 1px solid #e0e0e0;
        padding: 0 20px;
        display: flex;
        gap: 32px;
        margin-top: 0;
    }
    .page-tab {
        padding: 14px 0;
        font-size: 15px;
        font-weight: 600;
        color: #666;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .page-tab i { font-size: 18px; }
    .page-tab:hover { color: #2e7d32; }
    .page-tab.active { color: #2e7d32; border-bottom-color: #2e7d32; }
    .page-tab-badge {
        background: #f0f0f0;
        color: #666;
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 500;
    }
    .page-tab.active .page-tab-badge { background: #e8f5e9; color: #2e7d32; }

    .main-layout { display: flex; max-width: 100%; min-height: calc(100vh - 160px); }

    /* Sidebar */
    .sidebar { 
        width: 340px; 
        min-width: 340px; 
        background: #fff; 
        border-right: 1px solid #e0e0e0; 
        padding: 0; 
        overflow-y: auto; 
        max-height: calc(100vh - 160px); 
        position: sticky; 
        top: 145px; 
    }
    .sidebar-tabs { display: flex; border-bottom: 1px solid #e0e0e0; padding: 10px 16px 0; gap: 16px; }
    .sidebar-tab { display: flex; align-items: center; gap: 5px; padding: 10px 0; font-size: 13px; font-weight: 500; color: #666; cursor: pointer; border-bottom: 2px solid transparent; }
    .sidebar-tab.active { color: #2e7d32; border-bottom: 2px solid #2e7d32; }
    .sidebar-content { padding: 20px; }
    .filter-group { margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid #eee; }
    .filter-group:last-child { border-bottom: none; }
    .filter-label { font-size: 13px; font-weight: 600; color: #333; margin-bottom: 8px; }
    .filter-input { width: 100%; padding: 10px 0; border: none; border-bottom: 1px solid #ddd; font-size: 14px; outline: none; background: transparent; }
    .filter-input:focus { border-bottom-color: #2e7d32; }
    .filter-select { width: 100%; padding: 10px 25px 10px 0; border: none; border-bottom: 1px solid #ddd; font-size: 14px; outline: none; background: transparent; appearance: none; cursor: pointer; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24'%3E%3Cpath fill='%23999' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right center; }
    .search-btn { width: 100%; padding: 12px; background: #2e7d32; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; margin-top: 8px; }
    .search-btn:hover { background: #1b5e20; }
    .reset-btn { width: 100%; padding: 10px; background: transparent; color: #666; border: 1px solid #ddd; border-radius: 8px; font-size: 13px; cursor: pointer; margin-top: 8px; }
    .reset-btn:hover { background: #f5f5f5; color: #2e7d32; }

    /* Sidebar Ads */
    .sidebar-ads { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
    .sidebar-ad { margin-bottom: 25px; text-align: center; }
    .sidebar-ad .ad-placeholder {
        background: linear-gradient(135deg, #f8f9fa, #f0f0f0);
        padding: 35px 15px;
        text-align: center;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    .sidebar-ad .ad-placeholder:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .sidebar-ad .ad-placeholder i {
        font-size: 48px;
        color: #2e7d32;
        opacity: 0.5;
        display: block;
        margin-bottom: 10px;
    }
    .sidebar-ad .ad-placeholder p {
        font-size: 13px;
        font-weight: 500;
        color: #666;
        margin: 0;
    }
    .sidebar-ad .ad-placeholder small {
        font-size: 10px;
        color: #999;
    }
    .advertise-cta { 
        background: #e8f5e9; 
        padding: 15px; 
        border-radius: 10px; 
        text-align: center; 
        margin-top: 15px;
        border: 1px solid #c8e6c9;
    }
    .advertise-cta i { font-size: 24px; color: #2e7d32; margin-bottom: 8px; display: block; }
    .advertise-cta div { font-size: 14px; font-weight: 600; color: #333; margin-bottom: 8px; }
    .advertise-cto-cta a { color: #2e7d32; text-decoration: none; font-size: 13px; font-weight: 600; }
    .advertise-cta a:hover { text-decoration: underline; }

    .content-area { flex: 1; padding: 0; overflow-y: auto; }
    .listings-section { overflow-y: auto; max-height: calc(100vh - 160px); }
    .content-header { display: flex; align-items: center; justify-content: space-between; padding: 12px 20px; background: #fff; border-bottom: 1px solid #e0e0e0; }
    .results-count { font-size: 13px; color: #555; }
    .results-count strong { color: #2e7d32; }

    .listings-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; padding: 20px; }
    .listing-card { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06); transition: all 0.2s; cursor: pointer; position: relative; }
    .listing-card:hover { box-shadow: 0 8px 20px rgba(0,0,0,0.12); transform: translateY(-3px); }
    
    .featured-badge { position: absolute; top: 10px; left: 10px; background: #FFD700; color: #333; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; z-index: 2; display: flex; align-items: center; gap: 4px; }
    
    .listing-card-image { position: relative; height: 180px; overflow: hidden; background: #e0e0e0; }
    .listing-card-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
    .listing-card-image .no-image { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #bbb; font-size: 48px; background: linear-gradient(135deg, #e0e0e0, #d0d0d0); }
    
    .listing-card-info { position: absolute; bottom: 0; left: 0; right: 0; padding: 20px 16px; background: linear-gradient(transparent, rgba(0,0,0,0.85)); color: #fff; }
    .listing-card-title { font-size: 16px; font-weight: 700; margin-bottom: 4px; color: #fff; }
    .listing-card-location { font-size: 12px; display: flex; align-items: center; gap: 4px; opacity: 0.9; }
    
    .listing-card-body { padding: 16px; }
    .listing-card-body p { font-size: 12px; color: #666; margin-bottom: 6px; }
    .listing-card-body p i { color: #2e7d32; margin-right: 5px; width: 16px; }
    .website-link {
        font-size: 11px;
        word-break: break-all;
        color: #1a73e8;
        text-decoration: none;
    }
    .website-link:hover {
        text-decoration: underline;
    }

    /* Premium Showcase Styles - Clean, No Tier Labels Visible */
    .showcase-section { padding: 24px; }
    .showcase-header { margin-bottom: 24px; }
    .showcase-header h2 { font-size: 20px; font-weight: 700; color: #2e7d32; margin-bottom: 8px; }
    .showcase-header p { font-size: 13px; color: #666; }
    .showcase-sponsor-tag { display: inline-block; background: #f0f8f0; color: #2e7d32; font-size: 11px; padding: 4px 12px; border-radius: 20px; margin-bottom: 16px; }
    
    .showcase-item { margin-bottom: 32px; padding-bottom: 32px; border-bottom: 1px solid #eee; }
    .showcase-item:last-child { border-bottom: none; }
    
    .showcase-item .company-title { font-size: 20px; font-weight: 700; color: #1a2c3e; margin-bottom: 12px; }
    .showcase-item .company-desc { font-size: 14px; line-height: 1.7; color: #555; }
    .showcase-item .company-logo-box { min-height: 140px; border: 1px solid #e0e0e0; background: #fafafa; }
    .showcase-item .company-logo-box img { max-height: 100px; }
    .showcase-item .company-contact-info { font-size: 13px; }
    
    /* Consistent styling for all showcase items - no visible tier differences */
    .company-title { margin-bottom: 12px; letter-spacing: 0.5px; }
    .company-desc { color: #555; margin-bottom: 16px; line-height: 1.6; }
    .company-desc.expanded { max-height: none; }
    .company-desc:not(.expanded) { max-height: 100px; overflow: hidden; position: relative; }
    .company-desc:not(.expanded)::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 40px; background: linear-gradient(transparent, #fff); }
    .company-website a { color: #1a73e8; text-decoration: none; font-weight: 500; word-break: break-all; }
    .company-website a:hover { text-decoration: underline; }
    .company-contact-info { display: flex; flex-wrap: wrap; gap: 16px; margin: 12px 0; color: #555; font-size: 13px; }
    .company-contact-info i { color: #2e7d32; margin-right: 5px; width: 16px; }
    .read-more-btn { display: inline-block; background-color: #2e7d32; color: #fff; padding: 8px 20px; font-size: 13px; font-weight: 500; border: none; border-radius: 4px; cursor: pointer; margin-top: 8px; transition: background-color 0.3s; }
    .read-more-btn:hover { background-color: #1b5e20; }
    .contact-btn { display: inline-block; background-color: transparent; color: #2e7d32; padding: 7px 19px; font-size: 13px; font-weight: 500; border: 1px solid #2e7d32; border-radius: 4px; margin-left: 10px; cursor: pointer; transition: all 0.3s; }
    .contact-btn:hover { background-color: #2e7d32; color: #fff; }
    .company-logo-box { border: 1px solid #ddd; padding: 20px; display: flex; align-items: center; justify-content: center; background: #fff; border-radius: 8px; }
    .company-logo-box img { max-width: 100%; object-fit: contain; }
    
    .news-section { padding: 24px; }
    .news-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .news-title { font-size: 20px; font-weight: 700; color: #2e7d32; border-left: 4px solid #f08a1e; padding-left: 15px; }
    .news-item { background: #fff; padding: 20px; margin-bottom: 16px; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.08); transition: all 0.2s; }
    .news-item:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
    .news-date { font-size: 13px; color: #888; margin-bottom: 8px; }
    .news-date .day { color: #2e7d32; font-weight: 700; font-size: 16px; margin-right: 4px; }
    .news-item .title { font-weight: 600; color: #333; text-decoration: none; font-size: 16px; display: block; margin-bottom: 8px; }
    .news-item .title:hover { color: #2e7d32; }
    .news-item .summary { font-size: 13px; color: #666; line-height: 1.5; }
    .sponsored-news { background: #fef8e7; border-left: 3px solid #f08a1e; }
    .sponsored-tag { display: inline-block; background: #f08a1e; color: #fff; font-size: 10px; padding: 2px 8px; border-radius: 12px; margin-left: 10px; vertical-align: middle; }

    .pagination { display: flex; align-items: center; justify-content: center; gap: 4px; padding: 16px; flex-wrap: wrap; }
    .page-btn { min-width: 32px; height: 32px; border-radius: 50%; border: 1px solid #ddd; background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 12px; font-weight: 500; color: #555; padding: 0 8px; }
    .page-btn:hover { border-color: #2e7d32; color: #2e7d32; }
    .page-btn.active { background: #2e7d32; color: #fff; border-color: #2e7d32; }
    .page-btn:disabled { opacity: 0.4; cursor: not-allowed; }

    .tab-content { display: none; }
    .tab-content.active { display: block; }

    @media (max-width: 1100px) {
        .sidebar { width: 300px; min-width: 300px; }
    }
    @media (max-width: 900px) {
        .listings-grid { grid-template-columns: 1fr; }
        .sidebar { width: 280px; min-width: 280px; }
    }
    @media (max-width: 768px) {
        body.index-page { padding-top: 70px; }
        .category-bar { top: 70px; }
        .sidebar { width: 100%; min-width: 100%; max-height: none; position: relative; top: 0; }
        .main-layout { flex-direction: column; }
        .page-tabs { gap: 16px; padding: 0 16px; overflow-x: auto; }
        .page-tab { font-size: 13px; white-space: nowrap; }
        .showcase-item .company-title { font-size: 18px; }
        .company-logo-box { margin-top: 16px; }
    }
    
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .fade-in-up { animation: fadeInUp 0.5s ease-out; }
</style>
</head>

<body class="index-page">

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<main class="main">

    <!-- BREADCRUMB NAVIGATION -->
    <div class="breadcrumb-wrapper">
        <ul class="breadcrumb">
            <li><a href="<?= SITE_ROOT ?>/index.php"><i class="bi bi-house-door"></i> Home</a></li>
            <li><a href="<?= SITE_ROOT ?>/industries.php">Industries</a></li>
            <li><span class="current"><?php echo $industryName; ?> Suppliers</span></li>
        </ul>
    </div>

    <div class="category-bar">
        <div class="category-bar-inner">
            <div class="category-bar-title">What are you looking for?</div>
            <div class="category-tabs-wrapper">
                <button class="scroll-arrow left" onclick="scrollTabs(-1)"><i class="bi bi-chevron-left"></i></button>
                <div class="category-tabs" id="categoryTabs">
                    <a href="<?= SITE_ROOT ?>/industry.php?slug=auto" class="category-tab <?php echo $slug == 'auto' ? 'active' : ''; ?>"><i class="bi bi-car-front"></i> Auto</a>
                    <a href="<?= SITE_ROOT ?>/industry.php?slug=accommodation" class="category-tab <?php echo $slug == 'accommodation' ? 'active' : ''; ?>"><i class="bi bi-building"></i> Accommodation</a>
                    <a href="<?= SITE_ROOT ?>/industry.php?slug=agriculture" class="category-tab <?php echo $slug == 'agriculture' ? 'active' : ''; ?>"><i class="bi bi-leaf"></i> Agriculture</a>
                    <a href="<?= SITE_ROOT ?>/industry.php?slug=banking-finance" class="category-tab <?php echo $slug == 'banking-finance' ? 'active' : ''; ?>"><i class="bi bi-bank"></i> Finance</a>
                    <a href="<?= SITE_ROOT ?>/industry.php?slug=construction" class="category-tab <?php echo $slug == 'construction' ? 'active' : ''; ?>"><i class="bi bi-hammer"></i> Construction</a>
                    <a href="<?= SITE_ROOT ?>/industry.php?slug=education" class="category-tab <?php echo $slug == 'education' ? 'active' : ''; ?>"><i class="bi bi-book"></i> Education</a>
                    <a href="<?= SITE_ROOT ?>/industry.php?slug=energy-power" class="category-tab <?php echo $slug == 'energy-power' ? 'active' : ''; ?>"><i class="bi bi-lightning-charge"></i> Energy</a>
                    <a href="<?= SITE_ROOT ?>/industry.php?slug=healthcare" class="category-tab <?php echo $slug == 'healthcare' ? 'active' : ''; ?>"><i class="bi bi-capsule"></i> Healthcare</a>
                    <a href="<?= SITE_ROOT ?>/industry.php?slug=manufacturing" class="category-tab <?php echo $slug == 'manufacturing' ? 'active' : ''; ?>"><i class="bi bi-gear"></i> Manufacturing</a>
                    <a href="<?= SITE_ROOT ?>/industry.php?slug=mining" class="category-tab <?php echo $slug == 'mining' ? 'active' : ''; ?>"><i class="bi bi-minecart"></i> Mining</a>
                    <a href="<?= SITE_ROOT ?>/industry.php?slug=technology-ict" class="category-tab <?php echo $slug == 'technology-ict' ? 'active' : ''; ?>"><i class="bi bi-laptop"></i> ICT</a>
                    <a href="<?= SITE_ROOT ?>/industry.php?slug=tourism-hospitality" class="category-tab <?php echo $slug == 'tourism-hospitality' ? 'active' : ''; ?>"><i class="bi bi-compass"></i> Tourism</a>
                    <a href="<?= SITE_ROOT ?>/industry.php?slug=transport-logistics" class="category-tab <?php echo $slug == 'transport-logistics' ? 'active' : ''; ?>"><i class="bi bi-truck"></i> Transport</a>
                </div>
                <button class="scroll-arrow right" onclick="scrollTabs(1)"><i class="bi bi-chevron-right"></i></button>
            </div>
        </div>
    </div>

    <!-- Page Tabs -->
    <div class="page-tabs">
        <div class="page-tab active" data-tab="directory-tab">
            <i class="bi bi-grid-3x3-gap-fill"></i> All Suppliers
            <span class="page-tab-badge" id="supplierCount">0</span>
        </div>
        <div class="page-tab" data-tab="showcase-tab">
            <i class="bi bi-star-fill" style="color: #f08a1e;"></i> Premium Showcase
            <span class="page-tab-badge"><?php echo count($showcaseCompanies); ?></span>
        </div>
        <div class="page-tab" data-tab="news-tab">
            <i class="bi bi-newspaper"></i> Industry News
            <span class="page-tab-badge"><?php echo count($industryNews); ?></span>
        </div>
    </div>

    <div class="main-layout">
        
        <!-- Sidebar -->
        <div class="sidebar" id="main-sidebar">
            <div class="sidebar-tabs"><div class="sidebar-tab active"><i class="bi bi-funnel"></i> Filters</div></div>
            <div class="sidebar-content">
                <div class="filter-group">
                    <div class="filter-label">Search</div>
                    <input type="text" class="filter-input" id="searchKeywords" placeholder="Search companies...">
                </div>
                <div class="filter-group">
                    <div class="filter-label">Province</div>
                    <select class="filter-select" id="provinceFilter">
                        <option value="">All Provinces</option>
                    </select>
                </div>
                <button class="search-btn" onclick="applyFilters()">Search</button>
                <button class="reset-btn" onclick="resetFilters()">Reset Filters</button>
                
                <!-- Sidebar Advertisement Space -->
                <div class="sidebar-ads">
                    <div class="sidebar-ad">
                        <a href="<?= SITE_ROOT ?>/advertise.php?industry=<?php echo $slug; ?>">
                            <div class="ad-placeholder">
                                <i class="bi bi-megaphone"></i>
                                <p>Advertise Here</p>
                                <small>300x250</small>
                            </div>
                            <div style="font-size: 10px; color: #999; margin-top: 6px;">Advertisement</div>
                        </a>
                    </div>
                    <div class="sidebar-ad">
                        <a href="<?= SITE_ROOT ?>/advertise.php?industry=<?php echo $slug; ?>">
                            <div class="ad-placeholder">
                                <i class="bi bi-star-fill"></i>
                                <p>Featured Spot</p>
                                <small>Premium Placement</small>
                            </div>
                            <div style="font-size: 10px; color: #999; margin-top: 6px;">Advertisement</div>
                        </a>
                    </div>
                    <div class="advertise-cta">
                        <i class="bi bi-megaphone"></i>
                        <div>Want to be here?</div>
                        <a href="<?= SITE_ROOT ?>/advertise.php?industry=<?php echo $slug; ?>">Advertise with us →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <div class="listings-section">
                
                <!-- TAB 1: DIRECTORY -->
                <div id="directory-tab" class="tab-content active">
                    <div class="content-header">
                        <span class="results-count">Showing <strong id="resultCount">0</strong> results</span>
                    </div>
                    <div class="listings-grid" id="listingsGrid">
                        <div class="text-center py-5" style="grid-column:1/-1;">
                            <div class="spinner-border text-success"></div>
                        </div>
                    </div>
                    <div class="pagination" id="pagination"></div>
                </div>

                <!-- TAB 2: PREMIUM SHOWCASE - Clean, No Tier Labels -->
                <div id="showcase-tab" class="tab-content">
                    <div class="showcase-section">
                        <div class="showcase-header">
                            <div class="showcase-sponsor-tag">Premium Partners</div>
                            <h2>Top <?php echo $industryName; ?> Suppliers in Zimbabwe</h2>
                            <p>Discover Zimbabwe's leading <?php echo strtolower($industryName); ?> companies and suppliers.</p>
                        </div>
                        
                        <?php foreach($showcaseCompanies as $index => $company): 
                            $websiteData = formatWebsiteUrl($company['website']);
                        ?>
                        <div class="showcase-item fade-in-up" style="animation-delay: <?php echo $index * 0.1; ?>s;">
                            <div class="row align-items-center g-4">
                                <?php if($index % 2 == 0): ?>
                                <div class="col-md-3">
                                    <figure class="company-logo-box">
                                        <?php if($company['logo']): ?>
                                        <img src="<?= SITE_ROOT ?>/<?php echo $company['logo']; ?>" alt="<?php echo $company['name']; ?>">
                                        <?php else: ?>
                                        <div style="font-size:48px; color:#ccc;"><i class="bi bi-building"></i></div>
                                        <?php endif; ?>
                                    </figure>
                                </div>
                                <div class="col-md-9 pe-xl-5">
                                    <h2 class="company-title"><?php echo $company['name']; ?></h2>
                                    <div class="company-desc" id="desc-<?php echo $company['id']; ?>">
                                        <?php echo $company['description']; ?>
                                    </div>
                                    <div class="company-contact-info">
                                        <?php if($company['phone']): ?><span><i class="bi bi-telephone"></i> <?php echo $company['phone']; ?></span><?php endif; ?>
                                        <?php if($company['email']): ?><span><i class="bi bi-envelope"></i> <?php echo $company['email']; ?></span><?php endif; ?>
                                        <?php if($company['province_name']): ?><span><i class="bi bi-geo-alt"></i> <?php echo $company['province_name']; ?></span><?php endif; ?>
                                    </div>
                                    <p class="company-website">
                                        <a href="<?php echo $websiteData['href']; ?>" target="_blank" rel="noopener noreferrer">
                                            <i class="bi bi-globe"></i> <?php echo $websiteData['display']; ?>
                                        </a>
                                    </p>
                                    <div>
                                        <button class="read-more-btn" onclick="toggleReadMore(<?php echo $company['id']; ?>)">Read More</button>
                                        <button class="contact-btn" onclick="showContactForm(<?php echo $company['id']; ?>, '<?php echo addslashes($company['name']); ?>')">Contact</button>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="col-md-9 pe-xl-5">
                                    <h2 class="company-title"><?php echo $company['name']; ?></h2>
                                    <div class="company-desc" id="desc-<?php echo $company['id']; ?>">
                                        <?php echo $company['description']; ?>
                                    </div>
                                    <div class="company-contact-info">
                                        <?php if($company['phone']): ?><span><i class="bi bi-telephone"></i> <?php echo $company['phone']; ?></span><?php endif; ?>
                                        <?php if($company['email']): ?><span><i class="bi bi-envelope"></i> <?php echo $company['email']; ?></span><?php endif; ?>
                                        <?php if($company['province_name']): ?><span><i class="bi bi-geo-alt"></i> <?php echo $company['province_name']; ?></span><?php endif; ?>
                                    </div>
                                    <p class="company-website">
                                        <a href="<?php echo $websiteData['href']; ?>" target="_blank" rel="noopener noreferrer">
                                            <i class="bi bi-globe"></i> <?php echo $websiteData['display']; ?>
                                        </a>
                                    </p>
                                    <div>
                                        <button class="read-more-btn" onclick="toggleReadMore(<?php echo $company['id']; ?>)">Read More</button>
                                        <button class="contact-btn" onclick="showContactForm(<?php echo $company['id']; ?>, '<?php echo addslashes($company['name']); ?>')">Contact</button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <figure class="company-logo-box">
                                        <?php if($company['logo']): ?>
                                        <img src="<?= SITE_ROOT ?>/<?php echo $company['logo']; ?>" alt="<?php echo $company['name']; ?>">
                                        <?php else: ?>
                                        <div style="font-size:48px; color:#ccc;"><i class="bi bi-building"></i></div>
                                        <?php endif; ?>
                                    </figure>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                      

                <!-- TAB 3: INDUSTRY NEWS -->
                <div id="news-tab" class="tab-content">
                    <div class="news-section">
                        <div class="news-header">
                            <h2 class="news-title"><?php echo $industryName; ?> Industry News</h2>
                            <a href="#" class="more-news-btn" style="font-size:13px;">More News <i class="bi bi-arrow-right"></i></a>
                        </div>
                        <?php foreach($industryNews as $index => $news): ?>
                        <div class="news-item fade-in-up" style="animation-delay: <?php echo $index * 0.1; ?>s;">
                            <div class="news-date"><span class="day"><?php echo $news['date']; ?></span> <?php echo $news['month']; ?></div>
                            <a href="#" class="title"><?php echo $news['title']; ?></a>
                            <div class="summary"><?php echo $news['summary']; ?></div>
                        </div>
                        <?php endforeach; ?>
                        <div class="news-item sponsored-news">
                            <div class="news-date"><span class="day">Sponsored</span></div>
                            <a href="#" class="title">Promote Your Company Here <span class="sponsored-tag">Sponsored</span></a>
                            <div class="summary">Reach thousands of industry professionals with your company news, product launches, or announcements.</div>
                            <a href="<?= SITE_ROOT ?>/advertise.php?industry=<?php echo $slug; ?>" style="display: inline-block; margin-top: 12px; font-size: 13px; color: #f08a1e; font-weight: 600;">Publish Your News →</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    const slug = '<?php echo $slug; ?>';
    const API = '<?= SITE_ROOT ?>/api/public';
    let allCompanies = [];
    let filteredCompanies = [];
    let currentPage = 1;
    const perPage = 8;

    // Tab switching
    document.querySelectorAll('.page-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            document.querySelectorAll('.page-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            const sidebar = document.getElementById('main-sidebar');
            if (tabId === 'directory-tab') {
                sidebar.style.display = 'block';
            } else {
                sidebar.style.display = 'none';
            }
            document.querySelector('.listings-section').scrollTo({ top: 0, behavior: 'smooth' });
        });
    });

    document.querySelectorAll('.category-tab').forEach(tab => {
        if (tab.href.includes('slug=' + slug)) tab.classList.add('active');
        else tab.classList.remove('active');
    });

    // Fetch companies
    fetch(API + '/companies.php?industry=' + slug + '&limit=2000&stakeholder=null')
      .then(r => r.json())
      .then(d => {
        if (d.status === 'success' && d.data && d.data.length > 0) {
          allCompanies = d.data; 
          filteredCompanies = [...allCompanies];
          let provinces = [...new Set(allCompanies.map(c => c.province_name))].sort();
          let select = document.getElementById('provinceFilter');
          provinces.forEach(p => { select.innerHTML += `<option value="${p}">${p}</option>`; });
          renderPage();
          document.getElementById('supplierCount').textContent = allCompanies.length;
        } else {
          document.getElementById('listingsGrid').innerHTML = '<div class="text-center py-5" style="grid-column:1/-1;"><p class="text-muted">No companies found.</p></div>';
          document.getElementById('supplierCount').textContent = '0';
        }
      })
      .catch(err => {
        console.error("API Error:", err);
        document.getElementById('listingsGrid').innerHTML = '<div class="text-center py-5" style="grid-column:1/-1;"><p class="text-muted">Error loading companies.</p></div>';
        document.getElementById('supplierCount').textContent = '0';
      });

    function applyFilters() {
      let search = document.getElementById('searchKeywords').value.toLowerCase();
      let province = document.getElementById('provinceFilter').value;
      filteredCompanies = allCompanies.filter(c => {
        if (search && !c.name.toLowerCase().includes(search) && (!c.description || !c.description.toLowerCase().includes(search))) return false;
        if (province && c.province_name !== province) return false;
        return true;
      });
      currentPage = 1; 
      renderPage();
    }

    function resetFilters() {
      document.getElementById('searchKeywords').value = '';
      document.getElementById('provinceFilter').value = '';
      filteredCompanies = [...allCompanies]; 
      currentPage = 1; 
      renderPage();
    }

    function renderPage() {
      document.getElementById('resultCount').textContent = filteredCompanies.length;
      let totalPages = Math.ceil(filteredCompanies.length / perPage);
      let pageItems = filteredCompanies.slice((currentPage-1)*perPage, currentPage*perPage);
      let grid = document.getElementById('listingsGrid');
      if (pageItems.length === 0) {
        grid.innerHTML = '<div class="text-center py-5" style="grid-column:1/-1;"><p class="text-muted">No companies match.</p></div>';
      } else {
        grid.innerHTML = pageItems.map(c => {
            let displayUrl = '';
            let hrefUrl = '#';
            if (c.website) {
                let cleanUrl = c.website.replace(/^https?:\/\//, '');
                displayUrl = cleanUrl;
                hrefUrl = 'https://' + cleanUrl;
            }
            return `
          <div class="listing-card" onclick="window.location.href='/company.php?id=${c.id}'">
            ${c.is_featured ? '<div class="featured-badge">Featured</div>' : ''}
            <div class="listing-card-image">
              ${c.logo ? `<img src="<?= SITE_ROOT ?>/${c.logo}" alt="${c.name}">` : `<div class="no-image"><i class="bi bi-building"></i></div>`}
              <div class="listing-card-info">
                <div class="listing-card-title">${c.name}</div>
                <div class="listing-card-location"><i class="bi bi-geo-alt"></i> ${c.province_name}</div>
              </div>
            </div>
            <div class="listing-card-body">
              ${c.phone ? `<p><i class="bi bi-telephone"></i> ${c.phone}</p>` : ''}
              ${c.email ? `<p><i class="bi bi-envelope"></i> ${c.email}</p>` : ''}
              ${c.website ? `<p><i class="bi bi-globe"></i> <a href="${hrefUrl}" target="_blank" onclick="event.stopPropagation();" rel="noopener noreferrer" class="website-link">${displayUrl}</a></p>` : ''}
              ${c.description ? `<p style="color:#999;font-size:11px;margin-top:4px;">${c.description.substring(0,80)}${c.description.length>80?'...':''}</p>` : ''}
            </div>
          </div>`}).join('');
      }
      let pag = document.getElementById('pagination');
      if (totalPages <= 1) { pag.innerHTML = ''; return; }
      let html = `<button class="page-btn" onclick="goToPage(${currentPage-1})" ${currentPage===1?'disabled':''}>«</button>`;
      let s = Math.max(1,currentPage-2), e = Math.min(totalPages,currentPage+2);
      if(s>1){html+=`<button class="page-btn" onclick="goToPage(1)">1</button>`; if(s>2)html+=`<span class="page-btn ellipsis">…</span>`;}
      for(let i=s;i<=e;i++) html+=`<button class="page-btn ${i===currentPage?'active':''}" onclick="goToPage(${i})">${i}</button>`;
      if(e<totalPages){if(e<totalPages-1)html+=`<span class="page-btn ellipsis">…</span>`; html+=`<button class="page-btn" onclick="goToPage(${totalPages})">${totalPages}</button>`;}
      html+=`<button class="page-btn" onclick="goToPage(${currentPage+1})" ${currentPage===totalPages?'disabled':''}>»</button>`;
      pag.innerHTML = html;
    }

    function goToPage(page) { let t=Math.ceil(filteredCompanies.length/perPage); if(page<1||page>t)return; currentPage=page; renderPage(); document.querySelector('.listings-section').scrollTo({top:0,behavior:'smooth'}); }
    function scrollTabs(dir) { document.getElementById('categoryTabs').scrollBy({left:dir*200,behavior:'smooth'}); }
    document.getElementById('searchKeywords').addEventListener('keyup', function(e) { if(e.key==='Enter') applyFilters(); });
    
    function toggleReadMore(companyId) {
        const desc = document.getElementById(`desc-${companyId}`);
        const btn = event.target;
        if (desc.classList.contains('expanded')) {
            desc.classList.remove('expanded');
            btn.textContent = 'Read More';
        } else {
            desc.classList.add('expanded');
            btn.textContent = 'Read Less';
        }
    }
    
    function showContactForm(companyId, companyName) {
        const phoneNumber = prompt(`Please enter your phone number to contact ${companyName}:`);
        if (phoneNumber && phoneNumber.length > 5) {
            alert(`Thank you! ${companyName} will contact you at ${phoneNumber} within 24 hours.`);
        } else if (phoneNumber) {
            alert('Please enter a valid phone number.');
        }
    }
</script>

</body>
</html>