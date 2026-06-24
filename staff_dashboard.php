<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ['staff', 'admin', 'super admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masisso - Restaurant Staff Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <h1 class="slide-in-text" style="margin: 0; font-size: 24px; color: var(--primary-orange);">Massiso Restaurant Dashboard</h1>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="staff_profile.php" class="add-btn" style="text-decoration: none; text-align: center;">Staff Profile</a>
            <a href="logout.php" class="add-btn" style="text-decoration: none; text-align: center; ">Logout</a>
        </div>
    </div>

    <div class="main-content" style="max-width: 1000px; margin: 0 auto;">
        <div class="dashboard-cards margin-top">
            <div class="dash-card" style="text-align: left; background: var(--light-orange); border-color: var(--primary-orange); cursor: default;">
                <h2 style="color: var(--primary-orange); margin-top: 0;">Hello, Massiso Staff!</h2>
                <p style="color: var(--text-dark); margin-bottom: 0;">Welcome to your operational dashboard. Manage your Sarawak Laksa menu options or track customer orders below.</p>
            </div>
        </div>

        <h2 class="section-title">Operational Hub</h2>
        <div class="dashboard-cards">
            <div class="dash-card" onclick="window.location.href='manage_food_items.php'">
                <h3 style="color: var(--text-dark);">Menu Items Catalog</h3>
                <p class="menu-desc" style="margin-bottom: 15px;">Add new Sarawak Laksa variations, edit prices, or update catalog inventory records.</p>
                <a href="manage_food_items.php" class="add-btn" style="width:100% ;">Manage Menu</a>
            </div>

            <div class="dash-card" onclick="window.location.href='view_orders.php'">
                <h3 style="color: var(--text-dark);">Customer Orders</h3>
                <p class="menu-desc" style="margin-bottom: 15px;">Track incoming kitchen tickets and update fulfillment statuses in real-time.</p>
                <a href="view_orders.php" class="add-btn" style="width: 100%; text-decoration: none; text-align: center;">View Orders</a>
            </div>
        </div>
    </div>
</body>
</html>
