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
    <?php include_once "header.php"; ?>

    
    <?php
    // Add New Post code start
      if(isset($_POST['submit'])) {
        include "config.php";

        date_default_timezone_set("Asia/Kolkata");
        $post_id = date("d/m/Y~:~h:i:s~:~").mt_rand(50000,10000000000);

        $post_title =  mysqli_real_escape_string($conn,$_POST['post_title']);
        $post_dec =  mysqli_real_escape_string($conn,$_POST['postdesc']);
        $category =  mysqli_real_escape_string($conn,$_POST['category']);

        $post_date = date("d M, Y");
        $author = $_SESSION['user_id'];

        $img = $_FILES["fileToUpload"];

        $type = $_FILES["fileToUpload"]["type"];//file type
        
        // $file_extension = array("jpg","jpeg","png");
        //file type check
        if($type == "image/jpeg" || $type == "image/png") {
          if($_FILES["fileToUpload"]["size"] <= 2097152) {

            $tmp_name = $_FILES["fileToUpload"]["tmp_name"];
            $image_name = date("d-m-Y_h-i-s__").mt_rand(10,100000)."__".$_FILES["fileToUpload"]["name"];

            $sql1 = "INSERT INTO post(post_id,title,description,category,post_date,author,post_img) VALUES('$post_id','$post_title','$post_dec','$category','$post_date','$author','$image_name')";
            
            mysqli_query($conn,$sql1) or die("<h1>Query Failed</h1>");
            
            move_uploaded_file($tmp_name,"upload/".$image_name);

            $sql2 = "UPDATE category SET post=post+1 WHERE category_id='$category'";

            mysqli_query($conn,$sql2) or die("<h1>Query Failed</h1>");

            mysqli_close($conn);

            echo "
              <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>Post Add Successful</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
              header("Location: $hostname/admin/post.php");
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
    // Add New Post code end
    ?>

    <div id="admin-content">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h1 class="admin-heading">Add New Post</h1>
          </div>
          <div class="col-md offset-3 col-md-6 admin-style">
            <!-- FORM START-->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title-name" class="form-label">Title</label>
                    <input type="text" name="post_title" class="form-control" id="title-name" required>
                </div>
                <div class="mb-3">
                    <label for="dec-title" class="form-label">Description</label>
                    <textarea class="form-control" name="postdesc" id="dec-title" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="category-name" class="form-label">Category</label>
                    <select class="form-select" name="category" id="category-name" required>
                        <option value="" selected disabled>Select Category</option>
                        <?php
                          include "config.php";
                          $sql = "SELECT category_id,category_name FROM category ORDER BY numbering";
                          $result = mysqli_query($conn,$sql) or die("<h1>Query Failed</h1>");
                          mysqli_close($conn);
                          if(mysqli_num_rows($result) > 0) {
                            while($arr = mysqli_fetch_assoc($result)) {
                        ?>
                        <option value='<?php echo $arr['category_id']; ?>'><?php echo $arr['category_name']; ?></option>
                        <?php
                            }
                          }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="formFile" class="form-label">Post image</label>
                    <input class="form-control" type="file" name="fileToUpload" id="formFile" required>
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