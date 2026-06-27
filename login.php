<?php 
   session_start();
   include "db_connect.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Masisso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css?v=13">
    <!-- FontAwesome for the eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html, body {
            padding-bottom: 0 !important;
            background-color: var(--primary-orange) !important;
            height: 100% !important;
            margin: 0 !important;
        }
    </style>
</head>
<body class="login-body">

    <div class="login-card-wrapper">
        <!-- LEFT: Form Panel -->
        <div class="login-form-panel">
            <div>
                
                <div class="login-brand">
                    <svg class="login-brand-icon" viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M2 10h20c0 5-4.5 9-10 9S2 15 2 10z" fill="currentColor" />
                        <path d="M6 19c2 2 6 2 8 0" stroke="currentColor" />
                        <line x1="6" y1="2" x2="16" y2="12" stroke="currentColor" stroke-width="2.5" />
                        <line x1="9" y1="2" x2="19" y2="12" stroke="currentColor" stroke-width="2.5" />
                    </svg>
                    <span class="login-brand-name">Masisso</span>
                </div>

                <h2 class="login-title">Login</h2>

                <form action="php/check-login.php" method="post">
                    <?php if (isset($_GET['error'])) { ?>
                    <div class="alert alert-danger py-2 px-3 mb-4 small" role="alert" style="border-radius: 10px;">
                        <?=htmlspecialchars($_GET['error'])?> 
                    </div>
                    <?php } ?>
                    <?php if (isset($_GET['success'])) { ?>
                    <div class="alert alert-success py-2 px-3 mb-4 small" role="alert" style="border-radius: 10px;">
                        <?=htmlspecialchars($_GET['success'])?> 
                    </div>
                    <?php } ?>

                    <!-- Username input group -->
                    <div class="login-input-group">
                        <input type="text" name="username" id="username" placeholder="Username or Email" required autocomplete="username">
                    </div>

                    <!-- Password input group with eye toggle -->
                    <div class="login-input-group">
                        <input type="password" name="password" id="password" placeholder="Password" required autocomplete="current-password">
                        <button type="button" class="password-toggle-btn" id="password-toggle" aria-label="Toggle Password Visibility">
                            <i class="fas fa-eye" id="toggle-icon"></i>
                        </button>
                    </div>

                    <button type="submit" class="login-submit-btn">LOGIN</button>
                </form>
            </div>

            <div class="login-footer">
                <span class="text-muted">Don't have an account?</span>
                <a href="register.php" class="text-primary font-weight-bold" style="text-decoration: underline; margin-left: 5px;">Create here</a>
                <div style="margin-top: 15px;">&copy; 2026 Masisso Ltd.</div>
            </div>
        </div>

        <!-- RIGHT: Visual Panel with Orange Background and Rounded Laksa Picture Card -->
        <div class="login-visual-panel">
            <div class="login-image-card">
                <img src="images/laksaLogin.jpg" alt="Masisso Signature Laksa">
            </div>
        </div>
    </div>

    <script>
        // Password Visibility Toggle Logic
        document.getElementById('password-toggle').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const icon = document.getElementById('toggle-icon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                passwordField.type = 'password';
                icon.className = 'fas fa-eye';
            }
        });
    </script>
</body>
</html>