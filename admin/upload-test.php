<?php
require_once __DIR__ . '/includes/header.php';
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>📤 Upload Images</h3>
        <a href="dashboard.php" class="btn btn-info">← Back to Dashboard</a>
    </div>
    
    <div id="alertArea"></div>
    
    <div class="row">
        <!-- Upload Form -->
        <div class="col-lg-6">
            <div class="card" style="border: 1px solid #e0e0e0;">
                <div class="card-body">
                    <h5 class="mb-3"><i class="bi bi-cloud-upload"></i> Upload New File</h5>
                    
                    <form id="uploadForm" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label class="fw-bold">Select Image *</label>
                            <input type="file" class="form-control" name="file" id="fileInput" accept="image/*" required>
                            <small class="text-muted">Max size: 10MB | Formats: JPG, PNG, GIF, WebP</small>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="fw-bold">Upload Type *</label>
                            <select class="form-select" name="type" id="uploadType">
                                <option value="gallery">🖼️ Gallery Image</option>
                                <option value="logo">🏢 Company Logo</option>
                                <option value="banner">📢 Banner Ad</option>
                                <option value="poster">📋 Poster</option>
                                <option value="flyer">📄 Flyer</option>
                                <option value="document">📁 Document</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-upload"></i> Upload File
                        </button>
                    </form>
                    
                    <!-- Preview -->
                    <div id="preview" class="text-center mt-3"></div>
                    
                    <!-- Result Message -->
                    <div id="result" class="mt-3"></div>
                </div>
            </div>
        </div>
        
        <!-- Upload Info -->
        <div class="col-lg-6">
            <div class="card" style="border: 1px solid #e0e0e0;">
                <div class="card-body">
                    <h5 class="mb-3"><i class="bi bi-info-circle"></i> Upload Information</h5>
                    
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Folder</th>
                                <th>Allowed Formats</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Gallery Image</td>
                                <td><code>uploads/gallery/</code></td>
                                <td>JPG, PNG, GIF, WebP</td>
                            </tr>
                            <tr>
                                <td>Company Logo</td>
                                <td><code>uploads/logos/</code></td>
                                <td>JPG, PNG, GIF, WebP</td>
                            </tr>
                            <tr>
                                <td>Banner Ad</td>
                                <td><code>uploads/banners/</code></td>
                                <td>JPG, PNG, GIF, WebP</td>
                            </tr>
                            <tr>
                                <td>Poster</td>
                                <td><code>uploads/posters/</code></td>
                                <td>JPG, PNG, WebP</td>
                            </tr>
                            <tr>
                                <td>Flyer</td>
                                <td><code>uploads/flyers/</code></td>
                                <td>PDF, JPG, PNG</td>
                            </tr>
                            <tr>
                                <td>Document</td>
                                <td><code>uploads/documents/</code></td>
                                <td>PDF, DOC, DOCX</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="alert alert-info mt-3 mb-0">
                        <small>
                            <i class="bi bi-lightbulb"></i> 
                            <strong>Tip:</strong> After uploading, copy the file path to use in product listings, company profiles, or advertisements.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Uploaded Files Gallery -->
<div class="card mt-4">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>🖼️ Uploaded Files</h3>
        <button class="btn btn-info" onclick="loadUploadedFiles()">
            <i class="bi bi-arrow-clockwise"></i> Refresh
        </button>
    </div>
    
    <div id="uploadedFiles" class="row">
        <div class="col-12 text-center py-4">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mt-2">Loading uploaded files...</p>
        </div>
    </div>
</div>

