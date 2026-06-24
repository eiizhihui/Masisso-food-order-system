// Global States
let usersDict = {};
let currentEditingId = null; // Used for user editing
let editingMenuId = null;
let editingOfferId = null;
let editingRewardId = null;
let currentTab = 'Customer'; // Used for user toggle tabs

function switchUserTab(tabName) {
    currentTab = tabName;
    document.querySelectorAll('.toggle-option').forEach(btn => btn.classList.remove('active'));
    document.getElementById(tabName === 'Customer' ? 'tab-customer' : 'tab-staff').classList.add('active');
    loadUsers();
}

// Global Helpers
async function fetchApi(url, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        }
    };
    if (data) {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(url, options);
        return await response.json();
    } catch (e) {
        console.error("API error:", e);
        return null;
    }
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.style.display = 'block';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.style.display = 'none';
}

function showAddForm() {
    document.getElementById('list-section').style.display = 'none';
    document.getElementById('add-section').style.display = 'block';
}

function hideAddForm() {
    document.getElementById('list-section').style.display = 'block';
    document.getElementById('add-section').style.display = 'none';
}

// ==========================================
// 1. DASHBOARD FEATURE
// ==========================================
async function loadDashboard() {
    const orders = await fetchApi('staff-php/orders_read.php') || [];
    const users = await fetchApi('staff-php/user_read.php') || [];
    const offers = await fetchApi('staff-php/offer_read.php') || [];

    users.forEach(u => usersDict[u.user_id] = u.name);

    document.getElementById('total-orders').innerText = orders.filter(o => o.order_status === 'Pending').length;
    
    const numCustomers = users.filter(u => u.role === 'Customer').length;
    const numStaff = users.length - numCustomers;
    document.getElementById('total-users').innerText = users.length;
    if (document.getElementById('user-breakdown')) {
        document.getElementById('user-breakdown').innerText = `Customers: ${numCustomers} | Staff: ${numStaff}`;
    }

    // Render Recent Orders (newest 3)
    const recentOrders = [...orders].reverse().slice(0, 3);
    const ordersList = document.getElementById('recent-orders');
    if (ordersList) {
        ordersList.innerHTML = '';
        recentOrders.forEach(order => {
            const card = document.createElement('div');
            card.className = 'list-card order';
            card.innerHTML = `
                <div class="list-info">
                    <span class="badge ${order.order_status === 'Pending' ? 'pending' : ''}" style="${order.order_status === 'Completed' ? 'background:#4CAF50' : ''}">${order.order_status}</span>
                    <h4>Order #${order.order_id} - ${usersDict[order.user_id] || 'Unknown'}</h4>
                    <p>Total: RM ${parseFloat(order.total_price).toFixed(2)} | Date: ${order.order_date}</p>
                </div>
                <div class="list-actions">
                    ${order.order_status === 'Pending' ? `<button class="complete-btn" aria-label="Complete" title="Mark Completed" onclick="completeOrderDash(${order.order_id})"><i class="fas fa-check-circle"></i></button>` : `<button class="complete-btn" style="color:#ccc; cursor:not-allowed;" disabled><i class="fas fa-check-circle"></i></button>`}
                    <button class="delete-btn" aria-label="Delete" title="Delete" onclick="deleteOrderDash(${order.order_id})"><i class="fas fa-trash"></i></button>
                </div>
            `;
            ordersList.appendChild(card);
        });
    }

    // Render Recent Events (newest 3)
    const recentEvents = [...offers].reverse().slice(0, 3);
    const eventsList = document.getElementById('recent-events');
    if (eventsList) {
        eventsList.innerHTML = '';
        recentEvents.forEach(offer => {
            const card = document.createElement('div');
            card.className = 'list-card event';
            card.innerHTML = `
                <div class="list-info">
                    <span class="badge pending">${offer.code}</span>
                    <h4>${offer.title}</h4>
                    <p>${offer.description}</p>
                </div>
                <div class="list-actions">
                    <button class="edit-btn" aria-label="Edit" title="Edit" onclick="location.href='manage-event.php?editId=${offer.offer_id}'"><i class="fas fa-edit"></i></button>
                    <button class="delete-btn" aria-label="Delete" title="Delete" onclick="deleteEventDash(${offer.offer_id})"><i class="fas fa-trash"></i></button>
                </div>
            `;
            eventsList.appendChild(card);
        });
    }
}

