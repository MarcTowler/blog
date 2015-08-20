<a href="https://www.sitepoint.com">
    <img src="img/ambassador.png" />
</a>
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

<h2>Useful Links</h2>

<ul>
    <?php
    if(isset($_SESSION['uid']) && $_SESSION['uid'] > 0)
    {
?>
    <li>
        <a href="/admin">
            Admin Section
        </a>
    </li>
    <li>
        <a href="/admin/logout.php">
            Logout
        </a>
    </li>
<?php
    } else {
?>
    <li>
        <a href="https://marctowler.co.uk/rss.php" title="Subscribe to the RSS feed">
            <img src="img/rss.png" height="50" width="50" />
        </a>
    </li>
    <li>
        <a href="/admin">
            Login
        </a>
    </li>
<?php
    }
    ?>
</ul>