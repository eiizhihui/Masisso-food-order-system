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
        <div class="export-container" style="margin-bottom: 20px; display: flex; gap: 10px; align-items: center; justify-content: space-between; background: white; padding: 10px 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); flex-wrap: wrap;">
            <div style="display: flex; gap: 10px; align-items: center;">
                <label style="font-weight: bold; font-size: 14px; color: #333;"><i class="fas fa-chart-bar"></i> Monthly sale:</label>
                <input type="month" id="report-month" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 5px; font-weight: bold; outline: none; font-size: 14px; color: #333;">
            </div>
            <button class="add-btn" onclick="exportMonthlyReport()" style="margin: 0; background: #e65100;"><i class="fas fa-file-pdf"></i> Export Report</button>
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

            // Calculate daily income totals
            const dailyIncome = {};
            orders.forEach(order => {
                const dObj = new Date(order.order_date);
                const dKey = dObj.toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                dailyIncome[dKey] = (dailyIncome[dKey] || 0) + (parseFloat(order.total_price) || 0);
            });

            let lastDate = "";
            orders.forEach(order => {
                const orderId = order.order_id;
                const currentStatus = order.order_status || 'Pending';
                
                const dateObj = new Date(order.order_date);
                const formattedDate = dateObj.toLocaleString();
                const dateKey = dateObj.toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

                if (dateKey !== lastDate) {
                    lastDate = dateKey;
                    const separator = document.createElement('div');
                    separator.className = 'order-date-separator';
                    separator.style.cssText = 'margin: 25px 0 10px 0; padding: 8px 12px; background: #FFF3E0; border-radius: 8px; font-weight: bold; color: #E65100; border-left: 4px solid var(--primary-orange); font-size: 14px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 5px rgba(0,0,0,0.05);';
                    separator.innerHTML = `
                        <span style="display: flex; align-items: center; gap: 8px;"><i class="far fa-calendar-alt"></i> ${dateKey}</span>
                        <span>Daily Total: RM ${dailyIncome[dateKey].toFixed(2)}</span>
                    `;
                    list.appendChild(separator);
                }

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

        function exportMonthlyReport() {
            const selectedMonth = document.getElementById('report-month').value;
            if (!selectedMonth) {
                alert("Please select a month first.");
                return;
            }

            const [year, month] = selectedMonth.split('-');
            const yearInt = parseInt(year);
            const monthInt = parseInt(month);

            // Filter orders for the selected month (Completed orders represent actual sales/income)
            const completedOrders = allOrders.filter(o => {
                const oDate = new Date(o.order_date);
                return oDate.getFullYear() === yearInt && 
                       (oDate.getMonth() + 1) === monthInt && 
                       (o.order_status || '').toLowerCase() === 'completed';
            });

            // If no completed orders, check if there are any orders at all so we can show warning
            const totalOrdersInMonth = allOrders.filter(o => {
                const oDate = new Date(o.order_date);
                return oDate.getFullYear() === yearInt && (oDate.getMonth() + 1) === monthInt;
            });

            if (totalOrdersInMonth.length === 0) {
                alert(`No orders found for ${selectedMonth}.`);
                return;
            }

            // Calculations
            let totalRevenue = 0;
            let dineInCount = 0;
            let deliveryCount = 0;
            let takeawayCount = 0;
            const itemsSold = {};
            const dailyIncome = {};

            completedOrders.forEach(o => {
                const price = parseFloat(o.total_price) || 0;
                totalRevenue += price;

                // Order type count
                const type = (o.order_type || '').toLowerCase();
                if (type === 'dine-in') dineInCount++;
                else if (type === 'delivery') deliveryCount++;
                else if (type === 'takeaway') takeawayCount++;

                // Daily income grouping
                const dayStr = new Date(o.order_date).toLocaleDateString(undefined, { day: 'numeric', month: 'short' });
                dailyIncome[dayStr] = (dailyIncome[dayStr] || 0) + price;

                // Parse items JSON
                if (o.items) {
                    try {
                        const itemsArr = typeof o.items === 'string' ? JSON.parse(o.items) : o.items;
                        if (Array.isArray(itemsArr)) {
                            itemsArr.forEach(item => {
                                const name = item.name || 'Unknown Item';
                                const qty = parseInt(item.quantity) || 1;
                                const itemPrice = parseFloat(item.totalPrice) || 0;
                                if (!itemsSold[name]) {
                                    itemsSold[name] = { quantity: 0, revenue: 0 };
                                }
                                itemsSold[name].quantity += qty;
                                itemsSold[name].revenue += itemPrice;
                            });
                        }
                    } catch (e) {
                        console.error("Error parsing order items for PDF report", e);
                    }
                }
            });

            // Highest daily income
            let highestDailyIncome = 0;
            let highestDailyDay = 'N/A';
            for (const day in dailyIncome) {
                if (dailyIncome[day] > highestDailyIncome) {
                    highestDailyIncome = dailyIncome[day];
                    highestDailyDay = day;
                }
            }

            // New Customer Total (customers whose first-ever order date was in this month)
            const customerFirstOrder = {};
            allOrders.forEach(o => {
                const uid = o.user_id;
                if (!uid) return;
                const oTime = new Date(o.order_date).getTime();
                if (!customerFirstOrder[uid] || oTime < customerFirstOrder[uid]) {
                    customerFirstOrder[uid] = oTime;
                }
            });

            let newCustomers = 0;
            for (const uid in customerFirstOrder) {
                const firstDate = new Date(customerFirstOrder[uid]);
                if (firstDate.getFullYear() === yearInt && (firstDate.getMonth() + 1) === monthInt) {
                    newCustomers++;
                }
            }

            // Sort items sold by quantity descending
            const sortedItems = Object.entries(itemsSold)
                .map(([name, data]) => ({ name, ...data }))
                .sort((a, b) => b.quantity - a.quantity);

            // Month display name
            const monthName = new Date(yearInt, monthInt - 1, 1).toLocaleString('default', { month: 'long', year: 'numeric' });

            // Generate Print Document
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Sales Report - ${monthName}</title>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                    <style>
                        body {
                            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                            color: #333;
                            padding: 40px;
                            line-height: 1.6;
                        }
                        .report-header {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            border-bottom: 3px solid #E65100;
                            padding-bottom: 20px;
                            margin-bottom: 30px;
                        }
                        .report-title h1 {
                            margin: 0;
                            color: #E65100;
                            font-size: 28px;
                        }
                        .report-title p {
                            margin: 5px 0 0 0;
                            color: #666;
                            font-size: 14px;
                        }
                        .report-month {
                            text-align: right;
                            font-size: 20px;
                            font-weight: bold;
                            color: #E65100;
                        }
                        .stats-grid {
                            display: grid;
                            grid-template-columns: repeat(4, 1fr);
                            gap: 15px;
                            margin-bottom: 30px;
                        }
                        .stat-card {
                            background: #F9F9F9;
                            border: 1px solid #EEE;
                            border-radius: 8px;
                            padding: 15px;
                            text-align: center;
                            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
                        }
                        .stat-card h3 {
                            margin: 0;
                            font-size: 20px;
                            color: #E65100;
                        }
                        .stat-card p {
                            margin: 5px 0 0 0;
                            color: #777;
                            font-size: 12px;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                        }
                        .section-title {
                            border-bottom: 2px solid #EEE;
                            padding-bottom: 8px;
                            margin-top: 30px;
                            margin-bottom: 15px;
                            color: #333;
                            font-size: 18px;
                            display: flex;
                            align-items: center;
                            gap: 10px;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 30px;
                        }
                        th, td {
                            padding: 12px;
                            text-align: left;
                            border-bottom: 1px solid #EEE;
                        }
                        th {
                            background-color: #F5F5F5;
                            font-weight: bold;
                            color: #555;
                        }
                        .text-right {
                            text-align: right;
                        }
                        .footer {
                            margin-top: 50px;
                            text-align: center;
                            font-size: 12px;
                            color: #999;
                            border-top: 1px solid #EEE;
                            padding-top: 15px;
                        }
                        @media print {
                            body { padding: 0; }
                            button { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div style="text-align: right; margin-bottom: 20px;">
                        <button onclick="window.print()" style="background:#E65100; color:white; border:none; padding:10px 20px; border-radius:5px; font-weight:bold; cursor:pointer; font-size:14px;"><i class="fas fa-print"></i> Print / Save as PDF</button>
                    </div>
                    <div class="report-header">
                        <div class="report-title">
                            <h1>Masisso Sales Report</h1>
                            <p>Generated on ${new Date().toLocaleDateString()} | System Administrator Panel</p>
                        </div>
                        <div class="report-month">
                            ${monthName}
                        </div>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <h3>RM ${totalRevenue.toFixed(2)}</h3>
                            <p>Total Revenue</p>
                        </div>
                        <div class="stat-card">
                            <h3>${completedOrders.length} / ${totalOrdersInMonth.length}</h3>
                            <p>Completed / Total Orders</p>
                        </div>
                        <div class="stat-card">
                            <h3>${newCustomers}</h3>
                            <p>New Customers</p>
                        </div>
                        <div class="stat-card">
                            <h3>RM ${highestDailyIncome.toFixed(2)}</h3>
                            <p>Highest Day (${highestDailyDay})</p>
                        </div>
                    </div>

                    <h2 class="section-title"><i class="fas fa-chart-line"></i> Order Type Breakdown</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Order Type</th>
                                <th class="text-right">Completed Orders Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="fas fa-utensils"></i> Dine-In</td>
                                <td class="text-right">${dineInCount}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-box-open"></i> Takeaway</td>
                                <td class="text-right">${takeawayCount}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-motorcycle"></i> Delivery</td>
                                <td class="text-right">${deliveryCount}</td>
                            </tr>
                        </tbody>
                    </table>

                    <h2 class="section-title"><i class="fas fa-utensils"></i> Total Item Sales (Ordered by Quantity Sold)</h2>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 80px;">Rank</th>
                                <th>Item Name</th>
                                <th class="text-right">Quantity Sold</th>
                                <th class="text-right">Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${sortedItems.length > 0 ? sortedItems.map((item, index) => `
                                <tr>
                                    <td>#${index + 1}</td>
                                    <td><strong>${item.name}</strong></td>
                                    <td class="text-right">${item.quantity}</td>
                                    <td class="text-right">RM ${item.revenue.toFixed(2)}</td>
                                </tr>
                            `).join('') : '<tr><td colspan="4" style="text-align:center; color:#999;">No items sold.</td></tr>'}
                        </tbody>
                    </table>

                    <div class="footer">
                        <p>© ${new Date().getFullYear()} Masisso Food Order System. Confidential internal document.</p>
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadOrders();
            // Set default month in the report select
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const monthEl = document.getElementById('report-month');
            if (monthEl) monthEl.value = `${year}-${month}`;
        });
    </script>
</body>

</html>