async function globalSearch(query) {
    const resultsDiv = document.getElementById('search-results');
    const dashDiv = document.getElementById('dashboard-content');
    const list = document.getElementById('search-list');

    if (!query) {
        if (resultsDiv) resultsDiv.style.display = 'none';
        if (dashDiv) dashDiv.style.display = 'block';
        return;
    }

    if (resultsDiv) resultsDiv.style.display = 'block';
    if (dashDiv) dashDiv.style.display = 'none';
    if (list) list.innerHTML = 'Searching...';

    const results = await fetchApi('staff-php/global_search.php?q=' + encodeURIComponent(query));
    if (list) {
        list.innerHTML = '';
        if (!results || results.length === 0) {
            list.innerHTML = '<p>No results found.</p>';
            return;
        }

        results.forEach(item => {
            const card = document.createElement('div');
            card.className = 'list-card';
            card.style.borderRightColor = '#9C27B0';

            let title = '';
            let desc = '';
            let link = '';

            if (item.type === 'user') { title = `User: ${item.name}`; desc = item.email; link = 'manage-user.php'; }
            if (item.type === 'menu') { title = `Menu: ${item.name}`; desc = `RM ${item.price} - ${item.category}`; link = 'manage-menu.php'; }
            if (item.type === 'offer') { title = `Offer: ${item.title}`; desc = item.code; link = 'manage-event.php'; }
            if (item.type === 'reward') { title = `Reward: ${item.title}`; desc = `${item.bowls_required} Bowls`; link = 'manage-reward.php'; }

            card.innerHTML = `
                <div class="list-info">
                    <h4>${title}</h4>
                    <p>${desc}</p>
                </div>
                <div class="list-actions">
                    <button class="edit-btn" aria-label="Edit" onclick="location.href='${link}'"><i class="fas fa-arrow-right"></i></button>
                </div>
            `;
            list.appendChild(card);
        });
    }
}

async function completeOrderDash(id) {
    const res = await fetchApi('staff-php/order_update.php', 'POST', { order_id: id, order_status: 'Completed' });
    if (res && res.success) loadDashboard();
}

async function deleteOrderDash(id) {
    if (confirm('Delete this order?')) {
        const res = await fetchApi('staff-php/order_delete.php', 'POST', { order_id: id });
        if (res && res.success) loadDashboard();
    }
}

async function deleteEventDash(id) {
    if (confirm('Delete this event?')) {
        const res = await fetchApi('staff-php/offer_delete.php', 'POST', { offer_id: id });
        if (res && res.success) loadDashboard();
    }
}


// ==========================================
// 2. USERS FEATURE
// ==========================================
async function loadUsers() {
    const users = await fetchApi('staff-php/user_read.php');
    renderUsers(users);
}

async function searchUsers(query) {
    if (!query) {
        loadUsers();
        return;
    }
    const users = await fetchApi('staff-php/user_search.php?q=' + encodeURIComponent(query));
    renderUsers(users);
}

