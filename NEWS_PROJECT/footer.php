<footer class="bg-primary text-white text-center p-3">
    <?php
        // logo
        include "config.php";
        $sql_logo = "SELECT footer_dec FROM setting";
        $result_logo = mysqli_query($conn,$sql_logo) or die("<h1>Query Failed</h1>");
        mysqli_close($conn);
        if(mysqli_num_rows($result_logo)>0) {
        while($arr_logo = mysqli_fetch_assoc($result_logo)) {
    ?>
    <span><?php echo $arr_logo['footer_dec']; ?></span>
    <?php
        }
    }
    ?>
</footer>