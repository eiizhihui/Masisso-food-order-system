<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ['staff', 'admin', 'super admin'])) {
    header("Location: login.php");
    exit();
}
$role = strtolower($_SESSION['role']);
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
            padding: 20px;
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

    <!-- ========================================== -->
    <!-- MAIN LIST SECTION (Shared by Staff & Admin)-->
    <!-- ========================================== -->
    <div id="list-section">
        <div class="header">
            <div class="header-left">
                <?php if ($role === 'staff'): ?>
                    <a href="staff_dashboard.php" aria-label="Back to Dashboard"><i class="fas fa-arrow-left"></i></a>
                <?php else: ?>
                    <a href="admin-dashboard.php" aria-label="Back to Dashboard"><i class="fas fa-arrow-left"></i></a>
                <?php endif; ?>
                <h2>Manage Menu</h2>
            </div>
            <?php if ($role !== 'staff'): ?>
                <button class="add-new-btn" onclick="openMenuModal()"><i class="fas fa-plus"></i> Add</button>
            <?php endif; ?>
        </div>

        <div class="main-content">
            <div class="nav-search" style="margin-bottom: 20px;">
                <input type="text" id="search-input" placeholder="Search menu..." oninput="filterMenu(this.value)"
                    style="width:100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
            </div>
            <div class="list-container" id="menu-list">
                <p style="text-align: center; color: #666;">Loading menu...</p>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- BOTTOM NAV WRAPPER (Shared by Staff & Admin)-->
    <!-- ========================================== -->
    <div class="bottom-nav">
        <?php if ($role === 'staff'): ?>
            <a href="staff_dashboard.php" class="nav-item-bottom">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="manage-menu.php" class="nav-item-bottom active">
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
        <?php else: ?>
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
        <?php endif; ?>
    </div>

    <!-- ========================================== -->
    <!-- ADD/EDIT FORM SECTION (Admin Only)         -->
    <!-- ========================================== -->
    <?php if ($role !== 'staff'): ?>
        <div id="add-section" style="display: none;">
            <div class="header">
                <div class="header-left">
                    <a href="#" onclick="hideAddForm()"><i class="fas fa-arrow-left"></i></a>
                    <h2 id="form-title">Add Menu Item</h2>
                </div>
            </div>

            <div class="profile-container">
                <div class="profile-header">
                    <h2>Create/Edit Menu Item</h2>
                    <p>Enter details to save the menu item entry</p>
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

                    <div class="form-group">
                        <label for="menu-status">Availability</label>
                        <select id="menu-status" required>
                            <option value="1">Available</option>
                            <option value="0">Unavailable</option>
                        </select>
                    </div>

                    <button type="button" class="submit-profile-btn" id="submit-btn" onclick="saveMenu()">Save Menu Item</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- ========================================== -->
    <!-- JAVASCRIPT CONTROLLERS                    -->
    <!-- ========================================== -->
    <script>
        // Global variables / state
        const userRole = <?php echo json_encode($role); ?>;
        let allMenuItems = [];
        let editingMenuId = null;

        // ==========================================
        // 1. SHARED FEATURES (Staff & Admin)
        // ==========================================
        
        async function loadMenu() {
            try {
                const response = await fetch('staff-php/menu_read.php');
                allMenuItems = await response.json() || [];
                renderMenu(allMenuItems);
            } catch (e) {
                console.error("Error loading menu:", e);
                document.getElementById('menu-list').innerHTML = '<p style="text-align: center; color: red;">Failed to load menu.</p>';
            }
        }

        function renderMenu(items) {
            const list = document.getElementById('menu-list');
            if (!list) return;

            if (items.length === 0) {
                list.innerHTML = '<p style="text-align: center; color: #666;">No menu items found.</p>';
                return;
            }

            list.innerHTML = '';

            items.forEach(item => {
                const isAvail = parseInt(item.is_available) === 1;
                const card = document.createElement('div');
                card.className = 'menu-card-admin';
                
                // Add Admin Edit/Delete elements dynamically
                let adminActions = '';
                if (userRole !== 'staff') {
                    adminActions = `
                        <div style="display: flex; gap: 15px; margin-top: 2px; align-self: center;">
                            <button class="edit-btn" aria-label="Edit" onclick='editMenu(${JSON.stringify(item).replace(/'/g, "&apos;")})'><i class="fas fa-edit"></i></button>
                            <button class="delete-btn" aria-label="Delete" onclick="deleteMenu(${item.item_id})"><i class="fas fa-trash"></i></button>
                        </div>
                    `;
                }

                card.innerHTML = `
                    <div class="menu-img">
                        ${item.image_url ? `<img src="images/${item.image_url}" alt="${item.name}" style="width:100%; height:100%; object-fit:cover; border-radius:10px;">` : `<i class="fas fa-image"></i>`}
                    </div>
                    <div class="menu-info">
                        <h4>${item.name}</h4>
                        <p>${item.category}</p>
                        <div class="menu-price">RM ${parseFloat(item.price).toFixed(2)}</div>
                        <p style="margin-top: 8px; font-size: 14px; font-weight: bold; color: ${isAvail ? '#28a745' : '#dc3545'};">
                            Status: ${isAvail ? 'Available' : 'Unavailable'}
                        </p>
                    </div>
                    <div class="menu-actions" style="display: flex; flex-direction: column; gap: 8px; justify-content: center; align-items: flex-end; min-width: 140px;">
                        <button 
                            style="background-color: ${isAvail ? '#dc3545' : '#28a745'}; color: white; border: none; padding: 8px 14px; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 12px; white-space: nowrap; width: 100%;" 
                            onclick="toggleMenuStatus(${item.item_id}, ${isAvail ? 0 : 1})">
                            <i class="fas ${isAvail ? 'fa-times-circle' : 'fa-check-circle'}"></i> 
                            Set ${isAvail ? 'Unavailable' : 'Available'}
                        </button>
                        ${adminActions}
                    </div>
                `;
                list.appendChild(card);
            });
        }

        function filterMenu(query) {
            const lowerQuery = query.toLowerCase().trim();
            if (!lowerQuery) {
                renderMenu(allMenuItems);
                return;
            }

            const filtered = allMenuItems.filter(item => {
                const nameMatch = (item.name || '').toLowerCase().includes(lowerQuery);
                const categoryMatch = (item.category || '').toLowerCase().includes(lowerQuery);
                const descMatch = (item.description || '').toLowerCase().includes(lowerQuery);
                return nameMatch || categoryMatch || descMatch;
            });

            renderMenu(filtered);
        }

        async function toggleMenuStatus(itemId, newStatus) {
            try {
                const response = await fetch('staff-php/menu_update.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        item_id: itemId,
                        is_available: newStatus
                    })
                });
                const res = await response.json();
                if (res && res.success) {
                    loadMenu();
                } else {
                    alert('Error updating item status.');
                }
            } catch (e) {
                console.error(e);
                alert('Error updating item status.');
            }
        }

        // ==========================================
        // 2. ADMIN-ONLY FEATURES
        // ==========================================
        
        function showAddForm() {
            document.getElementById('list-section').style.display = 'none';
            const addSec = document.getElementById('add-section');
            if (addSec) addSec.style.display = 'block';
        }

        function hideAddForm() {
            document.getElementById('list-section').style.display = 'block';
            const addSec = document.getElementById('add-section');
            if (addSec) addSec.style.display = 'none';
        }

        function openMenuModal() {
            editingMenuId = null;
            showAddForm();
            document.getElementById('form-title').innerText = 'Add Menu Item';
            document.getElementById('submit-btn').innerText = 'Save Menu Item';
            document.getElementById('menu-name').value = '';
            document.getElementById('menu-category').value = 'A La Carte';
            document.getElementById('menu-price').value = '';
            document.getElementById('menu-desc').value = '';
            document.getElementById('menu-image').value = '';
            document.getElementById('menu-status').value = '1';
        }

        function editMenu(item) {
            editingMenuId = item.item_id;
            showAddForm();
            document.getElementById('form-title').innerText = 'Edit Menu Item';
            document.getElementById('submit-btn').innerText = 'Save Changes';
            document.getElementById('menu-name').value = item.name;
            document.getElementById('menu-category').value = item.category;
            document.getElementById('menu-price').value = item.price;
            document.getElementById('menu-desc').value = item.description || '';
            document.getElementById('menu-image').value = item.image_url || '';
            document.getElementById('menu-status').value = item.is_available.toString();
        }

        async function saveMenu() {
            const data = {
                name: document.getElementById('menu-name').value,
                category: document.getElementById('menu-category').value,
                price: parseFloat(document.getElementById('menu-price').value) || 0,
                description: document.getElementById('menu-desc').value,
                image_url: document.getElementById('menu-image').value,
                is_available: parseInt(document.getElementById('menu-status').value)
            };

            if (!data.name) return alert("Name is required");

            let url = 'staff-php/menu_create.php';
            if (editingMenuId) {
                data.item_id = editingMenuId;
                url = 'staff-php/menu_update.php';
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const res = await response.json();
                if (res && res.success) {
                    hideAddForm();
                    loadMenu();
                } else {
                    alert('Error saving menu item');
                }
            } catch (e) {
                console.error(e);
                alert('Error saving menu item');
            }
        }

        async function deleteMenu(id) {
            if (!confirm('Are you sure you want to delete this menu item?')) return;

            try {
                const response = await fetch('staff-php/menu_delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ item_id: id })
                });
                const res = await response.json();
                if (res && res.success) {
                    loadMenu();
                } else {
                    alert('Error deleting menu item');
                }
            } catch (e) {
                console.error(e);
                alert('Error deleting menu item');
            }
        }

        // Initialize Page
        document.addEventListener('DOMContentLoaded', loadMenu);
    </script>
</body>
</html>