<div id="sidebar" class="col-md-4">
    <!-- post-container -->

    <!-- search box -->
    <div class="search-box-container">
        <h4>Search</h4>
        <form class="search-post" action="search.php" method ="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search ....." required>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-danger">Search</button>
                </span>
            </div>
        </form>
    </div>
    <!-- /search box -->

    <!-- recent posts box -->
    <div class="recent-post-container">
        <h4>Recent Posts</h4>

        <?php
            include "config.php";
            $limit = 5;
            $sql_s = "SELECT p.post_id,p.title,p.category,p.post_date,p.post_img,c.category_name FROM post AS p INNER JOIN category AS c ON p.category=c.category_id INNER JOIN user_t AS u ON p.author=u.user_id ORDER BY p.numbering DESC LIMIT $limit";
            $result_s = mysqli_query($conn,$sql_s) or die("<h1>Query Failed</h1>");
            mysqli_close($conn);
            if(mysqli_num_rows($result_s)>0) {
                while($arr_s = mysqli_fetch_assoc($result_s)) {
        ?>
        <!-- posts box -->
        <div class="recent-post">
            <a class="post-img" href="single.php?id=<?php echo sha1($arr_s['post_id']); ?>">
                <img src="admin/upload/<?php echo $arr_s['post_img']; ?>" alt=""/><!-- image -->
            </a>
            <div class="post-content">
                <h5><a href="single.php?id=<?php echo sha1($arr_s['post_id']); ?>"><?php echo $arr_s['title']; ?></a></h5>
                <span>
                    <i class="fa fa-tags" aria-hidden="true"></i>
                    <a href='category.php?cid=<?php echo sha1($arr_s['category']); ?>'><?php echo $arr_s['category_name']; ?></a>
                </span>
                <span>
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                    <?php echo $arr_s['post_date']; ?>
                </span>
                <a class="read-more" href="single.php?id=<?php echo sha1($arr_s['post_id']); ?>">read more</a>
            </div>
        </div>
        <!-- posts box -->
        <?php
                }
            }
        ?>
        
    </div>
    <!-- recent posts box -->

    


    <!-- post-container -->
    
</div>