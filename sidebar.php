<h2>Recent Posts</h2>


<ul>
    <?php
    $stmt = $db->query('SELECT postTitle, postSlug FROM blog_posts_seo WHERE published = 1 ORDER BY postID DESC LIMIT 5');
    while($row = $stmt->fetch()){
        echo '<li><a href="'.$row['postSlug'].'">'.$row['postTitle'].'</a></li>';
    }
    ?>
</ul>

<h2>Catgories</h2>


<ul>
    <?php
    $stmt = $db->query('SELECT catTitle, catSlug FROM blog_cats ORDER BY catID DESC');
    while($row = $stmt->fetch()){
        echo '<li><a href="c-'.$row['catSlug'].'">'.$row['catTitle'].'</a></li>';
    }
    ?>
</ul>

<h2>Archives</h2>


<ul>
    <?php
    $stmt = $db->query("SELECT Month(postDate) as Month, Year(postDate) as Year FROM blog_posts_seo GROUP BY Month(postDate), Year(postDate) ORDER BY postDate DESC");
    while($row = $stmt->fetch()){
        $monthName = date("F", mktime(0, 0, 0, $row['Month'], 10));
        $slug = 'a-'.$row['Month'].'-'.$row['Year'];
        echo "<li><a href='$slug'>$monthName</a></li>";
    }
    ?>
</ul>