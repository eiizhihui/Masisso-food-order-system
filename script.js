// Dynamic Greeting 
window.onload = function() {
    let customerName = "Joey"; 
    let greetingElement = document.getElementById("greeting");
    if (greetingElement) greetingElement.innerText = "Hey, " + customerName + " 👋";
};

// load the menu from the database 
document.addEventListener('DOMContentLoaded', () => {
    fetch('fetch_menu.php?nocache=' + new Date().getTime())
        .then(response => response.json())
        .then(data => {
            const menuContainer = document.getElementById('popular-menu-container');
            if(menuContainer) menuContainer.innerHTML = ''; 
            
            window.masissoMenu = data;
            
            if(!menuContainer) return;

            const menuByCategory = {};
            data.forEach(item => {
                let categoryName = item.category ? item.category : 'Other'; 
                if (!menuByCategory[categoryName]) {
                    menuByCategory[categoryName] = [];
                }
                menuByCategory[categoryName].push(item);
            });

            for (const category in menuByCategory) {
                menuContainer.innerHTML += `<h2 class="section-title">${category}</h2>`;
                menuByCategory[category].forEach(item => {
                    let imgFile = item.image_url ? item.image_url : 'default.jpg';
                    
                    let isAvailable = (item.is_available === undefined || item.is_available == 1 || item.is_available === "1");

                    let cardOpacity = isAvailable ? "1" : "0.6";
                    let imgFilter = isAvailable ? "none" : "grayscale(100%)";
                    
                    let buttonHtml = isAvailable 
                        ? `<button class="add-btn" onclick="openCustomization(${item.item_id})">+ Add</button>` 
                        : `<button class="add-btn" style="background: #999; cursor: not-allowed; border-color: #999; color: white;" disabled>Unavailable</button>`;

                    menuContainer.innerHTML += `
                        <div class="menu-card" style="opacity: ${cardOpacity}">
                            <img src="images/${imgFile}" alt="${item.name}" class="menu-image" style="filter: ${imgFilter}">
                            <div class="menu-info">
                                <h3 class="menu-title">${item.name}</h3>
                                <p class="menu-price">RM ${parseFloat(item.price).toFixed(2)}</p>
                                <p class="menu-desc">${item.description}</p>
                            </div>
                            ${buttonHtml}
                        </div>
                    `;
                });
            }
        })
        .catch(error => {
            console.error("Error loading menu:", error);
            if(document.getElementById('popular-menu-container')) {
                document.getElementById('popular-menu-container').innerHTML = "<p>Failed to load menu. Is XAMPP running?</p>";
            }
        });
});

function openMapSelector() {
    let address = prompt("Please enter your delivery address or drop a pin:");
    if (address && address.trim() !== "") {
        document.getElementById("delivery-address").innerText = "Delivery to: " + address;
    }
}

let currentItem = "";
let currentPrice = 0.00;
let currentQuantity = 1; 

let cartCount = parseInt(localStorage.getItem('masisso_cart_count')) || 0;
let cartTotal = parseFloat(localStorage.getItem('masisso_cart_total')) || 0;

document.addEventListener('DOMContentLoaded', () => {
    if (cartCount > 0) {
        let floatingCart = document.getElementById('floating-cart');
        if (floatingCart) {
            floatingCart.classList.remove('hidden');
            let cartTextDiv = document.querySelector('.cart-text div:nth-child(2)');
            if (cartTextDiv) {
                cartTextDiv.innerText = cartCount + (cartCount === 1 ? ' item' : ' items') + ' • RM ' + cartTotal.toFixed(2);
            }
            let cartItemsArray = JSON.parse(localStorage.getItem('masisso_cart_items')) || [];
            if (cartItemsArray.length > 0) {
                let lastItem = cartItemsArray[cartItemsArray.length - 1];
                let cartImg = document.getElementById('floating-cart-img');
                if (cartImg && lastItem.image) {
                    cartImg.src = 'images/' + lastItem.image;
                }
            }
        }
    }
});

let currentItemImage = "";