function renderUsers(users) {
    const list = document.getElementById('user-list');
    if (!list) return;
    list.innerHTML = '';
    
    let filteredUsers = [];
    if (users && users.length > 0) {
        filteredUsers = users.filter(user => {
            if (currentTab === 'Customer') return user.role === 'Customer';
            return user.role !== 'Customer';
        });
    }

    if (filteredUsers.length === 0) {
        list.innerHTML = `<p>No ${currentTab}s found.</p>`;
        return;
    }
    
    filteredUsers.forEach(user => {
        const isSuperAdmin = user.role === 'super admin';
        const infoHtml = currentTab === 'Customer' ? `🍜 Bowls: ${user.bowls}` : `ID: ${user.user_id}`;
        
        const card = document.createElement('div');
        card.className = 'list-card';
        card.innerHTML = `
            <div class="list-info">
                <h4>${user.name} (${user.role})</h4>
                <p>${user.email}</p>
                <p style="color:var(--primary-orange); font-weight:bold; margin-top:5px;">${infoHtml}</p>
            </div>
            <div class="list-actions">
                ${!isSuperAdmin ? `
                <button class="edit-btn" aria-label="Edit" onclick='editUser(${JSON.stringify(user).replace(/'/g, "&apos;")})'><i class="fas fa-edit"></i></button>
                <button class="delete-btn" aria-label="Delete" onclick="deleteUser(${user.user_id}, '${user.role}')"><i class="fas fa-trash"></i></button>
                ` : '<span style="color:#aaa; font-size:12px;">Protected</span>'}
            </div>
        `;
        list.appendChild(card);
    });
}

function toggleUserFormFields() {
    const role = document.getElementById('edit-role').value;
    if (role === 'Customer') {
        document.getElementById('group-bowls').style.display = 'block';
        document.getElementById('group-address').style.display = 'block';
        document.getElementById('group-gender').style.display = 'none';
        document.getElementById('group-branch').style.display = 'none';
    } else {
        document.getElementById('group-bowls').style.display = 'none';
        document.getElementById('group-address').style.display = 'none';
        document.getElementById('group-gender').style.display = 'block';
        document.getElementById('group-branch').style.display = 'block';
    }
}

function openUserModal() {
    currentEditingId = null;
    showAddForm();
    document.getElementById('form-title').innerText = 'Create User Profile';
    document.getElementById('submit-btn').innerText = 'Save User Entry';
    document.getElementById('edit-name').value = '';
    document.getElementById('edit-email').value = '';
    document.getElementById('edit-role').value = 'Customer';
    toggleUserFormFields();
    document.getElementById('edit-phone').value = '';
    document.getElementById('edit-password').value = '';
    document.getElementById('edit-bowls').value = '0';
    document.getElementById('edit-address').value = '';
    document.getElementById('edit-gender').value = 'Female';
    document.getElementById('edit-branch').value = 'Masisso JB City Square';
}

function editUser(user) {
    currentEditingId = user.user_id;
    showAddForm();
    document.getElementById('form-title').innerText = 'Edit User Profile';
    document.getElementById('submit-btn').innerText = 'Save Changes';
    document.getElementById('edit-name').value = user.name;
    document.getElementById('edit-email').value = user.email;
    document.getElementById('edit-role').value = user.role;
    toggleUserFormFields();
    document.getElementById('edit-phone').value = user.phone || '';
    document.getElementById('edit-password').value = '';
    
    if (user.role === 'Customer') {
        document.getElementById('edit-bowls').value = user.bowls || 0;
        document.getElementById('edit-address').value = user.address || '';
    } else {
        document.getElementById('edit-gender').value = user.gender || 'Female';
        document.getElementById('edit-branch').value = user.branch || 'Masisso JB City Square';
    }
}

async function saveUser() {
    const role = document.getElementById('edit-role').value;
    const data = {
        name: document.getElementById('edit-name').value,
        email: document.getElementById('edit-email').value,
        role: role,
        phone: document.getElementById('edit-phone').value,
    };
    
    const pw = document.getElementById('edit-password').value;
    if (pw) data.password = pw;

    if (role === 'Customer') {
        data.bowls = parseInt(document.getElementById('edit-bowls').value) || 0;
        data.address = document.getElementById('edit-address').value;
    } else {
        data.gender = document.getElementById('edit-gender').value;
        data.branch = document.getElementById('edit-branch').value;
    }

    if (!data.name || !data.email) return alert("Name and Email are required");

    let url = 'staff-php/user_create.php';
    if (currentEditingId) {
        data.user_id = currentEditingId;
        url = 'staff-php/user_update.php';
    }

    const res = await fetchApi(url, 'POST', data);
    if (res && res.success) {
        hideAddForm();
        loadUsers();
    } else {
        alert('Error saving user: ' + (res ? res.error : 'unknown error'));
    }
}

