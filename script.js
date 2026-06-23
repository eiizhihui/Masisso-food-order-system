// Dynamic Greeting 
window.onload = function() {
    // use data from mysql - need sn change ba , do profile, login
    let customerName = "Joey"; 
    let greetingElement = document.getElementById("greeting");
    
    greetingElement.innerText = "Hey, " + customerName + " 👋";
};

// load the menu from the database 
document.addEventListener('DOMContentLoaded', () => {
    
    fetch('fetch_menu.php?nocache=' + new Date().getTime())
        .then(response => response.json())
        .then(data => {
            const menuContainer = document.getElementById('popular-menu-container');
            menuContainer.innerHTML = ''; // Clear the "Loading..." text

            // Group the food items by their database Category
            const menuByCategory = {};
            
            data.forEach(item => {
                let categoryName = item.category ? item.category : 'Other'; 
                
                if (!menuByCategory[categoryName]) {
                    menuByCategory[categoryName] = [];
                }
                
                menuByCategory[categoryName].push(item);
            });

            // 2. Loop through our new grouped categories to build the screen
            for (const category in menuByCategory) {
                
                // Print the Section Title
                menuContainer.innerHTML += `
                    <h2 class="section-title">${category}</h2>
                `;

                // Print every food item
                menuByCategory[category].forEach(item => {
                    
                    // Safety check: if no image is in the database, use a blank/default one
                    let imgFile = item.image_url ? item.image_url : 'default.jpg';

                    menuContainer.innerHTML += `
                        <div class="menu-card">
                            
                            <img src="images/${imgFile}" alt="${item.name}" class="menu-image">

                            <div class="menu-info">
                                <h3 class="menu-title">${item.name}</h3>
                                <p class="menu-price">RM ${parseFloat(item.price).toFixed(2)}</p>
                                <p class="menu-desc">${item.description}</p>
                            </div>
                            
                            <button class="add-btn" onclick="openCustomization('${item.name}', ${parseFloat(item.price)}, '${imgFile}', '${category}')">+ Add</button>
                        </div>
                    `;
                });
            }
        })
        .catch(error => {
            console.error("Error loading menu:", error);
            document.getElementById('popular-menu-container').innerHTML = "<p>Failed to load menu. Is XAMPP running?</p>";
        });
});

// 2. Address / Map Feature
function openMapSelector() {
    // In a real app, this would open Google Maps API. For the project, a prompt works perfectly.
    let address = prompt("Please enter your delivery address or drop a pin:");
    if (address && address.trim() !== "") {
        document.getElementById("delivery-address").innerText = "Delivery to: " + address;
    }
}

// Customization Modal
let currentItem = "";
let currentPrice = 0.00;
let currentQuantity = 1; 

// Initialize from localStorage so cart survives page refreshes!
let cartCount = parseInt(localStorage.getItem('masisso_cart_count')) || 0;
let cartTotal = parseFloat(localStorage.getItem('masisso_cart_total')) || 0;

