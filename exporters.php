<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Verified Exporters - industry.co.zw</title>
  <meta name="description" content="Connect with verified Zimbabwean exporters. Get latest prices and request quotes for products.">
  <meta name="keywords" content="Zimbabwe exporters, verified exporters, B2B, product sourcing, Zimbabwe products">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    /* Product Card Styles */
    .product-card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 15px rgba(0,0,0,0.08);
      padding: 20px;
      transition: all 0.3s ease;
      height: 100%;
      position: relative;
      border: 1px solid #e0e0e0;
    }
    
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.12);
      border-color: #006400;
    }
    
    .product-card .verified-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background: #006400;
      color: white;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
    }
    
    .product-card .verified-badge i {
      font-size: 10px;
    }
    
    .product-card .product-img {
      text-align: center;
      margin-bottom: 15px;
      height: 120px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .product-card .product-img img {
      max-height: 100%;
      max-width: 100%;
      object-fit: contain;
    }
    
    .product-card .product-name {
      font-size: 15px;
      font-weight: 600;
      color: #333;
      margin-bottom: 5px;
      line-height: 1.3;
    }
    
    .product-card .product-specs {
      font-size: 12px;
      color: #666;
      margin-bottom: 8px;
    }
    
    .product-card .price {
      font-size: 20px;
      font-weight: 700;
      color: #006400;
      margin-bottom: 5px;
    }
    
    .product-card .price small {
      font-size: 12px;
      color: #888;
    }
    
    .product-card .company-name {
      font-size: 13px;
      color: #555;
      margin-bottom: 8px;
    }
    
    .product-card .rating {
      margin-bottom: 8px;
    }
    
    .product-card .rating .stars {
      color: #FFD700;
      font-size: 13px;
    }
    
    .product-card .rating .count {
      font-size: 12px;
      color: #888;
    }
    
    .product-card .exports-to {
      font-size: 11px;
      color: #999;
      margin-bottom: 12px;
    }
    
    .product-card .exports-to i {
      color: #006400;
      font-size: 10px;
    }
    
    .product-card .btn-get-price {
      display: block;
      width: 100%;
      padding: 10px;
      background: #FFD700;
      color: #000;
      border: none;
      border-radius: 5px;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    .product-card .btn-get-price:hover {
      background: #FFC107;
      transform: scale(1.02);
    }
    
    .product-card .certifications {
      margin-top: 8px;
      display: flex;
      gap: 5px;
      flex-wrap: wrap;
    }
    
    .product-card .cert-badge {
      font-size: 10px;
      padding: 3px 8px;
      border-radius: 3px;
      background: #E8F5E9;
      color: #006400;
      font-weight: 600;
    }
    
    .product-card .moq {
      font-size: 11px;
      color: #C62828;
      font-weight: 600;
      margin-bottom: 5px;
    }

    /* Sidebar Filters */
    .filter-sidebar {
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 15px rgba(0,0,0,0.08);
      margin-bottom: 20px;
    }
    
    .filter-sidebar h5 {
      font-weight: 600;
      margin-bottom: 15px;
      padding-bottom: 10px;
      border-bottom: 2px solid #006400;
    }
    
    .filter-group {
      margin-bottom: 20px;
    }
    
    .filter-group label {
      font-weight: 600;
      font-size: 13px;
      color: #333;
      margin-bottom: 5px;
      display: block;
    }
    
    .filter-group .form-check {
      margin-bottom: 5px;
    }
    
    .filter-group .form-check-label {
      font-size: 13px;
      color: #555;
    }

    /* RFQ Modal */
    .rfq-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.6);
      z-index: 9999;
      justify-content: center;
      align-items: center;
    }
    
    .rfq-modal.show {
      display: flex;
    }
    
    .rfq-modal-content {
      background: #fff;
      border-radius: 12px;
      padding: 30px;
      width: 90%;
      max-width: 500px;
      position: relative;
      animation: slideUp 0.3s ease;
    }
    
    @keyframes slideUp {
      from { transform: translateY(50px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    
    .rfq-modal-content .close-modal {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 24px;
      cursor: pointer;
      color: #999;
    }
    
    .rfq-modal-content h4 {
      margin-bottom: 5px;
      color: #006400;
    }
    
    .rfq-modal-content .product-summary {
      background: #f5f5f5;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 15px;
    }

    /* Pagination */
    .pagination .page-link {
      color: #006400;
    }
    
    .pagination .active .page-link {
      background: #006400;
      border-color: #006400;
      color: #fff;
    }
  </style>

</head>

<body class="index-page">

  <?php
$pageTitle = "Page Title Here";
$pageDescription = "Page description for SEO";
require_once __DIR__ . '/includes/head.php';
?>
</head>

<body class="index-page">

  <?php require_once __DIR__ . '/includes/navbar.php'; ?>


  <main class="main">

    <!-- Page Title -->
    <section class="page-title section dark-background" style="background: url('assets/img/hero-section2.jpg') center center; background-size: cover;">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center" data-aos="fade-up">
            <h1>Verified Exporters</h1>
            <p style="color: rgba(255,255,255,0.8);">Connect with trusted Zimbabwean exporters and get the best prices</p>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb justify-content-center mt-2">
                <li class="breadcrumb-item"><a href="index.php" style="color: #FFD700;">Home</a></li>
                <li class="breadcrumb-item"><a href="exports.php" style="color: #FFD700;">Exports</a></li>
                <li class="breadcrumb-item active" style="color: #fff;">Verified Exporters</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <section class="services section light-background">
      <div class="container">
        <div class="row">

          <!-- Sidebar Filters -->
          <div class="col-lg-3" data-aos="fade-right">
            <div class="filter-sidebar">
              <h5><i class="bi bi-funnel"></i> Filters</h5>
              
              <div class="filter-group">
                <label>Product Category</label>
                <select class="form-select" id="categoryFilter" onchange="applyFilters()">
                  <option value="">All Categories</option>
                  <option value="agriculture">Agriculture</option>
                  <option value="minerals">Minerals</option>
                  <option value="manufacturing">Manufacturing</option>
                  <option value="textiles">Textiles</option>
                </select>
              </div>

              <div class="filter-group">
                <label>Certification</label>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="iso" id="certISO" onchange="applyFilters()">
                  <label class="form-check-label" for="certISO">ISO Certified</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="gst" id="certGST" onchange="applyFilters()">
                  <label class="form-check-label" for="certGST">GST Verified</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="trustseal" id="certTrust" onchange="applyFilters()">
                  <label class="form-check-label" for="certTrust">TrustSEAL Verified</label>
                </div>
              </div>

              <div class="filter-group">
                <label>Export Destination</label>
                <select class="form-select" id="destinationFilter" onchange="applyFilters()">
                  <option value="">All Destinations</option>
                  <option value="south-africa">South Africa</option>
                  <option value="usa">USA</option>
                  <option value="canada">Canada</option>
                  <option value="uae">UAE</option>
                  <option value="nepal">Nepal</option>
                  <option value="kuwait">Kuwait</option>
                </select>
              </div>

              <div class="filter-group">
                <label>Price Range</label>
                <input type="range" class="form-range" min="0" max="20000" step="100" id="priceRange" onchange="applyFilters()">
                <small>Up to R <span id="priceValue">20,000</span></small>
              </div>

              <button class="btn btn-outline-success w-100 mt-3" onclick="resetFilters()">
                <i class="bi bi-arrow-repeat"></i> Reset Filters
              </button>
            </div>
          </div>

          <!-- Products Grid -->
          <div class="col-lg-9">
            <!-- Search Bar -->
            <div class="row mb-4" data-aos="fade-up">
              <div class="col-md-8">
                <input type="text" class="form-control" id="searchInput" placeholder="Search products, companies, or specifications..." onkeyup="applyFilters()">
              </div>
              <div class="col-md-4">
                <select class="form-select" id="sortBy" onchange="applyFilters()">
                  <option value="relevance">Sort by: Relevance</option>
                  <option value="price-low">Price: Low to High</option>
                  <option value="price-high">Price: High to Low</option>
                  <option value="rating">Rating</option>
                </select>
              </div>
            </div>

            <!-- Results Count -->
            <p class="mb-3" data-aos="fade-up">
              <span id="resultCount">0</span> products found
            </p>

            <!-- Products Grid -->
            <div class="row gy-4" id="productsContainer">
              <div class="col-12 text-center">
                <div class="spinner-border text-success" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>
            </div>

            <!-- Pagination -->
            <nav class="mt-4" data-aos="fade-up">
              <ul class="pagination justify-content-center" id="pagination">
              </ul>
            </nav>
          </div>

        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="call-to-action section dark-background">
      <img src="assets/img/bg/bg-8.webp" alt="">
      <div class="container">
        <div class="row" data-aos="zoom-in" data-aos-delay="100">
          <div class="col-xl-9 text-center text-xl-start">
            <h3>Are You a Verified Exporter?</h3>
            <p>Join our platform and connect with international buyers looking for quality Zimbabwean products.</p>
          </div>
          <div class="col-xl-3 cta-btn-container text-center">
            <a class="cta-btn align-middle" href="contact.php">Get Verified Today</a>
          </div>
        </div>
      </div>
    </section>

  </main>

  <!-- RFQ Modal -->
  <div class="rfq-modal" id="rfqModal">
    <div class="rfq-modal-content">
      <span class="close-modal" onclick="closeRFQ()">&times;</span>
      <h4>Get Latest Price</h4>
      <div class="product-summary" id="rfqProductSummary"></div>
      <form id="rfqForm" onsubmit="submitRFQ(event)">
        <div class="mb-3">
          <label class="form-label">Your Name *</label>
          <input type="text" class="form-control" id="rfqName" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email Address / Phone *</label>
          <input type="text" class="form-control" id="rfqContact" placeholder="Email or Phone Number" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Your Country</label>
          <input type="text" class="form-control" id="rfqCountry" value="South Africa" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Quantity Required</label>
          <input type="number" class="form-control" id="rfqQuantity" placeholder="Enter quantity" min="1">
        </div>
        <div class="mb-3">
          <label class="form-label">Additional Requirements</label>
          <textarea class="form-control" id="rfqMessage" rows="2" placeholder="Any specific requirements..."></textarea>
        </div>
        <button type="submit" class="btn btn-success w-100">Submit Enquiry</button>
        <div class="text-center mt-2">
          <small class="text-muted">OR</small><br>
          <button type="button" class="btn btn-outline-dark btn-sm mt-1">
            <i class="bi bi-google"></i> Continue with Google
          </button>
        </div>
      </form>
      <div class="alert alert-success mt-3" id="rfqSuccess" style="display:none;">
        Your enquiry has been sent! The exporter will contact you shortly.
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer id="footer" class="footer">
    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.php" class="d-flex align-items-center">
            <img src="assets/img/industry-logo-20.png" alt="industry.co.zw Logo">
          </a>
          <div class="footer-contact pt-3">
            <p>Harare, Zimbabwe</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+263 242 123456</span></p>
            <p><strong>Email:</strong> <span>info@industry.co.zw</span></p>
          </div>
        </div>
        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Quick Links</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="index.php">Home</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="industries.php">Industries</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="provinces.php">Provinces</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="stakeholders.php">Stakeholders</a></li>
          </ul>
        </div>
        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Resources</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="tenders.php">Tenders</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="events.php">Events</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="exports.php">Exports</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="exporters.php">Verified Exporters</a></li>
          </ul>
        </div>
        <div class="col-lg-4 col-md-12">
          <h4>Follow Us</h4>
          <p>Stay connected with Zimbabwe's industrial community</p>
          <div class="social-links d-flex">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
      </div>
    </div>
    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">industry.co.zw</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        Developed by <a href="https://sadacnet.com/">SADACNET</a>
      </div>
    </div>
  </footer>

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <div id="preloader"></div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/js/main.js"></script>

  <script>
    // Sample product data (replace with API call)
    const products = [
      {
        id: 1,
        name: "Premium Virginia Gold Leaf Tobacco",
        specs: "Grade A, Cured, 200kg Bales",
        price: 18104.72,
        moq: 10,
        company: "Raj Electronic Enterprises",
        rating: 4.5,
        reviews: 128,
        exportsTo: ["South Africa", "China", "UAE"],
        certifications: ["iso", "gst"],
        category: "agriculture",
        verified: true
      },
      {
        id: 2,
        name: "Raw Diamonds - Marange Fields",
        specs: "Industrial Grade, 1-5 carats",
        price: 50000,
        moq: 1,
        company: "Zimbabwe Diamond Co.",
        rating: 4.8,
        reviews: 56,
        exportsTo: ["South Africa", "USA", "Canada"],
        certifications: ["iso", "trustseal"],
        category: "minerals",
        verified: true
      },
      {
        id: 3,
        name: "Platinum Group Metals",
        specs: "99.9% Pure, Ingot Form",
        price: 35000,
        moq: 5,
        company: "Zimplats Mining",
        rating: 4.6,
        reviews: 89,
        exportsTo: ["South Africa", "USA", "UAE"],
        certifications: ["iso", "gst", "trustseal"],
        category: "minerals",
        verified: true
      },
      {
        id: 4,
        name: "Processed Black Tea - Tanganda",
        specs: "Orange Pekoe, 50kg Bags",
        price: 2500,
        moq: 20,
        company: "Tanganda Tea Company",
        rating: 4.3,
        reviews: 215,
        exportsTo: ["South Africa", "Canada", "Nepal"],
        certifications: ["iso"],
        category: "agriculture",
        verified: true
      },
      {
        id: 5,
        name: "Granite Stone Products",
        specs: "Black Granite, Polished Slabs",
        price: 8200,
        moq: 50,
        company: "Zimbabwe Granite Works",
        rating: 4.1,
        reviews: 42,
        exportsTo: ["South Africa", "USA", "Kuwait"],
        certifications: ["iso", "gst"],
        category: "minerals",
        verified: true
      },
      {
        id: 6,
        name: "Ferrochrome Alloys",
        specs: "High Carbon, 60% Cr",
        price: 15000,
        moq: 100,
        company: "ZimAlloys",
        rating: 4.7,
        reviews: 73,
        exportsTo: ["South Africa", "China", "UAE", "Nepal"],
        certifications: ["iso", "trustseal"],
        category: "manufacturing",
        verified: true
      },
      {
        id: 7,
        name: "Cotton Lint - Premium Grade",
        specs: "Long Staple, Ginned",
        price: 4200,
        moq: 25,
        company: "Zimbabwe Cotton Company",
        rating: 4.2,
        reviews: 94,
        exportsTo: ["South Africa", "Canada"],
        certifications: ["gst"],
        category: "agriculture",
        verified: true
      },
      {
        id: 8,
        name: "Leather Products - Finished",
        specs: "Bovine Leather, Various Colors",
        price: 6800,
        moq: 15,
        company: "ZimLeather Industries",
        rating: 4.0,
        reviews: 38,
        exportsTo: ["South Africa", "USA", "Canada", "Kuwait"],
        certifications: ["gst"],
        category: "manufacturing",
        verified: true
      },
      {
        id: 9,
        name: "Refined Sugar - White",
        specs: "ICUMSA 45, 50kg Bags",
        price: 3200,
        moq: 100,
        company: "Triangle Sugar Estates",
        rating: 4.4,
        reviews: 167,
        exportsTo: ["South Africa", "UAE", "Nepal"],
        certifications: ["iso", "gst", "trustseal"],
        category: "agriculture",
        verified: true
      }
    ];

    let currentPage = 1;
    const itemsPerPage = 6;

    document.addEventListener('DOMContentLoaded', displayProducts);

    function displayProducts() {
      const filtered = getFilteredProducts();
      const sorted = sortProducts(filtered);
      const paginated = paginate(sorted);
      
      document.getElementById('resultCount').textContent = filtered.length;
      renderProducts(paginated);
      renderPagination(filtered.length);
    }

    function getFilteredProducts() {
      const category = document.getElementById('categoryFilter').value;
      const destination = document.getElementById('destinationFilter').value;
      const search = document.getElementById('searchInput').value.toLowerCase();
      const priceMax = parseInt(document.getElementById('priceRange').value);
      const certISO = document.getElementById('certISO').checked;
      const certGST = document.getElementById('certGST').checked;
      const certTrust = document.getElementById('certTrust').checked;

      return products.filter(p => {
        if (category && p.category !== category) return false;
        if (destination && !p.exportsTo.some(d => d.toLowerCase().replace(' ', '-') === destination)) return false;
        if (search && !p.name.toLowerCase().includes(search) && !p.company.toLowerCase().includes(search) && !p.specs.toLowerCase().includes(search)) return false;
        if (p.price > priceMax) return false;
        if (certISO && !p.certifications.includes('iso')) return false;
        if (certGST && !p.certifications.includes('gst')) return false;
        if (certTrust && !p.certifications.includes('trustseal')) return false;
        return true;
      });
    }

    function sortProducts(products) {
      const sortBy = document.getElementById('sortBy').value;
      const sorted = [...products];
      switch(sortBy) {
        case 'price-low': return sorted.sort((a, b) => a.price - b.price);
        case 'price-high': return sorted.sort((a, b) => b.price - a.price);
        case 'rating': return sorted.sort((a, b) => b.rating - a.rating);
        default: return sorted;
      }
    }

    function paginate(products) {
      const start = (currentPage - 1) * itemsPerPage;
      return products.slice(start, start + itemsPerPage);
    }

    function renderProducts(products) {
      const container = document.getElementById('productsContainer');
      if (products.length === 0) {
        container.innerHTML = '<div class="col-12 text-center"><p>No products match your criteria. Try adjusting filters.</p></div>';
        return;
      }

      container.innerHTML = products.map((p, i) => `
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="${i * 100}">
          <div class="product-card">
            ${p.verified ? '<span class="verified-badge"><i class="bi bi-patch-check-fill"></i> Verified Exporter</span>' : ''}
            <div class="product-img">
              <img src="assets/img/portfolio/portfolio-${(p.id % 9) + 1}.webp" alt="${p.name}">
            </div>
            <div class="product-name">${p.name}</div>
            <div class="product-specs">${p.specs}</div>
            <div class="price">R ${p.price.toLocaleString()} <small>/ Piece</small></div>
            ${p.moq ? `<div class="moq">MOQ: ${p.moq} Pieces</div>` : ''}
            <div class="company-name"><i class="bi bi-building"></i> ${p.company}</div>
            <div class="rating">
              <span class="stars">
                ${'★'.repeat(Math.floor(p.rating))}${p.rating % 1 >= 0.5 ? '½' : ''}
              </span>
              <span class="count">(${p.reviews})</span>
            </div>
            <div class="exports-to">
              <i class="bi bi-globe"></i> Exports To: ${p.exportsTo.join(', ')}
            </div>
            <div class="certifications">
              ${p.certifications.map(c => `<span class="cert-badge">${c.toUpperCase()} Verified</span>`).join(' ')}
            </div>
            <button class="btn-get-price mt-2" onclick="openRFQ(${p.id})">
              <i class="bi bi-chat-dots"></i> Get Latest Price
            </button>
          </div>
        </div>
      `).join('');
    }

    function renderPagination(totalItems) {
      const totalPages = Math.ceil(totalItems / itemsPerPage);
      const pagination = document.getElementById('pagination');
      let html = '';
      
      for (let i = 1; i <= totalPages; i++) {
        html += `<li class="page-item ${i === currentPage ? 'active' : ''}">
          <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
        </li>`;
      }
      
      pagination.innerHTML = html;
    }

    function changePage(page) {
      currentPage = page;
      displayProducts();
      window.scrollTo({ top: 400, behavior: 'smooth' });
    }

    function applyFilters() {
      currentPage = 1;
      displayProducts();
    }

    function resetFilters() {
      document.getElementById('categoryFilter').value = '';
      document.getElementById('destinationFilter').value = '';
      document.getElementById('searchInput').value = '';
      document.getElementById('priceRange').value = 20000;
      document.getElementById('priceValue').textContent = '20,000';
      document.getElementById('certISO').checked = false;
      document.getElementById('certGST').checked = false;
      document.getElementById('certTrust').checked = false;
      currentPage = 1;
      displayProducts();
    }

    document.getElementById('priceRange').addEventListener('input', function() {
      document.getElementById('priceValue').textContent = parseInt(this.value).toLocaleString();
      applyFilters();
    });

    function openRFQ(productId) {
      const product = products.find(p => p.id === productId);
      document.getElementById('rfqProductSummary').innerHTML = `
        <strong>${product.name}</strong><br>
        <small>${product.company} | R ${product.price.toLocaleString()}/Piece | MOQ: ${product.moq}</small>
      `;
      document.getElementById('rfqModal').classList.add('show');
    }

    function closeRFQ() {
      document.getElementById('rfqModal').classList.remove('show');
      document.getElementById('rfqSuccess').style.display = 'none';
      document.getElementById('rfqForm').style.display = 'block';
    }

    function submitRFQ(event) {
      event.preventDefault();
      document.getElementById('rfqForm').style.display = 'none';
      document.getElementById('rfqSuccess').style.display = 'block';
      setTimeout(() => {
        closeRFQ();
        document.getElementById('rfqForm').style.display = 'block';
        document.getElementById('rfqForm').reset();
      }, 3000);
    }

    window.onclick = function(event) {
      if (event.target == document.getElementById('rfqModal')) closeRFQ();
    }
  </script>

</body>
</html>