function openCustomization(itemId) { 
    let item = window.masissoMenu.find(m => m.item_id == itemId);
    if (!item) return;

    let itemName = item.name;
    let itemPrice = parseFloat(item.price);
    let itemImage = item.image_url ? item.image_url : 'default.jpg';
    let itemCategory = item.category ? item.category : 'Other';

    currentItem = itemName;
    currentPrice = itemPrice;
    currentItemImage = itemImage; 
    currentQuantity = 1; 

    let comboSection = document.getElementById('combo-section');
    let comboDivider = document.getElementById('combo-divider');
    let preferencesSection = document.getElementById('preferences-section');
    let dynamicPreferencesList = document.getElementById('dynamic-preferences-list');
    
    if (comboSection && comboDivider) {
        if (itemCategory === 'Combo' || itemName.toLowerCase().includes('combo') || itemName.includes('+')) {
            comboSection.style.display = 'none';
            comboDivider.style.display = 'none';
            let aLaCarteRadio = document.querySelector('input[name="combo"][value="0"]');
            if(aLaCarteRadio) aLaCarteRadio.checked = true;
        } else {
            comboSection.style.display = 'block';
            comboDivider.style.display = 'block';
            document.getElementById('combo-name-1').innerText = `A La Carte (Just the ${itemName})`;
            document.getElementById('combo-price-1').innerText = `+ RM 0.00`;
            document.getElementById('combo-radio-1').value = 0;

            if (itemName.toLowerCase().includes('laksa')) {
                document.getElementById('combo-name-2').innerText = `Add Teh C Beng Special (三色奶茶)`;
                document.getElementById('combo-price-2').innerText = `+ RM 4.50`;
                document.getElementById('combo-radio-2').value = 4.50;
                document.getElementById('combo-name-3').innerText = `Add Fruit Rojak`;
                document.getElementById('combo-price-3').innerText = `+ RM 3.00`;
                document.getElementById('combo-radio-3').value = 3.00;
            } else if (itemName.toLowerCase().includes('rojak')) {
                document.getElementById('combo-name-2').innerText = `Add Laksa`;
                document.getElementById('combo-price-2').innerText = `+ RM 14.90`;
                document.getElementById('combo-radio-2').value = 14.90;
                document.getElementById('combo-name-3').innerText = `Add Teh C Beng Special`;
                document.getElementById('combo-price-3').innerText = `+ RM 4.50`;
                document.getElementById('combo-radio-3').value = 4.50;
            } else if (itemCategory.toLowerCase().includes('drink') || itemName.toLowerCase().includes('teh') || itemName.toLowerCase().includes('kopi')) {
                document.getElementById('combo-name-2').innerText = `Add Laksa`;
                document.getElementById('combo-price-2').innerText = `+ RM 14.90`;
                document.getElementById('combo-radio-2').value = 14.90;
                document.getElementById('combo-name-3').innerText = `Add Fruit Rojak`;
                document.getElementById('combo-price-3').innerText = `+ RM 6.90`;
                document.getElementById('combo-radio-3').value = 6.90;
            } else {
                document.getElementById('combo-name-2').innerText = `Add Teh C Beng Special`;
                document.getElementById('combo-price-2').innerText = `+ RM 4.50`;
                document.getElementById('combo-radio-2').value = 4.50;
                document.getElementById('combo-name-3').innerText = `Add Fruit Rojak`;
                document.getElementById('combo-price-3').innerText = `+ RM 6.90`;
                document.getElementById('combo-radio-3').value = 6.90;
            }
        }
    }

    if (preferencesSection && dynamicPreferencesList) {
        dynamicPreferencesList.innerHTML = ''; 
        let prefObj = null;
        try {
            if (item.preferences) {
                prefObj = JSON.parse(item.preferences);
            }
        } catch(e) {
            console.error("Error parsing preferences", e);
        }

        if (prefObj && Object.keys(prefObj).length > 0) {
            preferencesSection.style.display = 'block';
            for (const category in prefObj) {
                dynamicPreferencesList.innerHTML += `<h4 style="margin: 15px 0 5px; color: #e65100; font-size: 14px; border-bottom: 1px solid #ffe0b2; padding-bottom: 3px;">${category}</h4>`;
                prefObj[category].forEach(pref => {
                    dynamicPreferencesList.innerHTML += `
                        <label class="custom-checkbox">
                            <input type="checkbox" value="${pref}"> ${pref}
                        </label>
                    `;
                });
            }
        } else {
            preferencesSection.style.display = 'none';
        }
    }

    document.getElementById("modal-item-name").innerText = itemName;
    document.getElementById("modal-base-price").innerText = currentPrice.toFixed(2);
    let modalImage = document.querySelector('.product-hero img');
    if (modalImage) {
        modalImage.src = 'images/' + itemImage;
    }
    document.getElementById("display-total").innerText = currentPrice.toFixed(2);
    document.getElementById("display-quantity").innerText = currentQuantity;
    document.querySelector('input[name="combo"][value="0"]').checked = true;
    document.getElementById("customize-modal").style.display = "block";
}

