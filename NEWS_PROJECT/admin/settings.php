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
    // update setting start
      if(isset($_POST['submit'])) {
        include "config.php";
        $old_logo =  $_POST['old_logo'];

        $sql_l = "SELECT logo FROM setting";
        $result_l = mysqli_query($conn,$sql_l) or die("<h1>Query Failed</h1>");
        $check = 0;
        if(mysqli_num_rows($result_l)>0) {
          while($arr_l = mysqli_fetch_assoc($result_l)) {
            if($old_logo == sha1($arr_l['logo'])) {
              $old_logo = $arr_l['logo'];
              $check = 1;
              break;
            }
          }
        }
        if($check) {
          date_default_timezone_set("Asia/Kolkata");

          $website_name =  mysqli_real_escape_string($conn,$_POST['website_name']);
          $footer_desc =  mysqli_real_escape_string($conn,$_POST['footer_desc']);

          $post_date = date("d M, Y");

          $img = $_FILES["web_logo"];

          $type = $_FILES["web_logo"]["type"];//file type
          
          //file type check
          if($type == "image/jpeg" || $type == "image/png") {
            if($_FILES["web_logo"]["size"] <= 2097152) {

              $tmp_name = $_FILES["web_logo"]["tmp_name"];
              $image_name = date("d-m-Y_h-i-s__").mt_rand(10,100000)."__".$_FILES["web_logo"]["name"];

              $sql1 = "UPDATE setting SET website_name='$website_name',logo='$image_name',footer_dec='$footer_desc' WHERE logo='$old_logo'";
              
              mysqli_query($conn,$sql1) or die("<h1>Query Failed</h1>");
              
              move_uploaded_file($tmp_name,"image/".$image_name);
              unlink("image/".$old_logo); //old image remove

              mysqli_close($conn);

              echo "
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                  <strong>Update Successful</strong>
                  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("Location: $hostname/admin/settings.php");
            }
            else {
              mysqli_close($conn);
              echo "
                <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                  <strong>Uploaded image size should be 2MB or less,</strong> Try Again...
                  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
            }
          }
          else {
            mysqli_close($conn);
            echo "
              <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>Uploaded image type should be 'jpg' or 'png'. Not other,</strong> Try Again...
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
          }
        }
        else {
          mysqli_close($conn);
          echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
              <strong>Somthing Went to Wrong.</strong> Try Again...
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }
      }
      // update setting end
    ?>

    <div id="admin-content">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h1 class="admin-heading">Add New Post</h1>
          </div>
          <div class="col-md offset-3 col-md-6 admin-style">
            <?php 
              include "config.php";
              $sql = "SELECT * FROM setting";
              $result = mysqli_query($conn,$sql) or die("<h1>Query Failed</h1>");
              if(mysqli_num_rows($result)>0) {
                while($arr = mysqli_fetch_assoc($result)) {
            ?>
            <!-- FORM START-->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="Website_Name" class="form-label">Website Name</label>
                    <input type="text" name="website_name" value="<?php echo $arr['website_name']; ?>" class="form-control" id="Website_Name" required>
                </div>
                <div class="mb-3">
                    <label for="fileimage" class="form-label">Wevsite Logo</label>
                    <input class="form-control" type="file" name="web_logo" id="fileimage" required>
                    <img src="image/<?php echo $arr['logo']; ?>" title="" height="150px" class="update-image">
                    <input type="hidden" name="old_logo" value="<?php echo sha1($arr['logo']); ?>">
                </div>
                <div class="mb-3">
                    <label for="dec-title" class="form-label">Footer Description</label>
                    <textarea class="form-control" name="footer_desc" id="dec-title" rows="3" required><?php echo $arr['footer_dec']; ?></textarea>
                </div>
                <input type="submit" class="btn btn-primary" name="submit" value="save">
            </form>
            <!-- FORM END-->
            <?php
                }
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