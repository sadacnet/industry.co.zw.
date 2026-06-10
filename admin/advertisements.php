<?php require_once __DIR__ . '/includes/header.php'; ?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>📢 Advertisements Management</h3>
        <button class="btn btn-primary" onclick="openAddModal()">+ Add New Advertisement</button>
    </div>
    
    <!-- Filter Tabs -->
    <ul class="nav nav-tabs mb-3" id="adTabs">
        <li class="nav-item"><button class="nav-link active" onclick="filterAds('all')">All Ads</button></li>
        <li class="nav-item"><button class="nav-link" onclick="filterAds('CZI')">CZI Ads</button></li>
        <li class="nav-item"><button class="nav-link" onclick="filterAds('CIFOZ')">CIFOZ Ads</button></li>
        <li class="nav-item"><button class="nav-link" onclick="filterAds('logo')">Logos</button></li>
        <li class="nav-item"><button class="nav-link" onclick="filterAds('banner')">Banners</button></li>
        <li class="nav-item"><button class="nav-link" onclick="filterAds('flyer')">Flyers</button></li>
        <li class="nav-item"><button class="nav-link" onclick="filterAds('poster')">Posters</button></li>
    </ul>
    
    <table id="adsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Preview</th>
                <th>Title</th>
                <th>Stakeholder</th>
                <th>Type</th>
                <th>Views</th>
                <th>Clicks</th>
                <th>Order</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody><tr><td colspan="10">Loading...</td></tr></tbody>
    </table>
</div>

<!-- Ad Modal -->
<div class="modal" id="adModal">
    <div class="modal-content" style="max-width:650px;">
        <h3 id="modalTitle">Add New Advertisement</h3>
        <form id="adForm">
            <input type="hidden" id="adId">
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Stakeholder *</label>
                        <select id="stakeholder" class="form-select" required>
                            <option value="">Select</option>
                            <option value="CZI">CZI</option>
                            <option value="CIFOZ">CIFOZ</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Type *</label>
                        <select id="type" class="form-select" required>
                            <option value="">Select</option>
                            <option value="logo">Logo</option>
                            <option value="banner">Banner</option>
                            <option value="flyer">Flyer</option>
                            <option value="poster">Poster</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Title</label>
                <input type="text" id="title" class="form-control" placeholder="e.g., Turnall Logo">
            </div>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>File Path *</label>
                        <input type="text" id="file_path" class="form-control" placeholder="e.g., uploads/logos/turnall.jpg" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>File Type</label>
                        <input type="text" id="file_type" class="form-control" placeholder="jpg, png, pdf">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Link URL (optional)</label>
                        <input type="text" id="link_url" class="form-control" placeholder="www.company.co.zw">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Display Order</label>
                        <input type="number" id="display_order" class="form-control" value="0" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Status</label>
                        <select id="is_active" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Preview</label>
                <div id="adPreview" class="text-center" style="background:#f9f9f9;padding:15px;border-radius:8px;min-height:60px;">
                    <span class="text-muted">Enter file path to see preview</span>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Advertisement</button>
            </div>
        </form>
    </div>
</div>

<script>
let allAds = [];
let currentFilter = 'all';

loadAds();

async function loadAds() {
    try {
        const res = await fetch('/industry.co.zw/admin/api/advertisements.php');
        const data = await res.json();
        if (data.status === 'success') {
            allAds = data.data;
            renderAds(allAds);
        }
    } catch(e) { showAlert('Error loading ads','error'); }
}

function renderAds(ads) {
    const tbody = document.querySelector('#adsTable tbody');
    if (ads.length === 0) { tbody.innerHTML = '<tr><td colspan="10">No advertisements found</td></tr>'; return; }
    
    tbody.innerHTML = ads.map(a => {
        const typeColors = { logo: '#1565C0', banner: '#006400', flyer: '#E65100', poster: '#7B1FA2' };
        const typeColor = typeColors[a.type] || '#666';
        const fileExt = a.file_type ? a.file_type.toLowerCase() : '';
        const isImage = ['jpg','jpeg','png','gif','webp'].includes(fileExt);
        
        return `
        <tr>
            <td>${a.id}</td>
            <td>
                ${isImage ? 
                    `<img src="/industry.co.zw/${a.file_path}" style="max-height:40px;max-width:60px;border-radius:4px;cursor:pointer;" onclick="window.open('/industry.co.zw/${a.file_path}','_blank')" onerror="this.style.display='none'">` :
                    '<span class="badge bg-secondary">File</span>'}
            </td>
            <td><strong>${a.title || 'Untitled'}</strong></td>
            <td><span class="badge" style="background:${a.stakeholder==='CZI'?'#1565C0':'#7B1FA2'};color:#fff;">${a.stakeholder}</span></td>
            <td><span class="badge" style="background:${typeColor};color:#fff;">${a.type}</span></td>
            <td>${a.views || 0}</td>
            <td>${a.clicks || 0}</td>
            <td>${a.display_order || 0}</td>
            <td><span class="badge ${a.is_active==1?'badge-active':'badge-inactive'}">${a.is_active==1?'Active':'Inactive'}</span></td>
            <td>
                <button class="btn btn-info btn-sm" onclick="editAd(${a.id})">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteAd(${a.id})">Delete</button>
            </td>
        </tr>`;
    }).join('');
}

