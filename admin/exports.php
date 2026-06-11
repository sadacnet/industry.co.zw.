<?php require_once __DIR__ . '/includes/header.php'; ?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>📦 Products Management</h3>
        <button class="btn btn-primary" onclick="openAddModal()">+ Add New Product</button>
    </div>
    
    <table id="productsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Product</th>
                <th>Category</th>
                <th>Price</th>
                <th>Company</th>
                <th>Rating</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody><tr><td colspan="9">Loading...</td></tr></tbody>
    </table>
</div>

<div class="modal" id="productModal">
    <div class="modal-content" style="max-width:700px;">
        <h3 id="modalTitle">Add New Product</h3>
        <form id="productForm">
            <input type="hidden" id="productId">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group"><label>Product Name *</label><input type="text" id="productName" class="form-control" required></div>
                </div>
                <div class="col-md-6">
                    <div class="form-group"><label>Category *</label>
                        <select id="productCategory" class="form-select" required>
                            <option value="">Select</option>
                            <option value="Agriculture">Agriculture</option><option value="Minerals">Minerals</option>
                            <option value="Manufacturing">Manufacturing</option><option value="Textiles">Textiles</option>
                            <option value="Horticulture">Horticulture</option><option value="General">General</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6"><div class="form-group"><label>Specs</label><input type="text" id="productSpecs" class="form-control" placeholder="e.g., Grade A, 200kg Bales"></div></div>
                <div class="col-md-3"><div class="form-group"><label>Price (USD)</label><input type="number" id="productPrice" class="form-control" step="0.01" value="0"></div></div>
                <div class="col-md-3"><div class="form-group"><label>MOQ</label><input type="number" id="productMoq" class="form-control" value="1"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Company</label><input type="text" id="productCompany" class="form-control"></div></div>
                <div class="col-md-3"><div class="form-group"><label>Rating (0-5)</label><input type="number" id="productRating" class="form-control" step="0.1" min="0" max="5" value="0"></div></div>
                <div class="col-md-3"><div class="form-group"><label>Reviews</label><input type="number" id="productReviews" class="form-control" value="0"></div></div>
                <div class="col-md-12"><div class="form-group"><label>Description</label><textarea id="productDescription" class="form-control" rows="2"></textarea></div></div>
                <div class="col-md-6"><div class="form-group"><label>Exports To (comma separated)</label><input type="text" id="productExportsTo" class="form-control" placeholder="South Africa,China,UAE"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Certifications (comma separated)</label><input type="text" id="productCertifications" class="form-control" placeholder="iso,gst,trustseal"></div></div>
                <div class="col-md-8"><div class="form-group"><label>Image Path</label><input type="text" id="productImage" class="form-control" placeholder="uploads/gallery/image.jpg"></div></div>
                <div class="col-md-4">
                    <div class="form-group"><label>Verified</label><select id="productVerified" class="form-select"><option value="1">Yes</option><option value="0">No</option></select></div>
                    <div class="form-group"><label>Status</label><select id="productStatus" class="form-select"><option value="1">Active</option><option value="0">Inactive</option></select></div>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Product</button>
            </div>
        </form>
    </div>
</div>

<script>
loadProducts();

