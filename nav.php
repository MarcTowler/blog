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
                Admin Section (0)
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