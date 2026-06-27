// ==========================================
// STAFF.JS - Staff Panel JavaScript
// ==========================================

// Hardcoded staff ID for profile (same pattern as admin)
// Update this to match the logged-in staff's staff_id from the DB if needed.
const staffUserId = (typeof window !== 'undefined' && window.currentUserId) ? parseInt(window.currentUserId) : 2003;

// Global Helper to fetch data
async function fetchApi(url, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: { 'Content-Type': 'application/json' }
    };
    if (data) options.body = JSON.stringify(data);
    try {
        const response = await fetch(url, options);
        return await response.json();
    } catch (e) {
        console.error("API error:", e);
        return null;
    }
}

// ==========================================
// 1. DASHBOARD
// ==========================================
async function loadStaffDashboard() {
    const menu = await fetchApi('staff-php/menu_read.php') || [];

    // Count available vs unavailable
    // Ensure we parse the integer from the DB properly
    const available = menu.filter(m => parseInt(m.is_available) === 1).length;
    const unavailable = menu.length - available;

    // Update the HTML stats
    const availEl = document.getElementById('stat-available');
    const unavailEl = document.getElementById('stat-unavailable');

    if (availEl) availEl.innerText = available;
    if (unavailEl) unavailEl.innerText = unavailable;
}

// ==========================================
// 2. MANAGE MENU (Toggle Availability)
// ==========================================
async function loadStaffMenu() {
    const items = await fetchApi('staff-php/menu_read.php') || [];
    renderStaffMenu(items);
}

function renderStaffMenu(items) {
    const list = document.getElementById('menu-list');
    if (!list) return;

    list.innerHTML = '';
    items.forEach(item => {
        const isAvail = parseInt(item.is_available) === 1;
        const card = document.createElement('div');
        card.className = 'menu-card-admin';

        // Build the card with dynamic colors and buttons based on status
        card.innerHTML = `
            <div class="menu-img">
                ${item.image_url ? `<img src="images/${item.image_url}" alt="${item.name}" style="width:100%; height:100%; object-fit:cover; border-radius:10px;">` : `<i class="fas fa-image"></i>`}
            </div>
            
            <div class="menu-info">
                <h4>${item.name}</h4>
                <p style="margin: 4px 0; font-size: 13px; color: #666;">${item.category}</p>
                <div class="menu-price">RM ${item.price}</div>
                <p style="margin-top: 8px; font-size: 14px; font-weight: bold; color: ${isAvail ? '#28a745' : '#dc3545'};">
                    Status: ${isAvail ? 'Available' : 'Unavailable'}
                </p>
            </div>
            <div class="menu-actions" style="display: flex; flex-direction: column; justify-content: center;">
                <button 
                    style="background-color: ${isAvail ? '#dc3545' : '#28a745'}; color: white; border: none; padding: 10px 15px; border-radius: 8px; cursor: pointer; font-weight: bold;" 
                    onclick="toggleMenuStatus(${item.item_id}, ${isAvail ? 0 : 1})">
                    <i class="fas ${isAvail ? 'fa-times-circle' : 'fa-check-circle'}"></i> 
                    Set ${isAvail ? 'Unavailable' : 'Available'}
                </button>
            </div>
        `;
        list.appendChild(card);
    });
}

// Function to update the DB when the button is clicked
async function toggleMenuStatus(itemId, newStatus) {
    const res = await fetchApi('staff-php/menu_update.php', 'POST', {
        item_id: itemId,
        is_available: newStatus
    });

    if (res && res.success) {
        // Reload the menu to instantly show the updated status
        loadStaffMenu();
    } else {
        alert('Error updating item status.');
    }
}

// Handles the search bar on the staff menu page
async function searchStaffMenu(query) {
    const items = await fetchApi('staff-php/menu_search.php?q=' + encodeURIComponent(query)) || [];
    renderStaffMenu(items);
}

