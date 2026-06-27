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
    <title>Masisso - Staff Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .main-content {
            margin: 0 auto !important;
        }

        .staff-header {
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
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .stat-box.green {
            background: linear-gradient(135deg, #66BB6A, #2E7D32);
        }

        .stat-box.red {
            background: linear-gradient(135deg, #EF5350, #B71C1C);
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
    </style>
</head>

<body>

    <div class="staff-header">
        Masisso Staff Panel

        <a href="logout.php" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); color: white; text-decoration: none; font-weight: bold; font-size: 14px; background: rgba(255, 255, 255, 0.2); padding: 6px 15px; border-radius: 20px; display: flex; align-items: center; gap: 8px; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.4)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>

    </div>
    
    <div class="main-content">
        <h2 class="slide-in-text" style="color: #333;">Welcome, <?php echo htmlspecialchars($_SESSION['name'] ?? 'Staff'); ?> 👋</h2>
        <p style="color: #666; margin-bottom: 20px;">Manage menu availability for customers.</p>

        <div class="stats-container">
            <div class="stat-box green">
                <h2 id="stat-available">—</h2>
                <p>Available Items</p>
            </div>
            <div class="stat-box red">
                <h2 id="stat-unavailable">—</h2>
                <p>Unavailable Items</p>
            </div>
        </div>

        <div class="dash-card" style="border-left: 5px solid #e65100; text-align: left; cursor: pointer;"
            onclick="window.location.href='manage-menu.php'">
            <h3 style="margin-top: 0; color: #333;">🍲 Manage Laksa Menu</h3>
            <p style="color: #666; font-size: 14px;">Update food item availability (Available / Not Available) for customers.</p>
            <button class="add-btn solid-btn" style="width: auto; padding: 10px 20px;">Open Menu Manager</button>
        </div>
        <br>
        <br>
        <div class="dash-card" style="border-left: 5px solid #e65100; text-align: left; cursor: pointer;"
            onclick="window.location.href='manage-order.php'">
            <h3 style="margin-top: 0; color: #333;">📦 View Customer Orders</h3>
            <p style="color: #666; font-size: 14px;">Monitor and manage incoming customer orders.</p>
            <button class="add-btn solid-btn" style="width: auto; padding: 10px 20px;">View Orders</button>
        </div>
        
    </div>

    <div class="bottom-nav">
        <a href="staff_dashboard.php" class="nav-item-bottom active">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="manage-menu.php" class="nav-item-bottom">
            <i class="fas fa-utensils"></i>
            <span>Menu</span>
        </a>
        <a href="manage-order.php" class="nav-item-bottom">
            <i class="fas fa-clipboard-list"></i>
            <span>Orders</span>
        </a>
        <a href="manage-profile.php" class="nav-item-bottom">
            <i class="fas fa-user-cog"></i>
            <span>Profile</span>
        </a>
    </div>

    <script src="staff.js"></script>

</body>

</html>