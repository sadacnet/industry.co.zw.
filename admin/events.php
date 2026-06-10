<?php require_once __DIR__ . '/includes/header.php'; ?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>📅 Events Management</h3>
        <button class="btn btn-primary" onclick="openAddModal()">+ Add New Event</button>
    </div>
    
    <table id="eventsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Poster</th>
                <th>Title</th>
                <th>Organizer</th>
                <th>Date</th>
                <th>Location</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="8">Loading...</td></tr>
        </tbody>
    </table>
</div>

<!-- Event Modal -->
<div class="modal" id="eventModal">
    <div class="modal-content" style="max-width: 650px;">
        <h3 id="modalTitle">Add New Event</h3>
        <form id="eventForm">
            <input type="hidden" id="eventId">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Event Title *</label>
                        <input type="text" id="title" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Organizer *</label>
                        <select id="organizer" class="form-select" required>
                            <option value="CZI">CZI</option>
                            <option value="CIFOZ">CIFOZ</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Event Date *</label>
                        <input type="date" id="event_date" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" id="end_date" class="form-control">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Location</label>
                <input type="text" id="location" class="form-control" placeholder="e.g., HICC Harare">
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea id="description" class="form-control" rows="3" placeholder="Describe the event..."></textarea>
            </div>
            
            <div class="form-group">
                <label>Event Poster / Image</label>
                <div class="input-group">
                    <input type="text" id="poster" class="form-control" placeholder="e.g., uploads/posters/event-image.jpg">
                    <button type="button" class="btn btn-info" onclick="window.open('/industry.co.zw/admin/upload-images.php', '_blank')">
                        <i class="bi bi-cloud-upload"></i> Upload New
                    </button>
                </div>
                <small class="text-muted">Upload an image from the Upload Images page, then paste the path here</small>
                <div id="posterPreview" class="mt-2" style="display:none;">
                    <img id="posterPreviewImg" src="" style="max-height:100px; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Status</label>
                        <select id="is_active" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Event</button>
            </div>
        </form>
    </div>
</div>

<script>
    loadEvents();
    
    // Live preview when poster path changes
    document.getElementById('poster').addEventListener('input', function() {
        const path = this.value.trim();
        const preview = document.getElementById('posterPreview');
        const img = document.getElementById('posterPreviewImg');
        if (path) {
            img.src = '/industry.co.zw/' + path;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    });
    
    // Handle image error
    document.getElementById('posterPreviewImg').addEventListener('error', function() {
        this.style.display = 'none';
        document.getElementById('posterPreview').innerHTML += '<small class="text-danger">Image not found at this path</small>';
    });
    
    async function loadEvents() {
        try {
            const response = await fetch('/industry.co.zw/admin/api/events.php');
            const data = await response.json();
            
            if (data.status === 'success') {
                const tbody = document.querySelector('#eventsTable tbody');
                
                if (data.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="8">No events found</td></tr>';
                    return;
                }
                
                tbody.innerHTML = data.data.map(event => `
                    <tr>
                        <td>${event.id}</td>
                        <td>
                            ${event.poster ? 
                                `<img src="/industry.co.zw/${event.poster}" style="max-height:40px; max-width:60px; border-radius:4px; cursor:pointer;" onclick="window.open('/industry.co.zw/${event.poster}','_blank')" onerror="this.style.display='none'">` : 
                                '<span class="badge bg-secondary">No Image</span>'}
                        </td>
                        <td><strong>${event.title}</strong></td>
                        <td><span class="badge badge-${event.organizer.toLowerCase()}">${event.organizer}</span></td>
                        <td>${formatDate(event.event_date)}</td>
                        <td>${event.location || 'N/A'}</td>
                        <td>
                            <span class="badge ${event.is_active == 1 ? 'badge-active' : 'badge-inactive'}">
                                ${event.is_active == 1 ? 'Active' : 'Inactive'}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm" onclick="editEvent(${event.id})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteEvent(${event.id})">Delete</button>
                        </td>
                    </tr>
                `).join('');
            }
        } catch (error) {
            showAlert('Error loading events', 'error');
        }
    }
    
    function openAddModal() {
        document.getElementById('modalTitle').textContent = 'Add New Event';
        document.getElementById('eventForm').reset();
        document.getElementById('eventId').value = '';
        document.getElementById('posterPreview').style.display = 'none';
        document.getElementById('eventModal').style.display = 'block';
    }
    
    function closeModal() {
        document.getElementById('eventModal').style.display = 'none';
    }
    
    async function editEvent(id) {
        try {
            const response = await fetch('/industry.co.zw/admin/api/events.php');
            const data = await response.json();
            const event = data.data.find(e => e.id == id);
            
            if (event) {
                document.getElementById('modalTitle').textContent = 'Edit Event';
                document.getElementById('eventId').value = event.id;
                document.getElementById('title').value = event.title;
                document.getElementById('organizer').value = event.organizer;
                document.getElementById('event_date').value = event.event_date;
                document.getElementById('end_date').value = event.end_date || '';
                document.getElementById('location').value = event.location || '';
                document.getElementById('description').value = event.description || '';
                document.getElementById('poster').value = event.poster || '';
                document.getElementById('is_active').value = event.is_active;
                
                // Show preview if poster exists
                if (event.poster) {
                    document.getElementById('posterPreviewImg').src = '/industry.co.zw/' + event.poster;
                    document.getElementById('posterPreview').style.display = 'block';
                } else {
                    document.getElementById('posterPreview').style.display = 'none';
                }
                
                document.getElementById('eventModal').style.display = 'block';
            }
        } catch (error) {
            showAlert('Error loading event', 'error');
        }
    }
    
    async function deleteEvent(id) {
        if (!confirmDelete()) return;
        
        try {
            const response = await fetch(`/industry.co.zw/admin/api/events.php?id=${id}`, {
                method: 'DELETE'
            });
            const data = await response.json();
            
            if (data.status === 'success') {
                showAlert('Event deleted successfully');
                loadEvents();
            }
        } catch (error) {
            showAlert('Error deleting event', 'error');
        }
    }
    
    document.getElementById('eventForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const eventId = document.getElementById('eventId').value;
        const formData = {
            title: document.getElementById('title').value,
            organizer: document.getElementById('organizer').value,
            event_date: document.getElementById('event_date').value,
            end_date: document.getElementById('end_date').value || null,
            location: document.getElementById('location').value || null,
            description: document.getElementById('description').value || null,
            poster: document.getElementById('poster').value || null,
            is_active: document.getElementById('is_active').value
        };
        
        const url = eventId ? 
            `/industry.co.zw/admin/api/events.php?id=${eventId}` : 
            '/industry.co.zw/admin/api/events.php';
        
        const method = eventId ? 'PUT' : 'POST';
        
        try {
            const response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (data.status === 'success') {
                showAlert(eventId ? 'Event updated successfully' : 'Event added successfully');
                closeModal();
                loadEvents();
            } else {
                showAlert('Operation failed', 'error');
            }
        } catch (error) {
            showAlert('Error saving event', 'error');
        }
    });
    
    window.onclick = function(event) {
        if (event.target == document.getElementById('eventModal')) {
            closeModal();
        }
    }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>