<?php
$pageTitle = "Events - industry.co.zw";
$pageDescription = "Upcoming industry events, conferences, and networking opportunities in Zimbabwe";
require_once __DIR__ . '/includes/head.php';
?>
<style>
    body.index-page { padding-top: 85px; }
    
    /* BREADCRUMB */
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

    /* Page Title */
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
        max-width: 600px;
        margin: 0 auto;
    }

    /* Filter Bar */
    .filter-bar {
        background: #fff;
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
    .filter-bar label {
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        color: #666;
        margin-bottom: 6px;
        letter-spacing: 0.5px;
    }
    .filter-bar .form-select, .filter-bar .form-control {
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        padding: 10px 12px;
        font-size: 14px;
        background-color: #fafafa;
    }
    .filter-bar .form-select:focus, .filter-bar .form-control:focus {
        border-color: #2e7d32;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(46,125,50,0.1);
    }
    .btn-outline-success {
        border-color: #2e7d32;
        color: #2e7d32;
        border-radius: 10px;
        padding: 10px;
    }
    .btn-outline-success:hover {
        background: #2e7d32;
        border-color: #2e7d32;
        color: #fff;
    }

    /* Stats Bar */
    .stats-bar {
        background: #fff;
        padding: 12px 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        border: 1px solid #f0f0f0;
    }
    .results-count {
        font-size: 14px;
        color: #555;
    }
    .results-count strong {
        color: #2e7d32;
        font-size: 18px;
        font-weight: 700;
    }

    /* ========== TIMELINE CARD DESIGN ========== */
    .events-timeline {
        position: relative;
        padding-left: 30px;
    }
    .events-timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #2e7d32, #a5d6a7, #e8f5e9);
    }
    
    .event-item {
        position: relative;
        margin-bottom: 30px;
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: row;
    }
    .event-item:hover {
        transform: translateX(8px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    /* Timeline dot */
    .event-item::before {
        content: '';
        position: absolute;
        left: -26px;
        top: 28px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #2e7d32;
        border: 2px solid #fff;
        box-shadow: 0 0 0 3px rgba(46,125,50,0.2);
        z-index: 2;
    }
    
    /* Date column */
    .event-date-column {
        width: 140px;
        min-width: 140px;
        background: linear-gradient(135deg, #f8f9fa, #fff);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 20px 12px;
        border-right: 1px solid #f0f0f0;
    }
    .event-day {
        font-size: 42px;
        font-weight: 800;
        color: #2e7d32;
        line-height: 1;
        margin-bottom: 4px;
    }
    .event-month {
        font-size: 14px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .event-year {
        font-size: 12px;
        color: #999;
        margin-top: 4px;
    }
    .event-badge {
        margin-top: 12px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
    }
    .event-badge.upcoming { background: #e8f5e9; color: #2e7d32; }
    .event-badge.soon { background: #fff3e0; color: #e65100; }
    .event-badge.past { background: #f5f5f5; color: #999; }
    
    /* Content column */
    .event-content-column {
        flex: 1;
        padding: 20px;
    }
    .event-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 10px;
    }
    .event-title {
        font-size: 18px;
        font-weight: 700;
        color: #1a2c3e;
        margin: 0;
    }
    .event-organizer {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        color: #fff;
    }
    .event-organizer.czi { background: linear-gradient(135deg, #1565C0, #0d47a1); }
    .event-organizer.cifoz { background: linear-gradient(135deg, #28256a, #1a1845); }
    
    .event-location {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #666;
        margin-bottom: 10px;
    }
    .event-description {
        font-size: 13px;
        color: #777;
        line-height: 1.5;
        margin-bottom: 15px;
    }
    .event-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 8px;
    }
    .btn-details {
        background: transparent;
        border: 1px solid #2e7d32;
        color: #2e7d32;
        padding: 6px 18px;
        border-radius: 25px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-details:hover {
        background: #2e7d32;
        color: #fff;
    }
    
    /* Image preview - clickable for flyer only */
    .event-image-preview {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid #eee;
    }
    .event-image-preview:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .no-image-preview {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ccc;
        font-size: 28px;
        cursor: not-allowed;
    }

    /* Skeleton loader */
    .skeleton-item {
        background: #fff;
        border-radius: 16px;
        padding: 0;
        margin-bottom: 30px;
        display: flex;
        flex-direction: row;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .skeleton-date {
        width: 140px;
        padding: 20px;
        background: #f8f9fa;
    }
    .skeleton-date div {
        height: 50px;
        background: linear-gradient(90deg, #e0e0e0 25%, #f0f0f0 50%, #e0e0e0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 8px;
    }
    .skeleton-content {
        flex: 1;
        padding: 20px;
    }
    .skeleton-title {
        height: 24px;
        width: 60%;
        background: linear-gradient(90deg, #e0e0e0 25%, #f0f0f0 50%, #e0e0e0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 4px;
        margin-bottom: 12px;
    }
    .skeleton-text {
        height: 14px;
        width: 100%;
        background: linear-gradient(90deg, #e0e0e0 25%, #f0f0f0 50%, #e0e0e0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 4px;
        margin-bottom: 8px;
    }
    .skeleton-text.short {
        width: 40%;
    }
    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* FLYER MODAL - Image only */
    .flyer-modal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.9);
        z-index: 99999;
        justify-content: center;
        align-items: center;
    }
    .flyer-modal.show {
        display: flex;
    }
    .flyer-modal .flyer-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
    }
    .flyer-modal .flyer-image {
        max-width: 100%;
        max-height: 90vh;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }
    .flyer-modal .close-flyer {
        position: absolute;
        top: -40px;
        right: 0;
        color: #fff;
        font-size: 32px;
        cursor: pointer;
        background: rgba(0,0,0,0.5);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .flyer-modal .close-flyer:hover {
        background: rgba(0,0,0,0.8);
        transform: scale(1.1);
    }

    /* DETAILS MODAL - Text only (no image) */
    .details-modal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.7);
        z-index: 99998;
        justify-content: center;
        align-items: center;
    }
    .details-modal.show {
        display: flex;
    }
    .details-modal .details-content {
        position: relative;
        max-width: 550px;
        width: 90%;
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        animation: popIn 0.25s ease;
    }
    .details-modal .close-details {
        position: absolute;
        top: 12px;
        right: 15px;
        color: #999;
        font-size: 22px;
        cursor: pointer;
        background: transparent;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        z-index: 10;
    }
    .details-modal .close-details:hover {
        background: #f5f5f5;
        color: #333;
    }
    .details-modal .details-info {
        padding: 24px;
    }
    .details-modal .details-info h4 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 12px;
        color: #1a2c3e;
        padding-right: 24px;
    }
    .details-modal .details-info p {
        font-size: 14px;
        color: #555;
        margin-bottom: 10px;
        line-height: 1.5;
    }
    .details-modal .details-info hr {
        margin: 16px 0;
        border-color: #eee;
    }
    @keyframes popIn {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    @media (max-width: 768px) {
        body.index-page { padding-top: 70px; }
        .page-title { padding: 30px 0 20px; }
        .page-title h1 { font-size: 24px; }
        .event-item { flex-direction: column; }
        .event-date-column {
            width: 100%;
            flex-direction: row;
            justify-content: space-between;
            padding: 15px 20px;
            border-right: none;
            border-bottom: 1px solid #f0f0f0;
        }
        .event-date-column .event-day { font-size: 28px; }
        .event-item::before { left: 18px; top: 35px; }
        .events-timeline { padding-left: 20px; }
        .events-timeline::before { left: 8px; }
        .flyer-modal .close-flyer { top: -35px; right: 0; font-size: 28px; }
    }
</style>
</head>

<body class="index-page">

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<main class="main">

    <!-- BREADCRUMB -->
    <div class="breadcrumb-wrapper">
        <ul class="breadcrumb">
            <li><a href="index.php"><i class="bi bi-house-door"></i> Home</a></li>
            <li><span class="current">Events</span></li>
        </ul>
    </div>

    <!-- PAGE TITLE -->
    <section class="page-title">
        <div class="container">
            <h1>Industry Events</h1>
            <p>Discover upcoming conferences, workshops, and networking opportunities in Zimbabwe</p>
        </div>
    </section>

    <section style="padding: 40px 0 60px; background: #f5f5f5;">
        <div class="container">

            <!-- FILTER BAR -->
            <div class="filter-bar">
                <div class="row align-items-end g-3">
                    <div class="col-md-3">
                        <label>Organizer</label>
                        <select class="form-select" id="organizerFilter">
                            <option value="all">All Events</option>
                            <option value="CZI">CZI Events</option>
                            <option value="CIFOZ">CIFOZ Events</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="all">All Events</option>
                            <option value="upcoming" selected>Upcoming</option>
                            <option value="past">Past Events</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Search</label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search events..." autocomplete="off">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-success w-100" id="resetBtn">
                            <i class="bi bi-arrow-repeat"></i> Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- STATS BAR -->
            <div class="stats-bar">
                <div class="results-count"><i class="bi bi-calendar3"></i> <strong id="resultCount">0</strong> events found</div>
                <div class="results-count" id="upcomingCount"></div>
            </div>

            <!-- TIMELINE EVENTS -->
            <div class="events-timeline" id="eventsContainer">
                <!-- Skeleton loaders -->
                <div class="skeleton-item"><div class="skeleton-date"><div></div></div><div class="skeleton-content"><div class="skeleton-title"></div><div class="skeleton-text"></div><div class="skeleton-text short"></div></div></div>
                <div class="skeleton-item"><div class="skeleton-date"><div></div></div><div class="skeleton-content"><div class="skeleton-title"></div><div class="skeleton-text"></div><div class="skeleton-text short"></div></div></div>
                <div class="skeleton-item"><div class="skeleton-date"><div></div></div><div class="skeleton-content"><div class="skeleton-title"></div><div class="skeleton-text"></div><div class="skeleton-text short"></div></div></div>
            </div>

        </div>
    </section>

</main>

<!-- FLYER MODAL - Image Only -->
<div class="flyer-modal" id="flyerModal">
    <div class="flyer-content">
        <span class="close-flyer" onclick="closeFlyer()">&times;</span>
        <img id="flyerImage" src="" alt="Event Flyer" class="flyer-image">
    </div>
</div>

<!-- DETAILS MODAL - Text Only (No Image) -->
<div class="details-modal" id="detailsModal">
    <div class="details-content">
        <span class="close-details" onclick="closeDetails()">&times;</span>
        <div class="details-info" id="detailsInfo"></div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    let allEvents = [];
    let isLoading = true;

    const container = document.getElementById('eventsContainer');
    const organizerFilter = document.getElementById('organizerFilter');
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchInput');
    const resetBtn = document.getElementById('resetBtn');
    const resultCountSpan = document.getElementById('resultCount');
    const upcomingCountSpan = document.getElementById('upcomingCount');

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func(...args), wait);
        };
    }

    async function fetchEvents() {
        try {
            const response = await fetch('<?= SITE_ROOT ?>/api/public/events.php');
            const data = await response.json();
            allEvents = (data.status === 'success' && data.data.length > 0) ? data.data : [];
        } catch (error) {
            console.error('Error:', error);
            allEvents = [];
        }
        isLoading = false;
        applyFilters();
    }

    function applyFilters() {
        if (isLoading) return;
        
        const organizer = organizerFilter.value;
        const status = statusFilter.value;
        const search = searchInput.value.toLowerCase();

        const filtered = allEvents.filter(event => {
            if (organizer !== 'all' && event.organizer !== organizer) return false;
            if (status === 'upcoming' && !event.is_upcoming) return false;
            if (status === 'past' && event.is_upcoming) return false;
            if (search && !event.title.toLowerCase().includes(search) && 
                !(event.location || '').toLowerCase().includes(search) &&
                !(event.description || '').toLowerCase().includes(search)) return false;
            return true;
        });

        displayEvents(filtered);
        updateStats(filtered);
    }

    function updateStats(filtered) {
        resultCountSpan.textContent = filtered.length;
        const upcoming = filtered.filter(e => e.is_upcoming).length;
        upcomingCountSpan.innerHTML = upcoming > 0 ? `<i class="bi bi-calendar-check"></i> ${upcoming} upcoming events` : '';
    }

    function displayEvents(events) {
        if (events.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5" style="background:#fff; border-radius:16px; padding:40px;">
                    <i class="bi bi-calendar-x" style="font-size:48px;color:#ccc;"></i>
                    <h4 class="mt-3" style="color:#666;">No events found</h4>
                    <button class="btn btn-success mt-3" onclick="resetFilters()" style="background:#2e7d32; border:none; border-radius:30px; padding:8px 24px;">Clear Filters</button>
                </div>`;
            return;
        }

        container.innerHTML = events.map(event => {
            const eventDate = new Date(event.event_date);
            const day = eventDate.getDate();
            const month = eventDate.toLocaleString('default', { month: 'short' }).toUpperCase();
            const year = eventDate.getFullYear();
            const isUpcoming = event.is_upcoming;
            const daysUntil = event.days_until;
            
            let badgeClass = '';
            let badgeText = '';
            if (!isUpcoming) {
                badgeClass = 'past';
                badgeText = 'Past';
            } else if (daysUntil <= 7) {
                badgeClass = 'soon';
                badgeText = '🔥 Closing Soon';
            } else {
                badgeClass = 'upcoming';
                badgeText = 'Upcoming';
            }

            const organizerClass = event.organizer === 'CZI' ? 'czi' : 'cifoz';
            
            // Image preview - opens FLYER modal (image only)
            const hasImage = event.poster ? true : false;
            const imageHtml = hasImage ? 
                `<img src="<?= SITE_ROOT ?>/${event.poster}" alt="${escapeHtml(event.title)}" class="event-image-preview" onclick="event.stopPropagation(); openFlyer('${event.poster}')" loading="lazy">` :
                `<div class="no-image-preview"><i class="bi bi-image"></i></div>`;

            return `
                <div class="event-item">
                    <div class="event-date-column">
                        <div class="event-day">${day}</div>
                        <div class="event-month">${month}</div>
                        <div class="event-year">${year}</div>
                        <div class="event-badge ${badgeClass}">${badgeText}</div>
                    </div>
                    <div class="event-content-column">
                        <div class="event-header">
                            <h3 class="event-title">${escapeHtml(event.title)}</h3>
                            <span class="event-organizer ${organizerClass}">
                                <i class="bi bi-building"></i> ${event.organizer}
                            </span>
                        </div>
                        ${event.location ? `<div class="event-location"><i class="bi bi-geo-alt"></i> ${escapeHtml(event.location)}</div>` : ''}
                        ${event.description ? `<div class="event-description">${escapeHtml(event.description.substring(0, 120))}${event.description.length > 120 ? '...' : ''}</div>` : ''}
                        <div class="event-footer">
                            ${imageHtml}
                            <button class="btn-details" onclick="openDetails(${event.id})">
                                <i class="bi bi-info-circle"></i> View Details
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    // OPEN FLYER MODAL - Image only (no text)
    function openFlyer(posterPath) {
        if (!posterPath) return;
        const flyerImage = document.getElementById('flyerImage');
        flyerImage.src = '<?= SITE_ROOT ?>/' + posterPath;
        document.getElementById('flyerModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeFlyer() {
        document.getElementById('flyerModal').classList.remove('show');
        document.body.style.overflow = '';
        document.getElementById('flyerImage').src = '';
    }

    // OPEN DETAILS MODAL - Text only (no image)
    function openDetails(eventId) {
        const event = allEvents.find(e => e.id == eventId);
        if (!event) return;
        
        const eventDate = new Date(event.event_date);
        const formattedDate = eventDate.toLocaleDateString('en-ZA', {weekday:'long', day:'numeric', month:'long', year:'numeric'});
        const endDate = event.end_date ? new Date(event.end_date).toLocaleDateString('en-ZA', {day:'numeric', month:'long', year:'numeric'}) : null;
        
        document.getElementById('detailsInfo').innerHTML = `
            <h4>${escapeHtml(event.title)}</h4>
            <p><i class="bi bi-building"></i> <strong>Organizer:</strong> ${event.organizer}</p>
            <p><i class="bi bi-calendar3"></i> <strong>Date:</strong> ${formattedDate}</p>
            ${endDate ? `<p><i class="bi bi-calendar-range"></i> <strong>End Date:</strong> ${endDate}</p>` : ''}
            ${event.location ? `<p><i class="bi bi-geo-alt"></i> <strong>Location:</strong> ${escapeHtml(event.location)}</p>` : ''}
            ${event.description ? `<hr><p><strong>Description:</strong><br>${escapeHtml(event.description)}</p>` : ''}
        `;
        
        document.getElementById('detailsModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeDetails() {
        document.getElementById('detailsModal').classList.remove('show');
        document.body.style.overflow = '';
    }

    function resetFilters() {
        organizerFilter.value = 'all';
        statusFilter.value = 'upcoming';
        searchInput.value = '';
        applyFilters();
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

    // Event listeners
    organizerFilter.addEventListener('change', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    searchInput.addEventListener('keyup', debounce(applyFilters, 300));
    resetBtn.addEventListener('click', resetFilters);
    
    // Close modals with ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeFlyer();
            closeDetails();
        }
    });
    
    // Close modals when clicking outside
    document.getElementById('flyerModal').addEventListener('click', function(e) {
        if (e.target === this) closeFlyer();
    });
    document.getElementById('detailsModal').addEventListener('click', function(e) {
        if (e.target === this) closeDetails();
    });

    fetchEvents();
</script>

</body>
</html>