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
    <?php include_once "header.php"; 
      if($_SESSION['role']==0) {
        include "config.php";
        mysqli_close($conn);
        header("Location:$hostname/admin/post.php");
      }
    ?>

    <?php 
      if(isset($_POST['delete_categorie_btn'])) {
        $cid = $_POST['id_del'];
        include "config.php";
        $sq = "SELECT category_id FROM category";
        $res = mysqli_query($conn,$sq) or die("<h1>Query Failed</h1>");
        if(mysqli_num_rows($res)>0) {
          $count = 0;
          while($ar = mysqli_fetch_assoc($res)) {
            if($cid == sha1($ar['category_id'])) {
              $cid = $ar['category_id'];
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
          <strong>Can't Delete Category Record.</strong> Category id Change Error! GO TO -> category page, Try again...
          </div>");
        }
        
        //delete category
        $sql1 = "DELETE FROM category WHERE category_id='$cid'";
        $result1 = mysqli_query($conn,$sql1) or die("<h1>Query Failed</h1>");
        mysqli_close($conn);
        if($result1) {
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
            <h1 class="admin-heading">All Categories</h1>
          </div>
          <div class="col-md-2">
            <a class="add-new btn btn-primary" href="add-category.php">add category</a>
          </div>
          <div class="col-md-12">
            <table class="content-table">
              <thead>
                  <tr><th>S.No.</th>
                  <th>Category Name</th>
                  <th>No. of Posts</th>
                  <th>Edit</th>
                  <th>Delete</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    include "config.php";
                    
                    $limit = 3;

                    $sql3 = "SELECT * FROM category";
                    $result3 = mysqli_query($conn,$sql3) or die("<h1>Query Failed</h1>");
                    $t_p = ceil((mysqli_num_rows($result3))/$limit);

                    if(isset($_GET['page'])) {
                      $page = $_GET['page'];
                      if($page=="0" || $page=="" || $t_p<$page) {
                        mysqli_close($conn);
                        header("Location: $hostname/admin/category.php");
                      }
                    }
                    else {
                      $page=1;
                    }
                    
                    $offset = ($page-1)*$limit;
                    


                    $sql = "SELECT * FROM category ORDER BY numbering DESC LIMIT $offset,$limit";
                    $result = mysqli_query($conn,$sql) or die("<h1>Query Failed</h1>");
                    mysqli_close($conn);
                    if(mysqli_num_rows($result)>0) {
                      $i=$offset;
                      while($arr = mysqli_fetch_assoc($result)) {
                        $i++;
                  ?>
                  <tr>
                    <td class='id'><?php echo $i; ?></td>
                    <td><?php echo $arr['category_name']; ?></td>
                    <td><?php echo $arr['post']; ?></td>
                    <td class='edit'>
                      <form action="update-category.php" method="post">
                        <input type="hidden" name="id_edit" value="<?php echo sha1($arr['category_id']); ?>">
                        <button class="btn-e" type="submit" name="edit_categorie_btn" value="delete"><i class='fa fa-edit'></i></button>
                      </form>
                    </td>

                    <td class='delete'>
                      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <input type="hidden" name="id_del" value="<?php echo sha1($arr['category_id']); ?>">
                        <button class="btn-d" type="submit" name="delete_categorie_btn" value="delete"><i class='fa fa-trash'></i></button>
                      </form>
                    </td>
                  </tr>
                  <!-- <tr>
                    <td class='id'>2</td>
                    <td>Css</td>
                    <td>15</td>
                    <td class='edit'><a href='update-category.php'><i class='fa fa-edit'></i></a></td>
                    <td class='delete'><a href='delete-category.php'><i class='fa fa-trash'></i></a></td>
                  </tr>-->
                  <?php
                      }
                    }
                    else {
                  ?>

                  <tr>
                    <td colspan='5' class='id'>No Record Found</td>
                  </tr>

                  <?php
                    }
                  ?>
                </tbody>
            </table>
            <?php
              include "config.php";
              $sql2 = "SELECT * FROM category";
              $result2 = mysqli_query($conn,$sql2) or die("<h1>Query Failed</h1>");
              mysqli_close($conn);
              $total_no_of_record = mysqli_num_rows($result2);
              if($total_no_of_record > 0) {
                
                $total_page = ceil($total_no_of_record/$limit);

                if($total_page>1) {

                  echo "<div class='pagination admin-pagination'>";

                  if($page>1) {
                    $p=$page-1;
                    echo "<a href='category.php?page=$p'>Prev</a>";
                  }
                  else {
                      echo "<a disibled class='disabled_a'>Prev</a>";
                  }

                  for($j=1;$j<=$total_page;$j++) {
                    if($page==$j) {
                      echo "<a class='active' href='category.php?page=$j'>$j</a>";
                    }
                    else {
                      echo "<a href='category.php?page=$j'>$j</a>";
                    }
                  }

                  if($total_page>$page) {
                    $p=$page+1;
                    echo "<a href='category.php?page=$p'>Next</a>";
                  }
                  else {
                    echo "<a disibled class='disabled_a'>Next</a>";
                  }

                  echo "</div>";
                }
              }
            ?>
            
                  <!-- <a href="" class='active'>1</a> -->
                  
                  
              
          </div>
        </div>
      </div>
    </div>

    <?php include_once "footer.php"; ?>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
  </body>
</html>