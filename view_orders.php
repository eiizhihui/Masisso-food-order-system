<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ['staff', 'admin', 'super admin'])) {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';

$query = "SELECT o.order_id, o.order_status, o.order_type, o.total_price, o.order_date, c.name AS customer_name FROM orders o LEFT JOIN customer c ON o.user_id = c.user_id ORDER BY o.order_id DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff - View Orders</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .main-content { margin: 0 auto !important; padding: 20px; }
        .header { background-color: var(--primary-orange); color: white; padding: 20px; display: flex; align-items: center; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
        .header h2 { margin: 0; font-size: 24px; margin-left: 15px; }
        .header a { color: white; text-decoration: none; font-size: 20px; }
        
        .order-card-admin { background: white; border-radius: 10px; padding: 15px; margin-bottom: 15px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05); border-left: 5px solid var(--primary-orange); }
        .order-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px; }
        .order-id { font-weight: bold; color: var(--primary-orange); font-size: 16px; }
        .order-date { font-size: 12px; color: #888; }
        
        .order-details { margin-bottom: 15px; font-size: 14px; color: #444; }
        .order-details p { margin: 5px 0; }
        
        .update-section { display: flex; gap: 10px; align-items: center; background: #f9f9f9; padding: 10px; border-radius: 8px; }
        .status-select { padding: 8px; border-radius: 5px; border: 1px solid #ccc; flex-grow: 1; font-weight: bold; outline: none; }
    </style>
</head>
<body>

    <div class="header">
        <a href="staff_dashboard.php"><i class="fas fa-arrow-left"></i></a>
        <h2>Customer Orders</h2>
    </div>

    <div class="main-content">
        <div id="orders-list">
            <p style="text-align: center; color: #666;">Loading orders...</p>
        </div>
    </div>

    <div class="bottom-nav">
        <a href="staff_dashboard.php" class="nav-item-bottom">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="staff_manage_menu.php" class="nav-item-bottom">
            <i class="fas fa-utensils"></i>
            <span>Menu</span>
        </a>
        <a href="view_orders.php" class="nav-item-bottom active">
            <i class="fas fa-clipboard-list"></i>
            <span>Orders</span>
        </a>
        <a href="staff_profile.php" class="nav-item-bottom">
            <i class="fas fa-user-cog"></i>
            <span>Profile</span>
        </a>
    </div>

    <script src="staff.js"></script>
</body>
</html>
<?php $conn->close(); ?>
