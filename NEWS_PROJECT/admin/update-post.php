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
      if($_SESSION['role'] == 0) {
        if(isset($_POST['edit_post_btn'])) {
          $pid = $_POST['post_id'];
          include "config.php";
          $sql = "SELECT post_id FROM post";
          $result= mysqli_query($conn,$sql) or die("<h1>Query Failed</h1>");
          $check = 0;
          if(mysqli_num_rows($result)>0) {
            while($arr = mysqli_fetch_assoc($result)) {
              if($pid==sha1($arr['post_id'])) {
                $pid = $arr['post_id'];
                $check = 1;
                break;
              }
            }
          }
          if($check) {
            $author_id = "";
            $sq = "SELECT author FROM post WHERE post_id='$pid'";
            $re = mysqli_query($conn,$sq) or die("<h1>Query Failed</h1>");
            mysqli_close($conn);
            if(mysqli_num_rows($re)>0) {
              while($arr = mysqli_fetch_assoc($re)) {
                $author_id = $arr['author'];
              }
            }
            
            if($author_id != $_SESSION['user_id'])  {
              header("Location:$hostname/admin/post.php");
            }
          }
        }
      }

      //edit post
      if(isset($_POST['edit_post_btn'])) {
        $pid = $_POST['post_id'];
        include "config.php";
        $sql = "SELECT post_id FROM post";
        $result= mysqli_query($conn,$sql) or die("<h1>Query Failed</h1>");
        mysqli_close($conn);
        if(mysqli_num_rows($result)>0) {
          while($arr = mysqli_fetch_assoc($result)) {
            if($pid==sha1($arr['post_id'])) {
              $pid = $arr['post_id'];
              break;
            }
          }
        }
      }
      else if($_POST['submit']) { //update post code

        include "config.php";

        $post_id = mysqli_real_escape_string($conn,$_POST['p_id']);
        $post_title =  mysqli_real_escape_string($conn,$_POST['post_title']);
        $post_dec =  mysqli_real_escape_string($conn,$_POST['postdesc']);
        $category =  mysqli_real_escape_string($conn,$_POST['category']);

        $img = $_FILES["fileToUpload"];

        $type = $_FILES["fileToUpload"]["type"];

        if($type == "image/jpeg" || $type == "image/png") {
          if($_FILES["fileToUpload"]["size"] <= 2097152) {

            $tmp_name = $_FILES["fileToUpload"]["tmp_name"];
            date_default_timezone_set("Asia/Kolkata");
            $image_name = date("d-m-Y_h-i-s__").mt_rand(10,100000)."__".$_FILES["fileToUpload"]["name"];//image name generator

            $sql4 = "SELECT category,post_img FROM post WHERE post_id='$post_id'";//old category and post_image name find in post table in query
            $result4 = mysqli_query($conn,$sql4) or die("<h1>Query Failed</h1>");
            $post_old_imag_name = "";
            if(mysqli_num_rows($result4) > 0) {
              while($arr4 = mysqli_fetch_assoc($result4)) {
                $post_old_imag_name = $arr4['post_img'];
                //check old category and new category not same
                if($arr4['category'] != $category) {
                  // old category -1 hoba
                  $sql5 = "UPDATE category SET post=post-1 WHERE category_id='{$arr4['category']}'";
                  mysqli_query($conn,$sql5) or die("<h1>Query Failed</h1>");

                  // new category +1 hoba
                  $sql6 = "UPDATE category SET post=post+1 WHERE category_id='$category'";
                  mysqli_query($conn,$sql6) or die("<h1>Query Failed</h1>");
                }
              }
            }

            //update query
            $sql3 = "UPDATE post SET title='$post_title',description='$post_dec',category='$category',post_img='$image_name' WHERE post_id='$post_id'";
            
            mysqli_query($conn,$sql3) or die("<h1>Query Failed</h1>");//update query run
            
            move_uploaded_file($tmp_name,"upload/".$image_name);//image upload

            unlink("upload/".$post_old_imag_name); //old image remove

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
                <strong>Uploaded image size should be 2MB or less,</strong>Go to Post page. Try Again...
              </div>";
              die();
          }
        }
        else {
          mysqli_close($conn);
          echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
              <strong>Uploaded image type should be 'jpg' or 'png'. Not other,</strong>Go to Post page. Try Again...
            </div>";
            die();
        }
      }
      else {
        include "config.php";
        mysqli_close($conn);
        header("Location: $hostname/admin/post.php");
      }
    ?>

    <div id="admin-content">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h1 class="admin-heading">Update Post</h1>
          </div>
          <div class="col-md offset-3 col-md-6 admin-style">
            <?php
              include "config.php";
              $sql1 = "SELECT post_id,title,description,category,post_img FROM post WHERE post_id='$pid'";
              $result1 = mysqli_query($conn,$sql1) or die("<h1>Query Failed</h1>");
              if(mysqli_num_rows($result1)>0) {
                while($arr1 = mysqli_fetch_assoc($result1)) {
            ?>
            <!-- FORM START-->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="p_id" value='<?php echo $arr1['post_id']; ?>'>
                <div class="mb-3">
                    <label for="title-name" class="form-label">Title</label>
                    <input type="text" name="post_title" value="<?php echo $arr1['title']; ?>" class="form-control" id="title-name" required>
                </div>
                <div class="mb-3">
                    <label for="dec-title" class="form-label">Description</label>
                    <textarea class="form-control" name="postdesc" id="dec-title" rows="10" required><?php echo $arr1['description']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="category-name" class="form-label">Category</label>
                    <select class="form-select" name="category" id="category-name" required>
                        <option value="" selected disabled>Select Category</option>
                        <?php
                          $cid = $arr1['category'];
                          $sql2 = "SELECT category_id,category_name FROM category ";
                          $result2 = mysqli_query($conn,$sql2) or die("<h1>Query Failed</h1>");
                          mysqli_close($conn);
                          if(mysqli_num_rows($result1)>0) {
                            while($arr2 = mysqli_fetch_assoc($result2)) {
                              if($arr2['category_id']==$arr1['category']) {
                                echo "<option value='{$arr2['category_id']}' selected>{$arr2['category_name']}</option>";
                              }
                              else {
                                echo "<option value='{$arr2['category_id']}'>{$arr2['category_name']}</option>";
                              }
                            }
                          }
                        ?>
                        <!-- <option value='1'>One</option> -->
                    </select>
                </div>
                <div class="mb-3">
                    <label for="formFile" class="form-label">Post image</label>
                    <input class="form-control" type="file" name="fileToUpload" id="formFile" required>
                    <img src="upload/<?php echo $arr1['post_img']; ?>" title="<?php echo $arr1['post_img']; ?>" height="150px" class="update-image">
                </div>
                <input type="submit" class="btn btn-primary" name="submit" value="Update">
            </form>
            <!-- FORM END-->
            <?php
                }
              }
              else {
                mysqli_close($conn);
                echo "
                  <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                    <strong>Result Not Found.post id value is change!</strong> Go To post Page. Try Again...
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