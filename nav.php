<?php
$com = $db->query('SELECT count(cid) FROM blog_comments WHERE published = 0');
$count = $com->fetchColumn(0);
?>
<ul>
    <li><a href="index.php">Home</a> |</li>
    <li><a href="archives.php">Archives</a> |</li>
    <li><a href="catpost.php">Categories</a> |</li>
    <li><a href="https://www.marctowler.co.uk/rss.php">RSS Feed</a> |</li>
    <?php
    if(isset($_SESSION['uid']) && $_SESSION['uid'] > 0)
    {
        ?>
        <li>
            <a href="/admin">
                Admin Section (<?php echo($count); ?>)
            </a> |
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
            <a href="/admin">
                Login
            </a>
        </li>
        <?php
    }
    ?>
</ul>