async function loadProducts() {
    try {
        const res = await fetch('<?= SITE_ROOT ?>/admin/api/exports.php');
        const data = await res.json();
        if (data.status === 'success') {
            const tbody = document.querySelector('#productsTable tbody');
            if (data.data.length === 0) { tbody.innerHTML = '<tr><td colspan="9">No products</td></tr>'; return; }
            tbody.innerHTML = data.data.map(p => `
                <tr>
                    <td>${p.id}</td>
                    <td>${p.image ? `<img src="<?= SITE_ROOT ?>/${p.image}" style="max-height:35px;">` : '-'}</td>
                    <td><strong>${p.product_name}</strong><br><small>${p.specs||''}</small></td>
                    <td><span class="badge bg-success">${p.category||'General'}</span></td>
                    <td>$${parseFloat(p.price).toLocaleString()}</td>
                    <td>${p.company||'-'}</td>
                    <td>${'★'.repeat(Math.floor(p.rating))} ${p.rating} (${p.reviews})</td>
                    <td><span class="badge ${p.is_active==1?'badge-active':'badge-inactive'}">${p.is_active==1?'Active':'Inactive'}</span></td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="editProduct(${p.id})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteProduct(${p.id})">Delete</button>
                    </td>
                </tr>`).join('');
        }
    } catch(e) { showAlert('Error loading products','error'); }
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add New Product';
    document.getElementById('productForm').reset();
    document.getElementById('productId').value = '';
    document.getElementById('productModal').style.display = 'block';
}
function closeModal() { document.getElementById('productModal').style.display = 'none'; }

async function editProduct(id) {
    const res = await fetch('<?= SITE_ROOT ?>/admin/api/exports.php');
    const data = await res.json();
    const p = data.data.find(x => x.id == id);
    if (!p) return;
    document.getElementById('modalTitle').textContent = 'Edit Product';
    document.getElementById('productId').value = p.id;
    document.getElementById('productName').value = p.product_name;
    document.getElementById('productCategory').value = p.category||'';
    document.getElementById('productSpecs').value = p.specs||'';
    document.getElementById('productPrice').value = p.price||0;
    document.getElementById('productMoq').value = p.moq||1;
    document.getElementById('productCompany').value = p.company||'';
    document.getElementById('productRating').value = p.rating||0;
    document.getElementById('productReviews').value = p.reviews||0;
    document.getElementById('productDescription').value = p.description||'';
    document.getElementById('productExportsTo').value = Array.isArray(p.exports_to) ? p.exports_to.join(',') : (p.exports_to||'');
    document.getElementById('productCertifications').value = Array.isArray(p.certifications) ? p.certifications.join(',') : (p.certifications||'');
    document.getElementById('productImage').value = p.image||'';
    document.getElementById('productVerified').value = p.verified?1:0;
    document.getElementById('productStatus').value = p.is_active;
    document.getElementById('productModal').style.display = 'block';
}

async function deleteProduct(id) {
    if (!confirm('Delete this product?')) return;
    await fetch(`<?= SITE_ROOT ?>/admin/api/exports.php?id=${id}`, {method:'DELETE'});
    showAlert('Product deleted','success');
    loadProducts();
}

document.getElementById('productForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const id = document.getElementById('productId').value;
    const formData = {
        product_name: document.getElementById('productName').value,
        category: document.getElementById('productCategory').value,
        specs: document.getElementById('productSpecs').value,
        price: document.getElementById('productPrice').value,
        moq: document.getElementById('productMoq').value,
        company: document.getElementById('productCompany').value,
        rating: document.getElementById('productRating').value,
        reviews: document.getElementById('productReviews').value,
        description: document.getElementById('productDescription').value,
        exports_to: document.getElementById('productExportsTo').value,
        certifications: document.getElementById('productCertifications').value,
        image: document.getElementById('productImage').value,
        verified: document.getElementById('productVerified').value,
        is_active: document.getElementById('productStatus').value
    };
    const url = id ? `<?= SITE_ROOT ?>/admin/api/exports.php?id=${id}` : '<?= SITE_ROOT ?>/admin/api/exports.php';
    const method = id ? 'PUT' : 'POST';
    const res = await fetch(url, {method, headers:{'Content-Type':'application/json'}, body:JSON.stringify(formData)});
    const data = await res.json();
    if (data.status === 'success') { showAlert(id?'Updated!':'Created!','success'); closeModal(); loadProducts(); }
    else showAlert(data.message,'error');
});

window.onclick = function(e) { if (e.target == document.getElementById('productModal')) closeModal(); }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>