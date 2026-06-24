<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ['staff', 'admin', 'super admin'])) {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';

$query = "SELECT order_id, food_name, customization, status FROM orders ORDER BY order_id DESC";
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
                        <span class="badge-popular" style="background: #FFF3E0; color: #E65100; padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold;"><?php echo htmlspecialchars($row['status']); ?></span>
                    </div>
                    
                    <div class="menu-info" style="padding: 0; width: 100%;">
                        <h3 class="menu-title" style="font-size: 18px; margin-bottom: 8px; color: #333;"><strong><?php echo htmlspecialchars($row['food_name']); ?></strong></h3>
                        <?php if (!empty($row['customization'])): ?>
                            <p class="menu-desc" style="color: #d84315; font-weight: bold; margin: 5px 0;">💡 Customization: <?php echo htmlspecialchars($row['customization']); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="faded-divider" style="width: 100%; margin: 5px 0; border-bottom: 1px solid #eee;"></div>

                    <div style="width: 100%;">
                        <form action="update_order_status.php" method="POST" style="display: flex; width: 100%; gap: 10px; align-items: center;">
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['order_id']); ?>">
                            <label style="font-size: 14px; font-weight: bold; color: var(--text-dark);">Update Step:</label>
                            <select name="status" class="select-status" style="flex-grow: 1;">
                                <option value="Pending" <?php if ($row['status'] === 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Preparing" <?php if ($row['status'] === 'Preparing') echo 'selected'; ?>>Preparing</option>
                                <option value="Completed" <?php if ($row['status'] === 'Completed') echo 'selected'; ?>>Completed</option>
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