function closeModal() {
    document.getElementById("customize-modal").style.display = "none";
}

function updateQuantity(change) {
    currentQuantity += change;
    if (currentQuantity < 1) {
        currentQuantity = 1;
    }
    document.getElementById('display-quantity').innerText = currentQuantity;
    let comboPrice = parseFloat(document.querySelector('input[name="combo"]:checked').value);
    let finalTotal = (currentPrice + comboPrice) * currentQuantity;
    document.getElementById('display-total').innerText = finalTotal.toFixed(2);
}

function calculateModalPrice() {
    updateQuantity(0); 
}

function confirmAddToCart() {
    let comboElement = document.querySelector('input[name="combo"]:checked');
    let comboPrice = comboElement ? parseFloat(comboElement.value) : 0;
    
    let comboName = "";
    if (comboElement && comboElement.nextElementSibling) {
        let nameSpan = comboElement.nextElementSibling.querySelector('.option-name');
        if (nameSpan) comboName = nameSpan.innerText;
    }
    
    let finalPrice = (currentPrice + comboPrice) * currentQuantity;
    
    let preferences = [];
    let checkedBoxes = document.querySelectorAll('#dynamic-preferences-list input[type="checkbox"]:checked');
    checkedBoxes.forEach(box => {
        preferences.push(box.value);
    });

    let cartItem = {
        name: currentItem,
        image: currentItemImage,
        basePrice: currentPrice,
        comboName: comboName,
        comboPrice: comboPrice,
        preferences: preferences,
        quantity: currentQuantity,
        totalPrice: finalPrice
    };

    let cartItemsArray = JSON.parse(localStorage.getItem('masisso_cart_items')) || [];
    cartItemsArray.push(cartItem);
    localStorage.setItem('masisso_cart_items', JSON.stringify(cartItemsArray));

    cartCount += currentQuantity;
    cartTotal += finalPrice;
    
    let floatingCart = document.getElementById('floating-cart');
    if (floatingCart) {
        floatingCart.classList.remove('hidden');
        let cartTextDiv = document.querySelector('.cart-text div:nth-child(2)');
        if (cartTextDiv) {
            cartTextDiv.innerText = cartCount + (cartCount === 1 ? ' item' : ' items') + ' • RM ' + cartTotal.toFixed(2);
        }
        let cartImg = document.getElementById('floating-cart-img');
        if (cartImg && currentItemImage) {
            cartImg.src = 'images/' + currentItemImage;
        }
    }

    localStorage.setItem('masisso_cart_total', cartTotal.toFixed(2));
    localStorage.setItem('masisso_cart_count', cartCount);
    closeModal();
}

document.addEventListener('DOMContentLoaded', () => {
    const btnDelivery = document.getElementById('btn-delivery');
    const btnPickup   = document.getElementById('btn-pickup');

    let currentMode = "Delivery"; 

    if (btnDelivery && btnPickup) {
        btnDelivery.addEventListener('click', () => {
            openOrderModal('delivery-modal');
            setActiveToggle('Delivery');
        });

        btnPickup.addEventListener('click', () => {
            openOrderModal('pickup-modal');
            setActiveToggle('Pickup');
        });
    }

    function setActiveToggle(mode) {
        currentMode = mode;
        if (mode === 'Delivery') {
            btnDelivery.classList.add('active');
            btnPickup.classList.remove('active');
        } else {
            btnPickup.classList.add('active');
            btnDelivery.classList.remove('active');
        }
    }
});

function openOrderModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show'); 
    }
}

function closeOrderModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show'); 
    }
}

document.addEventListener('DOMContentLoaded', () => {
    ['delivery-modal', 'pickup-modal'].forEach(id => {
        const overlay = document.getElementById(id);
        if (overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) closeOrderModal(id);
            });
        }
    });
});

