<?php
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
if (empty($slug)) { header('Location: provinces.php'); exit; }

$pageTitle = "Province Details - industry.co.zw";
require_once __DIR__ . '/includes/head.php';

// Get province display name
function getProvinceName($slug) {
    $provinces = [
        'harare' => 'Harare',
        'bulawayo' => 'Bulawayo',
        'manicaland' => 'Manicaland',
        'mashonaland-central' => 'Mashonaland Central',
        'mashonaland-east' => 'Mashonaland East',
        'mashonaland-west' => 'Mashonaland West',
        'masvingo' => 'Masvingo',
        'matabeleland-north' => 'Matabeleland North',
        'matabeleland-south' => 'Matabeleland South',
        'midlands' => 'Midlands'
    ];
    return $provinces[$slug] ?? ucfirst(str_replace('-', ' ', $slug));
}

$provinceName = getProvinceName($slug);
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

    /* Section Headers */
    .section-header {
        margin-bottom: 30px;
        text-align: center;
    }
    .section-header h2 {
        font-size: 28px;
        font-weight: 700;
        color: #1a2c3e;
        margin-bottom: 10px;
    }
    .section-header p {
        color: #666;
        font-size: 14px;
    }

    /* Info Cards */
    .info-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        height: 100%;
    }
    .info-card h3 {
        font-size: 18px;
        font-weight: 700;
        color: #2e7d32;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e8f5e9;
    }
    .info-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .info-card ul li {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .info-card ul li i {
        color: #2e7d32;
        font-size: 14px;
    }
    .info-card ul li:last-child {
        border-bottom: none;
    }
    .fact-item {
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f0f0;
    }
    .fact-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .fact-item strong {
        display: block;
        font-size: 12px;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .fact-item span {
        font-size: 15px;
        font-weight: 600;
        color: #1a2c3e;
    }

    /* Service Items */
    .service-item {
        background: #fff;
        border-radius: 12px;
        padding: 25px 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
    }
    .service-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .service-item .icon {
        font-size: 40px;
        color: #2e7d32;
        margin-bottom: 15px;
    }
    .service-item h4 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .service-item h4 a {
        color: #1a2c3e;
        text-decoration: none;
    }
    .service-item p {
        font-size: 13px;
        color: #666;
        line-height: 1.5;
    }

    /* Company Card */
    .company-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
        text-align: center;
    }
    .company-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .company-logo {
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }
    .company-logo img {
        max-height: 50px;
        max-width: 100%;
        object-fit: contain;
    }
    .company-logo .default-icon {
        font-size: 40px;
        color: #ccc;
    }
    .company-name {
        font-size: 16px;
        font-weight: 700;
        color: #1a2c3e;
        margin-bottom: 8px;
    }
    .company-industry {
        display: inline-block;
        background: #e8f5e9;
        color: #2e7d32;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 10px;
        font-weight: 600;
        margin-bottom: 10px;
    }
    .company-stakeholder {
        display: inline-block;
        background: #fff3e0;
        color: #e65100;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 9px;
        font-weight: 600;
        margin-left: 5px;
    }
    .company-contact {
        font-size: 11px;
        color: #666;
        margin-top: 10px;
    }
    .company-contact i {
        color: #2e7d32;
        margin-right: 4px;
    }

    /* Filter Bar */
    .filter-bar {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    .filter-bar .form-select, .filter-bar .form-control {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 8px 12px;
        font-size: 13px;
    }
    .filter-bar .form-select:focus, .filter-bar .form-control:focus {
        border-color: #2e7d32;
        box-shadow: 0 0 0 2px rgba(46,125,50,0.1);
    }

    /* Stats and Pagination */
    .stats-row {
        background: #fff;
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .stats-row .total {
        font-size: 14px;
        color: #555;
    }
    .stats-row .total strong {
        color: #2e7d32;
        font-size: 18px;
    }

    .pagination-container {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }
    .pagination {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .page-btn {
        min-width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        color: #555;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        padding: 0 12px;
    }
    .page-btn:hover {
        border-color: #2e7d32;
        color: #2e7d32;
    }
    .page-btn.active {
        background: #2e7d32;
        color: #fff;
        border-color: #2e7d32;
    }
    .page-btn.disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #1a5e2a, #0d3b1a);
        padding: 50px 0;
        text-align: center;
        border-radius: 20px;
        margin-top: 20px;
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
    }

    @media (max-width: 768px) {
        body.index-page { padding-top: 70px; }
        .page-title { padding: 30px 0 20px; }
        .page-title h1 { font-size: 24px; }
        .section-header h2 { font-size: 22px; }
        .cta-section { margin-top: 30px; padding: 35px 20px; }
        .cta-section h3 { font-size: 20px; }
        .stats-row { flex-direction: column; align-items: flex-start; }
        .page-btn { min-width: 35px; height: 35px; font-size: 12px; }
    }
</style>
</head>

<body class="index-page">

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<main class="main">

    <!-- BREADCRUMB NAVIGATION -->
    <div class="breadcrumb-wrapper">
        <ul class="breadcrumb">
            <li><a href="index.php"><i class="bi bi-house-door"></i> Home</a></li>
            <li><a href="provinces.php">Provinces</a></li>
            <li><span class="current"><?php echo $provinceName; ?></span></li>
        </ul>
    </div>

    <!-- PAGE TITLE -->
    <section class="page-title">
        <div class="container">
            <h1><?php echo $provinceName; ?> Province</h1>
            <p>Explore businesses, industries, and investment opportunities in <?php echo $provinceName; ?></p>
        </div>
    </section>

    <section style="padding: 40px 0 60px; background: #f5f5f5;">
        <div class="container">

            <!-- Province Overview Section -->
            <div class="row g-4 mb-5">
                <div class="col-lg-7">
                    <div class="info-card">
                        <h3><i class="bi bi-info-circle"></i> About <?php echo $provinceName; ?></h3>
                        <p id="aboutText" style="line-height: 1.6; color: #555;">Loading province information...</p>
                        <div id="keyHighlights" style="margin-top: 15px;"></div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="info-card">
                        <h3><i class="bi bi-graph-up"></i> Key Economic Facts</h3>
                        <div id="keyFacts">
                            <p class="text-muted">Loading data...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Industries Section -->
            <div class="section-header">
                <h2>Key Industries</h2>
                <p>Major industrial sectors active in <?php echo $provinceName; ?></p>
            </div>
            <div class="row g-4 mb-5" id="industriesList">
                <div class="col-12 text-center py-4">
                    <div class="spinner-border text-success"></div>
                </div>
            </div>

            <!-- Investment Opportunities Section -->
            <div class="section-header">
                <h2>Investment Opportunities</h2>
                <p>Growth areas and investment potential in <?php echo $provinceName; ?></p>
            </div>
            <div class="row g-4 mb-5" id="opportunitiesList">
                <div class="col-12 text-center py-4">
                    <div class="spinner-border text-success"></div>
                </div>
            </div>

            <!-- Infrastructure Section -->
            <div class="section-header">
                <h2>Infrastructure & Development</h2>
                <p>Key infrastructure, industrial parks, and development zones</p>
            </div>
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="service-item">
                        <div class="icon"><i class="bi bi-truck"></i></div>
                        <h4>Transport Links</h4>
                        <p id="transportInfo">Loading...</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-item">
                        <div class="icon"><i class="bi bi-building"></i></div>
                        <h4>Industrial Parks</h4>
                        <p id="industrialParks">Loading...</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-item">
                        <div class="icon"><i class="bi bi-lightning-charge"></i></div>
                        <h4>Power & Utilities</h4>
                        <p id="utilitiesInfo">Loading...</p>
                    </div>
                </div>
            </div>

            <!-- Companies Section -->
            <div class="section-header">
                <h2>Companies in <?php echo $provinceName; ?></h2>
                <p>Registered businesses operating in this region</p>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select" id="industryFilter">
                            <option value="">All Industries</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search companies...">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-success w-100" id="resetBtn" style="border-color:#2e7d32; color:#2e7d32;">Reset</button>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-success w-100" id="viewAllBtn" style="background:#2e7d32; border:none;">View All →</button>
                    </div>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="stats-row">
                <div class="total"><strong id="companyCount">0</strong> companies found</div>
                <div class="total" id="stakeholderInfo"></div>
            </div>

            <!-- Companies Grid (Limited to 6 items initially) -->
            <div class="row g-4" id="companiesContainer">
                <div class="col-12 text-center py-4">
                    <div class="spinner-border text-success"></div>
                    <p class="mt-2 text-muted">Loading companies...</p>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination-container" id="paginationContainer" style="display: none;">
                <div class="pagination" id="pagination"></div>
            </div>

            <!-- Show All Button (for non-paginated view) -->
            <div class="text-center mt-4" id="showAllContainer" style="display: none;">
                <button class="btn btn-success" id="showAllCompaniesBtn" style="background:#2e7d32; border:none; padding:12px 30px; border-radius:40px;">
                    <i class="bi bi-eye"></i> Show All Companies
                </button>
            </div>

        </div>
    </section>

    <!-- CALL TO ACTION SECTION -->
    <div class="container" style="margin-bottom: -30px;">
        <div class="cta-section">
            <h3>Invest in <?php echo $provinceName; ?></h3>
            <p>Join the growing business community and take advantage of the opportunities available in this province.</p>
            <a href="contact.php" class="cta-btn">Get Started →</a>
        </div>
    </div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    const slug = '<?php echo $slug; ?>';
    const API = '/industry.co.zw/api/public';
    let allCompanies = [];
    let filteredCompanies = [];
    let currentPage = 1;
    let itemsPerPage = 6;
    let showAllMode = false;

    // Province data
    const provinceData = {
        'harare': {
            about: "Harare is Zimbabwe's capital and largest city, serving as the country's financial, commercial, and industrial hub. The province attracts significant investment and hosts major corporate headquarters, financial institutions, and industrial zones.",
            keyHighlights: [
                "Zimbabwe's capital and largest city",
                "Financial & commercial hub of Zimbabwe",
                "Well-developed industrial and commercial infrastructure",
                "Hosts Zimbabwe Stock Exchange and Reserve Bank"
            ],
            keyFacts: [
                { label: "Investment Hub", value: "Leading investment destination" },
                { label: "Key Sectors", value: "Finance, Manufacturing, ICT, Real Estate" },
                { label: "Industrial Areas", value: "Msasa, Graniteside, Southerton, Willowvale" },
                { label: "Airport", value: "Robert Gabriel Mugabe International" }
            ],
            industries: [
                { name: "Banking & Finance", desc: "Headquarters of major banks and financial institutions", slug: "banking-finance" },
                { name: "Manufacturing", desc: "Food processing, textiles, chemicals, and consumer goods", slug: "manufacturing" },
                { name: "Technology & ICT", desc: "Growing tech hub with software companies and innovation spaces", slug: "technology-ict" },
                { name: "Construction", desc: "Major infrastructure and real estate development", slug: "construction" }
            ],
            opportunities: [
                { title: "Commercial Real Estate", desc: "Growing demand for office space, shopping malls, and mixed-use developments" },
                { title: "Financial Technology", desc: "Opportunities in mobile banking, payment solutions, and insurance technology" },
                { title: "Light Manufacturing", desc: "Import substitution in packaging, plastics, and consumer goods" },
                { title: "ICT Services", desc: "Software development, data centers, and business process outsourcing" }
            ],
            transportInfo: "Well-connected by road to all major cities. International airport offers direct flights to regional and international destinations.",
            industrialParks: "Msasa Industrial Area, Graniteside, Southerton, Willowvale, and Sunway City technology park",
            utilitiesInfo: "Reliable electricity supply with backup systems. Municipal water and fiber optic connectivity across commercial areas."
        },
        'bulawayo': {
            about: "Bulawayo is Zimbabwe's second-largest city and traditional industrial heartland. Known for its strong manufacturing base, educational institutions, and cultural heritage.",
            keyHighlights: [
                "Zimbabwe's traditional industrial heartland",
                "Hosts Zimbabwe International Trade Fair annually",
                "Strong manufacturing and engineering base",
                "Excellent rail links to South Africa and Botswana"
            ],
            keyFacts: [
                { label: "Industrial Hub", value: "Major manufacturing center" },
                { label: "Key Sectors", value: "Manufacturing, Education, Tourism" },
                { label: "Trade Show", value: "ZITF (Annual International Fair)" },
                { label: "University", value: "NUST & Bulawayo Polytechnic" }
            ],
            industries: [
                { name: "Manufacturing", desc: "Textiles, food processing, steel fabrication", slug: "manufacturing" },
                { name: "Education", desc: "Major universities and technical colleges", slug: "education" },
                { name: "Tourism", desc: "Cultural tourism and Matobo Hills", slug: "tourism-hospitality" },
                { name: "Transport & Logistics", desc: "Cross-border trade hub", slug: "transport-logistics" }
            ],
            opportunities: [
                { title: "Manufacturing Revival", desc: "Reactivate dormant factories for domestic and export markets" },
                { title: "Cultural Tourism", desc: "Develop tourism around Matobo Hills and heritage sites" },
                { title: "Education Services", desc: "Student accommodation and services for university population" },
                { title: "Cross-Border Trade", desc: "Logistics and warehousing for regional trade" }
            ],
            transportInfo: "Major railway hub connecting to South Africa, Botswana, and Zambia. Good road connections to all major cities.",
            industrialParks: "Belmont, Kelvin, and Donnington Industrial Areas with existing factory shells available",
            utilitiesInfo: "Reliable municipal services. Industrial areas have three-phase power. Water from nearby dams."
        },
        'midlands': {
            about: "Midlands Province is Zimbabwe's mining and manufacturing powerhouse, with significant platinum, gold, and chrome operations. The province includes Gweru (capital), Kwekwe, and Zvishavane.",
            keyHighlights: [
                "Major mining operations (platinum, gold, chrome)",
                "Strong manufacturing base in Gweru and Kwekwe",
                "Central geographic location",
                "Excellent road and rail links"
            ],
            keyFacts: [
                { label: "Mining Hub", value: "Platinum, Gold, Chrome" },
                { label: "Key Sectors", value: "Mining, Manufacturing, Agriculture" },
                { label: "Major Towns", value: "Gweru, Kwekwe, Zvishavane" },
                { label: "University", value: "Midlands State University" }
            ],
            industries: [
                { name: "Mining", desc: "Platinum, gold, and chrome extraction", slug: "mining" },
                { name: "Manufacturing", desc: "Ferrochrome smelting and engineering", slug: "manufacturing" },
                { name: "Agriculture", desc: "Commercial farming and agro-processing", slug: "agriculture" },
                { name: "Education", desc: "Midlands State University", slug: "education" }
            ],
            opportunities: [
                { title: "Mineral Beneficiation", desc: "Chrome smelting and platinum processing facilities" },
                { title: "Mining Services", desc: "Equipment supply, drilling, and mine support services" },
                { title: "Steel & Engineering", desc: "Revival of steelworks and downstream industries" },
                { title: "Agro-Processing", desc: "Food processing and cold storage facilities" }
            ],
            transportInfo: "Centrally located on Harare-Bulawayo highway and railway. Good connections to all mining areas.",
            industrialParks: "Gweru Industrial Area, Kwekwe Industrial Area, and mining complexes",
            utilitiesInfo: "Industrial power supply available. Water from Gwenoro and Sebakwe Dams."
        }
    };

    const defaultData = {
        about: "This province offers a range of opportunities for investors and businesses across multiple sectors. The provincial administration supports business development and investment facilitation.",
        keyHighlights: ["Growing provincial economy", "Diverse industrial base", "Investment incentives available", "Strategic location"],
        keyFacts: [
            { label: "Sector Status", value: "Active & Growing" },
            { label: "Investment Climate", value: "Welcoming" },
            { label: "Key Advantage", value: "Strategic Location" },
            { label: "Workforce", value: "Available & Skilled" }
        ],
        industries: [
            { name: "Agriculture", desc: "Farming and agro-processing", slug: "agriculture" },
            { name: "Mining", desc: "Mineral extraction and processing", slug: "mining" },
            { name: "Manufacturing", desc: "Local production and value addition", slug: "manufacturing" }
        ],
        opportunities: [
            { title: "Business Development", desc: "Start or expand your business in this growing province" },
            { title: "Infrastructure Projects", desc: "Construction, roads, water, and energy infrastructure" },
            { title: "Value Addition", desc: "Processing using locally available resources" },
            { title: "Services Sector", desc: "Financial, educational, and professional services" }
        ],
        transportInfo: "Connected by road to major cities and towns. Freight services available.",
        industrialParks: "Designated industrial areas available for manufacturing businesses.",
        utilitiesInfo: "Electricity and water supply available. Telecommunications coverage."
    };

    const data = provinceData[slug] || defaultData;

    // Render province data
    document.getElementById('aboutText').textContent = data.about;
    
    document.getElementById('keyHighlights').innerHTML = '<ul style="list-style:none; padding:0;">' + 
        data.keyHighlights.map(h => `<li style="padding:5px 0;"><i class="bi bi-check2-circle" style="color:#2e7d32;"></i> ${h}</li>`).join('') + '</ul>';
    
    document.getElementById('keyFacts').innerHTML = data.keyFacts.map(f => 
        `<div class="fact-item"><strong>${f.label}</strong><span>${f.value}</span></div>`
    ).join('');
    
    document.getElementById('industriesList').innerHTML = data.industries.map((ind, i) => `
        <div class="col-lg-3 col-md-6">
            <div class="service-item">
                <div class="icon"><i class="bi bi-building"></i></div>
                <h4><a href="industry.php?slug=${ind.slug}">${ind.name}</a></h4>
                <p>${ind.desc}</p>
            </div>
        </div>
    `).join('');
    
    document.getElementById('opportunitiesList').innerHTML = data.opportunities.map((opp, i) => `
        <div class="col-lg-6">
            <div class="service-item" style="text-align:left;">
                <div class="icon"><i class="bi bi-lightbulb"></i></div>
                <h4>${opp.title}</h4>
                <p>${opp.desc}</p>
            </div>
        </div>
    `).join('');
    
    document.getElementById('transportInfo').textContent = data.transportInfo;
    document.getElementById('industrialParks').textContent = data.industrialParks;
    document.getElementById('utilitiesInfo').textContent = data.utilitiesInfo;

    // Load companies
    fetch(API + '/companies.php?province=' + slug)
        .then(r => r.json())
        .then(d => {
            if (d.status === 'success') {
                allCompanies = d.data || [];
                filteredCompanies = [...allCompanies];
                displayCompaniesWithPagination();
                
                const industries = [...new Set(allCompanies.map(c => c.industry_name).filter(Boolean))];
                const select = document.getElementById('industryFilter');
                industries.forEach(ind => {
                    const option = document.createElement('option');
                    option.value = ind;
                    option.textContent = ind;
                    select.appendChild(option);
                });
            }
        });

    function displayCompaniesWithPagination() {
        const totalItems = filteredCompanies.length;
        document.getElementById('companyCount').textContent = totalItems;
        
        if (totalItems === 0) {
            document.getElementById('companiesContainer').innerHTML = '<div class="col-12 text-center py-5"><i class="bi bi-building" style="font-size:48px;color:#ccc;"></i><h4 class="mt-3">No companies found</h4><p class="text-muted">Be the first to list your business in this province.</p></div>';
            document.getElementById('paginationContainer').style.display = 'none';
            document.getElementById('showAllContainer').style.display = 'none';
            return;
        }
        
        // Show/Hide buttons based on total count
        if (totalItems > itemsPerPage && !showAllMode) {
            document.getElementById('showAllContainer').style.display = 'block';
            document.getElementById('paginationContainer').style.display = 'none';
        } else if (showAllMode) {
            document.getElementById('showAllContainer').style.display = 'none';
            document.getElementById('paginationContainer').style.display = 'none';
            displayAllCompanies();
            return;
        } else {
            document.getElementById('showAllContainer').style.display = 'none';
            document.getElementById('paginationContainer').style.display = 'none';
        }
        
        // Paginate
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const pageItems = filteredCompanies.slice(start, end);
        
        displayCompanies(pageItems);
        
        // Show pagination if needed
        if (totalItems > itemsPerPage && !showAllMode) {
            document.getElementById('paginationContainer').style.display = 'flex';
            renderPagination(totalItems);
        }
    }
    
    function displayAllCompanies() {
        displayCompanies(filteredCompanies);
    }
    
    function displayCompanies(companies) {
        const container = document.getElementById('companiesContainer');
        
        if (companies.length === 0) {
            container.innerHTML = '<div class="col-12 text-center py-5"><i class="bi bi-building" style="font-size:48px;color:#ccc;"></i><h4 class="mt-3">No companies found</h4></div>';
            return;
        }
        
        container.innerHTML = companies.map(c => `
            <div class="col-lg-4 col-md-6">
                <div class="company-card">
                    <div class="company-logo">
                        ${c.logo ? `<img src="/industry.co.zw/${c.logo}" alt="${escapeHtml(c.name)}">` : `<div class="default-icon"><i class="bi bi-building"></i></div>`}
                    </div>
                    <div class="company-name">${escapeHtml(c.name)}</div>
                    <div>
                        <span class="company-industry">${c.industry_name || 'General'}</span>
                        ${c.stakeholder ? `<span class="company-stakeholder">${c.stakeholder}</span>` : ''}
                    </div>
                    <div class="company-contact">
                        ${c.phone ? `<div><i class="bi bi-telephone"></i> ${c.phone}</div>` : ''}
                        ${c.email ? `<div><i class="bi bi-envelope"></i> ${c.email.substring(0, 30)}${c.email.length > 30 ? '...' : ''}</div>` : ''}
                        ${c.website ? `<div><i class="bi bi-globe"></i> ${c.website.substring(0, 25)}${c.website.length > 25 ? '...' : ''}</div>` : ''}
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    function renderPagination(totalItems) {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const pagination = document.getElementById('pagination');
        let html = '';
        
        // Previous button
        html += `<button class="page-btn ${currentPage === 1 ? 'disabled' : ''}" onclick="changePage(${currentPage - 1})">«</button>`;
        
        // Page numbers
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, currentPage + 2);
        
        if (startPage > 1) {
            html += `<button class="page-btn" onclick="changePage(1)">1</button>`;
            if (startPage > 2) html += `<span class="page-btn disabled">...</span>`;
        }
        
        for (let i = startPage; i <= endPage; i++) {
            html += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">${i}</button>`;
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) html += `<span class="page-btn disabled">...</span>`;
            html += `<button class="page-btn" onclick="changePage(${totalPages})">${totalPages}</button>`;
        }
        
        // Next button
        html += `<button class="page-btn ${currentPage === totalPages ? 'disabled' : ''}" onclick="changePage(${currentPage + 1})">»</button>`;
        
        pagination.innerHTML = html;
    }
    
    function changePage(page) {
        if (page < 1 || page > Math.ceil(filteredCompanies.length / itemsPerPage)) return;
        currentPage = page;
        displayCompaniesWithPagination();
        window.scrollTo({ top: document.getElementById('companies').offsetTop - 100, behavior: 'smooth' });
    }
    
    function showAllCompanies() {
        showAllMode = true;
        document.getElementById('showAllContainer').style.display = 'none';
        document.getElementById('paginationContainer').style.display = 'none';
        displayCompanies(filteredCompanies);
    }
    
    function filterCompanies() {
        const industry = document.getElementById('industryFilter').value;
        const search = document.getElementById('searchInput').value.toLowerCase();
        filteredCompanies = allCompanies.filter(c => {
            if (industry && c.industry_name !== industry) return false;
            if (search && !c.name.toLowerCase().includes(search)) return false;
            return true;
        });
        currentPage = 1;
        showAllMode = false;
        displayCompaniesWithPagination();
    }
    
    function resetFilters() {
        document.getElementById('industryFilter').value = '';
        document.getElementById('searchInput').value = '';
        filteredCompanies = [...allCompanies];
        currentPage = 1;
        showAllMode = false;
        displayCompaniesWithPagination();
    }
    
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }
    
    document.getElementById('industryFilter').addEventListener('change', filterCompanies);
    document.getElementById('searchInput').addEventListener('keyup', filterCompanies);
    document.getElementById('resetBtn').addEventListener('click', resetFilters);
    document.getElementById('showAllCompaniesBtn').addEventListener('click', showAllCompanies);
    document.getElementById('viewAllBtn').addEventListener('click', showAllCompanies);
</script>

</body>
</html>