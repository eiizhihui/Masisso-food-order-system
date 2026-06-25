<?php
session_start();
include 'db_connect.php'; 

$is_logged_in = isset($_SESSION['user_id']);
$user = null;

if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT name, email, phone, address FROM customer WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
    } else {
        session_destroy();
        $is_logged_in = false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Massiso - Profile</title>
    <link rel="stylesheet" href="style.css?v=8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="app-header" style="background-color: #e65100; margin: 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <div class="top-brand-row" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px;">
            <div class="logo" style="color: white; font-weight: bold; font-size: 24px; font-style: italic; margin: 0; cursor: pointer;" onclick="window.location.href='home.php'">Masisso</div>
            <?php if ($is_logged_in): ?>
            <a href="logout.php" style="color: white; text-decoration: none; font-weight: bold; font-size: 14px; background: rgba(255, 255, 255, 0.2); padding: 6px 15px; border-radius: 20px; display: flex; align-items: center; gap: 8px; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.4)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <?php else: ?>
            <a href="login.php" style="color: white; text-decoration: none; font-weight: bold; font-size: 14px; background: rgba(255, 255, 255, 0.2); padding: 6px 15px; border-radius: 20px; display: flex; align-items: center; gap: 8px; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.4)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
            <?php endif; ?>
        </div>
    </header>

    <div style="padding: 20px; max-width: 800px; margin: auto;">
        <h2 style="color: var(--primary-orange);">My Profile</h2>
        
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            
            <?php if ($is_logged_in): ?>
            <div id="profile-view-mode">
                <p><strong>Name:</strong> <span id="display-name"><?php echo htmlspecialchars($user['name']); ?></span></p>
                <p><strong>Email:</strong> <span id="display-email"><?php echo htmlspecialchars($user['email']); ?></span></p>
                <p><strong>Phone:</strong> <span id="display-phone"><?php echo htmlspecialchars($user['phone']); ?></span></p>
                <p><strong>Default Address:</strong> <span id="display-address"><?php echo htmlspecialchars($user['address']); ?></span></p>
                <button class="add-btn solid-btn" onclick="toggleEditProfile()" style="margin-top: 20px;">Edit Profile</button>
            </div>

            <form id="profile-edit-mode" onsubmit="saveProfile(); return false;" style="display: none;">
                <p><strong>Name:</strong><br>
                    <input type="text" name="name" id="edit-name" value="<?php echo htmlspecialchars($user['name']); ?>" style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px;" required>
                </p>
                <p><strong>Email:</strong><br>
                    <input type="email" name="email" id="edit-email" value="<?php echo htmlspecialchars($user['email']); ?>" style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px;" required>
                </p>
                <p><strong>Phone:</strong><br>
                    <input type="text" name="phone" id="edit-phone" value="<?php echo htmlspecialchars($user['phone']); ?>" style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px;">
                </p>
                <p><strong>Address:</strong><br>
                    <textarea name="address" id="edit-address" style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; height: 60px; font-family: Arial;"><?php echo htmlspecialchars($user['address']); ?></textarea>
                </p>
                
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="add-btn" style="flex: 1; background-color: #4CAF50; color: white; border-color: #4CAF50; margin: 0;">Save Changes</button>
                    <button type="button" class="add-btn" onclick="toggleEditProfile()" style="flex: 1; border-color: #ccc; color: #555; margin: 0;">Cancel</button>
                </div>
            </form>
            <?php else: ?>
            <div style="padding: 20px 0; text-align: center;">
                <div style="font-size: 64px; color: #ddd; margin-bottom: 20px;"><i class="fas fa-user-circle"></i></div>
                <h3 style="color: #333; margin-bottom: 10px;">Guest Access</h3>
                <p style="color: #666; font-size: 14px; line-height: 1.5; margin-bottom: 25px; max-width: 400px; margin-left: auto; margin-right: auto;">
                    You are currently visiting as a Guest. Log in to view your profile, manage your orders, edit your delivery address, and track your reward points.
                </p>
                <div style="display: flex; gap: 15px; justify-content: center; max-width: 300px; margin: 0 auto;">
                    <button class="add-btn solid-btn" onclick="window.location.href='login.php'" style="margin: 0; flex: 1;">Log In</button>
                    <button class="add-btn" onclick="window.location.href='register.php'" style="margin: 0; flex: 1; border-color: #ccc; color: #555;">Register</button>
                </div>
            </div>
            <?php endif; ?>

    <nav class="bottom-nav">
        <a href="home.php" class="nav-item-bottom">
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
        <a href="profile.php" class="nav-item-bottom active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <span>Profile</span>
        </a>
    </nav>
    <script src="script.js?v=7"></script>
</body>
</html>