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
      $id = $_GET['id'];
      include "config.php";
      $sq = "SELECT user_id FROM user_t";
      $re = mysqli_query($conn,$sq) or die("<h1>Query Failed</h1>");
      mysqli_close($conn);
      if(mysqli_num_rows($re) > 0) {
        while($ar = mysqli_fetch_assoc($re)) {
          if($id == sha1($ar['user_id'])) {
            $id = $ar['user_id'];
            break;
          }
        }
      }
    ?>
    <?php 
      if(isset($_POST['submit'])) {
        include "config.php";

        $use_id = mysqli_real_escape_string($conn,$_POST["user_id"]);
        $fname = mysqli_real_escape_string($conn,$_POST["fname"]);
        $lname = mysqli_real_escape_string($conn,$_POST["lname"]);
        // $user = mysqli_real_escape_string($conn,$_POST["user"]);
        $role = mysqli_real_escape_string($conn,$_POST["role"]);
        // username='$user'
        $sql1 = "UPDATE user_t SET first_name='$fname',last_name='$lname',role=$role WHERE user_id='$use_id'";
        $result1 =  mysqli_query($conn,$sql1) or die("<h1>Query Failed</h1>");
        mysqli_close($conn);
        header("Location: $hostname/admin/users.php");
      }
    ?>

    <div id="admin-content">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h1 class="admin-heading">Modify User Details</h1>
          </div>
          <div class="col-md offset-3 col-md-6 admin-style">

            <?php 
              include "config.php";
              $sql = "SELECT * FROM user_t WHERE user_id='$id'";
              $result = mysqli_query($conn,$sql) or die("<h1>Query Failed</h1>");
              mysqli_close($conn);
              if(mysqli_num_rows($result)>0) {
                $i=0;
                while($arr = mysqli_fetch_assoc($result)) {
            ?>
            <!-- FORM START-->
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="<?php echo $arr['user_id']; ?>">
                <div class="mb-3">
                    <label for="fname" class="form-label">First Name</label>
                    <input type="text" name="fname" value="<?php echo $arr['first_name']; ?>" class="form-control" id="fname" placeholder="First Name" required>
                </div>
                <div class="mb-3">
                    <label for="lname" class="form-label">Last Name</label>
                    <input type="text" name="lname" value="<?php echo $arr['last_name']; ?>" class="form-control" id="lname" placeholder="Last Name" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="user" disabled value="<?php echo $arr['username']; ?>" class="form-control" id="username" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <label for="User_Role" class="form-label">User Role</label>
                    <select class="form-select" name="role" id="User_Role" required>
                        <option value="" selected disabled>User Role</option>
                        <?php 
                          if($arr['role']==0) {
                        ?> 
                              <option selected value="0">Normal User</option>
                              <option value="1">Admin</option>
                          <?php
                          }
                          else {
                          ?>
                              <option value="0">Normal User</option>
                              <option selected value="1">Admin</option>
                        <?php
                          }
                        ?>
                    </select>
                </div>
                <input type="submit" class="btn btn-primary" name="submit" value="Update">
            </form>
            <!-- FORM END-->
            <?php 
                }
              }
              else {
                echo "
                  <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                    <strong>id value is change!</strong> Go To users Page. Try Again...
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
              }
            ?>
          </div>
        </div>
      </div>
    </div>

    <?php include_once "footer.php"; ?>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
  </body>
</html>