async function deleteUser(id, role) {
    if (confirm('Are you sure you want to delete this user?')) {
        const res = await fetchApi('staff-php/user_delete.php', 'POST', { user_id: id, role: role });
        if (res && res.success) {
            loadUsers();
        } else {
            alert('Error deleting user: ' + (res ? res.error : 'unknown error'));
        }
    }
}


// ==========================================
// 3. ORDERS FEATURE
// ==========================================
async function loadOrdersData() {
    const users = await fetchApi('staff-php/user_read.php');
    if (users) {
        users.forEach(u => usersDict[u.user_id] = u.name);
    }
    loadOrders();
}

async function loadOrders() {
    const orders = await fetchApi('staff-php/orders_read.php');
    renderOrders(orders);
}

async function searchOrders(query) {
    if (!query) {
        loadOrders();
        return;
    }
    const orders = await fetchApi('staff-php/orders_read.php?q=' + encodeURIComponent(query));
    renderOrders(orders);
}

function getOrderUserName(id) {
    return usersDict[id] || 'Unknown';
}

function renderOrders(orders) {
    const list = document.getElementById('order-list');
    if (!list) return;
    list.innerHTML = '';

    if (!orders || orders.length === 0) {
        list.innerHTML = '<p>No orders found.</p>';
        return;
    }

    // Sort to show pending first
    const sortedOrders = [...orders].sort((a, b) => {
        if (a.order_status === 'Pending' && b.order_status !== 'Pending') return -1;
        if (b.order_status === 'Pending' && a.order_status !== 'Pending') return 1;
        return 0;
    });

    sortedOrders.forEach(order => {
        const isCompleted = order.order_status === 'Completed';
        const card = document.createElement('div');
        card.className = `list-card ${isCompleted ? 'completed' : ''}`;
        card.innerHTML = `
            <div class="order-header">
                <h4>Order #${order.order_id}</h4>
                <span class="badge ${isCompleted ? 'completed' : 'pending'}">${order.order_status}</span>
            </div>
            <div class="order-details">
                <p><strong>Customer:</strong> ${getOrderUserName(order.user_id)}</p>
                <p><strong>Type:</strong> ${order.order_type}</p>
                <p><strong>Date:</strong> ${order.order_date}</p>
                <p><strong>Total:</strong> RM ${parseFloat(order.total_price).toFixed(2)}</p>
            </div>
            <div class="order-actions">
                ${!isCompleted ? `<button class="status-btn" onclick="completeOrder(${order.order_id})">Mark Completed</button>` : ''}
                <button class="status-btn" style="background:#f44336; margin-left:10px;" onclick="deleteOrder(${order.order_id})">Delete</button>
            </div>
        `;
        list.appendChild(card);
    });
}

async function completeOrder(id) {
    const res = await fetchApi('staff-php/order_update.php', 'POST', { order_id: id, order_status: 'Completed' });
    if (res && res.success) {
        loadOrders();
    } else {
        alert('Error updating order');
    }
}

async function deleteOrder(id) {
    if (confirm('Are you sure you want to delete this order?')) {
        const res = await fetchApi('staff-php/order_delete.php', 'POST', { order_id: id });
        if (res && res.success) {
            loadOrders();
        } else {
            alert('Error deleting order');
        }
    }
}


// ==========================================
// 4. MENU FEATURE
// ==========================================
async function loadMenu() {
    const menu = await fetchApi('staff-php/menu_read.php');
    renderMenu(menu);
}

