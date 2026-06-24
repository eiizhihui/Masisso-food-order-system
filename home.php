<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'customer') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masisso - Authentic Sarawak Laksa</title>
    <link rel="stylesheet" href="style.css?v=8">
    <style>
        /* Critical modal styles - inline to avoid cache issues */
        .order-modal-overlay {
            position: fixed !important;
            top: 0 !important; left: 0 !important;
            width: 100% !important; height: 100% !important;
            background: rgba(0,0,0,0.55) !important;
            z-index: 99999 !important;
            display: none;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
        }
        .order-modal-overlay.show {
            display: flex !important;
        }
    </style>
</head>
<body>

    <header class="app-header" style="background-color: #e65100; padding-bottom: 15px; margin: 0;">
        <div class="top-brand-row" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px;">
            <div class="logo" style="color: white; font-weight: bold; font-size: 24px; font-style: italic; margin: 0;">Masisso</div>
            <div class="header-actions">
                <button onclick="window.location.href='search.html'" style="background: none; border: none; cursor: pointer; padding: 0;">
                    <img src="images/search_icon.png" alt="Search" style="width: 24px; height: auto;">
                </button>
            </div>
        </div>

        <div id="header-toggle-state" class="location-row" style="padding: 0 20px;">
            <div class="toggle-container">
                <button id="btn-delivery" class="toggle-option active">Delivery</button>
                <button id="btn-pickup" class="toggle-option">Pickup</button>
            </div>
        </div>

        <div id="header-selected-state" class="location-row hidden" style="padding: 0 20px;">
            <div onclick="reopenCurrentModal()" style="display: flex; align-items: center; background: white; padding: 10px 15px; border-radius: 30px; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                <div style="margin-right: 10px; font-size: 20px;" id="header-selected-icon">🛵</div>
                <div style="flex-grow: 1; overflow: hidden;">
                    <div id="header-selected-mode" style="font-size: 11px; color: #e65100; font-weight: bold; text-transform: uppercase;">Delivering to</div>
                    <div id="header-selected-text" style="font-size: 14px; font-weight: bold; color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Select Location...</div>
                </div>
                <div style="font-size: 18px; color: #888;">›</div>
            </div>
        </div>
    </header>

    <main class="main-content">
        <h2 id="greeting" class="slide-in-text"></h2>

        <div class="menu-container" id="popular-menu-container">
            <p style="color: #888; text-align: center;">Loading menu from database...</p>
        </div>

        <div style="height:100px;"></div>

    </main>

  
<div id="customize-modal" class="modal" style="z-index: 9999;">
    <span class="close-btn" onclick="closeModal()" style="position: absolute; top: 15px; right: 20px; font-size: 35px; color: white; z-index: 1002; cursor: pointer; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">&times;</span>

    <div class="product-page-container" style="display: flex; flex-direction: column; max-height: 80vh; overflow: hidden; background: white; position: relative; width: 90%; max-width: 500px; margin: 5vh auto; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        
        <div style="overflow-y: auto; flex-grow: 1; padding-bottom: 10px;">
            <div class="product-hero">
                <img src="images/laksa.jpg" alt="Masisso Signature Laksa" style="width: 100%; height: 100%; object-fit: cover;">
            </div>

            <div class="product-details">
                <div class="badge-row">
                    <span class="badge-promo">Limited Time Offer</span>
                    <span class="badge-popular">⭐ Best Seller</span>
                </div>
                
                <h1 class="product-title" id="modal-item-name">Massiso Signature Laksa</h1>
                <p class="product-desc">Authentic thin rice vermicelli topped with shredded chicken breast, fresh shrimp, and golden omelette strips in our rich, spicy broth.</p>
                <div class="product-price">Base Price: RM <span id="modal-base-price">14.90</span></div>

                <hr class="divider" id="combo-divider">

                <div class="custom-options customization-section" id="combo-section">
                    <h3>Choose Your Combo</h3>
                    <p class="section-subtitle">Select one option</p>
                    
                    <label class="custom-radio-card">
                        <input type="radio" name="combo" id="combo-radio-1" value="0" checked onchange="calculateModalPrice()">
                        <div class="card-content">
                            <span class="option-name" id="combo-name-1">A La Carte (Just the Laksa)</span>
                            <span class="option-price" id="combo-price-1">+ RM 0.00</span>
                        </div>
                    </label>

                    <label class="custom-radio-card">
                        <input type="radio" name="combo" id="combo-radio-2" value="4.50" onchange="calculateModalPrice()">
                        <div class="card-content">
                            <span class="option-name" id="combo-name-2">Laksa + Teh C Beng Special (三色奶茶)</span>
                            <span class="option-price" id="combo-price-2">+ RM 4.50</span>
                        </div>
                    </label>

                    <label class="custom-radio-card">
                        <input type="radio" name="combo" id="combo-radio-3" value="3.00" onchange="calculateModalPrice()">
                        <div class="card-content">
                            <span class="option-name" id="combo-name-3">Laksa + Fruit Rojak</span>
                            <span class="option-price" id="combo-price-3">+ RM 3.00</span>
                        </div>
                    </label>
                </div>

                <div class="custom-options customization-section" id="preferences-section">
                    <h3 class="margin-top">Preferences</h3>
                    <div id="dynamic-preferences-list">
                        <!-- Checkboxes will be automatically generated here by script.js! -->
                    </div>
                </div>
            </div>
        </div>

        <div class="sticky-action-bar" style="background: white; padding: 15px 15px 30px 15px; display: flex; gap: 15px; border-top: 1px solid #eee; align-items: center; flex-shrink: 0;">
            <div class="quantity-pill" style="display: flex; align-items: center; justify-content: space-between; background-color: #f4f4f4; border-radius: 25px; padding: 0 10px; width: 110px; height: 50px;">
                <button class="qty-control-btn" onclick="updateQuantity(-1)" style="background: none; border: none; font-size: 24px; color: #777; cursor: pointer; padding: 0 10px;">−</button>
                <span id="display-quantity" class="qty-number" style="font-weight: bold; font-size: 18px; color: #000;">1</span>
                <button class="qty-control-btn" onclick="updateQuantity(1)" style="background: none; border: none; font-size: 24px; color: #000; cursor: pointer; padding: 0 10px;">+</button>
            </div>
            
            <button class="add-order-btn" onclick="confirmAddToCart()" style="flex: 1; background-color: #e65100; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; height: 50px; transition: opacity 0.2s;">
                Add To Order • RM <span id="display-total">14.90</span>
            </button>
        </div>
    </div>
