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
    <title>Manage Orders</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .main-content {
            margin: 0 auto !important;
        }

        .header {
            background-color: var(--primary-orange);
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-left h2 {
            margin: 0;
            font-size: 24px;
        }

        .header a {
            color: white;
            text-decoration: none;
            font-size: 20px;
            margin: 0;
        }

        .list-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .list-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border-left: 5px solid #2196F3;
        }

        .list-card.completed {
            border-left-color: #4CAF50;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .order-header h4 {
            margin: 0;
            color: #333;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }

        .badge.pending {
            background: #FF9800;
        }

        .badge.completed {
            background: #4CAF50;
        }

        .order-details p {
            margin: 5px 0;
            color: #555;
            font-size: 14px;
        }

        .order-actions {
            margin-top: 10px;
            text-align: right;
        }

        .status-btn {
            background: var(--primary-orange);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="header-left">
            <a href="admin-dashboard.php" aria-label="Back to Dashboard"><i class="fas fa-arrow-left"></i></a>
            <h2>Manage Orders</h2>
        </div>
    </div>

    <div class="main-content">
        <div class="nav-search" style="margin-bottom: 20px;">
            <input type="text" id="search-input" placeholder="Search orders..." oninput="searchOrders(this.value)"
                style="width:100%; padding: 10px;">
        </div>
        <div class="list-container" id="order-list">
            <!-- Order items injected by JS -->
        </div>
    </div>

    <div class="bottom-nav">
        <a href="admin-dashboard.php" class="nav-item-bottom">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="manage-user.php" class="nav-item-bottom">
            <i class="fas fa-users"></i>
            <span>Users</span>
        </a>
        <a href="manage-order.php" class="nav-item-bottom active">
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

    <script src="admin.js"></script>

</body>

</html>
