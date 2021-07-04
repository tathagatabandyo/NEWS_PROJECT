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
      if(isset($_POST['submit'])) {
        include "config.php";
        $category_Name = mysqli_real_escape_string($conn,$_POST['cat']);

        date_default_timezone_set("Asia/Kolkata");
        $category_id = date("d/m/Y-:-h:i:s-:-").mt_rand(40000,10000000000);

        $sql = "INSERT INTO category(category_id,category_name,post) VALUES('$category_id','$category_Name',0)";
        mysqli_query($conn,$sql) or die("<h1>Query Failed</h1>");
        mysqli_close($conn);
        header("Location: $hostname/admin/category.php");
      }
    ?>

    <div id="admin-content">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h1 class="admin-heading">Add New Category</h1>
          </div>
          <div class="col-md offset-3 col-md-6 admin-style">
            <!-- FORM START-->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="mb-3">
                    <label for="Category_Name" class="form-label">Category Name</label>
                    <input type="text" name="cat" class="form-control" id="Category_Name" placeholder="Category Name" required>
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