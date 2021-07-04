    <?php include_once "header.php"; ?>


    <div id="main-container">
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <!-- post-container -->
            <div class="post-container">

              <!-- Category Name start-->
              <?php
                $check = 0;
                if(isset($_GET['cid'])) {
                  $cid = $_GET['cid'];
  
                  include "config.php";
                  $sql = "SELECT category_id FROM category";
                  $result = mysqli_query($conn,$sql) or die("<h1>Query Failed</h1>");
                  if(mysqli_num_rows($result)>0) {
                    while($arr = mysqli_fetch_assoc($result)) {
                      if($cid == sha1($arr['category_id'])) {
                        $cid = $arr['category_id'];
                        $check = 1;
                        break;
                      }
                    }
                  }
                  if($check) {
                    $sq = "SELECT category_name FROM category WHERE category_id='$cid'";
                    $res = mysqli_query($conn,$sq) or die("<h1>Query Failed</h1>");
                    $ar = mysqli_fetch_assoc($res);
                    echo "<h2 class='page-heading'>{$ar['category_name']}</h2>";
                  }
                }
              ?>
              <!-- Category Name end -->
              
              <?php
                if(isset($_GET['cid'])) {

                  if($check) {
                    // include "config.php";
                    $limit = 2;
                    $sql2 = "SELECT * FROM post WHERE category='$cid'";

                    $result2 = mysqli_query($conn,$sql2) or die("<h1>Query Failed</h1>");
                    $t_p = ceil((mysqli_num_rows($result2))/$limit);

                    if(isset($_GET['page'])) {
                      $page = $_GET['page'];
                      if($page=="0" || $page=="" || $t_p<$page) {
                        mysqli_close($conn);
                        $scid = sha1($cid);
                        header("Location: $hostname/category.php?cid=$scid");
                      }
                    }
                    else {
                      $page=1;
                    }

                    $offset = ($page-1)*$limit;

                    $sql3 = "SELECT p.post_id,p.title,p.description,p.category,p.post_date,p.post_img,p.author,c.category_name,u.first_name,u.last_name FROM post AS p INNER JOIN category AS c ON p.category=c.category_id INNER JOIN user_t AS u ON p.author=u.user_id WHERE p.category='$cid' ORDER BY p.numbering DESC LIMIT $offset,$limit";
                    $result3 = mysqli_query($conn,$sql3) or die("<h1>Query Failed</h1>");
                    mysqli_close($conn);
                    if(mysqli_num_rows($result3)>0) {
                      while($arr = mysqli_fetch_assoc($result3)) {  
              ?>
              <!-- post -->
              <div class="post-content">
                <div class="row">
                  <div class="col-md-4">
                    <a class="post-img" href="single.php?id=<?php echo sha1($arr['post_id']); ?>">
                      <img src="admin/upload/<?php echo $arr['post_img']; ?>" alt=""><!-- image -->
                    </a>
                  </div>
                  <div class="col-md-8">
                    <div class="inner-content clearfix">
                      <h3><a href="single.php?id=<?php echo sha1($arr['post_id']); ?>"><?php echo $arr['title']; ?></a></h3>
                      <div class="post-information">
                        <span>
                          <i class="fa fa-tags" aria-hidden="true"></i>
                          <a href="category.php?cid=<?php echo sha1($arr['category']); ?>"><?php echo $arr['category_name']; ?></a>
                        </span>
                        <span>
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <a href="author.php?aid=<?php echo sha1($arr['author']); ?>"><?php echo $arr['first_name']." ".$arr['last_name']; ?></a>
                        </span>
                        <span>
                          <i class="fa fa-calendar" aria-hidden="true"></i>
                            <?php echo $arr['post_date']; ?>
                          </span>
                      </div>
                      <p class="description"><?php echo substr($arr['description'],0,140)."..."; ?></p>
                      <a class="read-more pull-right" href="single.php?id=<?php echo sha1($arr['post_id']); ?>">read more</a>
                    </div>
                  </div>
                </div>
              </div>
              <!-- post -->

              <?php
                      }
                    }
                    else {
                      echo "
                        <div class='post-content single-post'>
                          <h3>No Category Record Found.</h3>
                        </div>";
                    }
                  }
                  else {
                    mysqli_close($conn);
                    echo "
                    <div class='post-content single-post'>
                      <h3>No Category Record Found.<span>Click the spacific Category in Category Navbar(Don't change Category id)</span></h3>
                    </div>";
                  }
                }
                else {
                  echo "
                  <div class='post-content single-post'>
                    <h3>No Category Record Found.<span>Click the spacific Category in Category Navbar</span></h3>
                  </div>";
                }
              ?>

              <?php
                if(isset($_GET['cid'])) {
                  if($check) {
                    include "config.php";
                    $sql4 = "SELECT * FROM post WHERE category='$cid'";
                    $result4 = mysqli_query($conn,$sql4);
                    mysqli_close($conn);
                    
                    $total_no_of_record = mysqli_num_rows($result4);
                    if($total_no_of_record > 0) {
                      $total_page = ceil($total_no_of_record/$limit);
                      if($total_page > 1) {

                        echo "<div class='pagination'>";
                        $scid = sha1($cid);
                        if($page>1) {
                          $p=$page-1;
                          echo "<a href='category.php?cid=$scid&page=$p'>Prev</a>";
                        }
                        else {
                          echo "<a disibled class='disabled_a'>Prev</a>";
                        }

                        for($j=1;$j<=$total_page;$j++) {
                          
                          if($page==$j) {
                            echo "<a class='active' href='category.php?cid=$scid&page=$j'>$j</a>";
                          }
                          else {
                            echo "<a href='category.php?cid=$scid&page=$j'>$j</a>";
                          }
                        }

                        if($total_page>$page) {
                          $p=$page+1;
                          echo "<a href='category.php?cid=$scid&page=$p'>Next</a>";
                        }
                        else {
                          echo "<a disibled class='disabled_a'>Next</a>";
                        }

                        echo "</div>";

                      }
                    }
                  }
                }
              ?>
            </div>
          </div>

          <!-- sidebar -->
          <?php include_once "sidebar.php"; ?>

          <!-- post-container -->
        </div>

      </div>
    </div>

    <?php include_once "footer.php"; ?>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
  </body>
</html>