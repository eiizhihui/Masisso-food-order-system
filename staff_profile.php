<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ['staff', 'admin', 'super admin'])) {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';

$staff_id = $_SESSION['user_id'];
$query = "SELECT * FROM staff WHERE staff_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masisso - Staff Profile</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        window.currentUserId = <?php echo json_encode($staff_id); ?>;
    </script>
    <style>
        .header {
            background-color: var(--primary-orange);
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
        }

        .header a {
            color: white;
            text-decoration: none;
            font-size: 20px;
        }

        .profile-container {
            max-width: 500px;
            margin: 40px auto 120px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            box-sizing: border-box;
        }

        .profile-header h2 {
            color: var(--primary-orange);
            margin: 0 0 5px 0;
        }

        .profile-header p {
            color: #777;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: var(--text-dark);
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 15px;
            background-color: #f9f9f9;
            color: #555;
            font-family: inherit;
        }

        .readonly-note {
            text-align: center;
            color: #aaa;
            font-size: 13px;
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <div class="header">
        <a href="staff_dashboard.php" aria-label="Back to Dashboard"><i class="fas fa-arrow-left"></i></a>
        <h2>My Profile</h2>
    </div>

    <div class="profile-container">
        <div class="profile-header">
            <h2>Staff Profile</h2>
            <p>Your account information (view only)</p>
        </div>

        <div class="form-group">
            <label>Name</label>
            <input type="text" id="profile-name" value="<?php echo htmlspecialchars($staff['name'] ?? ''); ?>" disabled>
        </div>

        <div class="form-group">
            <label>Username</label>
            <input type="text" id="profile-username" value="<?php echo htmlspecialchars($staff['username'] ?? ''); ?>" disabled>
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" id="profile-email" value="<?php echo htmlspecialchars($staff['email'] ?? ''); ?>" disabled>
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" id="profile-phone" value="<?php echo htmlspecialchars($staff['phone'] ?? ''); ?>" disabled>
        </div>

        <div class="form-group">
            <label>Branch</label>
            <input type="text" id="profile-branch" value="<?php echo htmlspecialchars($staff['branch'] ?? ''); ?>" disabled>
        </div>

        <div class="form-group">
            <label>Role / Position</label>
            <input type="text" id="profile-role" value="<?php echo htmlspecialchars($staff['position'] ?? ''); ?>" disabled>
        </div>

        <p class="readonly-note"><i class="fas fa-lock"></i> Profile can only be updated by an Admin.</p>
    </div>

    <div class="bottom-nav">
        <a href="staff_dashboard.php" class="nav-item-bottom">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="staff_manage_menu.php" class="nav-item-bottom">
            <i class="fas fa-utensils"></i>
            <span>Menu</span>
        </a>
        <a href="view_orders.php" class="nav-item-bottom">
            <i class="fas fa-clipboard-list"></i>
            <span>Orders</span>
        </a>
        <a href="staff_profile.php" class="nav-item-bottom active">
            <i class="fas fa-user-cog"></i>
            <span>Profile</span>
        </a>
    </div>

    <script src="staff.js"></script>
</body>

</html>