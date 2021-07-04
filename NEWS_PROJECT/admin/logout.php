<?php 
    include "config.php";
    mysqli_close($conn);
    
    session_start();
    session_unset();
    session_destroy();

    header("Location:$hostname/admin/");

?>