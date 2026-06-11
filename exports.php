<?php
$pageTitle = "Exports - industry.co.zw";
$pageDescription = "Browse Zimbabwean export products, connect with verified exporters, and request quotes";
require_once __DIR__ . '/includes/head.php';
?>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    /* Push below fixed header */
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

    /* Category Bar */
    .category-bar {
        background: #fff; border-bottom: 1px solid #e0e0e0; padding: 0;
        position: sticky; top: 85px; z-index: 99; box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .category-bar-inner {
        display: flex; align-items: center; max-width: 100%; overflow: hidden; position: relative;
    }
    .category-bar-title {
        font-size: 15px; font-weight: 700; color: #2e7d32; padding: 14px 18px;
        white-space: nowrap; min-width: 200px;
    }
    .category-tabs-wrapper { display: flex; align-items: center; flex: 1; overflow: hidden; }
    .category-tabs {
        display: flex; align-items: center; overflow-x: auto; scroll-behavior: smooth;
        scrollbar-width: none; -ms-overflow-style: none; padding: 0 8px; gap: 0;
    }
    .category-tabs::-webkit-scrollbar { display: none; }
    .category-tab {
        display: flex; align-items: center; gap: 6px; padding: 14px 16px;
        font-size: 13px; font-weight: 500; color: #555; cursor: pointer;
        white-space: nowrap; border-bottom: 3px solid transparent;
        transition: all 0.2s; text-decoration: none;
    }
    .category-tab:hover { color: #2e7d32; }
    .category-tab.active { color: #2e7d32; border-bottom: 3px solid #2e7d32; font-weight: 700; }
    .category-tab i { font-size: 18px; }
    .scroll-arrow {
        width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: #999; background: #fff; border: none; font-size: 18px;
        flex-shrink: 0; z-index: 2;
    }
    .scroll-arrow:hover { color: #2e7d32; }

    /* Main Layout */
    .main-layout { display: flex; max-width: 100%; min-height: calc(100vh - 160px); }

    /* Sidebar */
    .sidebar {
        width: 300px; min-width: 300px; background: #fff; border-right: 1px solid #e0e0e0;
        padding: 0; overflow-y: auto; max-height: calc(100vh - 160px);
        position: sticky; top: 145px;
    }
    .sidebar-tabs { display: flex; border-bottom: 1px solid #e0e0e0; padding: 10px 18px 0; gap: 18px; }
    .sidebar-tab {
        display: flex; align-items: center; gap: 5px; padding: 10px 0; font-size: 13px;
        font-weight: 500; color: #666; cursor: pointer; border-bottom: 2px solid transparent;
    }
    .sidebar-tab.active { color: #2e7d32; border-bottom: 2px solid #2e7d32; }
    .sidebar-content { padding: 18px; }

    .filter-group { margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid #eee; }
    .filter-group:last-child { border-bottom: none; }
    .filter-label { font-size: 13px; font-weight: 500; color: #333; margin-bottom: 8px; }
    .filter-input {
        width: 100%; padding: 8px 0; border: none; border-bottom: 1px solid #ddd;
        font-size: 13px; outline: none; background: transparent;
    }
    .filter-input:focus { border-bottom-color: #2e7d32; }
    .filter-select {
        width: 100%; padding: 8px 25px 8px 0; border: none; border-bottom: 1px solid #ddd;
        font-size: 13px; outline: none; background: transparent; appearance: none; cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24'%3E%3Cpath fill='%23999' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right center;
    }
    .form-check { margin-bottom: 6px; }
    .form-check-label { font-size: 13px; color: #555; cursor: pointer; }
    .form-check-input:checked { background-color: #2e7d32; border-color: #2e7d32; }

    .search-btn {
        width: 100%; padding: 12px; background: #2e7d32; color: #fff; border: none;
        border-radius: 4px; font-size: 14px; font-weight: 500; cursor: pointer; margin-top: 8px;
    }
    .search-btn:hover { background: #1b5e20; }
    .reset-btn {
        width: 100%; padding: 10px; background: transparent; color: #666; border: none;
        font-size: 13px; cursor: pointer; margin-top: 4px;
    }
    .reset-btn:hover { color: #2e7d32; }

    /* Content Area */
    .content-area { flex: 1; padding: 0; overflow-y: auto; }
    .content-header {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;
        padding: 12px 20px; background: #fff; border-bottom: 1px solid #e0e0e0; gap: 8px;
    }
    .results-count { font-size: 13px; color: #555; }
    .results-count strong { color: #2e7d32; }
    .content-header-right { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }

    /* Products Grid */
    .listings-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; padding: 20px;
    }
    
    .product-card {
        background: #fff; border-radius: 8px; overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06); transition: all 0.2s;
        display: flex; flex-direction: column; position: relative;
    }
    .product-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.12); transform: translateY(-2px); }
    
    .product-card .verified-badge {
        position: absolute; top: 10px; right: 10px; background: #2e7d32; color: #fff;
        padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 600; z-index: 2;
    }
    
    .product-card-image { position: relative; height: 180px; overflow: hidden; background: #f9f9f9; }
    .product-card-image img { width: 100%; height: 100%; object-fit: contain; padding: 10px; }
    .product-card-image .default-icon { 
        font-size: 56px; color: #2e7d32; opacity: 0.3; 
        display: flex; align-items: center; justify-content: center; height: 100%;
    }
    
    .product-card-body { padding: 16px; flex: 1; display: flex; flex-direction: column; }
    .category-badge {
        display: inline-block; font-size: 10px; padding: 3px 10px; border-radius: 15px;
        background: #E8F5E9; color: #2e7d32; font-weight: 600; margin-bottom: 8px;
    }
    .product-name { font-size: 15px; font-weight: 700; color: #1a1a1a; margin-bottom: 4px; }
    .product-specs { font-size: 12px; color: #777; margin-bottom: 4px; }
    .price { font-size: 20px; font-weight: 700; color: #2e7d32; margin-bottom: 2px; }
    .price small { font-size: 11px; color: #999; font-weight: 400; }
    .moq { font-size: 11px; color: #C62828; font-weight: 600; margin-bottom: 4px; }
    .company-name { font-size: 12px; color: #555; margin-bottom: 4px; }
    .rating { margin-bottom: 4px; display: flex; align-items: center; gap: 4px; }
    .rating .stars { color: #FFD700; font-size: 13px; }
    .rating .count { font-size: 11px; color: #999; }
    .exports-to { font-size: 11px; color: #999; margin-bottom: 4px; }
    .certifications { margin-bottom: 8px; display: flex; gap: 4px; flex-wrap: wrap; }
    .cert-badge {
        font-size: 9px; padding: 3px 8px; border-radius: 3px; background: #FFF8E1;
        color: #F57F17; font-weight: 600; border: 1px solid #FFE082;
    }
    
    .btn-get-price {
        display: block; width: 100%; padding: 10px; background: #FFD700; color: #1a1a1a;
        border: none; border-radius: 6px; font-weight: 700; font-size: 13px; cursor: pointer;
        transition: all 0.3s; margin-top: auto;
    }
    .btn-get-price:hover { background: #FFC107; }

    /* RFQ Modal */
    .rfq-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; justify-content: center; align-items: center; }
    .rfq-modal.show { display: flex; }
    .rfq-modal-content { background: #fff; border-radius: 12px; padding: 30px; width: 92%; max-width: 500px; position: relative; animation: slideUp 0.3s ease; }
    @keyframes slideUp { from { transform: translateY(60px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .rfq-modal-content .close-modal { position: absolute; top: 12px; right: 18px; font-size: 24px; cursor: pointer; color: #999; }

    /* Trade Info */
    .trade-section { background: #fff; padding: 40px 20px; }
    .trade-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; max-width: 1000px; margin: 0 auto; }
    .trade-card { text-align: center; padding: 25px 20px; border-radius: 8px; border: 1px solid #e0e0e0; }
    .trade-card .icon { font-size: 36px; color: #2e7d32; margin-bottom: 12px; }
    .trade-card h4 { font-weight: 700; margin-bottom: 8px; font-size: 16px; }
    .trade-card p { color: #666; font-size: 13px; }

    /* CTA */
    .cta-section {
        background: linear-gradient(135deg, #1a5e2a, #0d3b1a); color: #fff;
        text-align: center; padding: 40px 20px;
    }
    .cta-section h3 { color: #fff; font-size: 22px; margin-bottom: 8px; }
    .cta-section p { opacity: 0.85; font-size: 14px; margin-bottom: 15px; }
    .cta-btn {
        display: inline-block; background: #FFD700; color: #000; padding: 10px 24px;
        border-radius: 40px; font-weight: 700; text-decoration: none; font-size: 14px;
        transition: all 0.3s;
    }
    .cta-btn:hover { background: #FFC107; transform: translateY(-2px); }

    @media (max-width: 900px) {
        .sidebar { width: 260px; min-width: 260px; }
        .listings-grid { grid-template-columns: 1fr; }
        .trade-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 700px) {
        .main-layout { flex-direction: column; }
        .sidebar { width: 100%; min-width: 100%; max-height: none; position: relative; top: 0; }
        body.index-page { padding-top: 70px; }
        .category-bar { top: 70px; }
        .sidebar { top: 120px; }
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
            <li><span class="current">Exports</span></li>
        </ul>
    </div>

    <!-- Category Bar -->
    <div class="category-bar">
      <div class="category-bar-inner">
        <div class="category-bar-title">Export Products</div>
        <div class="category-tabs-wrapper">
          <button class="scroll-arrow left" onclick="scrollTabs(-1)"><i class="bi bi-chevron-left"></i></button>
          <div class="category-tabs" id="categoryTabs">
            <a href="?category=Agriculture" class="category-tab"><i class="bi bi-flower1"></i> Agriculture</a>
            <a href="?category=Minerals" class="category-tab"><i class="bi bi-gem"></i> Minerals</a>
            <a href="?category=Manufacturing" class="category-tab"><i class="bi bi-gear"></i> Manufacturing</a>
            <a href="?category=Textiles" class="category-tab"><i class="bi bi-basket"></i> Textiles</a>
            <a href="?category=Horticulture" class="category-tab"><i class="bi bi-flower2"></i> Horticulture</a>
          </div>
          <button class="scroll-arrow right" onclick="scrollTabs(1)"><i class="bi bi-chevron-right"></i></button>
        </div>
      </div>
    </div>

    <!-- Main Layout -->
    <div class="main-layout">
      <!-- Sidebar Filters -->
      <div class="sidebar">
        <div class="sidebar-tabs">
          <div class="sidebar-tab active"><i class="bi bi-funnel"></i> Filters</div>
        </div>
        <div class="sidebar-content">
          <div class="filter-group">
            <div class="filter-label">Product Category</div>
            <select class="filter-select" id="categoryFilter" onchange="applyFilters()">
              <option value="">All Categories</option>
            </select>
          </div>

          <div class="filter-group">
            <div class="filter-label">Export Destination</div>
            <select class="filter-select" id="destinationFilter" onchange="applyFilters()">
              <option value="">All Destinations</option>
            </select>
          </div>

          <div class="filter-group">
            <div class="filter-label">Certification</div>
            <div class="form-check"><input class="form-check-input" type="checkbox" value="iso" id="certISO" onchange="applyFilters()"><label class="form-check-label" for="certISO">ISO Certified</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" value="gst" id="certGST" onchange="applyFilters()"><label class="form-check-label" for="certGST">GST Verified</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" value="trustseal" id="certTrust" onchange="applyFilters()"><label class="form-check-label" for="certTrust">TrustSEAL Verified</label></div>
          </div>

          <div class="filter-group">
            <div class="filter-label">Price Range (USD)</div>
            <input type="range" class="form-range" min="0" max="50000" step="100" id="priceRange" onchange="updatePriceLabel()" style="width:100%;">
            <small>Up to $<span id="priceValue">50,000</span></small>
          </div>

          <button class="search-btn" onclick="applyFilters()"><i class="bi bi-search"></i> Search</button>
          <button class="reset-btn" onclick="resetFilters()"><i class="bi bi-arrow-repeat"></i> Reset Filters</button>
        </div>
      </div>

      <!-- Content Area -->
      <div class="content-area">
        <div class="content-header">
          <span class="results-count"><strong id="resultCount">0</strong> products found</span>
          <div class="content-header-right">
            <input type="text" id="searchInput" placeholder="Search products..." onkeyup="applyFilters()" style="width:180px;padding:6px 10px;border:1px solid #ddd;border-radius:4px;font-size:12px;">
            <select class="filter-select" id="sortBy" onchange="applyFilters()" style="width:160px;">
              <option value="relevance">Sort: Relevance</option>
              <option value="price-low">Price: Low to High</option>
              <option value="price-high">Price: High to Low</option>
              <option value="rating">Rating: Highest</option>
              <option value="name">Name: A-Z</option>
            </select>
          </div>
        </div>

        <div class="listings-grid" id="productsContainer">
          <div class="text-center py-5" style="grid-column:1/-1;">
            <div class="spinner-border text-success"></div>
            <p class="mt-2 text-muted">Loading products...</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Trade Information -->
    <section class="trade-section">
      <div class="container" style="text-align:center;margin-bottom:25px;">
        <h3 style="font-weight:700;">Zimbabwe Trade Information</h3>
        <p style="color:#888;">Key facts about Zimbabwe's export sector</p>
      </div>
      <div class="trade-grid">
        <div class="trade-card">
          <div class="icon"><i class="bi bi-box-seam"></i></div>
          <h4>Major Exports</h4>
          <p>Tobacco, gold, platinum, diamonds, ferrochrome, nickel, cotton, sugar, and horticultural products</p>
        </div>
        <div class="trade-card">
          <div class="icon"><i class="bi bi-globe-americas"></i></div>
          <h4>Export Markets</h4>
          <p>South Africa, China, UAE, Mozambique, Zambia, Botswana, United Kingdom, and European Union</p>
        </div>
        <div class="trade-card">
          <div class="icon"><i class="bi bi-graph-up-arrow"></i></div>
          <h4>Key Sectors</h4>
          <p>Agriculture, mining, and manufacturing form the backbone of Zimbabwe's export economy</p>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
      <h3>Are You a Zimbabwean Exporter?</h3>
      <p>Join industry.co.zw and connect with international buyers. Get verified, list your products, and grow your exports.</p>
      <a href="contact.php" class="cta-btn">Get Verified Today →</a>
    </section>

  </main>

  <!-- RFQ Modal -->
  <div class="rfq-modal" id="rfqModal">
    <div class="rfq-modal-content">
      <span class="close-modal" onclick="closeRFQ()">&times;</span>
      <h4 style="color:#2e7d32;font-weight:700;"><i class="bi bi-chat-dots"></i> Get Latest Price</h4>
      <div class="product-summary" id="rfqProductSummary" style="background:#f5f5f5;padding:12px;border-radius:8px;margin-bottom:15px;border-left:4px solid #2e7d32;"></div>
      <form id="rfqForm" onsubmit="submitRFQ(event)">
        <div class="mb-3"><label class="form-label" style="font-size:13px;">Your Name *</label><input type="text" class="form-control" id="rfqName" required placeholder="Enter your full name" style="font-size:13px;padding:8px 12px;"></div>
        <div class="mb-3"><label class="form-label" style="font-size:13px;">Email or Phone *</label><input type="text" class="form-control" id="rfqContact" required placeholder="Email or phone number" style="font-size:13px;padding:8px 12px;"></div>
        <div class="mb-3"><label class="form-label" style="font-size:13px;">Your Country</label><input type="text" class="form-control" id="rfqCountry" value="South Africa" readonly style="font-size:13px;padding:8px 12px;"></div>
        <div class="mb-3"><label class="form-label" style="font-size:13px;">Quantity Required</label><input type="number" class="form-control" id="rfqQuantity" placeholder="Enter quantity" min="1" style="font-size:13px;padding:8px 12px;"></div>
        <div class="mb-3"><label class="form-label" style="font-size:13px;">Additional Requirements</label><textarea class="form-control" id="rfqMessage" rows="2" placeholder="Specifications..." style="font-size:13px;padding:8px 12px;"></textarea></div>
        <button type="submit" class="btn w-100 py-2 fw-bold" style="background:#2e7d32;color:#fff;"><i class="bi bi-send"></i> Submit Enquiry</button>
      </form>
      <div class="alert alert-success mt-3 fw-bold" id="rfqSuccess" style="display:none;"><i class="bi bi-check-circle"></i> Enquiry sent successfully!</div>
    </div>
  </div>

  <?php require_once __DIR__ . '/includes/footer.php'; ?>

  <script>
    const API_BASE = '<?= SITE_ROOT ?>/api/public';
    let allProducts = [];
    let currentPage = 1;
    const itemsPerPage = 8;

    // Set active category tab based on URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const categoryParam = urlParams.get('category');
    if (categoryParam) {
        document.querySelectorAll('.category-tab').forEach(tab => {
            if (tab.getAttribute('href')?.includes('category=' + categoryParam)) {
                tab.classList.add('active');
            } else {
                tab.classList.remove('active');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', loadProducts);

    async function loadProducts() {
      try {
        const response = await fetch(API_BASE + '/exports.php');
        const data = await response.json();
        if (data.status === 'success' && data.data.length > 0) {
          allProducts = data.data.map(item => ({
            id: item.id,
            name: item.product_name, category: item.category || 'General',
            specs: item.specs || '', description: item.description || '',
            price: parseFloat(item.price) || 0, moq: parseInt(item.moq) || 1,
            company: item.company || 'Zimbabwe Exporter',
            rating: parseFloat(item.rating) || 0, reviews: parseInt(item.reviews) || 0,
            exportsTo: Array.isArray(item.exports_to) ? item.exports_to : (item.exports_to ? item.exports_to.split(',') : []),
            certifications: Array.isArray(item.certifications) ? item.certifications : (item.certifications ? item.certifications.split(',') : []),
            verified: item.verified == 1 || item.verified === true, image: item.image || null
          }));
        }
        populateFilters();
        
        // Apply category filter from URL if present
        if (categoryParam && categoryParam !== 'All') {
            const catSelect = document.getElementById('categoryFilter');
            if (catSelect) {
                for(let i = 0; i < catSelect.options.length; i++) {
                    if (catSelect.options[i].value === categoryParam) {
                        catSelect.selectedIndex = i;
                        break;
                    }
                }
            }
        }
        
        displayProducts();
      } catch (error) {
        document.getElementById('productsContainer').innerHTML = '<div class="text-center py-5" style="grid-column:1/-1;"><h4>Could not load products</h4></div>';
      }
    }

    function populateFilters() {
      let categories = [...new Set(allProducts.map(p => p.category))];
      let catSelect = document.getElementById('categoryFilter');
      catSelect.innerHTML = '<option value="">All Categories</option>';
      categories.sort().forEach(cat => { catSelect.innerHTML += `<option value="${cat}">${cat}</option>`; });

      let destinations = [...new Set(allProducts.flatMap(p => p.exportsTo))];
      let destSelect = document.getElementById('destinationFilter');
      destSelect.innerHTML = '<option value="">All Destinations</option>';
      destinations.sort().forEach(dest => { if (dest.trim()) destSelect.innerHTML += `<option value="${dest.trim()}">${dest.trim()}</option>`; });
    }

    function getFilteredProducts() {
      let category = document.getElementById('categoryFilter').value;
      let destination = document.getElementById('destinationFilter').value;
      let search = document.getElementById('searchInput').value.toLowerCase();
      let priceMax = parseInt(document.getElementById('priceRange').value);
      let certISO = document.getElementById('certISO').checked;
      let certGST = document.getElementById('certGST').checked;
      let certTrust = document.getElementById('certTrust').checked;

      return allProducts.filter(p => {
        if (category && p.category !== category) return false;
        if (destination && !p.exportsTo.some(d => d.trim() === destination)) return false;
        if (search && !p.name.toLowerCase().includes(search) && !p.company.toLowerCase().includes(search) && !p.specs.toLowerCase().includes(search) && !p.category.toLowerCase().includes(search)) return false;
        if (p.price > priceMax) return false;
        if (certISO && !p.certifications.some(c => c.toLowerCase() === 'iso')) return false;
        if (certGST && !p.certifications.some(c => c.toLowerCase() === 'gst')) return false;
        if (certTrust && !p.certifications.some(c => c.toLowerCase() === 'trustseal')) return false;
        return true;
      });
    }

    function sortProducts(products) {
      let sortBy = document.getElementById('sortBy').value;
      let sorted = [...products];
      switch(sortBy) {
        case 'price-low': return sorted.sort((a, b) => a.price - b.price);
        case 'price-high': return sorted.sort((a, b) => b.price - a.price);
        case 'rating': return sorted.sort((a, b) => b.rating - a.rating);
        case 'name': return sorted.sort((a, b) => a.name.localeCompare(b.name));
        default: return sorted;
      }
    }

    function paginate(products) {
      let start = (currentPage - 1) * itemsPerPage;
      return products.slice(start, start + itemsPerPage);
    }

    function displayProducts() {
      let filtered = getFilteredProducts();
      let sorted = sortProducts(filtered);
      let paginated = paginate(sorted);
      document.getElementById('resultCount').textContent = filtered.length;
      renderProducts(paginated);
    }

    function renderProducts(products) {
      let container = document.getElementById('productsContainer');
      if (products.length === 0) {
        container.innerHTML = '<div class="text-center py-5" style="grid-column:1/-1;"><i class="bi bi-search" style="font-size:48px;color:#ccc;"></i><h4>No products found</h4><button class="btn btn-success mt-2" onclick="resetFilters()" style="background:#2e7d32;border:none;">Reset Filters</button></div>';
        return;
      }
      container.innerHTML = products.map((p, i) => `
        <div class="product-card">
          ${p.verified ? '<span class="verified-badge"><i class="bi bi-patch-check-fill"></i> Verified</span>' : ''}
          <div class="product-card-image">
            ${p.image ? `<img src="<?= SITE_ROOT ?>/${p.image}" alt="${escapeHtml(p.name)}">` : `<div class="default-icon"><i class="bi bi-box-seam"></i></div>`}
          </div>
          <div class="product-card-body">
            <span class="category-badge">${escapeHtml(p.category)}</span>
            <div class="product-name">${escapeHtml(p.name)}</div>
            ${p.specs ? `<div class="product-specs">${escapeHtml(p.specs)}</div>` : ''}
            ${p.price > 0 ? `<div class="price">$ ${p.price.toLocaleString()} <small>/ Unit</small></div>` : ''}
            ${p.moq > 1 ? `<div class="moq"><i class="bi bi-box"></i> MOQ: ${p.moq} Units</div>` : ''}
            <div class="company-name"><i class="bi bi-building"></i> ${escapeHtml(p.company)}</div>
            ${p.rating > 0 ? `<div class="rating"><span class="stars">${'★'.repeat(Math.floor(p.rating))}${p.rating % 1 >= 0.5 ? '½' : ''}</span><span class="count">${p.rating} (${p.reviews})</span></div>` : ''}
            ${p.exportsTo.length > 0 ? `<div class="exports-to"><i class="bi bi-globe2"></i> ${escapeHtml(p.exportsTo.join(', '))}</div>` : ''}
            ${p.certifications.length > 0 ? `<div class="certifications">${p.certifications.map(c => `<span class="cert-badge">${escapeHtml(c.toUpperCase())}</span>`).join(' ')}</div>` : ''}
            <button class="btn-get-price" onclick="openRFQ(${p.id}, '${escapeHtml(p.name).replace(/'/g, "\\'")}', '${escapeHtml(p.company).replace(/'/g, "\\'")}', ${p.price})">
              <i class="bi bi-chat-dots"></i> Get Latest Price
            </button>
          </div>
        </div>
      `).join('');
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

    function applyFilters() { currentPage = 1; displayProducts(); }
    
    function resetFilters() {
      document.getElementById('categoryFilter').value = '';
      document.getElementById('destinationFilter').value = '';
      document.getElementById('searchInput').value = '';
      document.getElementById('priceRange').value = 50000;
      document.getElementById('priceValue').textContent = '50,000';
      document.getElementById('certISO').checked = false;
      document.getElementById('certGST').checked = false;
      document.getElementById('certTrust').checked = false;
      document.getElementById('sortBy').value = 'relevance';
      currentPage = 1;
      displayProducts();
    }

    function updatePriceLabel() {
      document.getElementById('priceValue').textContent = parseInt(document.getElementById('priceRange').value).toLocaleString();
      applyFilters();
    }

    function openRFQ(productId, productName, company, price) {
      document.getElementById('rfqProductSummary').innerHTML = `<strong>${productName}</strong><br><small class="text-muted">${company} | $${price.toLocaleString()}/Unit</small>`;
      document.getElementById('rfqModal').classList.add('show');
      document.body.style.overflow = 'hidden';
    }

    function closeRFQ() {
      document.getElementById('rfqModal').classList.remove('show');
      document.getElementById('rfqSuccess').style.display = 'none';
      document.getElementById('rfqForm').style.display = 'block';
      document.body.style.overflow = '';
    }

    function submitRFQ(event) {
      event.preventDefault();
      document.getElementById('rfqForm').style.display = 'none';
      document.getElementById('rfqSuccess').style.display = 'block';
      setTimeout(() => { closeRFQ(); document.getElementById('rfqForm').style.display = 'block'; document.getElementById('rfqForm').reset(); }, 3000);
    }

    window.onclick = function(event) { if (event.target == document.getElementById('rfqModal')) closeRFQ(); }

    function scrollTabs(dir) { document.getElementById('categoryTabs').scrollBy({ left: dir * 200, behavior: 'smooth' }); }
  </script>

</body>
</html>