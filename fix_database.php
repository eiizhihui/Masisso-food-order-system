<?php
include 'db_connect.php';

header('Content-Type: text/plain');
echo "Starting database fix...\n";

// 1. Drop foreign key constraints if they exist
echo "Dropping existing foreign keys...\n";
$conn->query("ALTER TABLE orders DROP FOREIGN KEY orders_ibfk_1");
$conn->query("ALTER TABLE orders DROP FOREIGN KEY fk_orders_customer");

// 2. Drop primary key if exists
echo "Dropping existing primary key...\n";
$conn->query("ALTER TABLE orders DROP PRIMARY KEY");

// 3. Make sure all user_id values in orders actually exist in customer table
echo "Cleaning up user_id references...\n";
$conn->query("UPDATE orders SET user_id = NULL WHERE user_id NOT IN (SELECT user_id FROM customer)");

// 4. Drop order_id column (we will recreate it to resolve duplicate 0s automatically)
echo "Re-creating order_id column to assign auto-incrementing values...\n";
$conn->query("ALTER TABLE orders DROP COLUMN order_id");

// 5. Add order_id back as INT AUTO_INCREMENT PRIMARY KEY at the beginning
$sql = "ALTER TABLE orders ADD COLUMN order_id INT AUTO_INCREMENT PRIMARY KEY FIRST";
if ($conn->query($sql) === TRUE) {
    echo "SUCCESS: Recreated order_id as AUTO_INCREMENT PRIMARY KEY.\n";
} else {
    echo "ERROR recreating order_id: " . $conn->error . "\n";
}

// 6. Re-add the foreign key constraint on user_id
$sql_fk = "ALTER TABLE orders ADD CONSTRAINT fk_orders_customer FOREIGN KEY (user_id) REFERENCES customer(user_id) ON DELETE SET NULL ON UPDATE CASCADE";
if ($conn->query($sql_fk) === TRUE) {
    echo "SUCCESS: Added foreign key constraint fk_orders_customer.\n";
} else {
    echo "ERROR adding foreign key constraint: " . $conn->error . "\n";
}

// 7. Check and add items column if not exists
$result = $conn->query("SHOW COLUMNS FROM orders LIKE 'items'");
if ($result->num_rows == 0) {
    $sql_items = "ALTER TABLE orders ADD COLUMN items TEXT DEFAULT NULL";
    if ($conn->query($sql_items) === TRUE) {
        echo "SUCCESS: Added items column to orders table.\n";
    } else {
        echo "ERROR adding items column: " . $conn->error . "\n";
    }
} else {
    echo "INFO: items column already exists.\n";
}

// 8. Check and add delivery_fee column if not exists
$result_df = $conn->query("SHOW COLUMNS FROM orders LIKE 'delivery_fee'");
if ($result_df->num_rows == 0) {
    $sql_df = "ALTER TABLE orders ADD COLUMN delivery_fee DECIMAL(10,2) DEFAULT 0.00";
    if ($conn->query($sql_df) === TRUE) {
        echo "SUCCESS: Added delivery_fee column to orders table.\n";
    } else {
        echo "ERROR adding delivery_fee column: " . $conn->error . "\n";
    }
} else {
    echo "INFO: delivery_fee column already exists.\n";
}

echo "Database fix completed successfully!\n";
$conn->close();
?>
