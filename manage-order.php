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
    <title>Manage Orders</title>
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

        .order-card-admin {
            background: white;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border-left: 5px solid var(--primary-orange);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .order-id {
            font-weight: bold;
            color: var(--primary-orange);
            font-size: 16px;
        }

        .order-date {
            font-size: 12px;
            color: #888;
        }

        .order-details p {
            margin: 5px 0;
            color: #555;
            font-size: 14px;
        }

        .update-section {
            margin-top: 10px;
            display: flex;
            gap: 10px;
            align-items: center;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 8px;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .status-select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-weight: bold;
            outline: none;
        }

        .add-btn {
            background: var(--primary-orange);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: opacity 0.2s;
        }

        .add-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="header-left">
            <?php if ($role === 'staff'): ?>
                <a href="staff_dashboard.php" aria-label="Back to Dashboard"><i class="fas fa-arrow-left"></i></a>
            <?php else: ?>
                <a href="admin-dashboard.php" aria-label="Back to Dashboard"><i class="fas fa-arrow-left"></i></a>
            <?php endif; ?>
            <h2>Customer Orders</h2>
        </div>
    </div>

    <div class="main-content">
        <div class="toggle-container" style="margin-bottom: 15px;">
            <button class="toggle-option active" id="tab-active" onclick="switchOrderTab('Active')">Active Orders</button>
            <button class="toggle-option" id="tab-completed" onclick="switchOrderTab('Completed')">Completed Orders</button>
        </div>
        <div class="nav-search" style="margin-bottom: 20px;">
            <input type="text" id="search-input" placeholder="Search orders by customer, ID, status or type..." oninput="filterOrders(this.value)"
                style="width:100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; font-size: 15px; outline: none;">
        </div>
        <div class="list-container" id="order-list">
            <p style="text-align: center; color: #666;">Loading orders...</p>
        </div>
    </div>

    <div class="bottom-nav">
        <?php if ($role === 'staff'): ?>
            <a href="staff_dashboard.php" class="nav-item-bottom">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="manage-menu.php" class="nav-item-bottom">
                <i class="fas fa-utensils"></i>
                <span>Menu</span>
            </a>
            <a href="manage-order.php" class="nav-item-bottom active">
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
        <?php endif; ?>
    </div>

    <script>
        const userRole = <?php echo json_encode($role); ?>;
        let allOrders = [];
        let currentTab = 'Active';

        function switchOrderTab(tabName) {
            currentTab = tabName;
            document.querySelectorAll('.toggle-option').forEach(btn => btn.classList.remove('active'));
            document.getElementById(tabName === 'Active' ? 'tab-active' : 'tab-completed').classList.add('active');
            filterAndRenderOrders();
        }

        async function loadOrders() {
            try {
                const response = await fetch('staff-php/orders_read.php');
                allOrders = await response.json() || [];
                allOrders.sort((a, b) => new Date(b.order_date) - new Date(a.order_date));
                filterAndRenderOrders();
            } catch (e) {
                console.error("Error loading orders:", e);
                document.getElementById('order-list').innerHTML = '<p style="text-align: center; color: red;">Failed to load orders.</p>';
            }
        }

        function renderOrders(orders) {
            const list = document.getElementById('order-list');
            if (!list) return;

            if (orders.length === 0) {
                list.innerHTML = '<p style="text-align: center; color: #666;">No orders found.</p>';
                return;
            }

            list.innerHTML = '';

            orders.forEach(order => {
                const orderId = order.order_id;
                const currentStatus = order.order_status || 'Pending';
                
                const dateObj = new Date(order.order_date);
                const formattedDate = dateObj.toLocaleString();

                const card = document.createElement('div');
                card.className = 'order-card-admin';
                
                let borderColor = '#ffc107'; // Pending yellow
                if (currentStatus === 'Preparing') borderColor = '#ff9800'; // Preparing orange
                if (currentStatus === 'Completed') borderColor = '#4caf50'; // Completed green

                let deleteBtnHtml = '';
                if (userRole === 'admin' || userRole === 'super admin') {
                    deleteBtnHtml = `<button class="add-btn" style="padding: 8px 15px; margin: 0; background: #f44336;" onclick="deleteOrder(${orderId})"><i class="fas fa-trash"></i> Delete</button>`;
                }

                let itemsHtml = '';
                if (order.items) {
                    try {
                        const itemsArr = typeof order.items === 'string' ? JSON.parse(order.items) : order.items;
                        if (Array.isArray(itemsArr) && itemsArr.length > 0) {
                            itemsHtml = '<div class="order-items-list" style="margin-top: 10px; border-top: 1px dashed #eee; padding-top: 8px;">';
                            itemsHtml += '<p style="font-weight: bold; margin-bottom: 5px; color: #333;"><i class="fas fa-utensils"></i> Ordered Items:</p>';
                            itemsArr.forEach(item => {
                                const qty = item.quantity || 1;
                                const comboInfo = item.comboName && item.comboName.indexOf("Just the") === -1 ? `<span style="font-size: 12px; color: #888;"> (${item.comboName})</span>` : '';
                                
                                // Format preferences/customizations if any
                                let prefInfo = '';
                                if (Array.isArray(item.preferences) && item.preferences.length > 0) {
                                    prefInfo = `<br><span style="font-size: 11px; color: #a04000; margin-left: 15px;">• ${item.preferences.join(', ')}</span>`;
                                }
                                
                                itemsHtml += `<p style="margin: 3px 0; color: #555; padding-left: 10px;">${qty}x <strong>${item.name}</strong>${comboInfo}${prefInfo}</p>`;
                            });
                            itemsHtml += '</div>';
                        }
                    } catch (err) {
                        console.error("Error parsing items for order #" + orderId, err);
                    }
                }

                card.innerHTML = `
                    <div class="order-header">
                        <span class="order-id">Order #${orderId}</span>
                        <span class="order-date">${formattedDate}</span>
                    </div>
                    <div class="order-details">
                        <p><strong>Customer:</strong> ${order.customer_name || 'Guest'}</p>
                        <p><strong>Type:</strong> ${order.order_type}</p>
                        <p><strong>Total:</strong> RM ${parseFloat(order.total_price).toFixed(2)}</p>
                        ${itemsHtml}
                    </div>
                    <div class="update-section" style="border-left: 4px solid ${borderColor};">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <label style="font-weight: bold; font-size: 14px; color: #333;">Status:</label>
                            <select id="status-${orderId}" class="status-select">
                                <option value="Pending" ${currentStatus === 'Pending' ? 'selected' : ''}>Pending</option>
                                <option value="Preparing" ${currentStatus === 'Preparing' ? 'selected' : ''}>Preparing</option>
                                <option value="Completed" ${currentStatus === 'Completed' ? 'selected' : ''}>Completed</option>
                            </select>
                        </div>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <button class="add-btn" onclick="updateOrderStatus(${orderId})">Update</button>
                            ${deleteBtnHtml}
                        </div>
                    </div>
                `;
                list.appendChild(card);
            });
        }

        function filterAndRenderOrders() {
            const query = document.getElementById('search-input').value.toLowerCase().trim();
            
            const filtered = allOrders.filter(order => {
                // First filter by active/completed tab
                const status = (order.order_status || 'Pending').toLowerCase();
                const isCompleted = status === 'completed';
                if (currentTab === 'Active' && isCompleted) return false;
                if (currentTab === 'Completed' && !isCompleted) return false;
                
                // Then filter by search query if exists
                if (query) {
                    const orderIdMatch = String(order.order_id).includes(query);
                    const nameMatch = (order.customer_name || '').toLowerCase().includes(query);
                    const typeMatch = (order.order_type || '').toLowerCase().includes(query);
                    const statusMatch = (order.order_status || '').toLowerCase().includes(query);
                    const dateMatch = (order.order_date || '').toLowerCase().includes(query);
                    const totalMatch = String(order.total_price).includes(query);
                    
                    let itemsMatch = false;
                    if (order.items) {
                        try {
                            const itemsArr = typeof order.items === 'string' ? JSON.parse(order.items) : order.items;
                            if (Array.isArray(itemsArr)) {
                                itemsMatch = itemsArr.some(item => (item.name || '').toLowerCase().includes(query));
                            }
                        } catch (e) {}
                    }
                    return orderIdMatch || nameMatch || typeMatch || statusMatch || dateMatch || totalMatch || itemsMatch;
                }
                
                return true;
            });
            
            renderOrders(filtered);
        }

        function filterOrders(query) {
            filterAndRenderOrders();
        }

        async function updateOrderStatus(orderId) {
            const selectEl = document.getElementById(`status-${orderId}`);
            const newStatus = selectEl.value;

            try {
                const res = await fetch('staff-php/order_update.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        order_id: orderId,
                        order_status: newStatus
                    })
                });
                const data = await res.json();
                if (data && data.success) {
                    alert(`Order #${orderId} updated to ${newStatus}!`);
                    loadOrders();
                } else {
                    alert('Failed to update order status: ' + (data?.error || ''));
                }
            } catch (e) {
                console.error(e);
                alert('Error updating order status.');
            }
        }

        async function deleteOrder(orderId) {
            if (!confirm('Are you sure you want to delete this order?')) return;

            try {
                const res = await fetch('staff-php/order_delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        order_id: orderId
                    })
                });
                const data = await res.json();
                if (data && data.success) {
                    alert(`Order #${orderId} deleted successfully.`);
                    loadOrders();
                } else {
                    alert('Failed to delete order: ' + (data?.error || ''));
                }
            } catch (e) {
                console.error(e);
                alert('Error deleting order.');
            }
        }

        document.addEventListener('DOMContentLoaded', loadOrders);
    </script>
</body>

</html>
