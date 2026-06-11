<?php require_once __DIR__ . '/includes/header.php'; ?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>📄 Tenders Management</h3>
        <button class="btn btn-primary" onclick="openAddModal()">+ Add New Tender</button>
    </div>
    
    <table id="tendersTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tender #</th>
                <th>Title</th>
                <th>Organization</th>
                <th>Category</th>
                <th>Closing Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody><tr><td colspan="8">Loading...</td></tr></tbody>
    </table>
</div>

<!-- Tender Modal -->
<div class="modal" id="tenderModal">
    <div class="modal-content" style="max-width:800px;">
        <h3 id="modalTitle">Add New Tender</h3>
        <form id="tenderForm">
            <input type="hidden" id="tenderId">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group"><label>Tender Title *</label><input type="text" id="title" class="form-control" required></div>
                </div>
                <div class="col-md-4">
                    <div class="form-group"><label>Tender Number</label><input type="text" id="tender_number" class="form-control" placeholder="e.g., ZIM/2026/001"></div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group"><label>Issuing Organization *</label><input type="text" id="issuing_organization" class="form-control" placeholder="e.g., Ministry of Mines"></div>
                </div>
                <div class="col-md-3">
                    <div class="form-group"><label>Category</label>
                        <select id="category" class="form-select">
                            <option value="">Select</option>
                            <option value="Construction">Construction</option>
                            <option value="Mining">Mining</option>
                            <option value="Supply">Supply</option>
                            <option value="IT">IT & Technology</option>
                            <option value="Agriculture">Agriculture</option>
                            <option value="Consultancy">Consultancy</option>
                            <option value="Infrastructure">Infrastructure</option>
                            <option value="Healthcare">Healthcare</option>
                            <option value="Education">Education</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group"><label>Budget (USD)</label><input type="number" id="budget" class="form-control" step="0.01" placeholder="Optional"></div>
                </div>
            </div>
            
            <div class="form-group"><label>Description</label><textarea id="description" class="form-control" rows="3"></textarea></div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group"><label>Location</label><input type="text" id="location" class="form-control" placeholder="e.g., Harare"></div>
                </div>
                <div class="col-md-6">
                    <div class="form-group"><label>Contact Email</label><input type="email" id="contact_email" class="form-control"></div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group"><label>Contact Phone</label><input type="text" id="contact_phone" class="form-control"></div>
                </div>
                <div class="col-md-3">
                    <div class="form-group"><label>Closing Date *</label><input type="date" id="closing_date" class="form-control" required></div>
                </div>
                <div class="col-md-3">
                    <div class="form-group"><label>Bid Opening Date</label><input type="date" id="bid_opening_date" class="form-control"></div>
                </div>
            </div>
            
            <div class="form-group"><label>Submission Requirements</label><textarea id="submission_requirements" class="form-control" rows="2" placeholder="e.g., Company profile, tax clearance, references..."></textarea></div>
            <div class="form-group"><label>Eligibility Criteria</label><textarea id="eligibility_criteria" class="form-control" rows="2" placeholder="e.g., Registered with relevant body, minimum 5 years experience..."></textarea></div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group"><label>Document 1 URL</label><input type="text" id="document_url" class="form-control" placeholder="uploads/documents/..."></div>
                </div>
                <div class="col-md-4">
                    <div class="form-group"><label>Document 2 URL</label><input type="text" id="document_url2" class="form-control"></div>
                </div>
                <div class="col-md-4">
                    <div class="form-group"><label>Document 3 URL</label><input type="text" id="document_url3" class="form-control"></div>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="form-group"><label>Status</label><select id="is_active" class="form-select"><option value="1">Active</option><option value="0">Inactive</option></select></div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Tender</button>
            </div>
        </form>
    </div>
</div>

<script>
loadTenders();

