<?php
require_once __DIR__ . '/includes/auth-check.php';
requireAdminLogin();

$currentAdmin = getCurrentAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - industry.co.zw Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        
        .admin-header {
            background: #1b5e20;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-header h1 { font-size: 20px; }
        
        .logout-btn {
            background: #ff5252;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 3px;
            cursor: pointer;
        }
        
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .stat-card .number {
            font-size: 36px;
            font-weight: bold;
            color: #1b5e20;
        }
        
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .menu-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-decoration: none;
            color: #333;
            transition: transform 0.2s;
        }
        
        .menu-card:hover {
            transform: translateY(-5px);
        }
        
        .menu-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .menu-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .menu-desc {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1>🏭 industry.co.zw Admin</h1>
        <div>
            <span style="margin-right: 20px;">Welcome, <?php echo htmlspecialchars($currentAdmin['username']); ?></span>
            <button class="logout-btn" onclick="logout()">Logout</button>
        </div>
    </div>
    
    <div class="container">
        <h2 style="margin-bottom: 20px;">Dashboard</h2>
        
        <div class="stats-grid" id="statsGrid">
            <div class="stat-card">
                <h3>Loading...</h3>
                <div class="number">...</div>
            </div>
        </div>
        
        <h2 style="margin: 30px 0 20px;">Management</h2>
        
        <div class="menu-grid">
            <a href="members.php" class="menu-card">
                <div class="menu-icon">🏢</div>
                <div class="menu-title">Members</div>
                <div class="menu-desc">Manage companies and organizations</div>
            </a>
            
            <a href="events.php" class="menu-card">
                <div class="menu-icon">📅</div>
                <div class="menu-title">Events</div>
                <div class="menu-desc">Manage events and activities</div>
            </a>
            
            <a href="tenders.php" class="menu-card">
                <div class="menu-icon">📄</div>
                <div class="menu-title">Tenders</div>
                <div class="menu-desc">Manage active tenders</div>
            </a>
            
            <a href="#" class="menu-card">
                <div class="menu-icon">📢</div>
                <div class="menu-title">Advertisements</div>
                <div class="menu-desc">Coming in next update</div>
            </a>
        </div>
    </div>

    <script>
        // Load dashboard stats
        async function loadStats() {
            try {
                const response = await fetch('<?= SITE_ROOT ?>/admin/api/dashboard.php');
                const data = await response.json();
                
                if (data.status === 'success') {
                    const stats = data.data;
                    const html = `
                        <div class="stat-card">
                            <h3>Total Companies</h3>
                            <div class="number">${stats.total_companies}</div>
                        </div>
                        <div class="stat-card">
                            <h3>Active Companies</h3>
                            <div class="number">${stats.active_companies}</div>
                        </div>
                        <div class="stat-card">
                            <h3>Upcoming Events</h3>
                            <div class="number">${stats.upcoming_events}</div>
                        </div>
                        <div class="stat-card">
                            <h3>Active Tenders</h3>
                            <div class="number">${stats.active_tenders}</div>
                        </div>
                        <div class="stat-card">
                            <h3>Total Ads</h3>
                            <div class="number">${stats.total_advertisements}</div>
                        </div>
                        <div class="stat-card">
                            <h3>Unread Messages</h3>
                            <div class="number">${stats.unread_messages}</div>
                        </div>
                    `;
                    document.getElementById('statsGrid').innerHTML = html;
                }
            } catch (error) {
                console.error('Failed to load stats:', error);
            }
        }
        
        async function logout() {
            await fetch('<?= SITE_ROOT ?>/admin/api/auth.php', { method: 'DELETE' });
            window.location.href = '<?= SITE_ROOT ?>/admin/login.php';
        }
        
        // Load stats on page load
        loadStats();
    </script>
</body>
</html>