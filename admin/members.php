<?php
require_once __DIR__ . '/includes/header.php';
?>

<div class="card" style="border-radius:4px;box-shadow:0 1px 3px rgba(0,0,0,0.08);">
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 20px 0;">
        <div>
            <h3 style="font-weight:600;color:#1d2327;margin:0;">🏢 Members</h3>
            <p style="color:#646970;font-size:13px;margin:4px 0 0;">Manage all company listings across directories</p>
        </div>
        <button class="btn" onclick="openAddModal()" style="background:#2271b1;color:#fff;border:none;border-radius:3px;padding:6px 16px;font-size:13px;font-weight:500;">
            + Add New Member
        </button>
    </div>
    
    <div id="alertArea" style="padding:0 20px;"></div>

    <!-- Filter Tabs -->
    <div style="padding:16px 20px 0;border-bottom:1px solid #c3c4c7;">
        <ul class="nav nav-tabs" style="border-bottom:none;gap:0;" id="memberTabs">
            <li class="nav-item">
                <button class="nav-link active" onclick="filterMembers('all')" style="border:none;border-bottom:3px solid transparent;color:#50575e;font-size:13px;font-weight:500;padding:8px 16px;background:transparent;border-radius:0;">
                    All <span class="badge bg-secondary" style="margin-left:4px;" id="countAll">0</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" onclick="filterMembers('industry')" style="border:none;border-bottom:3px solid transparent;color:#50575e;font-size:13px;font-weight:500;padding:8px 16px;background:transparent;border-radius:0;">
                    🏭 Industry Listings <span class="badge" style="background:#e8f5e9;color:#007017;margin-left:4px;" id="countIndustry">0</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" onclick="filterMembers('CZI')" style="border:none;border-bottom:3px solid transparent;color:#50575e;font-size:13px;font-weight:500;padding:8px 16px;background:transparent;border-radius:0;">
                    🔵 CZI <span class="badge" style="background:#e3f2fd;color:#1565C0;margin-left:4px;" id="countCZI">0</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" onclick="filterMembers('CIFOZ')" style="border:none;border-bottom:3px solid transparent;color:#50575e;font-size:13px;font-weight:500;padding:8px 16px;background:transparent;border-radius:0;">
                    🟣 CIFOZ <span class="badge" style="background:#f3e5f5;color:#7B1FA2;margin-left:4px;" id="countCIFOZ">0</span>
                </button>
            </li>
        </ul>
    </div>

    <style>
        #memberTabs .nav-link.active { color: #2271b1 !important; border-bottom-color: #2271b1 !important; font-weight:600 !important; }
        #memberTabs .nav-link:hover { color: #2271b1 !important; }
        .badge-industry { background:#e8f5e9; color:#007017; }
        .badge-czi { background:#e3f2fd; color:#1565C0; }
        .badge-cifoz { background:#f3e5f5; color:#7B1FA2; }
        .badge-general { background:#f5f5f5; color:#666; }
    </style>

    <!-- Search -->
    <div style="padding:12px 20px;border-bottom:1px solid #e0e0e0;">
        <div style="position:relative;max-width:350px;">
            <input type="text" id="memberSearch" class="form-control" placeholder="Search members..." onkeyup="doSearch()" style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;padding-left:32px;">
            <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#8c8f94;"></i>
        </div>
    </div>

    <!-- Table -->
    <div style="overflow-x:auto;">
        <table class="table table-striped" style="margin:0;font-size:13px;" id="membersTable">
            <thead style="background:#f0f0f1;">
                <tr>
                    <th style="width:60px;">ID</th>
                    <th>Company</th>
                    <th>Industry</th>
                    <th>Province</th>
                    <th>Type</th>
                    <th style="width:80px;">Status</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="7" style="text-align:center;padding:40px;color:#646970;">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="memberModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:4px;width:90%;max-width:600px;max-height:80vh;overflow-y:auto;box-shadow:0 10px 40px rgba(0,0,0,0.2);">
        <div style="padding:20px 24px;border-bottom:1px solid #dcdcde;">
            <h3 id="modalTitle" style="margin:0;font-size:16px;font-weight:600;">Add New Member</h3>
        </div>
        <form id="memberForm" style="padding:20px 24px;">
            <input type="hidden" id="memberId">
            
            <div class="row mb-3">
                <div class="col-md-8"><label class="fw-bold" style="font-size:13px;">Company Name *</label><input type="text" id="name" class="form-control" required style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"></div>
                <div class="col-md-4"><label class="fw-bold" style="font-size:13px;">Listing Type</label><select id="listing_type" class="form-select" style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;" onchange="updateStakeholder()"><option value="industry">Industry Listing</option><option value="partner">Partner Directory</option></select></div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6"><label class="fw-bold" style="font-size:13px;">Industry *</label><select id="industry_id" class="form-select" required style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"></select></div>
                <div class="col-md-6"><label class="fw-bold" style="font-size:13px;">Province *</label><select id="province_id" class="form-select" required style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"></select></div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6"><label class="fw-bold" style="font-size:13px;">Stakeholder</label><select id="stakeholder" class="form-select" style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"><option value="">None (Industry Listing)</option><option value="CZI">CZI</option><option value="CIFOZ">CIFOZ</option></select></div>
                <div class="col-md-6"><label class="fw-bold" style="font-size:13px;">Phone</label><input type="text" id="phone" class="form-control" style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"></div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6"><label class="fw-bold" style="font-size:13px;">Email</label><input type="email" id="email" class="form-control" style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"></div>
                <div class="col-md-6"><label class="fw-bold" style="font-size:13px;">Website</label><input type="text" id="website" class="form-control" style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"></div>
            </div>
            
            <div class="mb-3"><label class="fw-bold" style="font-size:13px;">Description</label><textarea id="description" class="form-control" rows="2" style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"></textarea></div>
            
            <div class="row mb-3">
                <div class="col-md-6"><label class="fw-bold" style="font-size:13px;">Status</label><select id="is_active" class="form-select" style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"><option value="1">Active</option><option value="0">Inactive</option></select></div>
            </div>
            
            <div style="display:flex;gap:8px;justify-content:flex-end;padding-top:12px;border-top:1px solid #dcdcde;">
                <button type="button" class="btn" onclick="closeModal()" style="background:#fff;border:1px solid #c3c4c7;color:#2271b1;border-radius:3px;padding:6px 16px;">Cancel</button>
                <button type="submit" class="btn" style="background:#2271b1;color:#fff;border:none;border-radius:3px;padding:6px 16px;">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
let allMembers = [];
let currentFilter = 'all';

loadMembers();
loadDropdowns();

async function loadMembers() {
    try {
        const response = await fetch('<?= SITE_ROOT ?>/admin/api/members.php');
        const data = await response.json();
        if (data.status === 'success') {
            allMembers = data.data;
            updateCounts();
            displayMembers(allMembers);
        }
    } catch (error) { console.error('Error:', error); }
}

function updateCounts() {
    document.getElementById('countAll').textContent = allMembers.length;
    document.getElementById('countIndustry').textContent = allMembers.filter(m => !m.stakeholder || m.listing_type==='industry').length;
    document.getElementById('countCZI').textContent = allMembers.filter(m => m.stakeholder==='CZI').length;
    document.getElementById('countCIFOZ').textContent = allMembers.filter(m => m.stakeholder==='CIFOZ').length;
}

function filterMembers(type) {
    currentFilter = type;
    document.querySelectorAll('#memberTabs .nav-link').forEach(b => b.classList.remove('active'));
    event.target.closest('.nav-link').classList.add('active');
    doSearch();
}

function doSearch() {
    const search = document.getElementById('memberSearch').value.toLowerCase();
    let filtered = allMembers;
    
    // Apply type filter
    if (currentFilter === 'industry') filtered = filtered.filter(m => !m.stakeholder || m.listing_type==='industry');
    else if (currentFilter === 'CZI') filtered = filtered.filter(m => m.stakeholder==='CZI');
    else if (currentFilter === 'CIFOZ') filtered = filtered.filter(m => m.stakeholder==='CIFOZ');
    
    // Apply search
    if (search) filtered = filtered.filter(m => m.name.toLowerCase().includes(search) || m.industry_name.toLowerCase().includes(search) || m.province_name.toLowerCase().includes(search));
    
    displayMembers(filtered);
}

function displayMembers(members) {
    const tbody = document.querySelector('#membersTable tbody');
    if (members.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:30px;color:#646970;">No members found</td></tr>';
        return;
    }
    
    tbody.innerHTML = members.map(m => {
        let typeBadge = '';
        if (m.stakeholder === 'CZI') typeBadge = '<span class="badge badge-czi">CZI Partner</span>';
        else if (m.stakeholder === 'CIFOZ') typeBadge = '<span class="badge badge-cifoz">CIFOZ Partner</span>';
        else typeBadge = '<span class="badge badge-industry">Industry</span>';
        
        return `
        <tr>
            <td>${m.id}</td>
            <td><strong>${m.name}</strong></td>
            <td>${m.industry_name}</td>
            <td>${m.province_name}</td>
            <td>${typeBadge}</td>
            <td><span class="badge ${m.is_active==1?'badge-industry':'badge-general'}">${m.is_active==1?'Active':'Inactive'}</span></td>
            <td>
                <button class="btn btn-sm" onclick="editMember(${m.id})" style="background:#fff;border:1px solid #c3c4c7;color:#2271b1;border-radius:3px;font-size:11px;padding:3px 8px;">Edit</button>
                <button class="btn btn-sm" onclick="deleteMember(${m.id})" style="background:#fff;border:1px solid #c3c4c7;color:#d63638;border-radius:3px;font-size:11px;padding:3px 8px;">Delete</button>
            </td>
        </tr>`;
    }).join('');
}

async function loadDropdowns() {
    try {
        const r1 = await fetch('<?= SITE_ROOT ?>/api/public/industries.php');
        const d1 = await r1.json();
        if (d1.status==='success') document.getElementById('industry_id').innerHTML = '<option value="">Select</option>' + d1.data.map(i=>`<option value="${i.id}">${i.name}</option>`).join('');
        
        const r2 = await fetch('<?= SITE_ROOT ?>/api/public/provinces.php');
        const d2 = await r2.json();
        if (d2.status==='success') document.getElementById('province_id').innerHTML = '<option value="">Select</option>' + d2.data.map(p=>`<option value="${p.id}">${p.name}</option>`).join('');
    } catch(e) { console.error(e); }
}

function updateStakeholder() {
    const type = document.getElementById('listing_type').value;
    const sel = document.getElementById('stakeholder');
    if (type === 'industry') { sel.value = ''; sel.disabled = true; }
    else { sel.disabled = false; }
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add New Member';
    document.getElementById('memberForm').reset();
    document.getElementById('memberId').value = '';
    document.getElementById('stakeholder').disabled = false;
    document.getElementById('listing_type').value = 'industry';
    updateStakeholder();
    document.getElementById('memberModal').style.display = 'flex';
}

function closeModal() { document.getElementById('memberModal').style.display = 'none'; }

async function editMember(id) {
    try {
        const r = await fetch(`<?= SITE_ROOT ?>/admin/api/members.php?id=${id}`);
        const d = await r.json();
        if (d.status==='success') {
            const m = d.data;
            document.getElementById('modalTitle').textContent = 'Edit Member';
            document.getElementById('memberId').value = m.id;
            document.getElementById('name').value = m.name;
            document.getElementById('industry_id').value = m.industry_id;
            document.getElementById('province_id').value = m.province_id;
            document.getElementById('stakeholder').value = m.stakeholder||'';
            document.getElementById('listing_type').value = m.stakeholder?'partner':'industry';
            document.getElementById('phone').value = m.phone||'';
            document.getElementById('email').value = m.email||'';
            document.getElementById('website').value = m.website||'';
            document.getElementById('description').value = m.description||'';
            document.getElementById('is_active').value = m.is_active;
            updateStakeholder();
            document.getElementById('memberModal').style.display = 'flex';
        }
    } catch(e) { showAlert('Error','error'); }
}

async function deleteMember(id) {
    if (!confirm('Delete this member?')) return;
    try {
        await fetch(`<?= SITE_ROOT ?>/admin/api/members.php?id=${id}`, {method:'DELETE'});
        showAlert('Deleted!','success');
        loadMembers();
    } catch(e) { showAlert('Error','error'); }
}

document.getElementById('memberForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const id = document.getElementById('memberId').value;
    const listingType = document.getElementById('listing_type').value;
    const stakeholder = listingType === 'industry' ? null : document.getElementById('stakeholder').value;
    
    const formData = {
        name: document.getElementById('name').value,
        industry_id: document.getElementById('industry_id').value,
        province_id: document.getElementById('province_id').value,
        stakeholder: stakeholder,
        listing_type: listingType,
        phone: document.getElementById('phone').value||null,
        email: document.getElementById('email').value||null,
        website: document.getElementById('website').value||null,
        description: document.getElementById('description').value||null,
        is_active: document.getElementById('is_active').value
    };
    
    const url = id ? `<?= SITE_ROOT ?>/admin/api/members.php?id=${id}` : '<?= SITE_ROOT ?>/admin/api/members.php';
    const method = id ? 'PUT' : 'POST';
    
    try {
        const r = await fetch(url, {method,headers:{'Content-Type':'application/json'},body:JSON.stringify(formData)});
        const d = await r.json();
        if (d.status==='success') { showAlert(id?'Updated!':'Added!','success'); closeModal(); loadMembers(); }
        else showAlert(d.message||'Error','error');
    } catch(e) { showAlert('Error','error'); }
});

window.onclick = function(e) { if (e.target == document.getElementById('memberModal')) closeModal(); }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>