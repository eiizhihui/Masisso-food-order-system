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
    <title>Manage Menu</title>
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
        }

        .header a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
            font-size: 20px;
        }

        .add-new-btn {
            background: white;
            color: var(--primary-orange);
            border: none;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            cursor: pointer;
        }

        .list-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .menu-card-admin {
            background: white;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border-left: 5px solid var(--primary-orange);
        }

        .menu-img {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            object-fit: cover;
            margin-right: 15px;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
        }

        .menu-info {
            flex-grow: 1;
        }

        .menu-info h4 {
            margin: 0 0 5px 0;
            color: #333;
        }

        .menu-info p {
            margin: 0 0 5px 0;
            font-size: 13px;
            color: #666;
        }

        .menu-price {
            font-weight: bold;
            color: var(--primary-orange);
        }

        .menu-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-left: 10px;
        }

        .menu-actions button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }

        .edit-btn {
            color: #2196F3;
        }

        .delete-btn {
            color: #f44336;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .profile-container {
            max-width: 500px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            box-sizing: border-box;
        }

        .profile-header h2 {
            color: var(--primary-orange);
            margin: 0 0 5px 0;
        }

        .profile-header p {
            color: #777;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .submit-profile-btn {
            background: var(--primary-orange);
            color: white;
            border: none;
            border-radius: 10px;
            width: 100%;
            padding: 14px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.2s;
            margin-top: 10px;
        }

        .submit-profile-btn:active {
            opacity: 0.8;
        }
    </style>
</head>

<body>

    <div id="list-section">
        <div class="header">
            <div class="header-left">
                <a href="admin-dashboard.php" aria-label="Back to Dashboard"><i class="fas fa-arrow-left"></i></a>
                <h2>Manage Menu</h2>
            </div>
            <button class="add-new-btn" onclick="openMenuModal()"><i class="fas fa-plus"></i> Add</button>
        </div>

        <div class="main-content">
            <div class="nav-search" style="margin-bottom: 20px;">
                <input type="text" id="search-input" placeholder="Search menu..." oninput="searchMenu(this.value)"
                    style="width:100%; padding: 10px;">
            </div>
            <div class="list-container" id="menu-list">
                <!-- Menu items injected by JS -->
            </div>
        </div>
    </div>

    <div id="add-section" style="display: none;">
        <div class="header">
            <div class="header-left">
                <a href="#" onclick="hideAddForm()"><i class="fas fa-arrow-left"></i></a>
                <h2 id="form-title">Add Menu Item</h2>
            </div>
        </div>

        <div class="profile-container">
            <div class="profile-header">
                <h2>Create Menu Item</h2>
                <p>Enter details to save a new menu item entry</p>
            </div>

            <form onsubmit="return false;">
                <div class="form-group">
                    <label for="menu-name">Name</label>
                    <input type="text" id="menu-name" placeholder="e.g., Masisso Signature Laksa" required>
                </div>

                <div class="form-group">
                    <label for="menu-category">Category</label>
                    <select id="menu-category" required>
                        <option value="A La Carte">A La Carte</option>
                        <option value="Combo">Combo</option>
                        <option value="Drinks">Drinks</option>
                        <option value="Sides">Sides</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="menu-price">Price (RM)</label>
                    <input type="number" id="menu-price" step="0.01" placeholder="e.g., 14.90" required>
                </div>

                <div class="form-group">
                    <label for="menu-desc">Description</label>
                    <textarea id="menu-desc" rows="3" placeholder="Enter menu item description"></textarea>
                </div>

                <div class="form-group">
                    <label for="menu-image">Image Filename</label>
                    <input type="text" id="menu-image" placeholder="e.g., laksa.jpg">
                </div>

                <button type="button" class="submit-profile-btn" id="submit-btn" onclick="saveMenu()">Save Menu
                    Item</button>
            </form>
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
        <a href="manage-order.php" class="nav-item-bottom">
            <i class="fas fa-clipboard-list"></i>
            <span>Orders</span>
        </a>
        <a href="manage-menu.php" class="nav-item-bottom active">
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
