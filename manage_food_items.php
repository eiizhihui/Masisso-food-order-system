<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ['staff', 'admin', 'super admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Massiso Staff - Manage Menu</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Form formatting contextual integration block */
        .form-group { margin-bottom: 15px; text-align: left; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: var(--text-dark); }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-family: Arial, sans-serif; }
        .form-section { background: white; padding: 20px; border-radius: 10px; border: 1px solid #ddd; margin-bottom: 20px; }

        /* Modern UI Brand Toast Notification Styling */
        .toast-notification {
            position: fixed;
            top: 20px;
            left: 0;
            right: 0;
            margin: 0 auto;
            width: max-content;
            background: linear-gradient(to right, #E65100, #ff9800);
            color: white;
            padding: 15px 25px;
            border-radius: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            font-weight: bold;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 10px;
            transform: translateY(-50px);
            opacity: 0;
            transition: all 0.4s ease;
        }

        .toast-notification.show {
            transform: translateY(0);
            opacity: 1;
        }
    </style>
</head>
<body>
    <div id="toastNotification" class="toast-notification">
        <span id="toastMessage">Success: Item added!</span>
    </div>

    <div class="navbar">
        <a href="staff_dashboard.php" class="add-btn" style="text-decoration: none;">← Dashboard</a>
        <h1 style="margin: 0; font-size: 20px; color: var(--text-dark);">Sarawak Laksa Catalog</h1>
    </div>

    <div class="main-content" style="max-width: 800px; margin: 0 auto;">
        <button id="toggleFormBtn" class="add-btn margin-top" style="margin-bottom: 20px; display: block; width: auto;">➕ Create New Item Entry</button>

        <div id="addFoodFormSection" class="form-section" style="display: none;">
            <h3 style="color: var(--primary-orange); margin-top: 0;">Add New Sarawak Laksa Variation</h3>
            <form action="add_food_item.php" method="POST" id="menuForm">
                <div class="form-group">
                    <label for="item_name">Food Name:</label>
                    <input type="text" id="item_name" name="item_name" placeholder="e.g., Special Sarawak Laksa (Big Prawns)">    
                </div>

                <div class="form-group">
                    <label for="description">Recipe Description:</label>
                    <textarea id="description" name="description" rows="3" placeholder="Describe ingredients, spice level, etc..."></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Price (RM):</label>
                    <input type="number" step="0.01" id="price" name="price" placeholder="0.00">
                </div>
                <button type="submit" class="add-to-cart-btn" style="width: 100%;">Save Item Entry</button>
            </form>
        </div>

        <h2 class="section-title">Current Active Menu Catalog</h2>
        
        <div id="menuCatalogContainer">
            <!-- Dynamic items loaded here -->
            <div class="menu-card">
                <div class="menu-info">
                    <h3 class="menu-title"><strong>Classic Sarawak Laksa</strong></h3>
                    <p class="menu-desc">Rice vermicelli in rich spiced coconut broth, shredded chicken, and prawns.</p>
                    <div class="menu-price" style="margin-top: 8px;">RM 12.50</div>
                </div>
                <span class="badge-popular">Active Item</span>
            </div>
        </div>
    </div>

    <script>
        // Form Panel Visibility Toggler
        document.getElementById('toggleFormBtn').addEventListener('click', function() {
            var formSection = document.getElementById('addFoodFormSection');
            if (formSection.style.display === "none" || formSection.style.display === "") {
                formSection.style.display = "block";
                this.textContent = "❌ Close Creation Panel";
                this.style.backgroundColor = "#333";
                this.style.color = "#fff";
                this.style.borderColor = "#333";
            } else {
                formSection.style.display = "none";
                this.textContent = "➕ Create New Item Entry";
                this.style.backgroundColor = "transparent";
                this.style.color = "var(--primary-orange)";
                this.style.borderColor = "var(--primary-orange)";
            }
        });

        // Function to Fetch and Render Menu Data Dynamically from PHP
        function loadMenuCatalog() {
            fetch('get_food_items.php')
            .then(response => response.json())
            .then(items => {
                var container = document.getElementById('menuCatalogContainer');
                container.innerHTML = ""; // Clear existing elements

                if(items.length === 0 || items.error) {
                    container.innerHTML = "<p style='color:#666;'>No active variations currently inside catalog dashboard.</p>";
                    return;
                }

                // Loop through your live SQL rows and create UI cards dynamically
                items.forEach(item => {
                    var card = document.createElement('div');
                    card.className = 'menu-card';
                    card.style.cssText = "background: white; border: 1px solid #ddd; padding: 15px; border-radius: 10px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;";
                    
                    card.innerHTML = `
                        <div class="menu-info">
                            <h3 class="menu-title" style="margin:0; color:#333;"><strong>${item.item_name}</strong></h3>
                            <p class="menu-desc" style="margin:5px 0; color:#666; font-size:14px;">${item.description || 'No description provided.'}</p>
                            <div class="menu-price" style="font-weight: bold; color: #E65100;">RM ${item.price}</div>
                        </div>
                        <span class="badge-popular" style="background: #FFF3E0; color: #E65100; padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold;">Active</span>
                    `;
                    container.appendChild(card);
                });
            });
        }

        // Run catalog data render on initial web layout compilation pass
        document.addEventListener("DOMContentLoaded", loadMenuCatalog);

        // Function to Trigger Custom Toast Alert Panel
        function showToastNotification(message) {
            var toast = document.getElementById('toastNotification');
            document.getElementById('toastMessage').textContent = message;
            toast.classList.add('show');
            
            // Auto hide notification panel after 3 seconds
            setTimeout(function() {
                toast.classList.remove('show');
            }, 3000);
        }

        // Asynchronous Form Validation and Execution
        document.getElementById('menuForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Pause traditional page navigation reload
            
            var nameField = document.getElementById('item_name').value.trim();
            var priceField = document.getElementById('price').value;

            if (nameField === "" || priceField === "") {
                alert("Validation Failure: Please enter both a menu item name and price.");
                return;
            }
            if (parseFloat(priceField) <= 0) {
                alert("Validation Failure: Price must be a positive value.");
                return;
            }

            // Send form data asynchronously via Fetch API
            var formData = new FormData(this);

            fetch('add_food_item.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes("SUCCESS")) {
                    showToastNotification("Success: New item added to catalog!");
                    document.getElementById('menuForm').reset(); // Clear text inputs
                    setTimeout(() => { window.location.reload(); }, 1500); // Reload data view smoothly
                } else {
                    showToastNotification("Error: " + data);
                }
            })
            .catch(error => {
                showToastNotification("Error submitting record entry.");
            });
        });
    </script>
</body>
</html>
