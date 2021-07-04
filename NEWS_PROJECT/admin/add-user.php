<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <!-- style.css -->
    <link rel="stylesheet" href="css/style.css">

    <title>Admin Panal</title>
  </head>
  <body>
    <!-- header -->
    <?php include_once "header.php"; 
      if($_SESSION['role']==0) {
        include "config.php";
        mysqli_close($conn);
        header("Location:$hostname/admin/post.php");
      }
    ?>
    
    <?php
      if(isset($_POST["submit"])) {
        include "config.php";

        $fname = mysqli_real_escape_string($conn,$_POST["fname"]);
        $lname = mysqli_real_escape_string($conn,$_POST["lname"]);
        $user = mysqli_real_escape_string($conn,$_POST["user"]);
        $pass = mysqli_real_escape_string($conn,md5($_POST["password"]));
        $role = mysqli_real_escape_string($conn,$_POST["role"]);
        
        $sql = "SELECT username FROM user_t WHERE username = '$user'";
        $result = mysqli_query($conn,$sql) or die("<h1>Query Failed</h1>");
        if(mysqli_num_rows($result)>0) {
          echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
              <strong>Username Already Exits!</strong> Try Again
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            mysqli_close($conn);
        }
        else {
          date_default_timezone_set("Asia/Kolkata");
          $user_id = date("d/m/Y-h:i:s--").mt_rand(10000,10000000000);
          $sql1 = "INSERT INTO user_t(user_id,first_name,last_name,username,password,role) VALUES('$user_id','$fname','$lname','$user','$pass',$role)";
          $result =  mysqli_query($conn,$sql1) or die("<h1>Query Failed</h1>");
          mysqli_close($conn);
          if($result) {
            header("Location: $hostname/admin/users.php");
          }
          else {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
              <strong>Query Failed</strong>
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            die();
          }
        }
      }
    ?>

    <div id="admin-content">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h1 class="admin-heading">Add User</h1>
          </div>
          <div class="col-md offset-3 col-md-6 admin-style">
            <!-- FORM START-->
            <form action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST">
                <div class="mb-3">
                    <label for="fname" class="form-label">First Name</label>
                    <input type="text" name="fname" class="form-control" id="fname" placeholder="First Name" required>
                </div>
                <div class="mb-3">
                    <label for="lname" class="form-label">Last Name</label>
                    <input type="text" name="lname" class="form-control" id="lname" placeholder="Last Name" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="user" class="form-control" id="username" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <label for="pass" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="pass" placeholder="Password" required>
                </div>
                <div class="mb-3">
                    <label for="User_Role" class="form-label">User Role</label>
                    <select class="form-select" name="role" id="User_Role" required>
                        <option value="" selected disabled>User Role</option>
                        <option value="0">Normal User</option>
                        <option value="1">Admin</option>
                    </select>
                </div>
                <input type="submit" class="btn btn-primary" name="submit" value="save">
            </form>
            <!-- FORM END-->
          </div>
        </div>
      </div>
    </div>

    <?php include_once "footer.php"; ?>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
  </body>
</html>