function filterAds(type) {
    currentFilter = type;
    document.querySelectorAll('#adTabs .nav-link').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    let filtered = allAds;
    if (type === 'all') filtered = allAds;
    else if (type === 'CZI' || type === 'CIFOZ') filtered = allAds.filter(a => a.stakeholder === type);
    else filtered = allAds.filter(a => a.type === type);
    
    renderAds(filtered);
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add New Advertisement';
    document.getElementById('adForm').reset();
    document.getElementById('adId').value = '';
    document.getElementById('adPreview').innerHTML = '<span class="text-muted">Enter file path to see preview</span>';
    document.getElementById('adModal').style.display = 'block';
}

function closeModal() { document.getElementById('adModal').style.display = 'none'; }

async function editAd(id) {
    const ad = allAds.find(a => a.id == id);
    if (!ad) return;
    document.getElementById('modalTitle').textContent = 'Edit Advertisement';
    document.getElementById('adId').value = ad.id;
    document.getElementById('stakeholder').value = ad.stakeholder;
    document.getElementById('type').value = ad.type;
    document.getElementById('title').value = ad.title || '';
    document.getElementById('file_path').value = ad.file_path || '';
    document.getElementById('file_type').value = ad.file_type || '';
    document.getElementById('link_url').value = ad.link_url || '';
    document.getElementById('display_order').value = ad.display_order || 0;
    document.getElementById('is_active').value = ad.is_active;
    
    // Show preview
    const fileExt = (ad.file_type || '').toLowerCase();
    const isImage = ['jpg','jpeg','png','gif','webp'].includes(fileExt);
    if (isImage) {
        document.getElementById('adPreview').innerHTML = `<img src="/industry.co.zw/${ad.file_path}" style="max-height:100px;border-radius:8px;">`;
    } else {
        document.getElementById('adPreview').innerHTML = `<i class="bi bi-file-earmark" style="font-size:48px;color:#999;"></i><p>${ad.file_path}</p>`;
    }
    
    document.getElementById('adModal').style.display = 'block';
}

async function deleteAd(id) {
    if (!confirm('Delete this advertisement?')) return;
    await fetch(`/industry.co.zw/admin/api/advertisements.php?id=${id}`, {method:'DELETE'});
    showAlert('Advertisement deleted','success');
    loadAds();
}

// Live preview when typing file path
document.getElementById('file_path').addEventListener('input', function() {
    const path = this.value.trim();
    const preview = document.getElementById('adPreview');
    if (path) {
        preview.innerHTML = `<img src="/industry.co.zw/${path}" style="max-height:100px;border-radius:8px;" onerror="this.innerHTML='<i class=\\'bi bi-file-earmark\\' style=\\'font-size:48px;color:#999;\\'></i><p>'+path+'</p>'">`;
    } else {
        preview.innerHTML = '<span class="text-muted">Enter file path to see preview</span>';
    }
});

document.getElementById('adForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const id = document.getElementById('adId').value;
    const formData = {
        stakeholder: document.getElementById('stakeholder').value,
        type: document.getElementById('type').value,
        title: document.getElementById('title').value,
        file_path: document.getElementById('file_path').value,
        file_type: document.getElementById('file_type').value,
        link_url: document.getElementById('link_url').value,
        display_order: document.getElementById('display_order').value,
        is_active: document.getElementById('is_active').value
    };
    
    const url = id ? `/industry.co.zw/admin/api/advertisements.php?id=${id}` : '/industry.co.zw/admin/api/advertisements.php';
    const method = id ? 'PUT' : 'POST';
    const res = await fetch(url, {method, headers:{'Content-Type':'application/json'}, body:JSON.stringify(formData)});
    const data = await res.json();
    if (data.status === 'success') { showAlert(id?'Updated!':'Created!','success'); closeModal(); loadAds(); }
    else showAlert(data.message,'error');
});

window.onclick = function(e) { if (e.target == document.getElementById('adModal')) closeModal(); }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>