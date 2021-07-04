<?php
  $pa = basename($_SERVER['PHP_SELF']);
  switch($pa) {
    case "single.php"://single
      // $page_name = "Single Page";
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
          $sql_title = "SELECT title FROM post WHERE post_id='$pid'";
          $result_title = mysqli_query($conn,$sql_title) or die("<h1>Title Query Failed</h1>".mysqli_error($conn));
          mysqli_close($conn);
          $arr_title = mysqli_fetch_assoc($result_title);
          $page_title = $arr_title['title'];
        }
        else {
          mysqli_close($conn);
          $page_title = "No Post Found";
        }
      }
      else {
        $page_title = "No Post Found";
      }
      break;
    case "category.php"://category
      // $page_name = "Category Page";
      if(isset($_GET['cid'])) {

        $cid = $_GET['cid'];

        include "config.php";
        $sql = "SELECT category_id FROM category";
        $result = mysqli_query($conn,$sql) or die("<h1>Query Failed</h1>");
        $check = 0;
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
          $sql_title = "SELECT category_name FROM category WHERE category_id ='$cid'";
          $result_title = mysqli_query($conn,$sql_title) or die("<h1>Title Query Failed</h1>".mysqli_error($conn));
          mysqli_close($conn);
          $arr_title = mysqli_fetch_assoc($result_title);
          $page_title = $arr_title['category_name']." News";
        }
        else {
          mysqli_close($conn);
          $page_title = "No Category Record Found";
        }
      }
      else {
        $page_title = "No Category Record Found";
      }
      break;
    case "search.php"://search
      if(isset($_GET['search']) && $_GET['search']!="") {
        $search = $_GET['search'];

        include "config.php";
        $sql_title = "SELECT title FROM post WHERE title LIKE '%$search%' or description LIKE '%$search%'";
        $result_title = mysqli_query($conn,$sql_title) or die("<h1>Query Failed</h1>");
        mysqli_close($conn);
        if(mysqli_num_rows($result_title) > 0 ) {
          $page_title = $search." News";
        }
        else {
          $page_title = "No Search Record Found";
        }
      }
      else {
        $page_title = "No Search Record Found";
      }
      break;
    case "author.php"://author
      // $page_name = "Author Page";
      if(isset($_GET['aid'])) {

        $aid = $_GET['aid'];

        include "config.php";
        $sql = "SELECT user_id FROM user_t";
        $result = mysqli_query($conn,$sql) or die("<h1>Query Failed</h1>");
        $check = 0;
        if(mysqli_num_rows($result)>0) {
          while($arr = mysqli_fetch_assoc($result)) {
            if($aid == sha1($arr['user_id'])) {
              $aid = $arr['user_id'];
              $check = 1;
              break;
            }
          }
        }
        if($check) {
          $sql_title = "SELECT first_name,last_name FROM user_t WHERE user_id ='$aid'";
          $result_title = mysqli_query($conn,$sql_title) or die("<h1>Title Query Failed</h1>".mysqli_error($conn));
          mysqli_close($conn);
          $arr_title = mysqli_fetch_assoc($result_title);
          $page_title = "News By ".$arr_title['first_name']." ".$arr_title['last_name'];
        }
        else {
          mysqli_close($conn);
          $page_title = "No Author Record Found";
        }
      }
      else {
        $page_title = "No Author Record Found";
      }
      break;
    default://default
    // $page_name = "News Site";
    $page_title = "News Site";
  }
  // echo $page_name;
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <!-- style.css -->
    <link rel="stylesheet" href="css/style.css" type="text/css">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title><?php echo $page_title; ?></title>
  </head>
  <body>
    <!-- HEADER start-->
    <div id="header" class="bg-primary py-2">
        <?php
          // logo
          include "config.php";
          $sql_logo = "SELECT website_name,logo FROM setting";
          $result_logo = mysqli_query($conn,$sql_logo) or die("<h1>Query Failed</h1>");
          mysqli_close($conn);
          if(mysqli_num_rows($result_logo)>0) {
            while($arr_logo = mysqli_fetch_assoc($result_logo)) {
              if($arr_logo['logo'] == "") {
                ?>
                  <a href="index.php" id="logo"><h2><?php echo $arr_logo['website_name']; ?></h2></a>
                <?php
              }
              else {
        ?>
        <a href="index.php" id="logo"><img src="admin/image/<?php echo $arr_logo['logo']; ?>" alt=""></a>
        <?php 
              }
            }
          }
        ?>
    </div>
    <!-- HEADER end-->


    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0" id="nav_bar">
      <div class="container-fluid">
        <!-- <a class="navbar-brand" href="#">Navbar</a> -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav text-center" id="nav-ul-tag">

            <li class="nav-item">
              <a class="nav-link py-2 px-3" href="index.php">HOME</a>
            </li>

            <?php
              if(isset($_GET['cid'])) {
                $cid = $_GET['cid'];
              }

              include "config.php";
              $sql = "SELECT category_id,category_name FROM category WHERE post>0";
              $result = mysqli_query($conn,$sql) or die("<h1>Query Failed</h1>");
              mysqli_close($conn);
              if(mysqli_num_rows($result)>0) {
                while($arr = mysqli_fetch_assoc($result)) {
                  $hcid = sha1($arr['category_id']);
                  echo "<li class='nav-item'>";
                  $active = "";
                  if(isset($_GET['cid'])) {
                    if($cid == $hcid) {
                      $active="bg-primary text-dark";
                    }
                    else {
                      $active="";
                    }
                  }
                  echo "<a class='nav-link py-2 px-3 $active' href='category.php?cid=$hcid'>{$arr['category_name']}</a>";
                  echo "</li>";
            ?>
              
                
              
            <?php
                }
              }
            ?>
            <!-- <li class="nav-item">
              <a class="nav-link py-2 px-3" href="category.php">Entertainment</a>
            </li> -->
            
          </ul>
        </div>
      </div>
    </nav>