</div>

    <!-- ===== DELIVERY MODAL ===== -->
    <div id="delivery-modal" class="order-modal-overlay">
        <div class="order-modal-card">
            <!-- Header -->
            <div class="order-modal-header">
                <button class="order-modal-back" onclick="closeOrderModal('delivery-modal')">&#8249;</button>
                <h2 class="order-modal-title">Delivery</h2>
            </div>

            <!-- Map background area -->
            <div class="order-modal-map">
                <!-- blurred map visual -->
                <div class="map-blur-bg"></div>

                <!-- Search bar -->
                <div class="order-modal-search-wrap">
                    <div class="order-modal-search-bar">
                        <span class="search-icon-svg">&#128269;</span>
                        <input type="text" placeholder="Search by Street" class="order-search-input" id="delivery-search-input">
                        <div id="delivery-results" style="background: white; border-radius: 10px; margin-top: 10px; display: none; box-shadow: 0 4px 10px rgba(0,0,0,0.1); max-height: 150px; overflow-y: auto;"></div>
                    </div>

                    <!-- Address card -->
                    <div class="address-card" id="profile-address-card" style="cursor: pointer;">
                        <p class="address-card-label">Saved Address</p>
                        <div class="address-card-row">
                            <span class="address-pin-icon">&#x1F3AF;</span>
                            <div class="address-card-text">
                                <span class="address-name" id="profile-address-name">Loading saved address...</span>
                                <span class="address-sub" id="profile-address-sub"></span>
                            </div>
                            <span class="address-chevron">&#8250;</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ===== PICKUP MODAL ===== -->
    <div id="pickup-modal" class="order-modal-overlay">
        <div class="order-modal-card pickup-card">
            <!-- Header -->
            <div class="order-modal-header">
                <button class="order-modal-back" onclick="closeOrderModal('pickup-modal')">&#8249;</button>
                <h2 class="order-modal-title">Pickup</h2>
            </div>

            <!-- Search bar -->
            <div class="pickup-search-wrap">
                <div class="order-modal-search-bar">
                    <span class="search-icon-svg">&#128269;</span>
                    <input type="text" placeholder="Search by postcode or street" class="order-search-input" id="pickup-search-input">
                </div>
                
                <div id="pickup-results" style="background: white; border-radius: 10px; margin-top: 10px; display: none; box-shadow: 0 4px 10px rgba(0,0,0,0.1); max-height: 150px; overflow-y: auto;"></div>
                
                <div id="nearby-branches-container" style="margin-top: 25px;">
                    <h4 style="margin: 0 0 10px 0; color: #555; font-size: 14px; font-weight: 600;">Nearby Branches</h4>
                    <div id="nearby-branches-list" style="background: white; border-radius: 10px; border: 1px solid #eee;">
                        </div>
                </div>
            </div>


        </div>
    </div>
    
    <script src="script.js?v=12"></script>

    <nav class="bottom-nav">
        <a href="home.php" class="nav-item-bottom active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            <span>Home</span>
        </a>
        <a href="offers.html" class="nav-item-bottom">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
            <span>Offers</span>
        </a>
       
        <a href="rewards.html" class="nav-item-bottom">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path></svg>
            <span>Rewards</span>
        </a>
        <a href="profile.php" class="nav-item-bottom">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <span>Profile</span>
        </a>
    </nav>

    <div id="floating-cart" class="floating-cart hidden" onclick="window.location.href='checkout.html'">
        <div class="cart-img-circle">
            <img id="floating-cart-img" src="images/default.jpg" alt="item" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
        </div>
        <div class="cart-text">
            <div style="font-weight: bold; font-size: 18px;">Checkout</div>
            <div style="font-size: 14px; opacity: 0.9;">1 item</div>
        </div>
        <div class="cart-arrow">&gt;</div>
    </div>

</body>
</html>
