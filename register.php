<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Massiso - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>
<body>
     <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh">
        <form class="border shadow p-3 rounded"
              action="php/check-register.php"
              method="post" 
              style="width: 450px;">
              <h1 class="text-center p-3">REGISTER</h1>
              
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
          
          <div class="mb-1">
            <label class="form-label">Select User Type:</label>
          </div>
          <select class="form-select mb-3" name="role" aria-label="Default select example">
              <option selected value="customer">Customer</option>
              <option value="staff">Staff</option>
              <option value="admin">Admin</option>
          </select>
         
          <button type="submit" class="btn btn-primary">REGISTER</button>
          <a href="login.php" class="btn btn-secondary">Go to Login</a>
        </form>
      </div>
</body>
</html>