document.addEventListener('DOMContentLoaded', () => {
    // Restore the floating cart if there are items from a previous session
    if (cartCount > 0) {
        let floatingCart = document.getElementById('floating-cart');
        if (floatingCart) {
            floatingCart.classList.remove('hidden');
            let cartTextDiv = document.querySelector('.cart-text div:nth-child(2)');
            if (cartTextDiv) {
                cartTextDiv.innerText = cartCount + (cartCount === 1 ? ' item' : ' items') + ' • RM ' + cartTotal.toFixed(2);
            }
            // Restore the image of the last item added to the cart
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

// Add a new variable at the top to track the image
let currentItemImage = "";

function openCustomization(itemName, itemPrice, itemImage, itemCategory) { 
    currentItem = itemName;
    currentPrice = itemPrice;
    currentItemImage = itemImage; 
    currentQuantity = 1; 

    // Logic to show/hide sections intelligently!
    let comboSection = document.getElementById('combo-section');
    let comboDivider = document.getElementById('combo-divider');
    let preferencesSection = document.getElementById('preferences-section');
    
    // 1. Show "Choose Your Combo" for everything EXCEPT existing combos
    if (comboSection && comboDivider) {
        if (itemCategory === 'Combo' || itemName.toLowerCase().includes('combo') || itemName.includes('+')) {
            comboSection.style.display = 'none';
            comboDivider.style.display = 'none';
            let aLaCarteRadio = document.querySelector('input[name="combo"][value="0"]');
            if(aLaCarteRadio) aLaCarteRadio.checked = true;
        } else {
            comboSection.style.display = 'block';
            comboDivider.style.display = 'block';

            // Dynamically update the combo text and prices!
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

    // 2. Hide "Preferences" (No Coriander, etc) if it's a Drink!
    if (preferencesSection) {
        if (itemCategory.toLowerCase().includes('drink') || itemCategory.toLowerCase().includes('beverage') || itemName.toLowerCase().includes('teh') || itemName.toLowerCase().includes('kopi')) {
            preferencesSection.style.display = 'none';
        } else {
            preferencesSection.style.display = 'block';
        }
    }

    // 1. Update the text at the top of the modal
    document.getElementById("modal-item-name").innerText = itemName;
    document.getElementById("modal-base-price").innerText = currentPrice.toFixed(2);

    // 2. THE MAGIC: Update the image at the top of the modal dynamically!
    let modalImage = document.querySelector('.product-hero img');
    if (modalImage) {
        modalImage.src = 'images/' + itemImage;
    }

    // 3. Update the sticky button at the bottom immediately
    document.getElementById("display-total").innerText = currentPrice.toFixed(2);
    document.getElementById("display-quantity").innerText = currentQuantity;

    // 4. Reset all checkboxes and radio buttons to default
    document.getElementById("no-coriander").checked = false;
    document.getElementById("no-shrimp-sauce").checked = false;
    document.getElementById("extra-sambal").checked = false;
    document.querySelector('input[name="combo"][value="0"]').checked = true;

    // 5. Show the modal
    document.getElementById("customize-modal").style.display = "block";
}

function closeModal() {
    document.getElementById("customize-modal").style.display = "none";
}

// Function to handle + and - buttons AND recalculate price
function updateQuantity(change) {
    currentQuantity += change;
    
    // Prevent quantity from going below 1
    if (currentQuantity < 1) {
        currentQuantity = 1;
    }
    
    document.getElementById('display-quantity').innerText = currentQuantity;
    
    // Check if a combo is selected 
    let comboPrice = parseFloat(document.querySelector('input[name="combo"]:checked').value);
    
    // Calculate the real total: (Base Price + Combo Price) * Quantity
    let finalTotal = (currentPrice + comboPrice) * currentQuantity;
    
    // Update the button
    document.getElementById('display-total').innerText = finalTotal.toFixed(2);
}

// 4. Cart Logic
function calculateModalPrice() {
    updateQuantity(0); // Trigger a recalculation without adding to the quantity!
}

// Function when they actually click Add to Order
function confirmAddToCart() {
    // Calculate the final price of the item they just customized
    let comboElement = document.querySelector('input[name="combo"]:checked');
    let comboPrice = comboElement ? parseFloat(comboElement.value) : 0;
    
    // Get the name of the selected combo from the span beside the radio button
    let comboName = "";
    if (comboElement && comboElement.nextElementSibling) {
        let nameSpan = comboElement.nextElementSibling.querySelector('.option-name');
        if (nameSpan) comboName = nameSpan.innerText;
    }
    
    let finalPrice = (currentPrice + comboPrice) * currentQuantity;
    
    // Gather any checked preferences
    let preferences = [];
    if (document.getElementById("no-coriander").checked) preferences.push("No Coriander");
    if (document.getElementById("no-shrimp-sauce").checked) preferences.push("No Shrimp Sauce");
    if (document.getElementById("extra-sambal").checked) preferences.push("Extra Sambal");

    // Construct the complete item object
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

    // Pull the existing array of items from localStorage, or start a new empty array
    let cartItemsArray = JSON.parse(localStorage.getItem('masisso_cart_items')) || [];
    cartItemsArray.push(cartItem);
    // Save the updated array back to localStorage
    localStorage.setItem('masisso_cart_items', JSON.stringify(cartItemsArray));

    // Update Cart numbers (This accumulates EVERY time you click Add!)
    cartCount += currentQuantity;
    cartTotal += finalPrice;
    
    // Show the floating cart button and update the text
    let floatingCart = document.getElementById('floating-cart');
    if (floatingCart) {
        floatingCart.classList.remove('hidden');
        let cartTextDiv = document.querySelector('.cart-text div:nth-child(2)');
        if (cartTextDiv) {
            // Display the accumulated Grand Total on the floating button
            cartTextDiv.innerText = cartCount + (cartCount === 1 ? ' item' : ' items') + ' • RM ' + cartTotal.toFixed(2);
        }
        
        // Update the cart image to the one we just added!
        let cartImg = document.getElementById('floating-cart-img');
        if (cartImg && currentItemImage) {
            cartImg.src = 'images/' + currentItemImage;
        }
    }

    // Save the grand total to the browser's memory so it doesn't get lost when you switch to your cart.html page!
    localStorage.setItem('masisso_cart_total', cartTotal.toFixed(2));
    localStorage.setItem('masisso_cart_count', cartCount);

    // Close the modal
    closeModal();
}

// delivery/pickup toggle
document.addEventListener('DOMContentLoaded', () => {
    
    const btnDelivery = document.getElementById('btn-delivery');
    const btnPickup   = document.getElementById('btn-pickup');

    let currentMode = "Delivery"; // Default state

    if (btnDelivery && btnPickup) {
        // Clicking Delivery button → open delivery modal & mark as active
        btnDelivery.addEventListener('click', () => {
            openOrderModal('delivery-modal');
            setActiveToggle('Delivery');
        });

        // Clicking Pickup button → open pickup modal & mark as active
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

// Open an order modal by id
function openOrderModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show'); // Fix: Add 'show' instead of removing 'hidden'
    }
}

// Close an order modal by id
function closeOrderModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show'); // Fix: Remove 'show' instead of adding 'hidden'
    }
}

// Close modal when clicking the dark overlay background
document.addEventListener('DOMContentLoaded', () => {
    ['delivery-modal', 'pickup-modal'].forEach(id => {
        const overlay = document.getElementById(id);
        if (overlay) {
            overlay.addEventListener('click', function(e) {
                // Only close if they clicked the overlay itself, not the card inside
                if (e.target === overlay) closeOrderModal(id);
            });
        }
    });
});


// Function to handle the accordion (Nutritional Info dropdown)
function toggleAccordion() {
    const content = document.getElementById('accordion-content');
    const icon = document.getElementById('accordion-icon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)'; // Flips the caret upside down
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// SEARCH FEATURE 
function executeSearch() {
    const inputElement = document.getElementById('searchInput');
    if (!inputElement) return; 
    
    const keyword = inputElement.value.trim();
    const resultsContainer = document.getElementById('search-results-container');
    if (!resultsContainer) return;

    // FIX: If the user deletes their text, stop searching and show the prompt!
    if (keyword === "") {
        resultsContainer.innerHTML = '<p style="color: #888; text-align: center; margin-top: 20px;">Start typing to search menu...</p>';
        return;
    }
    
    resultsContainer.innerHTML = '<p style="text-align:center; color:#888;">Searching...</p>';

    fetch(`search_menu.php?keyword=${encodeURIComponent(keyword)}`)
        .then(response => response.json())
        .then(data => {
            resultsContainer.innerHTML = ''; 

            if(data.length === 0) {
                resultsContainer.innerHTML = '<p style="text-align:center; color:#888;">No items found matching "' + keyword + '".</p>';
                return;
            }

            data.forEach(item => {
                // Grab the image just like the main menu
                let imgFile = item.image_url ? item.image_url : 'default.jpg';

                resultsContainer.innerHTML += `
                    <div class="menu-card">
                        
                        <img src="images/${imgFile}" alt="${item.name}" class="menu-image">

                        <div class="menu-info">
                            <h3 class="menu-title">${item.name}</h3>
                            <p class="menu-price">RM ${parseFloat(item.price).toFixed(2)}</p>
                            <p class="menu-desc">${item.description}</p>
                        </div>
                        
                        <button class="add-btn" onclick="openCustomization('${item.name}', ${parseFloat(item.price)}, '${imgFile}', '${item.category ? item.category : 'Other'}')">+ Add</button>
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
    
    // Only run this fetch code if the offers-container exists on the screen
    if (offersContainer) {
        fetch('fetch_offers.php')
            .then(response => response.json())
            .then(data => {
                offersContainer.innerHTML = ''; // Clear the loading text

                if (data.length === 0) {
                    offersContainer.innerHTML = '<p style="text-align:center;">No offers available right now.</p>';
                    return;
                }

                // Loop through the SQL data and create the cards
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

// Simple function when user clicks "Add Voucher"
function applyPromo(code) {
    localStorage.setItem('masisso_active_voucher', code);
    alert("Voucher " + code + " has been added! It will be automatically applied at checkout.");
}

// REWARDS PAGE LOGIC
document.addEventListener('DOMContentLoaded', () => {
    const rewardsContainer = document.getElementById('rewards-container');
    
    // Only run if we are actually on the rewards page
    if (rewardsContainer) {
        fetch('fetch_rewards.php')
            .then(response => response.json())
            .then(data => {
                rewardsContainer.innerHTML = ''; // Clear loading text

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

// Mock function for when they click Redeem
let currentUserPoints = 1000; // In a full system, this comes from the database too!

function redeemItem(itemName, pointsCost, itemImage) {
    if (currentUserPoints >= pointsCost) {
        let confirmRedeem = confirm(`Do you want to spend ${pointsCost} points for a free ${itemName}?`);
        
        if (confirmRedeem) {
            currentUserPoints -= pointsCost;
            document.getElementById('user-points-display').innerText = currentUserPoints;
            
            // Add the free item to the cart!
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
            
            // Update cart count in memory
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
        resultsBox.innerHTML = ''; // Clear old results

        // Hide the box if they delete their text
        if (keyword === "") {
            resultsBox.style.display = 'none';
            return;
        }

        // Filter the fake database
        const filtered = dataList.filter(item => item.toLowerCase().includes(keyword));

        // Show the results
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

// When a user clicks a search result
let activeOrderMode = "Delivery"; // Remembers what they locked in!

// When a user clicks a search result OR a nearby branch
function selectLocation(locName, modalId) {
    // 1. Update the Locked Header Text & Icons
    if (modalId === 'delivery-modal') {
        activeOrderMode = "Delivery";
        document.getElementById('header-selected-icon').innerText = "🛵";
        document.getElementById('header-selected-mode').innerText = "Delivering to";
    } else {
        activeOrderMode = "Pickup";
        document.getElementById('header-selected-icon').innerText = "🏪";
        document.getElementById('header-selected-mode').innerText = "Picking up from";
        locName = locName.split(' (')[0]; // Clean up the name for the header
    }

    document.getElementById('header-selected-text').innerText = locName;

    // 2. Hide the Big Toggle Buttons & Show the Locked Header
    document.getElementById('header-toggle-state').classList.add('hidden');
    document.getElementById('header-selected-state').classList.remove('hidden');

    // 3. Clean up and close
    document.getElementById('delivery-search-input').value = "";
    document.getElementById('pickup-search-input').value = "";
    if(document.getElementById('delivery-results')) document.getElementById('delivery-results').style.display = 'none';
    if(document.getElementById('pickup-results')) document.getElementById('pickup-results').style.display = 'none';
    
    closeOrderModal(modalId);
}

// Allows them to change their mind between Delivery and Pickup!
function reopenCurrentModal() {
    // 1. Hide the compact locked display
    document.getElementById('header-selected-state').classList.add('hidden');
    
    // 2. Bring back the big Delivery/Pickup toggle buttons!
    document.getElementById('header-toggle-state').classList.remove('hidden');
}

// Automatically prints the 3 stores to the screen
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

// Start everything when the page loads
document.addEventListener('DOMContentLoaded', () => {
    setupLocationSearch('delivery-search-input', 'delivery-results', deliveryLocations, 'delivery-modal');
    setupLocationSearch('pickup-search-input', 'pickup-results', pickupStores, 'pickup-modal');
    
    // Draw the nearby branches instantly!
    renderNearbyBranches(); 
});

// --- PROFILE PAGE LOGIC ---
document.addEventListener('DOMContentLoaded', () => {
    // Only run this if we are on the profile page
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
                // Update View Mode text
                document.getElementById('display-name').innerText = profile.name || "N/A";
                document.getElementById('display-email').innerText = profile.email || "N/A";
                document.getElementById('display-phone').innerText = profile.phone || "N/A";
                document.getElementById('display-address').innerText = profile.address || "N/A";
                document.getElementById('display-role').innerText = profile.role || "Customer";
                
                // Pre-fill Edit Mode inputs
                document.getElementById('edit-name').value = profile.name || "";
                document.getElementById('edit-email').value = profile.email || "";
                document.getElementById('edit-phone').value = profile.phone || "";
                document.getElementById('edit-address').value = profile.address || "";
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
        phone: document.getElementById('edit-phone').value.trim(),
        address: document.getElementById('edit-address').value.trim()
    };
    
    // Safety check
    if (!updatedData.name || !updatedData.email) {
        alert("Name and Email are required!");
        return;
    }

    // Change button text while saving
    let saveBtn = document.querySelector('#profile-edit-mode button.solid-btn');
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
            toggleEditProfile(); // go back to view mode
            loadProfile(); // refresh data
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