async function searchMenu(query) {
    if (!query) {
        loadMenu();
        return;
    }
    const menu = await fetchApi('staff-php/menu_search.php?q=' + encodeURIComponent(query));
    renderMenu(menu);
}

function renderMenu(menuItems) {
    const list = document.getElementById('menu-list');
    if (!list) return;
    list.innerHTML = '';

    if (!menuItems || menuItems.length === 0) {
        list.innerHTML = '<p>No menu items found.</p>';
        return;
    }

    menuItems.forEach(item => {
        const card = document.createElement('div');
        card.className = 'menu-card-admin';
        card.innerHTML = `
            <div class="menu-img">
                ${item.image_url ? `<img src="images/${item.image_url}" alt="${item.name}" style="width:100%; height:100%; object-fit:cover; border-radius:10px;">` : `<i class="fas fa-image"></i>`}
            </div>
            <div class="menu-info">
                <h4>${item.name}</h4>
                <p>${item.category}</p>
                <span class="menu-price">RM ${parseFloat(item.price).toFixed(2)}</span>
            </div>
            <div class="menu-actions">
                <button class="edit-btn" aria-label="Edit" onclick='editMenu(${JSON.stringify(item).replace(/'/g, "&apos;")})'><i class="fas fa-edit"></i></button>
                <button class="delete-btn" aria-label="Delete" onclick="deleteMenu(${item.item_id})"><i class="fas fa-trash"></i></button>
            </div>
        `;
        list.appendChild(card);
    });
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
}

function editMenu(item) {
    editingMenuId = item.item_id;
    showAddForm();
    document.getElementById('form-title').innerText = 'Edit Menu Item';
    document.getElementById('submit-btn').innerText = 'Save Changes';
    document.getElementById('menu-name').value = item.name;
    document.getElementById('menu-category').value = item.category;
    document.getElementById('menu-price').value = item.price;
    document.getElementById('menu-desc').value = item.description;
    document.getElementById('menu-image').value = item.image_url;
}

async function saveMenu() {
    const data = {
        name: document.getElementById('menu-name').value,
        category: document.getElementById('menu-category').value,
        price: parseFloat(document.getElementById('menu-price').value) || 0,
        description: document.getElementById('menu-desc').value,
        image_url: document.getElementById('menu-image').value
    };

    if (!data.name) return alert("Name is required");

    let url = 'staff-php/menu_create.php';
    if (editingMenuId) {
        data.item_id = editingMenuId;
        url = 'staff-php/menu_update.php';
    }

    const res = await fetchApi(url, 'POST', data);
    if (res && res.success) {
        hideAddForm();
        loadMenu();
    } else {
        alert('Error saving menu item');
    }
}

async function deleteMenu(id) {
    if (confirm('Are you sure you want to delete this menu item?')) {
        const res = await fetchApi('staff-php/menu_delete.php', 'POST', { item_id: id });
        if (res && res.success) {
            loadMenu();
        } else {
            alert('Error deleting menu item');
        }
    }
}


// ==========================================
// 5. EVENTS/OFFERS FEATURE
// ==========================================
async function loadOffers() {
    const offers = await fetchApi('staff-php/offer_read.php');
    renderOffers(offers);
}

async function searchOffers(query) {
    if (!query) {
        loadOffers();
        return;
    }
    const offers = await fetchApi('staff-php/offer_search.php?q=' + encodeURIComponent(query));
    renderOffers(offers);
}

