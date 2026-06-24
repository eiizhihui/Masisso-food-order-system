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
    <title>Manage Offers</title>
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
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .add-new-btn:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        .list-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .offer-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border-left: 5px solid #FF9800;
        }

        .offer-info h4 {
            margin: 0 0 5px 0;
            color: #333;
            font-size: 18px;
        }

        .offer-info p {
            margin: 3px 0;
            font-size: 14px;
            color: #666;
        }

        .offer-code {
            display: inline-block;
            background: #fff3e0;
            color: #e65100;
            padding: 3px 8px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .offer-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-left: 10px;
        }

        .offer-actions button {
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
                <h2>Manage Events / Offers</h2>
            </div>
            <button class="add-new-btn" onclick="openOfferModal()"><i class="fas fa-plus"></i> Add</button>
        </div>

        <div class="main-content">
            <div class="nav-search" style="margin-bottom: 20px;">
                <input type="text" id="search-input" placeholder="Search events/offers..."
                    oninput="searchOffers(this.value)" style="width:100%; padding: 10px;">
            </div>
            <div class="list-container" id="offer-list">
                <!-- Offer items injected by JS -->
            </div>
        </div>
    </div>

    <div id="add-section" style="display: none;">
        <div class="header">
            <div class="header-left">
                <a href="#" onclick="hideAddForm()"><i class="fas fa-arrow-left"></i></a>
                <h2 id="form-title">Add Offer / Event</h2>
            </div>
        </div>

        <div class="profile-container">
            <div class="profile-header">
                <h2>Create Offer</h2>
                <p>Enter details to save a new offer entry</p>
            </div>

            <form onsubmit="return false;">
                <div class="form-group">
                    <label for="offer-code">Code</label>
                    <input type="text" id="offer-code" placeholder="e.g., MINUS5" required>
                </div>

                <div class="form-group">
                    <label for="offer-title">Title</label>
                    <input type="text" id="offer-title" placeholder="e.g., 🎫 RM 5 OFF" required>
                </div>

                <div class="form-group">
                    <label for="offer-desc">Description</label>
                    <textarea id="offer-desc" rows="2" placeholder="Enter description"></textarea>
                </div>

                <div class="form-group">
                    <label for="offer-type">Discount Type</label>
                    <select id="offer-type" required>
                        <option value="fixed">Fixed (RM)</option>
                        <option value="percentage">Percentage (%)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="offer-value">Discount Value</label>
                    <input type="number" id="offer-value" step="0.01" placeholder="e.g., 5.00" required>
                </div>

                <div class="form-group">
                    <label for="offer-min">Min Spend (RM)</label>
                    <input type="number" id="offer-min" step="0.01" placeholder="e.g., 50.00" required>
                </div>

                <div class="form-group">
                    <label for="offer-valid">Valid Until</label>
                    <input type="date" id="offer-valid" required>
                </div>

                <button type="button" class="submit-profile-btn" id="submit-btn" onclick="saveOffer()">Save Offer Entry</button>
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
        <a href="manage-menu.php" class="nav-item-bottom">
            <i class="fas fa-utensils"></i>
            <span>Menu</span>
        </a>
        <a href="manage-event.php" class="nav-item-bottom active">
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
