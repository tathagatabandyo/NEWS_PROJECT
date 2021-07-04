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

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Admin Panal</title>
  </head>
  <body>
    <!-- header -->
    <?php include_once "header.php"; ?>

    <?php
      if(isset($_POST['delete_post_btn'])) {
        $pid = $_POST['post_id_del'];

        include "config.php";
        $sql3 = "SELECT post_id FROM post";
        $result3 = mysqli_query($conn,$sql3) or die("<h1>Query Failed</h1>");
        if(mysqli_num_rows($result3)>0) {
          $count = 0;
          while($arr3 = mysqli_fetch_assoc($result3)) {
            if($pid == sha1($arr3['post_id'])) {
              $pid = $arr3['post_id'];
              $count=1;
              break;
            }
            else {
              $count=0;
            }
          }
        }
        if($count==0) {
          mysqli_close($conn);
          die("<div class='alert alert-danger d-flex align-items-center' role='alert'>
          <strong>Can't Delete post Record.</strong> post id Change Error! GO TO -> post page, Try again...
          </div>");
        }
        
        
        //find category id and post_image name in specific post
        $sql_p = "SELECT category,post_img FROM post WHERE post_id='$pid'";
        $result_p = mysqli_query($conn,$sql_p) or die("<h1>Query Failed</h1>");
        $category=0;
        $post_imag_name = "";
        if(mysqli_num_rows($result_p)>0) {
          $count = 0;
          while($arr_p = mysqli_fetch_assoc($result_p)) {
            $category = $arr_p['category'];
            $post_imag_name = $arr_p['post_img'];
          }
        }
        
        $sql4 = "UPDATE category SET post=post-1 WHERE category_id='$category'";
        mysqli_query($conn,$sql4) or die("<h1>Query Failed</h1>");

        //delete post
        $sql5 = "DELETE FROM post WHERE post_id='$pid'";
        $result5 = mysqli_query($conn,$sql5) or die("<h1>Query Failed</h1>");
        mysqli_close($conn);
        if($result5) {

          unlink("upload/".$post_imag_name); //image remove

          echo "
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
              <strong>Category Record delete successful</strong>
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }
        else {
          echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
              <strong>Can't Delete Category Record.</strong>
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }

      }
    ?>

    <div id="admin-content">
      <div class="container">
        <div class="row">
          <div class="col-md-10">
            <h1 class="admin-heading">All Posts</h1>
          </div>
          <div class="col-md-2">
            <a class="add-new btn btn-primary" href="add-post.php">add POST</a>
          </div>
          <div class="col-md-12">
            <table class="content-table">
              <thead>
                  <tr><th>S.No.</th>
                  <th>TITLE</th>
                  <th>Category Name</th>
                  <th>DATE</th>
                  <th>AUTHORS</th>
                  <th>Edit</th>
                  <th>Delete</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                  $uid = $_SESSION['user_id'];
                  include "config.php";
                  $limit = 3;

                  if($_SESSION['role']==1) {
                    $sql2 = "SELECT * FROM post";
                  }
                  else {
                    $sql2 = "SELECT * FROM post WHERE author='$uid'";
                  }
                  $result2 = mysqli_query($conn,$sql2) or die("<h1>Query Failed</h1>");
                  $t_p = ceil((mysqli_num_rows($result2))/$limit);

                  if(isset($_GET['page'])) {
                    $page = $_GET['page'];
                    if($page=="0" || $page=="" || $t_p<$page) {
                      mysqli_close($conn);
                      header("Location: $hostname/admin/post.php");
                    }
                  }
                  else {
                    $page=1;
                  }

                  $offset = ($page-1)*$limit;
                  
                  if($_SESSION['role']==1) {
                    $sql = "SELECT p.post_id,p.title,p.post_date,c.category_name,u.first_name,u.last_name FROM post AS p INNER JOIN category AS c ON p.category=c.category_id INNER JOIN user_t AS u ON p.author=u.user_id ORDER BY p.numbering DESC LIMIT $offset,$limit";
                  }
                  else {
                    $sql = "SELECT p.post_id,p.title,p.post_date,c.category_name,u.first_name,u.last_name FROM post AS p INNER JOIN category AS c ON p.category=c.category_id INNER JOIN user_t AS u ON p.author=u.user_id WHERE p.author='$uid' ORDER BY p.numbering DESC LIMIT $offset,$limit";
                  }

                  $result = mysqli_query($conn,$sql) or die("<h1>Query Failed</h1>");
                  mysqli_close($conn);
                  if(mysqli_num_rows($result)>0) {
                    $i=$offset;
                    while($arr = mysqli_fetch_assoc($result)) {
                      $i++;
                ?>
                    <tr>
                      <td class='id'><?php echo $i; ?></td>
                      <td><?php echo $arr['title']; ?></td>
                      <td><?php echo $arr['category_name']; ?></td>
                      <td><?php echo $arr['post_date']; ?></td>
                      <td><?php echo $arr['first_name']." ".$arr['last_name']; ?></td>
                      <td class='edit'>
                        <form action="update-post.php" method="post">
                          <input type="hidden" name="post_id" value="<?php echo sha1($arr['post_id']); ?>">
                          <button class="btn-e" type="submit" name="edit_post_btn" value="delete"><i class='fa fa-edit'></i></button>
                        </form>
                      </td>

                      <td class='delete'>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                          <input type="hidden" name="post_id_del" value="<?php echo sha1($arr['post_id']); ?>">
                          <button class="btn-d" type="submit" name="delete_post_btn" value="delete"><i class='fa fa-trash'></i></button>
                        </form>
                      </td>
                    </tr>

                    <?php
                          }
                        }
                        else {
                      ?>
                      <tr>
                        <td colspan='7' class='id'>NO Record Found</td>
                      </tr>
                    <?php
                        }
                    ?>
                    <!-- <tr>
                        <td class='id'>8</td>
                        <td>Lorem ipsum dolor sit amet</td>
                        <td>Html</td>
                        <td>01 Nov, 2019</td>
                        <td>Admin</td>
                        <td class='edit'><a href='update-post.php'><i class='fa fa-edit'></i></a></td>
                        <td class='delete'><a href='delete-post.php'><i class='fa fa-trash'></i></a></td>
                    </tr> -->
                </tbody>
            </table>
            <?php
              include "config.php";
              if($_SESSION['role']==1) {
                $sql1 = "SELECT * FROM post";
              }
              else {
                $sql1 = "SELECT * FROM post WHERE author='$uid'";
              }
              $result1 = mysqli_query($conn,$sql1) or die("<h1>Query Failed</h1>");
              mysqli_close($conn);
              $total_no_of_record = mysqli_num_rows($result1);
              if($total_no_of_record>0) {
                $total_no_page =  ceil($total_no_of_record/$limit);

                if($total_no_page>1) {

                  echo "<div class='pagination admin-pagination'>";

                  if($page>1) {
                    $p=$page-1;
                    echo "<a href='post.php?page=$p'>Prev</a>";
                  }
                  else {
                    echo "<a disibled class='disabled_a'>Prev</a>";
                  }


                  for($j=1;$j<=$total_no_page;$j++) {
                    if($page==$j) {
                      echo "<a class='active' href='post.php?page=$j'>$j</a>";
                    }
                    else {
                      echo "<a href='post.php?page=$j'>$j</a>";
                    }
                  }

                  if($total_no_page>$page) {
                    $p=$page+1;
                    echo "<a href='post.php?page=$p'>Next</a>";
                  }
                  else {
                    echo "<a disibled class='disabled_a'>Next</a>";
                  }

                  echo "</div>";
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