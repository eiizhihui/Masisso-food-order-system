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
    <title>Masisso - Add Staff Profile</title>
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
        
        .form-group input, .form-group select { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ccc; 
            border-radius: 8px; 
            box-sizing: border-box; 
            font-size: 15px; 
            background-color: white;
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
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-left">
            <a href="staff_dashboard.php" class="nav-btn-link">← Dashboard</a>
        </div>
        <div class="navbar-title">Masisso Staff Administration</div>
        <div class="nav-right">
            <a onclick="navigateToLatestProfile()" class="nav-btn-link">View Profile</a>
        </div>
    </nav>

    <div class="profile-container">
        <div class="profile-header">
            <h2>Create Staff Profile</h2>
            <p>Enter details to save a new workplace profile entry</p>
        </div>

        <form onsubmit="return false;">
            <div class="form-group">
                <label for="staff_id">Staff ID</label>
                <input type="text" id="staff_id" name="staff_id" placeholder="e.g., STF001" required>
            </div>

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter full name" required>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="name@masisso.com" required>
            </div>

            <button type="button" class="submit-profile-btn" onclick="saveProfileData()">Save Profile Entry</button>
        </form>
    </div>

    <script>
        // SMART LINK: Automatically finds the correct ID to view
        function navigateToLatestProfile() {
            const currentInputId = document.getElementById('staff_id').value.trim();
            
            // If the user typed an ID into the input field right now, look that up
            if (currentInputId !== "") {
                window.location.href = 'view_profile.php?id=' + encodeURIComponent(currentInputId);
            } else {
                // Otherwise, ask the backend who the last updated active staff member is!
                fetch('get_curent_staff.php?latest=true')
                    .then(response => response.json())
                    .then(data => {
                        if (data.staff_id) {
                            window.location.href = 'view_profile.php?id=' + encodeURIComponent(data.staff_id);
                        } else {
                            window.location.href = 'view_profile.php?id=STF001';
                        }
                    })
                    .catch(() => {
                        window.location.href = 'view_profile.php?id=STF001';
                    });
            }
        }

        function saveProfileData() {
            const staffId = document.getElementById('staff_id').value.trim();
            const nameVal = document.getElementById('name').value.trim();
            const gendVal = document.getElementById('gender').value;
            const mailVal = document.getElementById('email').value.trim();

            if(!staffId || !nameVal || !gendVal || !mailVal) {
                alert("Please fill out all fields.");
                return;
            }

            const dataPayload = new FormData();
            dataPayload.append('staff_id', staffId);
            dataPayload.append('name', nameVal);
            dataPayload.append('gender', gendVal);
            dataPayload.append('email', mailVal);

            fetch('update_staff.php', {
                method: 'POST',
                body: dataPayload
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    window.location.href = 'view_profile.php?id=' + encodeURIComponent(staffId);
                } else {
                    alert("Database Error: " + data.message);
                }
            })
            .catch(error => {
                console.error('Submission Error:', error);
                alert("Could not complete registration request.");
            });
        }
    </script>
</body>
</html>