<script>
    // File preview
    document.getElementById('fileInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').innerHTML = 
                    `<p class="text-muted mb-1">Preview:</p>
                     <img src="${e.target.result}" style="max-width:200px; max-height:200px; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1);">`;
            };
            reader.readAsDataURL(file);
        }
    });

    // Upload form submission
    document.getElementById('uploadForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const fileInput = document.getElementById('fileInput');
        const file = fileInput.files[0];
        const type = document.getElementById('uploadType').value;
        const resultDiv = document.getElementById('result');
        
        if (!file) {
            showAlert('Please select a file first', 'error');
            return;
        }
        
        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', type);
        
        resultDiv.innerHTML = '<div class="alert alert-info"><div class="spinner-border spinner-border-sm"></div> Uploading file...</div>';
        
        try {
            const response = await fetch('<?= SITE_ROOT ?>/admin/api/upload.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.status === 'success') {
                resultDiv.innerHTML = `
                    <div class="alert alert-success">
                        <strong><i class="bi bi-check-circle"></i> Upload Successful!</strong><br>
                        <strong>Path:</strong> <code style="background:#e8f5e9; padding:2px 6px; border-radius:3px;">${data.data.file_path}</code><br>
                        <strong>URL:</strong> <code style="background:#e8f5e9; padding:2px 6px; border-radius:3px;">${data.data.full_url}</code><br>
                        <strong>Size:</strong> ${(data.data.file_size / 1024).toFixed(1)} KB
                        <div class="mt-2">
                            <img src="${data.data.full_url}" style="max-width:150px; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
                        </div>
                        <button class="btn btn-sm btn-outline-success mt-2" onclick="copyPath('${data.data.file_path}')">
                            <i class="bi bi-clipboard"></i> Copy Path
                        </button>
                    </div>`;
                
                showAlert('File uploaded successfully!', 'success');
                loadUploadedFiles();
                fileInput.value = '';
                document.getElementById('preview').innerHTML = '';
            } else {
                resultDiv.innerHTML = `<div class="alert alert-danger"><strong>Error:</strong> ${data.message}</div>`;
                showAlert('Upload failed: ' + data.message, 'error');
            }
        } catch (error) {
            resultDiv.innerHTML = `<div class="alert alert-danger"><strong>Connection Error:</strong> ${error.message}</div>`;
            showAlert('Connection error: ' + error.message, 'error');
        }
    });

    // Copy path to clipboard
    function copyPath(path) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(path).then(() => {
                showAlert('Path copied to clipboard!', 'success');
            });
        } else {
            prompt('Copy this path:', path);
        }
    }

    // Load uploaded files
    async function loadUploadedFiles() {
        const container = document.getElementById('uploadedFiles');
        container.innerHTML = `
            <div class="col-12 text-center py-4">
                <div class="spinner-border text-success" role="status"></div>
                <p class="text-muted mt-2">Loading...</p>
            </div>`;
        
        try {
            const response = await fetch('<?= SITE_ROOT ?>/api/public/gallery.php');
            const data = await response.json();
            
            if (data.status === 'success' && data.data.length > 0) {
                container.innerHTML = data.data.map(img => `
                    <div class="col-xl-3 col-md-4 col-6 mb-3">
                        <div class="card h-100">
                            <img src="<?= SITE_ROOT ?>/${img.file_path}"
                                 class="card-img-top" 
                                 style="height:120px; object-fit:cover; cursor:pointer;"
                                 onclick="window.open('<?= SITE_ROOT ?>/${img.file_path}', '_blank')"
                                 onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22><rect fill=%22%23ddd%22 width=%22100%22 height=%22100%22/><text y=%22.9em%22 font-size=%2290%22>🖼️</text></svg>'">
                            <div class="card-body p-2">
                                <small class="text-muted d-block text-truncate" title="${img.title || 'Untitled'}">${img.title || 'Untitled'}</small>
                                <small><code class="text-truncate d-block" style="font-size:10px;" title="${img.file_path}">${img.file_path}</code></small>
                                <button class="btn btn-sm btn-outline-info mt-1 w-100" onclick="copyPath('${img.file_path}')" style="font-size:11px;">
                                    <i class="bi bi-clipboard"></i> Copy
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="col-12 text-center py-4">
                        <i class="bi bi-cloud-upload" style="font-size:48px; color:#ccc;"></i>
                        <h5 class="mt-2">No files uploaded yet</h5>
                        <p class="text-muted">Upload your first image using the form above</p>
                    </div>`;
            }
        } catch (error) {
            container.innerHTML = `
                <div class="col-12 text-center py-4">
                    <p class="text-danger">Could not load files. Please try again.</p>
                </div>`;
        }
    }

    // Load on page load
    loadUploadedFiles();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>