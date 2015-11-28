<?php require('includes/config.php');
$row = '';
if(isset($_GET['id'])) {
    $id = rtrim($_GET['id'], '.html');
    $stmt = $db->prepare('SELECT catID,catTitle FROM blog_cats WHERE catSlug = :catSlug');
    $stmt->execute(array(':catSlug' => $id));
    $row = $stmt->fetch();

    //if post does not exists redirect user.
    if ($row['catID'] == '') {
        $row['catTitle'] = 'Categories';
    }
} else {
    $row['catTitle'] = 'Categories';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME . ' - ' . $row['catTitle'];?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="<?php echo DESCRIPTION ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="alternate" href="rss.php" title="My RSS feed" type="application/rss+xml" />
</head>
<body>

<div id="wrapper">
    <h1><?php echo SITENAME; ?></h1>
    <div id="nav">
        <?php require_once('nav.php'); ?>
    </div>
    <?php
    if(isset($_GET['id'])) {
    ?>
    <p>Posts in <?php echo $row['catTitle']; ?></p>
    <hr/>

    <div id='main'>

        <?php
        try {
            $pages = new Paginator('5', 'p');
            $stmt = $db->prepare('SELECT blog_posts_seo.postID FROM blog_posts_seo, blog_post_cats WHERE blog_posts_seo.postID = blog_post_cats.postID AND blog_post_cats.catID = :catID');
            $stmt->execute(array(':catID' => $row['catID']));
            //pass number of records to
            $pages->set_total($stmt->rowCount());
            $stmt = $db->prepare('
					SELECT 
						blog_posts_seo.postID, blog_posts_seo.postTitle, blog_posts_seo.postDesc, blog_posts_seo.postDate, blog_members.username,
						blog_posts_seo.postSlug
					FROM 
						blog_posts_seo,
						blog_post_cats,
						blog_members
					WHERE
						 blog_posts_seo.postID = blog_post_cats.postID
						 AND blog_members.memberID = blog_posts_seo.poster
						 AND blog_post_cats.catID = :catID
						 AND blog_posts_seo.postDate <= NOW()
						 AND blog_posts_seo.published = 1
					ORDER BY 
						postID DESC
					' . $pages->get_limit());
            $stmt->execute(array(':catID' => $row['catID']));
            while ($row = $stmt->fetch()) {

                echo '<div>';
                echo '<h1><a href="' .$row['postSlug'] . '">' . $row['postTitle'] . '</a></h1>';
                echo '<p>Posted on ' . date('jS M Y H:i:s', strtotime($row['postDate'])) . ' by <b>' . $row['username'] . '</b> in ';
                $stmt2 = $db->prepare('SELECT catTitle, catSlug	FROM blog_cats, blog_post_cats WHERE blog_cats.catID = blog_post_cats.catID AND blog_post_cats.postID = :postID');
                $stmt2->execute(array(':postID' => $row['postID']));
                $catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                $links = array();
                foreach ($catRow as $cat) {
                    $links[] = "<a href='c-" . $cat['catSlug'] . "'>" . $cat['catTitle'] . "</a>";
                }
                echo implode(", ", $links);
                echo '</p>';
                echo '<p>' . $row['postDesc'] . '</p>';
                echo '<p><a href="' . $row['postSlug'] . '">Read More</a></p>';
                echo '</div>';
            }
            echo $pages->page_links('c-' . $_GET['id'] . '&');
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } else {
?>
        <p>Categories</p>
        <hr/>
        <div id='main'>
            <ul>
<?php
    $stmt = $db->query('SELECT catTitle, catSlug from blog_cats');

    while($row = $stmt->fetch())
    {
        echo '<li><a href="c-'.$row['catSlug'].'.html">'.$row['catTitle'].'</a></li>';
    }
    echo('</ul>');
}
?>

    </div>
    <div id='clear'></div>

</div>
<?php require_once('footer.html'); ?>