function toggleAccordion() {
    const content = document.getElementById('accordion-content');
    const icon = document.getElementById('accordion-icon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)'; 
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

function executeSearch() {
    const inputElement = document.getElementById('searchInput');
    if (!inputElement) return; 
    
    const keyword = inputElement.value.trim();
    const resultsContainer = document.getElementById('search-results-container');
    if (!resultsContainer) return;

    if (keyword === "") {
        resultsContainer.innerHTML = '<p style="color: #888; text-align: center; margin-top: 20px;">Start typing to search menu...</p>';
        return;
    }
    
    resultsContainer.innerHTML = '<p style="text-align:center; color:#888;">Searching...</p>';

    fetch(`search_menu.php?keyword=${encodeURIComponent(keyword)}`)
        .then(response => response.json())
        .then(data => {
            resultsContainer.innerHTML = ''; 
            
            // Store search results so openCustomization works!
            window.masissoMenu = data;

            if(data.length === 0) {
                resultsContainer.innerHTML = '<p style="text-align:center; color:#888;">No items found matching "' + keyword + '".</p>';
                return;
            }

            data.forEach(item => {
                let imgFile = item.image_url ? item.image_url : 'default.jpg';

                let isAvailable = (item.is_available === undefined || item.is_available == 1 || item.is_available === "1");

                let cardOpacity = isAvailable ? "1" : "0.6";
                let imgFilter = isAvailable ? "none" : "grayscale(100%)";
                
                let buttonHtml = isAvailable 
                    ? `<button class="add-btn" onclick="openCustomization(${item.item_id})">+ Add</button>` 
                    : `<button class="add-btn" style="background: #999; cursor: not-allowed; border-color: #999; color: white;" disabled>Unavailable</button>`;

                resultsContainer.innerHTML += `
                    <div class="menu-card" style="opacity: ${cardOpacity}">
                        <img src="images/${imgFile}" alt="${item.name}" class="menu-image" style="filter: ${imgFilter}">
                        <div class="menu-info">
                            <h3 class="menu-title">${item.name}</h3>
                            <p class="menu-price">RM ${parseFloat(item.price).toFixed(2)}</p>
                            <p class="menu-desc">${item.description}</p>
                        </div>
                        ${buttonHtml}
                    </div>
                `;
            });
        })
        .catch(error => {
            console.error("Search error:", error);
            resultsContainer.innerHTML = "<p>Error searching the database.</p>";
        });
}

// OFFERS PAGE 
document.addEventListener('DOMContentLoaded', () => {
    const offersContainer = document.getElementById('offers-container');
    if (offersContainer) {
        fetch('fetch_offers.php')
            .then(response => response.json())
            .then(data => {
                offersContainer.innerHTML = ''; 
                if (data.length === 0) {
                    offersContainer.innerHTML = '<p style="text-align:center;">No offers available right now.</p>';
                    return;
                }
                data.forEach(offer => {
                    offersContainer.innerHTML += `
                        <div class="dash-card" style="border-left: 5px solid #E91E63; margin-bottom: 15px; text-align: left; padding: 15px; background: white; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                            <h3 style="margin-top: 0; color: #333;">${offer.title} (${offer.code})</h3>
                            <p style="color: #666; font-size: 14px;">${offer.description}</p>
                            <button class="add-btn" style="background: transparent; border: 2px solid #E65100; color: #E65100; padding: 8px 15px; border-radius: 20px; cursor: pointer; font-weight: bold; width: 100%; margin-top: 10px;" onclick="applyPromo('${offer.code}')">Add Voucher</button>
                        </div>
                    `;
                });
            })
            .catch(error => {
                console.error("Error loading offers:", error);
                offersContainer.innerHTML = "<p>Error loading offers. Make sure XAMPP is running!</p>";
            });
    }
});

function applyPromo(code) {
    localStorage.setItem('masisso_active_voucher', code);
    alert("Voucher " + code + " has been added! It will be automatically applied at checkout.");
}

// REWARDS PAGE LOGIC
document.addEventListener('DOMContentLoaded', () => {
    const rewardsContainer = document.getElementById('rewards-container');
    if (rewardsContainer) {
        fetch('fetch_rewards.php')
            .then(response => response.json())
            .then(data => {
                rewardsContainer.innerHTML = ''; 
                data.forEach(reward => {
                    rewardsContainer.innerHTML += `
                        <div class="menu-card" style="display: flex; align-items: center; margin-bottom: 15px; background: white; padding: 10px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            <img src="images/${reward.image_url}" alt="${reward.reward_name}" style="width: 80px; height: 80px; border-radius: 10px; object-fit: cover; margin-right: 15px;">
                            <div style="flex-grow: 1;">
                                <h3 style="margin: 0 0 5px 0; font-size: 16px;">${reward.reward_name}</h3>
                                <p style="margin: 0; color: #E65100; font-weight: bold;">🪙 ${reward.points_required} pts</p>
                            </div>
                            <button onclick="redeemItem('${reward.reward_name}', ${reward.points_required}, '${reward.image_url}')" style="background: #E65100; color: white; border: none; padding: 10px 15px; border-radius: 8px; font-weight: bold; cursor: pointer;">Redeem</button>
                        </div>
                    `;
                });
            })
            .catch(error => {
                console.error("Error loading rewards:", error);
                rewardsContainer.innerHTML = "<p>Failed to load rewards. Is XAMPP running?</p>";
            });
    }
});

let currentUserPoints = 1000; 

function redeemItem(itemName, pointsCost, itemImage) {
    if (currentUserPoints >= pointsCost) {
        let confirmRedeem = confirm(`Do you want to spend ${pointsCost} points for a free ${itemName}?`);
        if (confirmRedeem) {
            currentUserPoints -= pointsCost;
            let pointsDisplay = document.getElementById('user-points-display');
            if(pointsDisplay) pointsDisplay.innerText = currentUserPoints;
            
            let cartItem = {
                name: "Free " + itemName + " (Reward)",
                image: itemImage || "default.jpg",
                basePrice: 0.00,
                comboName: "",
                comboPrice: 0.00,
                preferences: [],
                quantity: 1,
                totalPrice: 0.00
            };

            let cartItemsArray = JSON.parse(localStorage.getItem('masisso_cart_items')) || [];
            cartItemsArray.push(cartItem);
            localStorage.setItem('masisso_cart_items', JSON.stringify(cartItemsArray));
            
            let cartCount = parseInt(localStorage.getItem('masisso_cart_count')) || 0;
            localStorage.setItem('masisso_cart_count', cartCount + 1);

            alert(`Success! Your free ${itemName} has been added to your cart. You have ${currentUserPoints} points remaining.`);
        }
    } else {
        alert(`Not enough points! You need ${pointsCost - currentUserPoints} more points to get the ${itemName}. Keep ordering!`);
    }
}

// --- FAKE DATABASE FOR SEARCH BARS ---
const deliveryLocations = [
    "123 Jalan Wong Ah Fook, Johor Bahru",
    "UTM Skudai Campus, Johor",
    "K38 - 38 Jalan Cengal, Taman Melodies"
];

const pickupStores = [
    "Masisso JB City Square (0.5km)",
    "Masisso Mount Austin (5.2km)",
    "Masisso Paradigm Mall (8.1km)"
];

// --- SEARCH BAR LOGIC ---
function setupLocationSearch(inputId, resultsId, dataList, modalId) {
    const input = document.getElementById(inputId);
    const resultsBox = document.getElementById(resultsId);

    if (!input || !resultsBox) return;

    input.addEventListener('input', function() {
        const keyword = this.value.toLowerCase();
        resultsBox.innerHTML = ''; 

        if (keyword === "") {
            resultsBox.style.display = 'none';
            return;
        }

        const filtered = dataList.filter(item => item.toLowerCase().includes(keyword));

        resultsBox.style.display = 'block';
        if (filtered.length > 0) {
            filtered.forEach(item => {
                resultsBox.innerHTML += `
                    <div onclick="selectLocation('${item}', '${modalId}')" style="padding: 12px 15px; border-bottom: 1px solid #eee; cursor: pointer; color: #333; font-size: 14px;">
                        📍 ${item}
                    </div>
                `;
            });
        } else {
            resultsBox.innerHTML = '<div style="padding: 12px 15px; color: #888; font-size: 14px;">No locations found</div>';
        }
    });
}

let activeOrderMode = "Delivery"; 

function selectLocation(locName, modalId) {
    if (modalId === 'delivery-modal') {
        activeOrderMode = "Delivery";
        document.getElementById('header-selected-icon').innerText = "🛵";
        document.getElementById('header-selected-mode').innerText = "Delivering to";
    } else {
        activeOrderMode = "Pickup";
        document.getElementById('header-selected-icon').innerText = "🏪";
        document.getElementById('header-selected-mode').innerText = "Picking up from";
        locName = locName.split(' (')[0]; 
    }

    localStorage.setItem('masisso_order_mode', activeOrderMode);
    localStorage.setItem('masisso_location_name', locName);
    document.getElementById('header-selected-text').innerText = locName;

    document.getElementById('header-toggle-state').classList.add('hidden');
    document.getElementById('header-selected-state').classList.remove('hidden');

    document.getElementById('delivery-search-input').value = "";
    document.getElementById('pickup-search-input').value = "";
    if(document.getElementById('delivery-results')) document.getElementById('delivery-results').style.display = 'none';
    if(document.getElementById('pickup-results')) document.getElementById('pickup-results').style.display = 'none';
    
    closeOrderModal(modalId);
}

function reopenCurrentModal() {
    document.getElementById('header-selected-state').classList.add('hidden');
    document.getElementById('header-toggle-state').classList.remove('hidden');
}

function renderNearbyBranches() {
    const listContainer = document.getElementById('nearby-branches-list');
    if (!listContainer) return;

    pickupStores.forEach(store => {
        listContainer.innerHTML += `
            <div onclick="selectLocation('${store}', 'pickup-modal')" style="padding: 15px; border-bottom: 1px solid #eee; cursor: pointer; color: #222; font-size: 15px; display: flex; align-items: center; font-weight: bold;">
                <span style="font-size: 20px; margin-right: 12px; color: #e65100;">🏪</span>
                ${store}
            </div>
        `;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    setupLocationSearch('delivery-search-input', 'delivery-results', deliveryLocations, 'delivery-modal');
    setupLocationSearch('pickup-search-input', 'pickup-results', pickupStores, 'pickup-modal');
    renderNearbyBranches(); 

    // Automatically restore the selected location state!
    let savedMode = localStorage.getItem('masisso_order_mode');
    let savedLocation = localStorage.getItem('masisso_location_name');
    if (savedMode && savedLocation) {
        let elIcon = document.getElementById('header-selected-icon');
        let elMode = document.getElementById('header-selected-mode');
        let elText = document.getElementById('header-selected-text');
        let elToggle = document.getElementById('header-toggle-state');
        let elSelected = document.getElementById('header-selected-state');

        if (elIcon && elMode && elText && elToggle && elSelected) {
            if (savedMode === "Delivery") {
                elIcon.innerText = "🛵";
                elMode.innerText = "Delivering to";
            } else {
                elIcon.innerText = "🏪";
                elMode.innerText = "Picking up from";
            }
            elText.innerText = savedLocation;
            elToggle.classList.add('hidden');
            elSelected.classList.remove('hidden');
        }
    }
});

// --- PROFILE PAGE LOGIC ---
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('profile-view-mode')) {
        loadProfile();
    }
});

