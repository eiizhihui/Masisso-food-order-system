<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ['admin', 'super admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masisso - Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .main-content {
            margin: 0 auto !important;
        }

        .admin-header {
            background-color: var(--primary-orange);
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .stats-container {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-box {
            flex: 1;
            background: linear-gradient(135deg, #FF9800, #E65100);
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(230, 81, 0, 0.2);
        }

        .stat-box h2 {
            margin: 0;
            font-size: 32px;
        }

        .stat-box p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .list-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 30px;
        }

        .list-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border-right: 5px solid #ccc;
        }

        .list-card.order {
            border-right-color: #2196F3;
        }

        .list-card.event {
            border-right-color: #FF9800;
        }

        .list-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-left: 15px;
            border-left: 1px solid #eee;
            padding-left: 15px;
        }

        .list-actions button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }

        .complete-btn {
            color: #4CAF50;
        }

        .edit-btn {
            color: #2196F3;
        }

        .delete-btn {
            color: #f44336;
        }

        .list-info {
            flex-grow: 1;
        }

        .list-info h4 {
            margin: 0 0 5px 0;
            color: #333;
        }

        .list-info p {
            margin: 2px 0;
            font-size: 14px;
            color: #666;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: bold;
            color: white;
            display: inline-block;
            margin-bottom: 5px;
        }

        .badge.pending {
            background: #ffc107;
            color: white;
        }
        .badge.preparing {
            background: #ff9800;
            color: white;
        }
        .badge.completed {
            background: #4caf50;
            color: white;
        }
    </style>
</head>

<body>

    <div class="admin-header">
        Masisso Admin Panel

        <a href="logout.php" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); color: white; text-decoration: none; font-weight: bold; font-size: 14px; background: rgba(255, 255, 255, 0.2); padding: 6px 15px; border-radius: 20px; display: flex; align-items: center; gap: 8px; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.4)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="main-content">
        <h2 class="slide-in-text" style="color: #333;">Welcome, <?php echo htmlspecialchars($_SESSION['name'] ?? 'Admin'); ?> 👋</h2>
        <p style="color: #666; margin-bottom: 20px;">Manage users, orders, events, menu items, and rewards.</p>

        <div class="nav-search" style="margin-bottom: 20px;">
            <input type="text" id="search-input" placeholder="Global search across all databases..."
                oninput="globalSearch(this.value)" style="width:100%; padding: 10px;">
        </div>

        <div id="search-results" style="display:none; margin-bottom: 30px;">
            <h2 class="section-title">Search Results</h2>
            <div class="list-container" id="search-list"></div>
        </div>

        <div id="dashboard-content">
            <div class="stats-container">
                <div class="stat-box">
                    <h2 id="total-orders">0</h2>
                    <p>Total Orders</p>
                </div>
                <div class="stat-box">
                    <h2 id="total-users">0</h2>
                    <p>Total Users</p>
                    <p id="user-breakdown" style="font-size: 12px; margin-top: 5px; opacity: 0.8;"></p>
                </div>
            </div>

            <h2 class="section-title">Newest 3 Orders</h2>
            <div class="list-container" id="recent-orders"></div>

            <h2 class="section-title">Newest 3 Events</h2>
            <div class="list-container" id="recent-events"></div>
        </div>
    </div>

    <div class="bottom-nav">
        <a href="admin-dashboard.php" class="nav-item-bottom active">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="manage-user.php" class="nav-item-bottom">
            <i class="fas fa-users"></i>
            <span>Users</span>
        </a>
        <a href="manage-order.php" class="nav-item-bottom">
            <i class="fas fa-clipboard-list"></i>
            <span>Orders</span>
        </a>
        <a href="manage-menu.php" class="nav-item-bottom">
            <i class="fas fa-utensils"></i>
            <span>Menu</span>
        </a>
        <a href="manage-event.php" class="nav-item-bottom">
            <i class="fas fa-calendar-alt"></i>
            <span>Events</span>
        </a>
        <a href="manage-reward.php" class="nav-item-bottom">
            <i class="fas fa-gift"></i>
            <span>Rewards</span>
        </a>
        <a href="manage-profile.php" class="nav-item-bottom">
            <i class="fas fa-user-cog"></i>
            <span>Profile</span>
        </a>
    </div>

    <script src="admin.js?v=1"></script>

</body>

</html>