function renderOffers(offers) {
    const list = document.getElementById('offer-list');
    if (!list) return;
    list.innerHTML = '';

    if (!offers || offers.length === 0) {
        list.innerHTML = '<p>No offers found.</p>';
        return;
    }

    offers.forEach(offer => {
        const card = document.createElement('div');
        card.className = 'offer-card';
        const discountText = offer.discount_type === 'fixed' ? `RM ${parseFloat(offer.discount_value).toFixed(2)}` : `${offer.discount_value}%`;

        card.innerHTML = `
            <div class="offer-info">
                <div class="offer-code">${offer.code}</div>
                <h4>${offer.title}</h4>
                <p>${offer.description}</p>
                <p><strong>Discount:</strong> ${discountText} | <strong>Min Spend:</strong> RM ${parseFloat(offer.min_spend).toFixed(2)}</p>
                <p><i class="fas fa-calendar-alt"></i> Valid until: ${offer.valid_until}</p>
            </div>
            <div class="offer-actions">
                <button class="edit-btn" aria-label="Edit" onclick='editOffer(${JSON.stringify(offer).replace(/'/g, "&apos;")})'><i class="fas fa-edit"></i></button>
                <button class="delete-btn" aria-label="Delete" onclick="deleteOffer(${offer.offer_id})"><i class="fas fa-trash"></i></button>
            </div>
        `;
        list.appendChild(card);
    });
}

function openOfferModal() {
    editingOfferId = null;
    showAddForm();
    document.getElementById('form-title').innerText = 'Create Offer';
    document.getElementById('submit-btn').innerText = 'Save Offer Entry';
    document.getElementById('offer-code').value = '';
    document.getElementById('offer-title').value = '';
    document.getElementById('offer-desc').value = '';
    document.getElementById('offer-type').value = 'fixed';
    document.getElementById('offer-value').value = '';
    document.getElementById('offer-min').value = '0';
    document.getElementById('offer-valid').value = '';
}

function editOffer(offer) {
    editingOfferId = offer.offer_id;
    showAddForm();
    document.getElementById('form-title').innerText = 'Edit Offer';
    document.getElementById('submit-btn').innerText = 'Save Changes';
    document.getElementById('offer-code').value = offer.code;
    document.getElementById('offer-title').value = offer.title;
    document.getElementById('offer-desc').value = offer.description;
    document.getElementById('offer-type').value = offer.discount_type;
    document.getElementById('offer-value').value = offer.discount_value;
    document.getElementById('offer-min').value = offer.min_spend;
    document.getElementById('offer-valid').value = offer.valid_until;
}

async function saveOffer() {
    const data = {
        code: document.getElementById('offer-code').value,
        title: document.getElementById('offer-title').value,
        description: document.getElementById('offer-desc').value,
        discount_type: document.getElementById('offer-type').value,
        discount_value: parseFloat(document.getElementById('offer-value').value) || 0,
        min_spend: parseFloat(document.getElementById('offer-min').value) || 0,
        valid_until: document.getElementById('offer-valid').value
    };

    if (!data.code || !data.title) return alert("Code and Title are required");

    let url = 'staff-php/offer_create.php';
    if (editingOfferId) {
        data.offer_id = editingOfferId;
        url = 'staff-php/offer_update.php';
    }

    const res = await fetchApi(url, 'POST', data);
    if (res && res.success) {
        hideAddForm();
        loadOffers();
    } else {
        alert('Error saving offer');
    }
}

async function deleteOffer(id) {
    if (confirm('Are you sure you want to delete this offer?')) {
        const res = await fetchApi('staff-php/offer_delete.php', 'POST', { offer_id: id });
        if (res && res.success) {
            loadOffers();
        } else {
            alert('Error deleting offer');
        }
    }
}


// ==========================================
// 6. REWARDS FEATURE
// ==========================================
async function loadRewards() {
    const rewards = await fetchApi('staff-php/reward_read.php');
    renderRewards(rewards);
}

async function searchRewards(query) {
    if (!query) {
        loadRewards();
        return;
    }
    const rewards = await fetchApi('staff-php/reward_search.php?q=' + encodeURIComponent(query));
    renderRewards(rewards);
}