function loadProfile() {
    fetch('fetch_profile.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let profile = data.profile;
                document.getElementById('display-name').innerText = profile.name || "N/A";
                document.getElementById('display-email').innerText = profile.email || "N/A";
                let dp = document.getElementById('display-phone');
                if(dp) dp.innerText = profile.phone || "N/A";
                let da = document.getElementById('display-address');
                if(da) da.innerText = profile.address || "N/A";
                
                document.getElementById('edit-name').value = profile.name || "";
                document.getElementById('edit-email').value = profile.email || "";
                let ep = document.getElementById('edit-phone');
                if(ep) ep.value = profile.phone || "";
                let ea = document.getElementById('edit-address');
                if(ea) ea.value = profile.address || "";
            } else {
                console.error("Failed to load profile", data.message);
            }
        })
        .catch(err => console.error("Error fetching profile:", err));
}

function toggleEditProfile() {
    let viewMode = document.getElementById('profile-view-mode');
    let editMode = document.getElementById('profile-edit-mode');
    
    if (viewMode.style.display === 'none') {
        viewMode.style.display = 'block';
        editMode.style.display = 'none';
    } else {
        viewMode.style.display = 'none';
        editMode.style.display = 'block';
    }
}

function saveProfile() {
    let updatedData = {
        name: document.getElementById('edit-name').value.trim(),
        email: document.getElementById('edit-email').value.trim(),
        phone: document.getElementById('edit-phone') ? document.getElementById('edit-phone').value.trim() : "",
        address: document.getElementById('edit-address') ? document.getElementById('edit-address').value.trim() : ""
    };
    
    if (!updatedData.name || !updatedData.email) {
        alert("Name and Email are required!");
        return;
    }

    let saveBtn = document.querySelector('#profile-edit-mode button.add-btn');
    let originalText = saveBtn.innerText;
    saveBtn.innerText = "Saving...";

    fetch('update_profile.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updatedData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            toggleEditProfile(); 
            loadProfile(); 
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(err => {
        console.error("Error saving profile:", err);
        alert("A network error occurred while saving.");
    })
    .finally(() => {
        saveBtn.innerText = originalText;
    });
}
