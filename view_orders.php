<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ['staff', 'admin', 'super admin'])) {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';

$query = "SELECT o.order_id, o.order_status, o.order_type, o.total_price, o.order_date, c.name AS customer_name FROM orders o LEFT JOIN customer c ON o.user_id = c.user_id ORDER BY o.order_id DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Massiso Staff - View Orders</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .select-status {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 20px;
            background: white;
            font-weight: bold;
            color: var(--text-dark);
            outline: none;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="staff_dashboard.php" class="add-btn" style="text-decoration: none;">← Dashboard</a>
        <h1 style="margin: 0; font-size: 20px; color: var(--text-dark);">Active Kitchen Queue</h1>
    </div>

    <div class="main-content" style="max-width: 800px; margin: 0 auto;">
        <h2 class="section-title">Incoming Preparation Stream</h2>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="menu-card" style="align-items: flex-start; flex-direction: column; gap: 15px; margin-bottom: 15px; background: white; border: 1px solid #ddd; padding: 20px; border-radius: 10px;">
                    <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
                        <span class="badge-promo" style="background-color: var(--text-dark); padding: 5px 10px; border-radius: 15px; color: white; font-weight: bold; font-size: 12px;">Order ID: #<?php echo htmlspecialchars($row['order_id']); ?></span>
                        <span class="badge-popular" style="background: #FFF3E0; color: #E65100; padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold;"><?php echo htmlspecialchars($row['order_status']); ?></span>
                        <span class="badge-info" style="background: #E0F7FA; color: #006064; padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; margin-left: 5px;">Type: <?php echo htmlspecialchars($row['order_type']); ?></span>
                        <span class="badge-price" style="background: #FFF9C4; color: #BF360C; padding: 10px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; margin-left: 5px;">Total: RM <?php echo number_format($row['total_price'], 2); ?></span>
                        <span class="badge-cust" style="background: #E8F5E9; color: #1B5E20; padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; margin-left: 5px;">Customer: <?php echo htmlspecialchars($row['customer_name']); ?></span>
                    </div>
                    
                    <div class="menu-info" style="padding: 0; width: 100%;">
                        <!-- Order details extended: showing order type and total price -->
                    </div>

                    <div class="faded-divider" style="width: 100%; margin: 5px 0; border-bottom: 1px solid #eee;"></div>

                    <div style="width: 100%;">
                        <form action="update_order_status.php" method="POST" style="display: flex; width: 100%; gap: 10px; align-items: center;">
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['order_id']); ?>">
                            <label style="font-size: 14px; font-weight: bold; color: var(--text-dark);">Update Step:</label>
                            <select name="status" class="select-status" style="flex-grow: 1;">
                                <option value="Pending" <?php if ($row['order_status'] === 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Preparing" <?php if ($row['order_status'] === 'Preparing') echo 'selected'; ?>>Preparing</option>
                                <option value="Completed" <?php if ($row['order_status'] === 'Completed') echo 'selected'; ?>>Completed</option>
                            </select>
                            <button type="submit" class="add-btn" style="padding: 6px 20px;">Update</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color:#666; text-align: center;">No incoming orders at the moment.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>