function renderRewards(rewards) {
    const list = document.getElementById('reward-list');
    if (!list) return;
    list.innerHTML = '';

    if (!rewards || rewards.length === 0) {
        list.innerHTML = '<p>No rewards found.</p>';
        return;
    }

    rewards.forEach(reward => {
        const card = document.createElement('div');
        card.className = 'reward-card';
        card.innerHTML = `
            <div class="reward-img">
                ${reward.image_url ? `<img src="images/${reward.image_url}" alt="${reward.title}" style="width:100%; height:100%; object-fit:cover; border-radius:10px;">` : `<i class="fas fa-gift fa-2x"></i>`}
            </div>
            <div class="reward-info">
                <h4>${reward.title}</h4>
                <span class="reward-bowls">🍜 ${reward.bowls_required} Bowls Required</span>
            </div>
            <div class="reward-actions">
                <button class="edit-btn" aria-label="Edit" onclick='editReward(${JSON.stringify(reward).replace(/'/g, "&apos;")})'><i class="fas fa-edit"></i></button>
                <button class="delete-btn" aria-label="Delete" onclick="deleteReward(${reward.reward_id})"><i class="fas fa-trash"></i></button>
            </div>
        `;
        list.appendChild(card);
    });
}

function openRewardModal() {
    editingRewardId = null;
    showAddForm();
    document.getElementById('form-title').innerText = 'Create Reward';
    document.getElementById('submit-btn').innerText = 'Save Reward Entry';
    document.getElementById('reward-title').value = '';
    document.getElementById('reward-bowls').value = '';
    document.getElementById('reward-image').value = '';
}

function editReward(reward) {
    editingRewardId = reward.reward_id;
    showAddForm();
    document.getElementById('form-title').innerText = 'Edit Reward';
    document.getElementById('submit-btn').innerText = 'Save Changes';
    document.getElementById('reward-title').value = reward.title;
    document.getElementById('reward-bowls').value = reward.bowls_required;
    document.getElementById('reward-image').value = reward.image_url;
}

async function saveReward() {
    const data = {
        title: document.getElementById('reward-title').value,
        bowls_required: parseInt(document.getElementById('reward-bowls').value) || 0,
        image_url: document.getElementById('reward-image').value
    };

    if (!data.title || data.bowls_required <= 0) return alert("Title and positive Bowls Required are mandatory");

    let url = 'staff-php/reward_create.php';
    if (editingRewardId) {
        data.reward_id = editingRewardId;
        url = 'staff-php/reward_update.php';
    }

    const res = await fetchApi(url, 'POST', data);
    if (res && res.success) {
        hideAddForm();
        loadRewards();
    } else {
        alert('Error saving reward');
    }
}

async function deleteReward(id) {
    if (confirm('Are you sure you want to delete this reward?')) {
        const res = await fetchApi('staff-php/reward_delete.php', 'POST', { reward_id: id });
        if (res && res.success) {
            loadRewards();
        } else {
            alert('Error deleting reward');
        }
    }
}


// ==========================================
// ROUTER INITIALIZATION
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    const path = window.location.pathname;
    if (path.includes('admin-dashboard.html') || path.includes('admin-dashboard.php')) {
        loadDashboard();
    } else if (path.includes('manage-user.html') || path.includes('manage-user.php')) {
        loadUsers();
    } else if (path.includes('manage-order.html') || path.includes('manage-order.php')) {
        loadOrdersData();
    } else if (path.includes('manage-menu.html') || path.includes('manage-menu.php')) {
        loadMenu();
    } else if (path.includes('manage-event.html') || path.includes('manage-event.php')) {
        loadOffers();
        const urlParams = new URLSearchParams(window.location.search);
        const editId = urlParams.get('editId');
        if (editId) {
            setTimeout(async () => {
                const offers = await fetchApi('staff-php/offer_read.php');
                if (offers) {
                    const offer = offers.find(o => o.offer_id == editId);
                    if (offer) editOffer(offer);
                }
            }, 300);
        }
    } else if (path.includes('manage-reward.html') || path.includes('manage-reward.php')) {
        loadRewards();
    }
});
