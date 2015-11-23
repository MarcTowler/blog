<?php
$com = $db->query('SELECT count(id) FROM blog_comments WHERE published = 0');
$count = $com->fetchColumn(0);
?>
<h1><?php echo SITENAME; ?></h1>
<ul id='adminmenu'>
    <li><a href="index.php">Admin Index</a></li>
    <li><a href="../" target="_blank">View Blog</a></li>
	<li><a href='list-post.php'>Blog Posts</a></li>
	<li><a href='categories.php'>Categories</a>
	<li><a href='file-manager.php'>Media Manager</a></li>
	<li><a href='users.php'>Users</a></li>
    <li><a href='comments.php'>Moderate Comments (<?php echo($count); ?>)</a></li>
	<li><a href='logout.php'>Logout</a></li>
</ul>
<div class='clear'></div>
<hr />

