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
      if(isset($_POST['edit_categorie_btn'])) {
        $cid = $_POST['id_edit'];
        include "config.php";
        $sq = "SELECT category_id FROM category";
        $res = mysqli_query($conn,$sq) or die("<h1>Query Failed</h1>");
        mysqli_close($conn);
        if(mysqli_num_rows($res)>0) {
          while($ar = mysqli_fetch_assoc($res)) {
            if($cid == sha1($ar['category_id'])) {
              $cid = $ar['category_id'];
              break;
            }
          }
        }
      }
      //update code
      else if((isset($_POST['submit']))) {
        if(isset($_POST['submit'])) {
          include "config.php";
          $category_Name = mysqli_real_escape_string($conn,$_POST["cat"]);
          $category_id = $_POST['cate_id'];
          $sql1 = "UPDATE category SET category_name='$category_Name' WHERE category_id='$category_id'";
          mysqli_query($conn,$sql1) or die("<h1>Query Failed</h1>");
          mysqli_close($conn);
          header("Location: $hostname/admin/category.php");
        }
      }
      else {
        include "config.php";
        mysqli_close($conn);
        header("Location: $hostname/admin/category.php");
      }
    ?>

    <div id="admin-content">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h1 class="admin-heading">Update Category</h1>
          </div>
          <div class="col-md offset-3 col-md-6 admin-style">

            <?php 
              include "config.php";
              $sql = "SELECT category_id,category_name FROM category WHERE category_id='$cid'";
              $result = mysqli_query($conn,$sql) or die("<h1>Query Failed</h1>");
              if(mysqli_num_rows($result)>0) {
                while($arr = mysqli_fetch_assoc($result)) {
            ?>
            <!-- FORM START-->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="cate_id" value="<?php echo $arr['category_id']; ?>">
                <div class="mb-3">
                    <label for="Category_Name" class="form-label">Category Name</label>
                    <input type="text" name="cat" value="<?php echo $arr['category_name']; ?>" class="form-control" id="Category_Name" placeholder="Category Name" required>
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
                    <strong>Category id value is change!</strong> Go To Category Page. Try Again...
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