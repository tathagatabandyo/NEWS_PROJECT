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
      if(isset($_POST["delete_btn"])) {

        $id = $_POST['id_del'];

        include "config.php";
        $sq = "SELECT user_id FROM user_t";
        $re = mysqli_query($conn,$sq) or die("<h1>Query Failed</h1>");
        mysqli_close($conn);
        if(mysqli_num_rows($re) > 0) {
          $count = 0;
          while($ar = mysqli_fetch_assoc($re)) {
            if($id == sha1($ar['user_id'])) {
              $id = $ar['user_id'];
              $count=1;
              break;
            }
            else {
              $count=0;
            }
          }
        }
        if($count==0) {
            die("<div class='alert alert-danger d-flex align-items-center' role='alert'>
            <strong>Can't Delete User Record.</strong> User id Change Error! GO TO -> User page, Try again...
          </div>");
        }

        include "config.php";
        $sql1 = "DELETE FROM user_t WHERE user_id='$id'";
        $result34 = mysqli_query($conn,$sql1) or die("<h1>Query Failed</h1>");
        if($result34) {
          echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
              <strong>User Record delete successful</strong>
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }
        else {
          echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
              <strong>Can't Delete User Record.</strong>
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }
        mysqli_close($conn);
      }
    ?>

    <div id="admin-content">
      <div class="container">
        <div class="row">
          <div class="col-md-10">
            <h1 class="admin-heading">All Users</h1>
          </div>
          <div class="col-md-2">
            <a class="add-new btn btn-primary" href="add-user.php">add user</a>
          </div>
          <div class="col-md-12">
            <table class="content-table">
            <thead>
                <th>S.No.</th>
                <th>Full Name</th>
                <th>User Name</th>
                <th>Role</th>
                <th>Edit</th>
                <th>Delete</th>
            </thead>
            <tbody>
            <?php 
              include "config.php";

              $limit = 3;

              $sq12 = "SELECT * FROM user_t";
              $res12 = mysqli_query($conn,$sq12) or die("<h1>Query Failed</h1>");
              $t_p = ceil((mysqli_num_rows($res12))/$limit);

              if(isset($_GET['page'])) {
                $page = $_GET['page'];
                if($page=="0" || $page=="" || $t_p<$page) {
                  // $page=1;
                  mysqli_close($conn);
                  header("Location: $hostname/admin/users.php");
                }
              }
              
              else {
                $page = 1;
              }
              $offset = ($page-1)*$limit;

              $sql = "SELECT * FROM user_t ORDER BY numbering  DESC LIMIT $offset,$limit";
              $result = mysqli_query($conn,$sql) or die("<h1>Query Failed</h1>");
              mysqli_close($conn);
              if(mysqli_num_rows($result)>0) {
                $i=$offset;
                while($arr = mysqli_fetch_assoc($result)) {
                  $i++;
            ?>
                <tr>
                    <td class='id'><?php echo $i; ?></td>
                    <td><?php echo $arr['first_name']." ".$arr['last_name']; ?></td>
                    <td><?php echo $arr['username']; ?></td>
                    <td><?php 
                      if($arr['role']==1) 
                        {echo "Admin";}
                        else 
                          {echo "Normal";} 
                    ?></td>
                    <td class='edit'><a href='update-user.php?id=<?php echo sha1($arr['user_id']); ?>'><i class='fa fa-edit'></i></a></td>
                    <td class='delete'>
                      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <input type="hidden" name="id_del" value="<?php echo sha1($arr['user_id']); ?>">
                        <button class="btn-d" type="submit" name="delete_btn" value="delete"><i class='fa fa-trash'></i></button>
                      </form>
                    </td>
                    <!-- <a href='delete-user.php?id=<?php //echo bin2hex($arr['user_id']); ?>'><i class='fa fa-trash'></i></a> -->
                </tr>
                <!-- <tr>
                    <td class='id'>2</td>
                    <td>Shyam Kumar</td>
                    <td>shyam</td>
                    <td>normal</td>
                    <td class='edit'><a href='update-user.php'><i class='fa fa-edit'></i></a></td>
                    <td class='delete'><a href='delete-user.php'><i class='fa fa-trash'></i></a></td>
                </tr>
                <tr>
                    <td class='id'>3</td>
                    <td>Ramesh Kumar</td>
                    <td>ramesh</td>
                    <td>admin</td>
                    <td class='edit'><a href='update-user.php'><i class='fa fa-edit'></i></a></td>
                    <td class='delete'><a href='delete-user.php'><i class='fa fa-trash'></i></a></td>
                </tr>
                <tr>
                    <td class='id'>4</td>
                    <td>Satish Sharma</td>
                    <td>satish</td>
                    <td>admin</td>
                    <td class='edit'><a href='update-user.php'><i class='fa fa-edit'></i></a></td>
                    <td class='delete'><a href='delete-user.php'><i class='fa fa-trash'></i></a></td>
                </tr> -->
                <?php 
                    }
                  }
                  else {
                  ?>
                    <tr>
                    <td class='id' colspan='6'>No Record Found</td>
                    </tr>  
                <?php } ?>
            </tbody>
            </table>

            <?php 
              include "config.php";
              $sql2 = "SELECT * FROM user_t";
              $result2 = mysqli_query($conn,$sql2) or die("<h1>Query Failed</h1>");
              mysqli_close($conn);
              $total_records=mysqli_num_rows($result2);
              if($total_records>0) {
                
                $total_page = ceil($total_records/$limit);

                if($total_page>1) {

                  echo "<div class='pagination admin-pagination'>";

                  if($page>1){
                    $p = $page-1;
                    echo "<a href='users.php?page=$p'>Prev</a>";
                  }
                  else {
                    echo "<a disibled class='disabled_a'>Prev</a>";
                  }

                  for($j=1;$j<=$total_page;$j++) {
                    if($j==$page) {
                      echo "<a class='active' href='users.php?page=$j'>$j</a>";
                    }
                    else {
                      echo "<a href='users.php?page=$j'>$j</a>";
                    }
                  }
                  
                  if($total_page>$page){
                    $p = $page+1;
                    echo "<a href='users.php?page=$p'>Next</a>";
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