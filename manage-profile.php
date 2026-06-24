<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ['staff', 'admin', 'super admin'])) {
    header("Location: login.php");
    exit();
}
$role = strtolower($_SESSION['role']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masisso - Profile</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: var(--primary-orange);
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-left h2 {
            margin: 0;
            font-size: 24px;
        }

        .header a {
            color: white;
            text-decoration: none;
            font-size: 20px;
            margin: 0;
        }

        .profile-container {
            max-width: 500px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            box-sizing: border-box;
            margin-bottom: 120px;
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
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 15px;
            background-color: white;
            font-family: inherit;
        }

        .form-group input:disabled {
            background-color: #f9f9f9;
            color: #555;
            border-color: #ddd;
        }

        .submit-profile-btn {
            background: var(--primary-orange);
            color: white;
            border: none;
            border-radius: 10px;
            width: 100%;
            padding: 14px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.2s;
            margin-top: 10px;
        }

        .submit-profile-btn:active {
            opacity: 0.8;
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
        <div class="header-left">
            <?php if ($role === 'staff'): ?>
                <a href="staff_dashboard.php" aria-label="Back to Dashboard"><i class="fas fa-arrow-left"></i></a>
            <?php else: ?>
                <a href="admin-dashboard.php" aria-label="Back to Dashboard"><i class="fas fa-arrow-left"></i></a>
            <?php endif; ?>
            <h2>Profile</h2>
        </div>
        <div style="display: flex; gap: 15px; align-items: center;">
            <?php if ($role !== 'staff'): ?>
                <button id="edit-profile-btn" onclick="toggleEditMode()" style="background: rgba(255, 255, 255, 0.2); border: none; color: white; font-weight: bold; font-size: 14px; padding: 6px 15px; border-radius: 20px; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.4)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'"><i class="fas fa-edit"></i> Edit</button>
            <?php endif; ?>
            <a href="logout.php" style="color: white; text-decoration: none; font-weight: bold; font-size: 14px; background: rgba(255, 255, 255, 0.2); padding: 6px 15px; border-radius: 20px; display: flex; align-items: center; gap: 8px; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.4)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <div class="profile-container">
        <div class="profile-header">
            <h2>User Profile</h2>
            <p>Your personal and login information</p>
        </div>

        <form onsubmit="return false;">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" placeholder="Enter your name" required disabled>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" placeholder="Choose a username" required disabled>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" placeholder="name@masisso.com" required disabled>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" placeholder="Enter phone number" disabled>
            </div>

            <div class="form-group">
                <label for="branch">Branch</label>
                <input type="text" id="branch" placeholder="Branch" disabled>
            </div>

            <?php if ($role !== 'staff'): ?>
                <div class="form-group">
                    <label for="password">New Password (leave blank to keep current)</label>
                    <input type="password" id="password" placeholder="Enter new password" disabled>
                </div>
                <button type="button" class="submit-profile-btn" id="update-profile-btn" onclick="saveProfileData()" style="display:none;">Update Profile</button>
            <?php else: ?>
                <p class="readonly-note"><i class="fas fa-lock"></i> Profile can only be updated by an Admin.</p>
            <?php endif; ?>
        </form>
    </div>

    <div class="bottom-nav">
        <?php if ($role === 'staff'): ?>
            <a href="staff_dashboard.php" class="nav-item-bottom">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="manage-menu.php" class="nav-item-bottom">
                <i class="fas fa-utensils"></i>
                <span>Menu</span>
            </a>
            <a href="manage-order.php" class="nav-item-bottom">
                <i class="fas fa-clipboard-list"></i>
                <span>Orders</span>
            </a>
            <a href="manage-profile.php" class="nav-item-bottom active">
                <i class="fas fa-user-cog"></i>
                <span>Profile</span>
            </a>
        <?php else: ?>
            <a href="admin-dashboard.php" class="nav-item-bottom">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="manage-user.php" class="nav-item-bottom">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
            <a href="manage-order.php" class="nav-item-bottom">
                <i class="fas fa-clipboard-list"></i>
                <span>Orders</span>
            </a>
            <a href="manage-menu.php" class="nav-item-bottom">
                <i class="fas fa-utensils"></i>
                <span>Menu</span>
            </a>
            <a href="manage-event.php" class="nav-item-bottom">
                <i class="fas fa-calendar-alt"></i>
                <span>Events</span>
            </a>
            <a href="manage-reward.php" class="nav-item-bottom">
                <i class="fas fa-gift"></i>
                <span>Rewards</span>
            </a>
            <a href="manage-profile.php" class="nav-item-bottom active">
                <i class="fas fa-user-cog"></i>
                <span>Profile</span>
            </a>
        <?php endif; ?>
    </div>

    <script>
        const currentUserId = <?php echo (int)$_SESSION['user_id']; ?>;
        const currentUserRole = <?php echo json_encode($_SESSION['role']); ?>;

        // ==========================================
        // 1. SHARED FEATURES (Staff & Admin)
        // ==========================================
        
        document.addEventListener('DOMContentLoaded', () => {
            fetch('staff-php/user_read.php')
                .then(res => res.json())
                .then(users => {
                    const user = users.find(u => parseInt(u.user_id) === currentUserId);
                    if (user) {
                        document.getElementById('name').value = user.name || '';
                        document.getElementById('username').value = user.username || '';
                        document.getElementById('email').value = user.email || '';
                        document.getElementById('phone').value = user.phone || '';
                        document.getElementById('branch').value = user.branch || '';
                    } else {
                        alert("Could not load profile data.");
                    }
                })
                .catch(err => {
                    console.error("Error fetching user data:", err);
                    alert("Error loading profile details.");
                });
        });

        // ==========================================
        // 2. ADMIN-ONLY FEATURES
        // ==========================================
        
        function toggleEditMode() {
            const inputs = document.querySelectorAll('#name, #username, #email, #phone, #branch, #password');
            inputs.forEach(input => input.disabled = false);
            const updateBtn = document.getElementById('update-profile-btn');
            if (updateBtn) updateBtn.style.display = 'block';
            const editBtn = document.getElementById('edit-profile-btn');
            if (editBtn) editBtn.style.display = 'none';
        }

        function saveProfileData() {
            const nameVal = document.getElementById('name').value.trim();
            const usernameVal = document.getElementById('username').value.trim();
            const emailVal = document.getElementById('email').value.trim();
            const phoneVal = document.getElementById('phone').value.trim();
            const branchVal = document.getElementById('branch').value.trim();
            const passwordVal = document.getElementById('password').value;

            if (!nameVal || !usernameVal || !emailVal) {
                alert("Name, Username, and Email are mandatory.");
                return;
            }

            const dataPayload = {
                user_id: currentUserId,
                role: currentUserRole,
                name: nameVal,
                username: usernameVal,
                email: emailVal,
                phone: phoneVal,
                branch: branchVal
            };

            if (passwordVal.trim() !== '') {
                dataPayload.password = passwordVal;
            }

            fetch('staff-php/profile_update.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(dataPayload)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Profile updated successfully!");
                        document.getElementById('password').value = ''; // clear password field
                        
                        // Disable input fields again and toggle edit button back
                        const inputs = document.querySelectorAll('#name, #username, #email, #phone, #branch, #password');
                        inputs.forEach(input => input.disabled = true);
                        document.getElementById('update-profile-btn').style.display = 'none';
                        document.getElementById('edit-profile-btn').style.display = 'block';
                    } else {
                        alert("Database Error: " + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Submission Error:', error);
                    alert("Could not complete profile update request.");
                });
        }
    </script>
</body>

</html>
