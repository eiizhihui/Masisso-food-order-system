<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Masisso Food Order System - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        html, body {
            padding-bottom: 0 !important;
        }
    </style>
</head>
<body class="login-page">
     <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh">
        <form class="border shadow p-4 rounded"
              action="php/check-register.php"
              method="post" 
              style="width: 450px;">
                <div class="text-center mb-4">
                    <h2 class="fw-bold" style="color: var(--primary-orange); margin-bottom: 0;">Masisso</h2>
                    <p class="text-muted small" style="letter-spacing: 1.5px; font-weight: 600; margin-bottom: 10px; font-size: 0.8rem;">FOOD ORDER SYSTEM</p>
                    <hr style="border-top: 1px solid #eee; margin: 15px 0;">
                    <h5 class="fw-bold text-secondary" style="margin-bottom: 20px; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 0.5px;">Register</h5>
                </div>
              
              <?php if (isset($_GET['error'])) { ?>
              <div class="alert alert-danger" role="alert">
                 <?=$_GET['error']?> 
              </div>
              <?php } ?>

              <?php if (isset($_GET['success'])) { ?>
              <div class="alert alert-success" role="alert">
                 <?=$_GET['success']?> 
              </div>
              <?php } ?>

          <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Enter your full name" required>
          </div>

          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" id="username" placeholder="Choose a username" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
          </div>
          
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Create a password" required>
          </div>

          <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter your phone number" required>
          </div>

          <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" name="address" id="address" placeholder="Enter your default address" required></textarea>
          </div>
          
               <input type="hidden" name="role" value="customer">
         
          <button type="submit" class="solid-btn">REGISTER</button>

          <div class="mt-2 text-start">
              <span class="text-muted">Already have an account?</span><br>
              <a href="login.php" class="text-primary" style="text-decoration: underline;">Click here to login</a>
          </div>
        </form>
      </div>
      <script src="script.js"></script>
</body>
</html>