<?php 
    session_start();
    if(isset($_SESSION['username'])) {
      include "config.php";
      mysqli_close($conn);
      header("Location:$hostname/admin/post.php");
    }  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <!-- style.css -->
    <link rel="stylesheet" href="css/style.css">

    <title>Admin | Login</title>
</head>
<body>
    <div class="admin-main">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-offset-4 col-md-4">
                    <img src="image/news.jpg" class="logo" alt="">
                    <h3>Admin</h3>
                    <!-- Form Start -->
                    <form  action="<?php echo $_SERVER['PHP_SELF']; ?>" method ="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" id="username" aria-describedby="emailHelp" required>
                        </div>
                        <div class="mb-3">
                            <label for="pass" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="pass" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="login" value="login">login</button>
                    </form>
                    <!-- Form End -->
                    <?php 
                        if(isset($_POST['login'])) {
                            include "config.php";
                            $user = mysqli_real_escape_string($conn,$_POST['username']);
                            $pass = md5($_POST['password']);
                            $sql = "SELECT user_id,username,role FROM user_t WHERE username='$user' && password='$pass'";
                            $result = mysqli_query($conn,$sql) or die("Query Failed");
                            mysqli_close($conn);
                            if(mysqli_num_rows($result)>0) {
                                while($arr = mysqli_fetch_assoc($result)) {
                                    session_start();
                                    $_SESSION['user_id']=$arr['user_id'];
                                    $_SESSION['username']=$arr['username'];
                                    $_SESSION['role']=$arr['role'];

                                    header("Location:$hostname/admin/post.php");
                                }
                            }
                            else {
                                echo "
                                    <div class='alert alert-danger alert-dismissible fade show mt-3' role='alert'>
                                    Username and Password are Not matched. <strong>Enter Valid Username and Password.</strong><br>Try Again...
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>";
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>