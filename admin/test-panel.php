<?php
require_once __DIR__ . '/../api/config/database.php';
require_once __DIR__ . '/includes/auth-check.php';

// For testing, we'll provide a way to login directly
if (isset($_POST['login'])) {
    session_start();
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_id'] = 1;
    $_SESSION['admin_username'] = 'admin';
    $_SESSION['admin_email'] = 'admin@industry.co.zw';
    header('Location: test-panel.php');
    exit;
}

requireAdminLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin API Test Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .section {
            background: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            border-left: 4px solid #1b5e20;
        }
        button {
            background: #2e7d32;
            color: white;
            border: none;
            padding: 8px 16px;
            margin: 5px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover { background: #1b5e20; }
        button.danger { background: #c62828; }
        button.danger:hover { background: #b71c1c; }
        pre {
            background: #263238;
            color: #aed581;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            max-height: 300px;
            overflow-y: auto;
            font-size: 12px;
        }
        .form-group {
            margin: 10px 0;
        }
        input, textarea, select {
            padding: 8px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 200px;
        }
        textarea { width: 300px; height: 60px; }
        h2 { color: #1b5e20; }
        .success { color: #2e7d32; font-weight: bold; }
        .error { color: #c62828; font-weight: bold; }
    </style>
</head>
<body>
    <h1>🔐 Admin API Test Panel</h1>
    <p>Logged in as: <strong>admin</strong> | <a href="<?= SITE_ROOT ?>/admin/login.php">Go to Dashboard</a></p>

    <!-- Dashboard Stats -->
    <div class="section">
        <h2>📊 Dashboard Statistics</h2>
        <button onclick="testEndpoint('<?= SITE_ROOT ?>/admin/api/dashboard.php', 'dashboardResult')">Load Stats</button>
        <pre id="dashboardResult">Click button to load...</pre>
    </div>

    <!-- Members Management -->
    <div class="section">
        <h2>🏢 Members Management</h2>
        
        <h3>Get All Members</h3>
        <button onclick="testEndpoint('<?= SITE_ROOT ?>/admin/api/members.php', 'allMembersResult')">Load All Members</button>
        <pre id="allMembersResult">Click button to load...</pre>
        
        <h3>Add New Member</h3>
        <div class="form-group">
            <input type="text" id="memberName" placeholder="Company Name" required>
            <select id="memberIndustry">
                <option value="">Select Industry</option>
                <option value="1">Auto</option>
                <option value="3">Agriculture</option>
                <option value="4">Banking & Finance</option>
                <option value="6">Construction</option>
                <option value="11">Mining</option>
                <option value="12">Technology & ICT</option>
            </select>
            <select id="memberProvince">
                <option value="">Select Province</option>
                <option value="1">Harare</option>
                <option value="2">Bulawayo</option>
                <option value="3">Manicaland</option>
            </select>
            <input type="text" id="memberPhone" placeholder="Phone">
            <input type="email" id="memberEmail" placeholder="Email">
            <button onclick="addMember()">Add Member</button>
        </div>
        <pre id="addMemberResult">Result will appear here...</pre>
        
        <h3>Delete Last Member</h3>
        <input type="number" id="deleteMemberId" placeholder="Member ID to delete">
        <button class="danger" onclick="deleteMember()">Delete Member</button>
        <pre id="deleteMemberResult">Result will appear here...</pre>
    </div>

    <!-- Events Management -->
    <div class="section">
        <h2>📅 Events Management</h2>
        
        <button onclick="testEndpoint('<?= SITE_ROOT ?>/admin/api/events.php', 'eventsResult')">Load All Events</button>
        <pre id="eventsResult">Click button to load...</pre>
        
        <h3>Add New Event</h3>
        <div class="form-group">
            <input type="text" id="eventTitle" placeholder="Event Title">
            <select id="eventOrganizer">
                <option value="CZI">CZI</option>
                <option value="CIFOZ">CIFOZ</option>
            </select>
            <input type="date" id="eventDate">
            <input type="text" id="eventLocation" placeholder="Location">
            <button onclick="addEvent()">Add Event</button>
        </div>
        <pre id="addEventResult">Result will appear here...</pre>
    </div>

    <!-- Tenders Management -->
    <div class="section">
        <h2>📄 Tenders Management</h2>
        
        <button onclick="testEndpoint('<?= SITE_ROOT ?>/admin/api/tenders.php', 'tendersResult')">Load All Tenders</button>
        <pre id="tendersResult">Click button to load...</pre>
        
        <h3>Add New Tender</h3>
        <div class="form-group">
            <input type="text" id="tenderTitle" placeholder="Tender Title">
            <input type="date" id="closingDate">
            <textarea id="tenderDescription" placeholder="Description"></textarea>
            <button onclick="addTender()">Add Tender</button>
        </div>
        <pre id="addTenderResult">Result will appear here...</pre>
    </div>

    <script>
        // Helper function to test GET endpoints
        async function testEndpoint(url, resultId) {
            const resultDiv = document.getElementById(resultId);
            resultDiv.innerHTML = 'Loading...';
            
            try {
                const response = await fetch(url);
                const data = await response.json();
                resultDiv.innerHTML = JSON.stringify(data, null, 2);
            } catch (error) {
                resultDiv.innerHTML = '<span class="error">Error: ' + error.message + '</span>';
            }
        }

        // Add Member
        async function addMember() {
            const resultDiv = document.getElementById('addMemberResult');
            
            const data = {
                name: document.getElementById('memberName').value,
                industry_id: document.getElementById('memberIndustry').value,
                province_id: document.getElementById('memberProvince').value,
                phone: document.getElementById('memberPhone').value,
                email: document.getElementById('memberEmail').value
            };
            
            if (!data.name || !data.industry_id || !data.province_id) {
                resultDiv.innerHTML = '<span class="error">Please fill in all required fields</span>';
                return;
            }
            
            resultDiv.innerHTML = 'Adding member...';
            
            try {
                const response = await fetch('<?= SITE_ROOT ?>/admin/api/members.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                resultDiv.innerHTML = JSON.stringify(result, null, 2);
                
                if (result.status === 'success') {
                    document.getElementById('memberName').value = '';
                    document.getElementById('memberPhone').value = '';
                    document.getElementById('memberEmail').value = '';
                }
            } catch (error) {
                resultDiv.innerHTML = '<span class="error">Error: ' + error.message + '</span>';
            }
        }

        // Delete Member
        async function deleteMember() {
            const resultDiv = document.getElementById('deleteMemberResult');
            const memberId = document.getElementById('deleteMemberId').value;
            
            if (!memberId) {
                resultDiv.innerHTML = '<span class="error">Please enter a Member ID</span>';
                return;
            }
            
            resultDiv.innerHTML = 'Deleting member...';
            
            try {
                const response = await fetch(`<?= SITE_ROOT ?>/admin/api/members.php?id=${memberId}`, {
                    method: 'DELETE'
                });
                const result = await response.json();
                resultDiv.innerHTML = JSON.stringify(result, null, 2);
            } catch (error) {
                resultDiv.innerHTML = '<span class="error">Error: ' + error.message + '</span>';
            }
        }

        // Add Event
        async function addEvent() {
            const resultDiv = document.getElementById('addEventResult');
            
            const data = {
                title: document.getElementById('eventTitle').value,
                organizer: document.getElementById('eventOrganizer').value,
                event_date: document.getElementById('eventDate').value,
                location: document.getElementById('eventLocation').value
            };
            
            if (!data.title || !data.event_date) {
                resultDiv.innerHTML = '<span class="error">Title and date are required</span>';
                return;
            }
            
            resultDiv.innerHTML = 'Adding event...';
            
            try {
                const response = await fetch('<?= SITE_ROOT ?>/admin/api/events.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                resultDiv.innerHTML = JSON.stringify(result, null, 2);
            } catch (error) {
                resultDiv.innerHTML = '<span class="error">Error: ' + error.message + '</span>';
            }
        }

        // Add Tender
        async function addTender() {
            const resultDiv = document.getElementById('addTenderResult');
            
            const data = {
                title: document.getElementById('tenderTitle').value,
                closing_date: document.getElementById('closingDate').value,
                description: document.getElementById('tenderDescription').value
            };
            
            if (!data.title || !data.closing_date) {
                resultDiv.innerHTML = '<span class="error">Title and closing date are required</span>';
                return;
            }
            
            resultDiv.innerHTML = 'Adding tender...';
            
            try {
                const response = await fetch('<?= SITE_ROOT ?>/admin/api/tenders.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                resultDiv.innerHTML = JSON.stringify(result, null, 2);
            } catch (error) {
                resultDiv.innerHTML = '<span class="error">Error: ' + error.message + '</span>';
            }
        }
    </script>
</body>
</html>