// ==========================================
// 3. PROFILE (View only)
// ==========================================
async function loadStaffProfile() {
    const users = await fetchApi('staff-php/user_read.php') || [];
    const staff = users.find(u => parseInt(u.user_id) === staffUserId);

    if (staff) {
        const setVal = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.value = val || '';
        };
        setVal('profile-name', staff.name);
        setVal('profile-username', staff.username);
        setVal('profile-email', staff.email);
        setVal('profile-phone', staff.phone);
        setVal('profile-branch', staff.branch);
        setVal('profile-role', staff.role);
    }
}

// ==========================================
// 4. MANAGE ORDERS
// ==========================================
async function loadStaffOrders() {
    const list = document.getElementById('orders-list');
    if (!list) return;

    // Fetch orders from the API
    const orders = await fetchApi('staff-php/orders_read.php') || [];

    if (orders.length === 0) {
        list.innerHTML = '<p style="text-align: center; color: #666;">No incoming orders at the moment.</p>';
        return;
    }

    list.innerHTML = ''; // Clear loading text

    orders.forEach(order => {
        const orderId = order.order_id;
        const currentStatus = order.order_status || 'Pending';

        // Format date slightly
        const dateObj = new Date(order.order_date);
        const formattedDate = dateObj.toLocaleString();

        const card = document.createElement('div');
        card.className = 'order-card-admin';

        // Define dropdown colors based on status
        let borderColor = '#ccc';
        if (currentStatus === 'Preparing') borderColor = '#ff9800';
        if (currentStatus === 'Completed') borderColor = '#4caf50';

        card.innerHTML = `
            <div class="order-header">
                <span class="order-id">Order #${orderId}</span>
                <span class="order-date">${formattedDate}</span>
            </div>
            <div class="order-details">
                <p><strong>Customer:</strong> ${order.customer_name || 'Guest'}</p>
                <p><strong>Type:</strong> ${order.order_type}</p>
                <p><strong>Total:</strong> RM ${parseFloat(order.total_price).toFixed(2)}</p>
            </div>
            <div class="update-section" style="border-left: 4px solid ${borderColor}">
                <label style="font-weight: bold; font-size: 14px; color: #333;">Status:</label>
                <select id="status-${orderId}" class="status-select">
                    <option value="Pending" ${currentStatus === 'Pending' ? 'selected' : ''}>Pending</option>
                    <option value="Preparing" ${currentStatus === 'Preparing' ? 'selected' : ''}>Preparing</option>
                    <option value="Completed" ${currentStatus === 'Completed' ? 'selected' : ''}>Completed</option>
                </select>
                <button class="add-btn" style="padding: 8px 15px; margin: 0;" onclick="updateOrderStatus(${orderId})">Update</button>
            </div>
        `;
        list.appendChild(card);
    });
}

async function updateOrderStatus(orderId) {
    const selectEl = document.getElementById(`status-${orderId}`);
    const newStatus = selectEl.value;

    const res = await fetchApi('staff-php/order_update.php', 'POST', {
        order_id: orderId,
        order_status: newStatus
    });

    if (res && res.success) {
        alert(`Order #${orderId} updated to ${newStatus}!`);
        loadStaffOrders(); // Reload the list to refresh colors/data
    } else {
        alert('Failed to update order status. ' + (res?.error || ''));
    }
}

// ==========================================
// ROUTER INITIALIZATION
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    const path = window.location.pathname;

    if (path.includes('staff_dashboard.html') || path.includes('staff_dashboard.php')) {
        loadStaffDashboard();
    } else if (path.includes('staff_manage_menu.html') || path.includes('staff_manage_menu.php') || path.includes('manage_menu.html')) {
        loadStaffMenu();
    } else if (path.includes('staff_profile.html')) {
        loadStaffProfile();
    } else if (path.includes('staff_vieworders.html') || path.includes('manage-order.php')) {
        loadStaffOrders();
    }
});