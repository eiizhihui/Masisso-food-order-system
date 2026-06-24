<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ['staff', 'admin', 'super admin'])) {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';

$staff_id = isset($_GET['id']) ? trim($_GET['id']) : '';

$staff = null;
if (!empty($staff_id)) {
    $stmt = $conn->prepare("SELECT staff_id, name, gender, email FROM staff_profiles WHERE staff_id = ?");
    if ($stmt) {
        $stmt->bind_param("s", $staff_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $staff = $result->fetch_assoc();
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masisso - Staff Profile Details</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 0;
        }

        .nav-btn-link {
            color: var(--primary-orange);
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            border: 2px solid var(--primary-orange);
            padding: 6px 16px;
            border-radius: 20px;
            transition: all 0.3s ease;
            background: transparent;
            cursor: pointer;
            display: inline-block;
        }

        .nav-btn-link:hover {
            background: var(--primary-orange);
            color: white;
        }

        .navbar-title {
            font-size: 16px;
            font-weight: bold;
            color: var(--text-dark);
            margin: 0;
        }

        .profile-container { 
            max-width: 500px; 
            margin: 40px auto; 
            padding: 30px; 
            background: white; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
            text-align: left; 
            box-sizing: border-box;
        }
        
        .profile-header h2 { 
            color: var(--primary-orange); 
            margin: 0 0 5px 0; 
            text-align: center;
        }
        
        .profile-header p { 
            color: #777; 
            margin-bottom: 25px; 
            font-size: 14px; 
            text-align: center;
        }

        .profile-detail {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .profile-label {
            font-weight: bold;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
        }

        .profile-value {
            font-size: 16px;
            color: var(--text-dark);
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-left">
            <a href="manage-profile.php" class="nav-btn-link">← Manage Profiles</a>
        </div>
        <div class="navbar-title">Masisso Staff Administration</div>
        <div class="nav-right">
            <a href="staff_dashboard.php" class="nav-btn-link">Dashboard</a>
        </div>
    </nav>

    <div class="profile-container">
        <div class="profile-header">
            <h2>Staff Profile Info</h2>
            <p>Workplace records matching ID: <?php echo htmlspecialchars($staff_id); ?></p>
        </div>

        <?php if ($staff): ?>
            <div class="profile-detail">
                <div class="profile-label">Staff ID</div>
                <div class="profile-value"><?php echo htmlspecialchars($staff['staff_id']); ?></div>
            </div>

            <div class="profile-detail">
                <div class="profile-label">Full Name</div>
                <div class="profile-value"><?php echo htmlspecialchars($staff['name']); ?></div>
            </div>

            <div class="profile-detail">
                <div class="profile-label">Gender</div>
                <div class="profile-value"><?php echo htmlspecialchars($staff['gender']); ?></div>
            </div>

            <div class="profile-detail">
                <div class="profile-label">Email Address</div>
                <div class="profile-value"><?php echo htmlspecialchars($staff['email']); ?></div>
            </div>
        <?php else: ?>
            <div style="text-align: center; color: #f44336; padding: 20px 0;">
                <p>No workplace staff profile record was found for this ID.</p>
                <a href="manage-profile.php" class="nav-btn-link" style="margin-top: 15px;">Create Profile</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
