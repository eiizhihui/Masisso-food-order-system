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
                            
                            <button class="add-btn" onclick="openCustomization('${item.name}', ${parseFloat(item.price)}, '${imgFile}')">+ Add</button>
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
let currentQuantity = 1; // Unified variable for quantity
let cartCount = 0;
let cartTotal = 0.00;

// Add a new variable at the top to track the image
let currentItemImage = "";

function openCustomization(itemName, itemPrice, itemImage) { // <-- Added itemImage parameter here
    currentItem = itemName;
    currentPrice = itemPrice;
    currentItemImage = itemImage; 
    currentQuantity = 1; 

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
    let finalPrice = (currentPrice + comboPrice) * currentQuantity;
    
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
    }

    // Save the grand total to the browser's memory so it doesn't get lost when you switch to your cart.html page!
    localStorage.setItem('masisso_cart_total', cartTotal.toFixed(2));
    localStorage.setItem('masisso_cart_count', cartCount);

    // Close the modal
    closeModal();
}

// delivery/pickup toggle
document.addEventListener('DOMContentLoaded', () => {
    
    // Find our buttons and modal in the HTML
    const btnDelivery = document.getElementById('btn-delivery');
    const btnPickup = document.getElementById('btn-pickup');
    const switchModal = document.getElementById('switch-modal');
    const btnCancelSwitch = document.getElementById('btn-cancel-switch');
    const btnConfirmSwitch = document.getElementById('btn-confirm-switch');

    let currentMode = "Delivery"; // Default state
    let targetMode = "";

    // 1. Listen for clicks on the Delivery/Pickup buttons
    if (btnDelivery && btnPickup) {
        btnDelivery.addEventListener('click', () => triggerSwitch('Delivery'));
        btnPickup.addEventListener('click', () => triggerSwitch('Pickup'));
    }

    // 2. The function that triggers the warning modal
    function triggerSwitch(mode) {
        if (currentMode === mode) return; // Do nothing if they click the one already selected
        
        targetMode = mode;
        
        // Dynamically change the text to match what they clicked
        document.getElementById('modal-title').innerText = `Switch to ${mode}?`;
        document.getElementById('modal-desc').innerText = `Some items in your cart may not be available for ${mode}. Do you want to switch from ${currentMode} to ${mode}?`;
        btnConfirmSwitch.innerText = `Switch to ${mode}`;
        
        // Show the modal by removing the "hidden" CSS class!
        switchModal.classList.remove('hidden');
    }

    // 3. If they click Cancel, just hide the modal again
    if (btnCancelSwitch) {
        btnCancelSwitch.addEventListener('click', () => {
            switchModal.classList.add('hidden');
        });
    }

    // 4. If they click Confirm, switch the button colors and hide modal
    if (btnConfirmSwitch) {
        btnConfirmSwitch.addEventListener('click', () => {
            currentMode = targetMode;
            
            // Swap the grey/white active styling
            if (currentMode === 'Delivery') {
                btnDelivery.classList.add('active');
                btnPickup.classList.remove('active');
            } else {
                btnPickup.classList.add('active');
                btnDelivery.classList.remove('active');
            }
            
            // Hide the modal when done
            switchModal.classList.add('hidden');
        });
    }
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
                        
                        <button class="add-btn" onclick="openCustomization('${item.name}', ${parseFloat(item.price)}, '${imgFile}')">+ Add</button>
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
                            <button class="add-btn" style="background: transparent; border: 2px solid #E65100; color: #E65100; padding: 8px 15px; border-radius: 20px; cursor: pointer; font-weight: bold; width: 100%; margin-top: 10px;" onclick="applyPromo('${offer.code}')">Use in Cart</button>
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

// Simple function when user clicks "Use in Cart"
function applyPromo(code) {
    alert("Promo code " + code + " has been copied and saved for your checkout!");
}