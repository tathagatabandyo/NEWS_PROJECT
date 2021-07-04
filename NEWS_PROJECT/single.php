    <?php include_once "header.php"; ?>


    <div id="main-container">
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <!-- post-container -->
            <div class="post-container">

              <?php
              if(isset($_GET['id'])) {

                $pid = $_GET['id'];

                include "config.php";
                $sql = "SELECT post_id FROM post";
                $result = mysqli_query($conn,$sql) or die("<h1>Query Failed</h1>");
                $check = 0;
                if(mysqli_num_rows($result)>0) {
                  while($arr = mysqli_fetch_assoc($result)) {
                    if($pid == sha1($arr['post_id'])) {
                      $pid = $arr['post_id'];
                      $check = 1;
                      break;
                    }
                  }
                }
                if($check) {
                  $sql1 = "SELECT p.post_id,p.title,p.description,p.category,p.post_date,p.post_img,p.author,c.category_name,u.first_name,u.last_name FROM post AS p INNER JOIN category AS c ON p.category=c.category_id INNER JOIN user_t AS u ON p.author=u.user_id WHERE post_id='$pid'";
                  $result1 = mysqli_query($conn,$sql1) or die("<h1>Query Failed</h1>");
                  mysqli_close($conn);
                  if(mysqli_num_rows($result1)>0) {
                    while($arr1 = mysqli_fetch_assoc($result1)) {
              ?>
              <!-- post -->
              <div class="post-content single-post">
                <h3><?php echo $arr1['title']; ?></h3>
                <div class="post-information">
                  <span>
                  <i class="fa fa-tags" aria-hidden="true"></i>
                  <a href="category.php?cid=<?php echo sha1($arr1['category']); ?>"><?php echo $arr1['category_name']; ?></a>
                  </span>
                  <span>
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <a href="author.php?aid=<?php echo sha1($arr1['author']); ?>"><?php echo $arr1['first_name']." ".$arr1['last_name']; ?></a>
                  </span>
                  <span>
                  <i class="fa fa-calendar" aria-hidden="true"></i>
                    <?php echo $arr1['post_date']; ?>
                  </span>
                </div>
                <img class="single-feature-image" src="admin/upload/<?php echo $arr1['post_img']; ?>" alt=""><!-- image -->
                <p class="description"><?php echo $arr1['description']; ?></p>
              </div>
              <!-- post -->
              <?php
                    }
                  }
                  else {
                ?>
                  <div class='post-content single-post'>
                    <h3>No Record Found.<span>id value is change. Go To <a href='index.php'>Home</a></span></h3>
                  </div>
                <?php
                  }
                }
                else {
                  mysqli_close($conn);
              ?>
              <div class="post-content single-post">
                <h3>No Record Found.<span>id value is change. Go To <a href="index.php">Home</a></span></h3>
              </div>
              <?php
                }
              }
              else {
              ?>
              <div class="post-content single-post">
                <h3>No Record Found.<span>Go To <a href="index.php">Home</a></span></h3>
              </div>
              <?php
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