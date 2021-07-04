<?php
  session_start();
  if(!isset($_SESSION['username'])) {
    include "config.php";
    mysqli_close($conn);
    header("Location:$hostname/admin/");
  }
?>

<!-- HEADER start-->
<div id="header" class="bg-primary py-4">
    <a href="post.php" id="logo"><img src="image/news.jpg" alt=""></a>

    <?php 
      //user name
      include "config.php";
      $uid = $_SESSION['user_id'];
      $sql = "SELECT first_name,last_name FROM user_t WHERE user_id='$uid'";
      $result = mysqli_query($conn,$sql);
      if(mysqli_num_rows($result)>0) {
        while($arr = mysqli_fetch_assoc($result)) {
          $name = $arr['first_name']." ".$arr['last_name'];
          echo "<span class='fs-6 fw-bold text-white user-select-none'>Hello $name</span>";
        }
      }
    ?>
    <a class="btn btn-danger" href="logout.php" class="admin-logout">logout</a>
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
          <a class="nav-link py-2 px-3 m" aria-current="page" href="post.php">Post</a>
        </li>
        <?php 
          if($_SESSION['role']==1) {
        ?>
        <li class="nav-item">
          <a class="nav-link py-2 px-3" href="category.php">Category</a>
        </li>
        <li class="nav-item">
          <a class="nav-link py-2 px-3" href="users.php">Users</a>
        </li>
        <li class="nav-item">
          <a class="nav-link py-2 px-3" href="settings.php">SETTING</a>
        </li>
        <?php 
          } 
        ?>
      </ul>
    </div>
  </div>
</nav>