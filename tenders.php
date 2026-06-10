<?php
$pageTitle = "Tenders";
$pageDescription = "Active tenders and business opportunities across Zimbabwe";
require_once __DIR__ . '/includes/head.php';
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

    /* Page Title Section */
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
    }
    
    /* Filter Bar */
    .filter-bar {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    .filter-bar label {
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        color: #666;
        margin-bottom: 5px;
    }
    .filter-bar .form-select, .filter-bar .form-control {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 8px 12px;
        font-size: 14px;
    }
    .filter-bar .form-select:focus, .filter-bar .form-control:focus {
        border-color: #2e7d32;
        box-shadow: 0 0 0 2px rgba(46,125,50,0.1);
    }
    
    /* Tender Cards */
    .tender-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 3px 20px rgba(0,0,0,0.08);
        padding: 25px;
        margin-bottom: 25px;
        border-left: 5px solid #2e7d32;
        transition: all 0.3s ease;
    }
    .tender-card:hover {
        box-shadow: 0 8px 30px rgba(46,125,50,0.15);
        transform: translateY(-3px);
    }
    .tender-card.urgent {
        border-left-color: #dc3545;
    }
    .tender-card.closed {
        border-left-color: #999;
        opacity: 0.75;
    }
    .tender-card .tender-header h4 {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
        color: #1a2c3e;
    }
    .meta-badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin: 12px 0;
    }
    .meta-badges .badge {
        padding: 5px 12px;
        font-weight: 500;
        font-size: 11px;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }
    .info-item strong {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        color: #888;
        letter-spacing: 0.5px;
        margin-bottom: 3px;
    }
    .info-item span {
        color: #333;
        font-weight: 500;
        font-size: 13px;
    }
    .closing-box {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
    }
    .closing-box .days {
        font-size: 28px;
        font-weight: 700;
        color: #2e7d32;
        line-height: 1;
    }
    .closing-box .days.urgent {
        color: #dc3545;
    }
    .closing-box .label {
        font-size: 10px;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .btn-view-details {
        background: #2e7d32;
        color: #fff;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s;
    }
    .btn-view-details:hover {
        background: #1b5e20;
    }

    @media (max-width: 768px) {
        body.index-page { padding-top: 70px; }
        .page-title { padding: 35px 0 25px; }
        .page-title h1 { font-size: 24px; }
        .tender-card { padding: 18px; }
        .tender-card .tender-header h4 { font-size: 18px; }
        .info-grid { grid-template-columns: 1fr; }
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
            <li><span class="current">Tenders</span></li>
        </ul>
    </div>

    <section class="page-title">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center">
            <h1>Tenders</h1>
            <p>Active tenders and business opportunities across Zimbabwe</p>
          </div>
        </div>
      </div>
    </section>

    <section class="services section light-background" style="padding: 40px 0 60px; background: #f5f5f5;">
      <div class="container">

        <!-- Filter Bar -->
        <div class="filter-bar" data-aos="fade-up">
          <div class="row align-items-end g-3">
            <div class="col-md-3">
              <label>Status</label>
              <select class="form-select" id="statusFilter" onchange="applyFilters()">
                <option value="all">All Tenders</option>
                <option value="active" selected>Active Only</option>
                <option value="expired">Expired</option>
              </select>
            </div>
            <div class="col-md-3">
              <label>Category</label>
              <select class="form-select" id="categoryFilter" onchange="applyFilters()">
                <option value="">All Categories</option>
              </select>
            </div>
            <div class="col-md-4">
              <label>Search</label>
              <input type="text" class="form-control" id="searchInput" placeholder="Search by title, organization, or description..." onkeyup="applyFilters()">
            </div>
            <div class="col-md-2">
              <button class="btn btn-outline-success w-100" onclick="resetFilters()" style="border-color:#2e7d32; color:#2e7d32;">
                <i class="bi bi-arrow-repeat"></i> Reset
              </button>
            </div>
          </div>
        </div>

        <p class="mb-4" data-aos="fade-up">
          <span id="resultCount" class="fw-bold" style="color:#2e7d32;">0</span> tenders found
        </p>

        <div id="tendersContainer">
          <div class="text-center py-5">
            <div class="spinner-border text-success"></div>
            <p class="mt-3">Loading tenders...</p>
          </div>
        </div>

      </div>
    </section>

    <section class="call-to-action section dark-background" style="background: linear-gradient(135deg, #1a5e2a, #0d3b1a); padding: 50px 0;">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-xl-9 text-center text-xl-start">
            <h3 style="color:#fff; font-size: 26px;">Have a Tender to Post?</h3>
            <p style="color: rgba(255,255,255,0.85);">Submit your tender on industry.co.zw and reach qualified businesses across Zimbabwe.</p>
          </div>
          <div class="col-xl-3 text-center text-xl-end mt-3 mt-xl-0">
            <a class="cta-btn" href="contact.php" style="background:#fff; color:#2e7d32; padding: 12px 30px; border-radius: 40px; font-weight: 600; text-decoration: none;">Submit Tender →</a>
          </div>
        </div>
      </div>
    </section>

  </main>

  <!-- Tender Detail Modal -->
  <div class="modal fade" id="tenderDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background:#2e7d32; color:#fff;">
          <h5 class="modal-title" id="modalTitle">Tender Details</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="modalBody"></div>
      </div>
    </div>
  </div>

  <?php require_once __DIR__ . '/includes/footer.php'; ?>

  <script>
    const API = '/industry.co.zw/api/public';
    let allTenders = [];

    fetch(API + '/tenders.php')
      .then(r => r.json())
      .then(d => {
        if (d.status === 'success') {
          allTenders = d.data;
          populateCategoryFilter();
        }
        show();
      })
      .catch(() => {
        document.getElementById('tendersContainer').innerHTML = '<div class="text-center py-5"><h4>Could not load tenders</h4></div>';
      });

    function populateCategoryFilter() {
      const categories = [...new Set(allTenders.map(t => t.category).filter(Boolean))];
      const select = document.getElementById('categoryFilter');
      categories.sort().forEach(cat => {
        select.innerHTML += `<option value="${cat}">${cat}</option>`;
      });
    }

    function getFiltered() {
      const status = document.getElementById('statusFilter').value;
      const category = document.getElementById('categoryFilter').value;
      const search = document.getElementById('searchInput').value.toLowerCase();
      return allTenders.filter(t => {
        if (status === 'active' && t.is_expired) return false;
        if (status === 'expired' && !t.is_expired) return false;
        if (category && t.category !== category) return false;
        if (search && !t.title.toLowerCase().includes(search) && 
            !t.description.toLowerCase().includes(search) &&
            !t.issuing_organization.toLowerCase().includes(search) &&
            !t.tender_number.toLowerCase().includes(search)) return false;
        return true;
      });
    }

    function show() {
      const filtered = getFiltered();
      document.getElementById('resultCount').textContent = filtered.length;
      const container = document.getElementById('tendersContainer');

      if (filtered.length === 0) {
        container.innerHTML = '<div class="text-center py-5"><i class="bi bi-file-earmark-x" style="font-size:48px;color:#ccc;"></i><h4 class="mt-3">No tenders found</h4><button class="btn btn-success mt-2" onclick="resetFilters()" style="background:#2e7d32; border:none;">Reset Filters</button></div>';
        return;
      }

      container.innerHTML = filtered.map(t => {
        const closingDate = new Date(t.closing_date);
        const today = new Date();
        const daysLeft = Math.ceil((closingDate - today) / (1000 * 60 * 60 * 24));
        const isExpired = t.is_expired;
        const isUrgent = !isExpired && daysLeft <= 7;

        let statusClass = '';
        let statusLabel = '';
        if (isExpired) { statusClass = 'closed'; statusLabel = '<span class="badge bg-secondary">Closed</span>'; }
        else if (isUrgent) { statusClass = 'urgent'; statusLabel = '<span class="badge bg-danger">Closing Soon</span>'; }
        else { statusLabel = '<span class="badge bg-success">Open</span>'; }

        let daysClass = isExpired ? '' : (isUrgent ? 'urgent' : '');
        let daysDisplay = isExpired ? 'Closed' : daysLeft;

        return `
          <div class="tender-card ${statusClass}" data-aos="fade-up">
            <div class="row">
              <div class="col-md-9">
                <div class="tender-header">
                  <h4>${escapeHtml(t.title)}</h4>
                </div>
                <div class="meta-badges">
                  ${statusLabel}
                  ${t.tender_number ? '<span class="badge bg-dark">' + escapeHtml(t.tender_number) + '</span>' : ''}
                  ${t.category ? '<span class="badge bg-info">' + escapeHtml(t.category) + '</span>' : ''}
                  ${t.budget ? '<span class="badge bg-warning text-dark">Budget: $' + parseFloat(t.budget).toLocaleString() + '</span>' : ''}
                </div>
                <p style="color:#555; margin-bottom:0;">${escapeHtml(t.description ? t.description.substring(0, 250) + (t.description.length > 250 ? '...' : '') : 'No description available')}</p>
                
                <div class="info-grid">
                  ${t.issuing_organization ? '<div class="info-item"><strong>Issuing Organization</strong><span>' + escapeHtml(t.issuing_organization) + '</span></div>' : ''}
                  ${t.location ? '<div class="info-item"><strong>Location</strong><span><i class="bi bi-geo-alt"></i> ' + escapeHtml(t.location) + '</span></div>' : ''}
                  ${t.contact_email ? '<div class="info-item"><strong>Contact Email</strong><span><i class="bi bi-envelope"></i> ' + escapeHtml(t.contact_email) + '</span></div>' : ''}
                  ${t.contact_phone ? '<div class="info-item"><strong>Contact Phone</strong><span><i class="bi bi-telephone"></i> ' + escapeHtml(t.contact_phone) + '</span></div>' : ''}
                  ${t.bid_opening_date ? '<div class="info-item"><strong>Bid Opening Date</strong><span><i class="bi bi-calendar-check"></i> ' + new Date(t.bid_opening_date).toLocaleDateString('en-ZA', {day:'numeric', month:'short', year:'numeric'}) + '</span></div>' : ''}
                </div>
              </div>
              <div class="col-md-3 text-center">
                <div class="closing-box">
                  <div class="days ${daysClass}">${daysDisplay}</div>
                  <div class="label">${isExpired ? 'Tender Closed' : 'Days Remaining'}</div>
                  <div style="font-size:12px; font-weight:600; color:#666; margin-top:5px;">
                    ${closingDate.toLocaleDateString('en-ZA', {day:'numeric', month:'short', year:'numeric'})}
                  </div>
                </div>
                <button class="btn-view-details mt-3 w-100" onclick="viewDetails(${t.id})">
                  <i class="bi bi-eye"></i> View Details
                </button>
                ${t.document_url ? '<a href="/industry.co.zw/' + t.document_url + '" target="_blank" class="btn btn-outline-success btn-sm mt-2 w-100" style="border-color:#2e7d32; color:#2e7d32;"><i class="bi bi-download"></i> Download</a>' : ''}
              </div>
            </div>
          </div>`;
      }).join('');
    }

    function escapeHtml(str) {
      if (!str) return '';
      return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    }

    function viewDetails(id) {
      const t = allTenders.find(x => x.id == id);
      if (!t) return;
      
      document.getElementById('modalTitle').textContent = t.title;
      document.getElementById('modalBody').innerHTML = `
        <div class="row">
          <div class="col-md-8">
            ${t.tender_number ? '<p><strong>Tender Number:</strong> ' + escapeHtml(t.tender_number) + '</p>' : ''}
            ${t.issuing_organization ? '<p><strong>Issuing Organization:</strong> ' + escapeHtml(t.issuing_organization) + '</p>' : ''}
            ${t.category ? '<p><strong>Category:</strong> <span class="badge bg-info">' + escapeHtml(t.category) + '</span></p>' : ''}
            ${t.budget ? '<p><strong>Budget:</strong> $' + parseFloat(t.budget).toLocaleString() + '</p>' : ''}
            ${t.location ? '<p><strong>Location:</strong> ' + escapeHtml(t.location) + '</p>' : ''}
            <hr>
            <h6>Description</h6>
            <p>${escapeHtml(t.description || 'No description provided')}</p>
            ${t.submission_requirements ? '<h6>Submission Requirements</h6><p>' + escapeHtml(t.submission_requirements) + '</p>' : ''}
            ${t.eligibility_criteria ? '<h6>Eligibility Criteria</h6><p>' + escapeHtml(t.eligibility_criteria) + '</p>' : ''}
          </div>
          <div class="col-md-4">
            <div style="background:#f8f9fa; padding:15px; border-radius:10px;">
              <p><strong><i class="bi bi-calendar-x"></i> Closing Date:</strong><br>${new Date(t.closing_date).toLocaleDateString('en-ZA', {weekday:'long', day:'numeric', month:'long', year:'numeric'})}</p>
              ${t.bid_opening_date ? '<p><strong><i class="bi bi-calendar-check"></i> Bid Opening:</strong><br>' + new Date(t.bid_opening_date).toLocaleDateString('en-ZA', {weekday:'long', day:'numeric', month:'long', year:'numeric'}) + '</p>' : ''}
              ${t.contact_email ? '<p><strong><i class="bi bi-envelope"></i> Email:</strong><br>' + escapeHtml(t.contact_email) + '</p>' : ''}
              ${t.contact_phone ? '<p><strong><i class="bi bi-telephone"></i> Phone:</strong><br>' + escapeHtml(t.contact_phone) + '</p>' : ''}
            </div>
            <div class="mt-3">
              ${t.document_url ? '<a href="/industry.co.zw/' + t.document_url + '" target="_blank" class="btn btn-success btn-sm w-100 mb-2" style="background:#2e7d32; border:none;"><i class="bi bi-download"></i> Download Document 1</a>' : ''}
              ${t.document_url2 ? '<a href="/industry.co.zw/' + t.document_url2 + '" target="_blank" class="btn btn-outline-success btn-sm w-100 mb-2" style="border-color:#2e7d32; color:#2e7d32;"><i class="bi bi-download"></i> Download Document 2</a>' : ''}
              ${t.document_url3 ? '<a href="/industry.co.zw/' + t.document_url3 + '" target="_blank" class="btn btn-outline-success btn-sm w-100" style="border-color:#2e7d32; color:#2e7d32;"><i class="bi bi-download"></i> Download Document 3</a>' : ''}
            </div>
          </div>
        </div>`;
      
      new bootstrap.Modal(document.getElementById('tenderDetailModal')).show();
    }

    function applyFilters() { show(); }
    function resetFilters() {
      document.getElementById('statusFilter').value = 'all';
      document.getElementById('categoryFilter').value = '';
      document.getElementById('searchInput').value = '';
      show();
    }
  </script>

</body>
</html>