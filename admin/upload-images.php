<?php
require_once __DIR__ . '/includes/header.php';
?>

<div class="card" style="border-radius:4px;box-shadow:0 1px 3px rgba(0,0,0,0.08);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding: 20px 20px 0;">
        <div>
            <h3 style="font-weight:600;color:#1d2327;margin:0;">📤 Media Library</h3>
            <p style="color:#646970;font-size:13px;margin:4px 0 0;">Upload logos, banners, images, and documents for companies and products</p>
        </div>
        <a href="dashboard.php" class="btn" style="background:#fff;border:1px solid #c3c4c7;color:#2271b1;border-radius:3px;font-size:13px;padding:6px 14px;">
            ← Back to Dashboard
        </a>
    </div>
    
    <div id="alertArea" style="padding:0 20px;"></div>

    <!-- WordPress-style Tabs -->
    <div style="border-bottom:1px solid #c3c4c7;padding:0 20px;">
        <ul class="nav nav-tabs" style="border-bottom:none;gap:0;" id="uploadTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#companyUpload" type="button" 
                    style="border:none;border-bottom:3px solid transparent;color:#50575e;font-size:13px;font-weight:500;padding:10px 16px;background:transparent;border-radius:0;">
                    <i class="bi bi-building"></i> Company Logo
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bannerUpload" type="button"
                    style="border:none;border-bottom:3px solid transparent;color:#50575e;font-size:13px;font-weight:500;padding:10px 16px;background:transparent;border-radius:0;">
                    <i class="bi bi-image"></i> Company Banner
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#productUpload" type="button"
                    style="border:none;border-bottom:3px solid transparent;color:#50575e;font-size:13px;font-weight:500;padding:10px 16px;background:transparent;border-radius:0;">
                    <i class="bi bi-box-seam"></i> Product Image
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#galleryUpload" type="button"
                    style="border:none;border-bottom:3px solid transparent;color:#50575e;font-size:13px;font-weight:500;padding:10px 16px;background:transparent;border-radius:0;">
                    <i class="bi bi-images"></i> Gallery
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#documentUpload" type="button"
                    style="border:none;border-bottom:3px solid transparent;color:#50575e;font-size:13px;font-weight:500;padding:10px 16px;background:transparent;border-radius:0;">
                    <i class="bi bi-file-earmark-pdf"></i> Documents
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#adUpload" type="button"
                    style="border:none;border-bottom:3px solid transparent;color:#50575e;font-size:13px;font-weight:500;padding:10px 16px;background:transparent;border-radius:0;">
                    <i class="bi bi-megaphone"></i> Advertisement
                </button>
            </li>
        </ul>
    </div>

    <style>
        #uploadTabs .nav-link.active { color: #2271b1 !important; border-bottom-color: #2271b1 !important; font-weight:600 !important; }
        #uploadTabs .nav-link:hover { color: #2271b1 !important; }
    </style>

    <div class="tab-content" style="padding:20px;">
        
        <!-- Company Logo Upload -->
        <div class="tab-pane fade show active" id="companyUpload">
            <div class="row">
                <div class="col-lg-6">
                    <div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;">
                        <h5 style="font-weight:600;font-size:14px;margin-bottom:16px;color:#1d2327;"><i class="bi bi-building"></i> Upload Company Logo</h5>
                        <div class="form-group mb-3">
                            <label class="fw-bold" style="font-size:13px;">Search Company</label>
                            <div style="position:relative;">
                                <input type="text" class="form-control" id="companySearch" placeholder="Type to search..." style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;padding-left:32px;" onkeyup="filterCompanies()">
                                <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#8c8f94;"></i>
                            </div>
                        </div>
                        <form id="companyUploadForm" enctype="multipart/form-data">
                            <div class="form-group mb-3"><label class="fw-bold" style="font-size:13px;">Select Company *</label><select class="form-select" id="companySelect" required style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;" size="8"><option value="">Loading...</option></select><small style="color:#2271b1;" id="companyCount"></small></div>
                            <div class="form-group mb-3"><label class="fw-bold" style="font-size:13px;">Company Logo *</label><input type="file" class="form-control" id="companyLogo" accept="image/*" required style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"><small style="color:#646970;">200x200px, PNG or JPG</small></div>
                            <button type="submit" class="btn" style="background:#2271b1;color:#fff;border:none;border-radius:3px;padding:8px 20px;font-size:13px;"><i class="bi bi-upload"></i> Upload Logo</button>
                        </form>
                        <div id="companyPreview" class="text-center mt-3"></div><div id="companyResult" class="mt-3"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;">
                        <h5 style="font-weight:600;font-size:14px;margin-bottom:16px;"><i class="bi bi-info-circle"></i> Company Info</h5>
                        <div id="companyInfo"><p style="color:#646970;">Select a company</p></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========== COMPANY BANNER UPLOAD - NEW TAB ========== -->
        <div class="tab-pane fade" id="bannerUpload">
            <div class="row">
                <div class="col-lg-6">
                    <div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;">
                        <h5 style="font-weight:600;font-size:14px;margin-bottom:16px;color:#1d2327;"><i class="bi bi-image"></i> Upload Company Banner</h5>
                        <p style="color:#646970;font-size:12px;margin-bottom:16px;">Full-width banner for listing cards. Recommended: 800x400px.</p>
                        <div class="form-group mb-3">
                            <label class="fw-bold" style="font-size:13px;">Search Company</label>
                            <div style="position:relative;">
                                <input type="text" class="form-control" id="bannerCompanySearch" placeholder="Type to search..." style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;padding-left:32px;" onkeyup="filterBannerCompanies()">
                                <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#8c8f94;"></i>
                            </div>
                        </div>
                        <form id="bannerUploadForm" enctype="multipart/form-data">
                            <div class="form-group mb-3"><label class="fw-bold" style="font-size:13px;">Select Company *</label><select class="form-select" id="bannerCompanySelect" required style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;" size="6"><option value="">Loading...</option></select><small style="color:#2271b1;" id="bannerCompanyCount"></small></div>
                            <div class="form-group mb-3"><label class="fw-bold" style="font-size:13px;">Banner Image *</label><input type="file" class="form-control" id="bannerImage" accept="image/*" required style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"><small style="color:#646970;">800x400px, JPG/PNG/WebP, Max 5MB</small></div>
                            <button type="submit" class="btn" style="background:#2271b1;color:#fff;border:none;border-radius:3px;padding:8px 20px;font-size:13px;"><i class="bi bi-upload"></i> Upload Banner</button>
                        </form>
                        <div id="bannerPreview" class="text-center mt-3"></div><div id="bannerResult" class="mt-3"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;">
                        <h5 style="font-weight:600;font-size:14px;margin-bottom:16px;"><i class="bi bi-info-circle"></i> Company Info</h5>
                        <div id="bannerCompanyInfo"><p style="color:#646970;">Select a company</p></div>
                        <hr>
                        <h6 style="font-weight:600;font-size:12px;">Banner Guidelines</h6>
                        <ul style="font-size:12px;color:#646970;padding-left:16px;"><li>Landscape orientation (wider than tall)</li><li>Keep content centered</li><li>Avoid text at bottom (overlay)</li><li>JPG, PNG, WebP accepted</li></ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Image Upload -->
        <div class="tab-pane fade" id="productUpload">
            <div class="row">
                <div class="col-lg-6">
                    <div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;">
                        <h5 style="font-weight:600;font-size:14px;margin-bottom:16px;"><i class="bi bi-box-seam"></i> Upload Product Image</h5>
                        <div class="form-group mb-3"><label class="fw-bold" style="font-size:13px;">Search Product</label><div style="position:relative;"><input type="text" class="form-control" id="productSearch" placeholder="Type to search..." style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;padding-left:32px;" onkeyup="filterProducts()"><i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#8c8f94;"></i></div></div>
                        <form id="productUploadForm" enctype="multipart/form-data">
                            <div class="form-group mb-3"><label class="fw-bold" style="font-size:13px;">Select Product *</label><select class="form-select" id="productSelect" required style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;" size="6"><option value="">Loading...</option></select><small style="color:#2271b1;" id="productCount"></small></div>
                            <div class="form-group mb-3"><label class="fw-bold" style="font-size:13px;">Product Image *</label><input type="file" class="form-control" id="productImage" accept="image/*" required style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"></div>
                            <button type="submit" class="btn" style="background:#2271b1;color:#fff;border:none;border-radius:3px;padding:8px 20px;"><i class="bi bi-upload"></i> Upload</button>
                        </form>
                        <div id="productPreview" class="text-center mt-3"></div><div id="productResult" class="mt-3"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;"><h5 style="font-weight:600;font-size:14px;margin-bottom:16px;"><i class="bi bi-info-circle"></i> Product Info</h5><div id="productInfo"><p style="color:#646970;">Select a product</p></div></div>
                </div>
            </div>
        </div>

        <!-- Gallery Upload -->
        <div class="tab-pane fade" id="galleryUpload">
            <div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;">
                <h5 style="font-weight:600;font-size:14px;margin-bottom:16px;"><i class="bi bi-images"></i> Upload to Gallery</h5>
                <form id="galleryUploadForm" enctype="multipart/form-data">
                    <div class="row"><div class="col-md-6"><div class="form-group mb-3"><label class="fw-bold" style="font-size:13px;">Title</label><input type="text" class="form-control" id="galleryTitle" placeholder="Image title" style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"></div></div><div class="col-md-6"><div class="form-group mb-3"><label class="fw-bold" style="font-size:13px;">Category</label><select class="form-select" id="galleryCategory" style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"><option value="events">Events</option><option value="industry">Industry</option><option value="tourism">Tourism</option><option value="general">General</option></select></div></div><div class="col-md-12"><div class="form-group mb-3"><label class="fw-bold" style="font-size:13px;">Image *</label><input type="file" class="form-control" id="galleryImage" accept="image/*" required style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"></div></div><div class="col-md-12"><button type="submit" class="btn" style="background:#2271b1;color:#fff;border:none;border-radius:3px;padding:8px 20px;"><i class="bi bi-upload"></i> Upload</button></div></div>
                </form>
                <div id="galleryPreview" class="text-center mt-3"></div><div id="galleryResult" class="mt-3"></div>
            </div>
        </div>

        <!-- Document Upload -->
        <div class="tab-pane fade" id="documentUpload">
            <div class="row"><div class="col-lg-6"><div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;"><h5 style="font-weight:600;font-size:14px;margin-bottom:16px;"><i class="bi bi-file-earmark-pdf"></i> Upload Document</h5><form id="documentUploadForm" enctype="multipart/form-data"><div class="form-group mb-3"><label class="fw-bold" style="font-size:13px;">Title</label><input type="text" class="form-control" id="docTitle" placeholder="Document title" style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"></div><div class="form-group mb-3"><label class="fw-bold" style="font-size:13px;">Category</label><select class="form-select" id="docCategory" style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"><option value="tender">Tender</option><option value="report">Report</option><option value="brochure">Brochure</option><option value="form">Form</option><option value="other">Other</option></select></div><div class="form-group mb-3"><label class="fw-bold" style="font-size:13px;">File *</label><input type="file" class="form-control" id="documentFile" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" required style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"></div><button type="submit" class="btn" style="background:#2271b1;color:#fff;border:none;border-radius:3px;padding:8px 20px;"><i class="bi bi-upload"></i> Upload</button></form><div id="documentResult" class="mt-3"></div></div></div><div class="col-lg-6"><div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;"><h5 style="font-weight:600;font-size:14px;">Guidelines</h5><table class="table table-sm"><tr><th>Type</th><th>Max</th><th>Use</th></tr><tr><td>PDF</td><td>10MB</td><td>Tenders, forms</td></tr><tr><td>DOC/DOCX</td><td>10MB</td><td>Documents</td></tr><tr><td>XLS/XLSX</td><td>10MB</td><td>Spreadsheets</td></tr><tr><td>PPT/PPTX</td><td>10MB</td><td>Presentations</td></tr></table></div></div></div>
        </div>

        <!-- Advertisement Upload -->
        <div class="tab-pane fade" id="adUpload">
            <div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;">
                <h5 style="font-weight:600;font-size:14px;margin-bottom:16px;"><i class="bi bi-megaphone"></i> Upload Advertisement</h5>
                <form id="adUploadForm" enctype="multipart/form-data">
                    <div class="row"><div class="col-md-4"><div class="form-group mb-3"><label class="fw-bold" style="font-size:13px;">Stakeholder *</label><select class="form-select" id="adStakeholder" required style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"><option value="CZI">CZI</option><option value="CIFOZ">CIFOZ</option></select></div></div><div class="col-md-4"><div class="form-group mb-3"><label class="fw-bold" style="font-size:13px;">Type *</label><select class="form-select" id="adType" required style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"><option value="banner">Banner</option><option value="logo">Logo</option><option value="poster">Poster</option><option value="flyer">Flyer</option></select></div></div><div class="col-md-4"><div class="form-group mb-3"><label class="fw-bold" style="font-size:13px;">Title</label><input type="text" class="form-control" id="adTitle" placeholder="Ad title" style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"></div></div><div class="col-md-12"><div class="form-group mb-3"><label class="fw-bold" style="font-size:13px;">File *</label><input type="file" class="form-control" id="adFile" accept="image/*,.pdf" required style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"></div></div><div class="col-md-12"><button type="submit" class="btn" style="background:#2271b1;color:#fff;border:none;border-radius:3px;padding:8px 20px;"><i class="bi bi-upload"></i> Upload</button></div></div>
                </form>
                <div id="adPreview" class="text-center mt-3"></div><div id="adResult" class="mt-3"></div>
            </div>
        </div>

    </div>
</div>

<!-- Recently Uploaded -->
<div class="card mt-4" style="border-radius:4px;box-shadow:0 1px 3px rgba(0,0,0,0.08);">
    <div style="display: flex; justify-content: space-between; align-items: center; padding:16px 20px; border-bottom:1px solid #c3c4c7;">
        <h3 style="font-weight:600;color:#1d2327;margin:0;font-size:14px;">🖼️ Recently Uploaded</h3>
        <button class="btn" onclick="loadRecentUploads()" style="background:#fff;border:1px solid #c3c4c7;color:#2271b1;border-radius:3px;font-size:12px;padding:4px 12px;"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
    </div>
    <div id="recentUploads" class="row" style="padding:16px;"><div class="col-12 text-center py-4"><div class="spinner-border" style="color:#2271b1;"></div></div></div>
</div>

<script>
    const API_BASE = '<?= SITE_ROOT ?>';
    let allCompanies = [];
    let allProducts = [];

    async function loadCompanies() {
        try {
            const r = await fetch(API_BASE + '/api/public/companies.php?limit=2000');
            const d = await r.json();
            if (d.status === 'success') {
                allCompanies = d.data;
                renderCompanyList(allCompanies, 'companySelect', 'companyCount');
                renderCompanyList(allCompanies, 'bannerCompanySelect', 'bannerCompanyCount');
            }
        } catch (e) { console.error(e); }
    }

    function renderCompanyList(companies, selectId, countId) {
        const select = document.getElementById(selectId);
        select.innerHTML = companies.map(c => `<option value="${c.id}" data-name="${c.name}" data-industry="${c.industry_name}" data-province="${c.province_name}" data-logo="${c.logo||''}" data-stakeholder="${c.stakeholder||''}">${c.name} (${c.industry_name})${c.stakeholder?' ['+c.stakeholder+']':''}</option>`).join('');
        document.getElementById(countId).textContent = `Showing ${companies.length} of ${allCompanies.length} companies`;
    }

    function filterCompanies() {
        const s = document.getElementById('companySearch').value.toLowerCase();
        renderCompanyList(allCompanies.filter(c => c.name.toLowerCase().includes(s)||c.industry_name.toLowerCase().includes(s)||(c.stakeholder||'').toLowerCase().includes(s)), 'companySelect', 'companyCount');
    }

    function filterBannerCompanies() {
        const s = document.getElementById('bannerCompanySearch').value.toLowerCase();
        renderCompanyList(allCompanies.filter(c => c.name.toLowerCase().includes(s)||c.industry_name.toLowerCase().includes(s)), 'bannerCompanySelect', 'bannerCompanyCount');
    }

    async function loadProducts() {
        try {
            const r = await fetch(API_BASE + '/api/public/exports.php');
            const d = await r.json();
            if (d.status === 'success') { allProducts = d.data; renderProductList(allProducts); }
        } catch (e) { console.error(e); }
    }

    function renderProductList(products) {
        const s = document.getElementById('productSelect');
        s.innerHTML = products.length === 0 ? '<option>No products</option>' : products.map(p => `<option value="${p.id}" data-name="${p.product_name}" data-category="${p.category||'General'}" data-image="${p.image||''}">${p.product_name} (${p.category||'General'})</option>`).join('');
        document.getElementById('productCount').textContent = `Showing ${products.length} of ${allProducts.length}`;
    }

    function filterProducts() {
        const s = document.getElementById('productSearch').value.toLowerCase();
        renderProductList(allProducts.filter(p => p.product_name.toLowerCase().includes(s)||(p.category||'').toLowerCase().includes(s)));
    }

    // Company Info
    document.getElementById('companySelect').addEventListener('change', async function() {
        await showCompanyInfo(this.value, 'companyInfo');
    });

    // Banner Company Info
    document.getElementById('bannerCompanySelect').addEventListener('change', async function() {
        await showCompanyInfo(this.value, 'bannerCompanyInfo');
    });

    async function showCompanyInfo(id, targetId) {
        if (!id) { document.getElementById(targetId).innerHTML = '<p style="color:#646970;">Select a company</p>'; return; }
        try {
            const r = await fetch(API_BASE + '/admin/api/members.php?id=' + id);
            const d = await r.json();
            if (d.status === 'success' && d.data) {
                const c = d.data;
                document.getElementById(targetId).innerHTML = `<table class="table table-sm"><tr><td><strong>Name:</strong></td><td>${c.name}</td></tr><tr><td><strong>Industry:</strong></td><td>${c.industry_name}</td></tr><tr><td><strong>Province:</strong></td><td>${c.province_name}</td></tr><tr><td><strong>Stakeholder:</strong></td><td>${c.stakeholder||'General'}</td></tr><tr><td><strong>Current Image:</strong></td><td>${c.logo?`<img src="${API_BASE}/${c.logo}" style="max-height:60px;border-radius:4px;">`:'<span class="badge bg-secondary">None</span>'}</td></tr></table>`;
            }
        } catch (e) { console.error(e); }
    }

    // Product Info
    document.getElementById('productSelect').addEventListener('change', function() {
        const o = this.options[this.selectedIndex];
        if (!this.value) { document.getElementById('productInfo').innerHTML = '<p style="color:#646970;">Select a product</p>'; return; }
        document.getElementById('productInfo').innerHTML = `<table class="table table-sm"><tr><td><strong>Product:</strong></td><td>${o.getAttribute('data-name')}</td></tr><tr><td><strong>Category:</strong></td><td><span class="badge bg-success">${o.getAttribute('data-category')}</span></td></tr><tr><td><strong>Image:</strong></td><td>${o.getAttribute('data-image')?`<img src="${API_BASE}/${o.getAttribute('data-image')}" style="max-height:60px;">`:'<span class="badge bg-secondary">None</span>'}</td></tr></table>`;
    });

    // Preview
    function setupPreview(inputId, previewId) {
        document.getElementById(inputId).addEventListener('change', function(e) {
            const f = e.target.files[0];
            if (f && f.type.startsWith('image/')) {
                const rd = new FileReader();
                rd.onload = function(ev) { document.getElementById(previewId).innerHTML = `<p style="color:#646970;">Preview:</p><img src="${ev.target.result}" style="max-width:250px;max-height:200px;border-radius:4px;">`; };
                rd.readAsDataURL(f);
            } else if (f) {
                document.getElementById(previewId).innerHTML = `<div class="alert alert-info">${f.name} (${(f.size/1024).toFixed(1)} KB)</div>`;
            }
        });
    }
    setupPreview('companyLogo','companyPreview'); setupPreview('bannerImage','bannerPreview');
    setupPreview('productImage','productPreview'); setupPreview('galleryImage','galleryPreview');
    setupPreview('adFile','adPreview');

    // Upload
    async function handleUpload(formData, resultId) {
        const rd = document.getElementById(resultId);
        rd.innerHTML = '<div class="alert alert-info">Uploading...</div>';
        try {
            const r = await fetch(API_BASE + '/admin/api/upload.php', { method: 'POST', body: formData });
            const d = await r.json();
            if (d.status === 'success') {
                rd.innerHTML = `<div class="alert alert-success"><strong>✅ Uploaded!</strong><br>Path: <code>${d.data.file_path}</code>${['jpg','jpeg','png','gif','webp'].includes(d.data.file_type?.toLowerCase())?`<br><img src="${d.data.full_url}" style="max-width:100px;margin-top:5px;">`:''}<br><button class="btn btn-sm" style="background:#fff;border:1px solid #c3c4c7;margin-top:4px;" onclick="copyPath('${d.data.file_path}')">Copy</button></div>`;
                showAlert('Uploaded!','success'); loadRecentUploads(); return d.data;
            } else { rd.innerHTML = `<div class="alert alert-danger">${d.message}</div>`; return null; }
        } catch (e) { rd.innerHTML = `<div class="alert alert-danger">${e.message}</div>`; return null; }
    }

    // Company Logo
    document.getElementById('companyUploadForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const cid=document.getElementById('companySelect').value, f=document.getElementById('companyLogo').files[0];
        if(!cid||!f){showAlert('Select company and file','error');return;}
        const fd=new FormData();fd.append('file',f);fd.append('type','logo');
        const r=await handleUpload(fd,'companyResult');
        if(r){await fetch(API_BASE+'/admin/api/members.php?id='+cid,{method:'PUT',headers:{'Content-Type':'application/json'},body:JSON.stringify({logo:r.file_path})});showAlert('Logo updated!','success');document.getElementById('companyLogo').value='';document.getElementById('companyPreview').innerHTML='';loadCompanies();}
    });

    // Company Banner - NEW
    document.getElementById('bannerUploadForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const cid=document.getElementById('bannerCompanySelect').value, f=document.getElementById('bannerImage').files[0];
        if(!cid||!f){showAlert('Select company and file','error');return;}
        const fd=new FormData();fd.append('file',f);fd.append('type','logo');
        const r=await handleUpload(fd,'bannerResult');
        if(r){await fetch(API_BASE+'/admin/api/members.php?id='+cid,{method:'PUT',headers:{'Content-Type':'application/json'},body:JSON.stringify({logo:r.file_path})});showAlert('Banner updated!','success');document.getElementById('bannerImage').value='';document.getElementById('bannerPreview').innerHTML='';loadCompanies();}
    });

    // Product
    document.getElementById('productUploadForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const pid=document.getElementById('productSelect').value, f=document.getElementById('productImage').files[0];
        if(!pid||!f){showAlert('Select product and file','error');return;}
        const fd=new FormData();fd.append('file',f);fd.append('type','gallery');
        const r=await handleUpload(fd,'productResult');
        if(r){await fetch(API_BASE+'/admin/api/exports.php?id='+pid,{method:'PUT',headers:{'Content-Type':'application/json'},body:JSON.stringify({image:r.file_path})});showAlert('Updated!','success');loadProducts();document.getElementById('productImage').value='';document.getElementById('productPreview').innerHTML='';}
    });

    // Gallery
    document.getElementById('galleryUploadForm').addEventListener('submit', async function(e) {
        e.preventDefault(); const f=document.getElementById('galleryImage').files[0];
        if(!f){showAlert('Select file','error');return;}
        const fd=new FormData();fd.append('file',f);fd.append('type','gallery');
        const r=await handleUpload(fd,'galleryResult');
        if(r){document.getElementById('galleryImage').value='';document.getElementById('galleryPreview').innerHTML='';document.getElementById('galleryTitle').value='';}
    });

    // Document
    document.getElementById('documentUploadForm').addEventListener('submit', async function(e) {
        e.preventDefault(); const f=document.getElementById('documentFile').files[0];
        if(!f){showAlert('Select file','error');return;}
        const fd=new FormData();fd.append('file',f);fd.append('type','document');fd.append('title',document.getElementById('docTitle').value);
        const r=await handleUpload(fd,'documentResult');
        if(r){document.getElementById('documentFile').value='';document.getElementById('docTitle').value='';}
    });

    // Ad
    document.getElementById('adUploadForm').addEventListener('submit', async function(e) {
        e.preventDefault(); const f=document.getElementById('adFile').files[0],t=document.getElementById('adType').value;
        if(!f){showAlert('Select file','error');return;}
        const fd=new FormData();fd.append('file',f);fd.append('type',t);
        const r=await handleUpload(fd,'adResult');
        if(r){document.getElementById('adFile').value='';document.getElementById('adPreview').innerHTML='';document.getElementById('adTitle').value='';}
    });

    async function loadRecentUploads() {
        try {
            const r=await fetch(API_BASE+'/api/public/gallery.php'),d=await r.json();
            const c=document.getElementById('recentUploads');
            if(d.status==='success'&&d.data.length>0){c.innerHTML=d.data.slice(0,8).map(img=>`<div class="col-xl-3 col-md-4 col-6 mb-3"><div class="card h-100" style="border-radius:4px;"><img src="${API_BASE}/${img.file_path}" style="height:100px;object-fit:cover;cursor:pointer;" onclick="window.open('${API_BASE}/${img.file_path}','_blank')" onerror="this.style.display='none'"><div class="card-body p-2"><small class="d-block text-truncate">${img.title||'Untitled'}</small><code class="d-block text-truncate" style="font-size:10px;">${img.file_path}</code></div></div></div>`).join('');}
            else{c.innerHTML='<div class="col-12 text-center py-4"><i class="bi bi-cloud-upload" style="font-size:48px;color:#ccc;"></i><h5>No files yet</h5></div>';}
        }catch(e){console.error(e);}
    }

    function copyPath(p){if(navigator.clipboard){navigator.clipboard.writeText(p).then(()=>showAlert('Copied!','success'));}else{prompt('Copy:',p);}}
    
    loadCompanies(); loadProducts(); loadRecentUploads();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>