async function loadTenders() {
    const res = await fetch('<?= SITE_ROOT ?>/admin/api/tenders.php');
    const data = await res.json();
    if (data.status === 'success') {
        const tbody = document.querySelector('#tendersTable tbody');
        if (data.data.length === 0) { tbody.innerHTML = '<tr><td colspan="8">No tenders</td></tr>'; return; }
        tbody.innerHTML = data.data.map(t => {
            const isExpired = new Date(t.closing_date) < new Date();
            return `
            <tr>
                <td>${t.id}</td>
                <td>${t.tender_number || '-'}</td>
                <td><strong>${t.title}</strong></td>
                <td>${t.issuing_organization || '-'}</td>
                <td><span class="badge bg-info">${t.category || 'General'}</span></td>
                <td>${new Date(t.closing_date).toLocaleDateString('en-ZA')}</td>
                <td><span class="badge ${isExpired ? 'badge-inactive' : 'badge-active'}">${isExpired ? 'Expired' : 'Active'}</span></td>
                <td>
                    <button class="btn btn-info btn-sm" onclick="editTender(${t.id})">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteTender(${t.id})">Delete</button>
                </td>
            </tr>`;
        }).join('');
    }
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add New Tender';
    document.getElementById('tenderForm').reset();
    document.getElementById('tenderId').value = '';
    document.getElementById('is_active').value = '1';
    document.getElementById('tenderModal').style.display = 'block';
}

function closeModal() { document.getElementById('tenderModal').style.display = 'none'; }

async function editTender(id) {
    const res = await fetch('<?= SITE_ROOT ?>/admin/api/tenders.php');
    const data = await res.json();
    const t = data.data.find(x => x.id == id);
    if (!t) return;
    document.getElementById('modalTitle').textContent = 'Edit Tender';
    document.getElementById('tenderId').value = t.id;
    document.getElementById('title').value = t.title || '';
    document.getElementById('tender_number').value = t.tender_number || '';
    document.getElementById('description').value = t.description || '';
    document.getElementById('issuing_organization').value = t.issuing_organization || '';
    document.getElementById('category').value = t.category || '';
    document.getElementById('budget').value = t.budget || '';
    document.getElementById('location').value = t.location || '';
    document.getElementById('contact_email').value = t.contact_email || '';
    document.getElementById('contact_phone').value = t.contact_phone || '';
    document.getElementById('submission_requirements').value = t.submission_requirements || '';
    document.getElementById('eligibility_criteria').value = t.eligibility_criteria || '';
    document.getElementById('closing_date').value = t.closing_date || '';
    document.getElementById('bid_opening_date').value = t.bid_opening_date || '';
    document.getElementById('document_url').value = t.document_url || '';
    document.getElementById('document_url2').value = t.document_url2 || '';
    document.getElementById('document_url3').value = t.document_url3 || '';
    document.getElementById('is_active').value = t.is_active;
    document.getElementById('tenderModal').style.display = 'block';
}

async function deleteTender(id) {
    if (!confirm('Delete this tender?')) return;
    await fetch(`<?= SITE_ROOT ?>/admin/api/tenders.php?id=${id}`, {method:'DELETE'});
    showAlert('Tender deleted','success');
    loadTenders();
}

document.getElementById('tenderForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const id = document.getElementById('tenderId').value;
    const formData = {
        title: document.getElementById('title').value,
        tender_number: document.getElementById('tender_number').value,
        description: document.getElementById('description').value,
        issuing_organization: document.getElementById('issuing_organization').value,
        category: document.getElementById('category').value,
        budget: document.getElementById('budget').value || null,
        location: document.getElementById('location').value,
        contact_email: document.getElementById('contact_email').value,
        contact_phone: document.getElementById('contact_phone').value,
        submission_requirements: document.getElementById('submission_requirements').value,
        eligibility_criteria: document.getElementById('eligibility_criteria').value,
        closing_date: document.getElementById('closing_date').value,
        bid_opening_date: document.getElementById('bid_opening_date').value || null,
        document_url: document.getElementById('document_url').value,
        document_url2: document.getElementById('document_url2').value,
        document_url3: document.getElementById('document_url3').value,
        is_active: document.getElementById('is_active').value
    };
    
    const url = id ? `<?= SITE_ROOT ?>/admin/api/tenders.php?id=${id}` : '<?= SITE_ROOT ?>/admin/api/tenders.php';
    const method = id ? 'PUT' : 'POST';
    const res = await fetch(url, {method, headers:{'Content-Type':'application/json'}, body:JSON.stringify(formData)});
    const data = await res.json();
    if (data.status === 'success') { showAlert(id?'Updated!':'Created!','success'); closeModal(); loadTenders(); }
});

window.onclick = function(e) { if (e.target == document.getElementById('tenderModal')) closeModal(); }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>