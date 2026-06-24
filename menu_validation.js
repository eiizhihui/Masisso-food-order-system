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

// --- Requirement 2: Price & Constraint Validation ---
document.getElementById('menuForm').addEventListener('submit', function(event) {
    var nameField = document.getElementById('item_name').value.trim();
    var priceField = document.getElementById('price').value;

    // Check for empty fields
    if (nameField === "" || priceField === "") {
        alert("Validation Failure: Please enter both a menu item name and price.");
        event.preventDefault(); // Blocks form transmission
        return false;
    }

    // Check for valid positive pricing values
    if (parseFloat(priceField) <= 0) {
        alert("Validation Failure: Price must be a positive number greater than RM 0.00.");
        event.preventDefault(); // Blocks form transmission
        return false;
    }

    return true;
});