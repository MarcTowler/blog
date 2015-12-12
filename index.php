<?php
require_once('includes/config.php');
require('classes/Parsedown.php');

$parsedown = new Parsedown();
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>
            <?php echo SITENAME; ?>
        </title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="description" content="<?php echo DESCRIPTION ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="css/normalize.css" />
        <link rel="stylesheet" href="css/main.css">
        <link rel="alternate" href="rss.php" title="My RSS feed" type="application/rss+xml" />
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <div id="wrapper">
            <h1>
                <?php echo SITENAME; ?>
            </h1>

            <div id="nav">
                <?php require_once('nav.php'); ?>
            </div>

            <div id="main">
                <?php
                try {

                    //instantiate the class
                    $pages = new Paginator('5','p');

                    //collect all records fro the next function
                    $stmt = $db->query('SELECT postID FROM blog_posts_seo where published = 1');

                    //determine the total number of records
                    $pages->set_total($stmt->rowCount());

                    $stmt = $db->query('SELECT p.postID, m.name, p.postTitle,
                        p.postSlug, p.postDesc, p.postDate, p.views FROM blog_posts_seo p,
                        blog_members m WHERE p.poster = m.memberID AND postDate
                        <= NOW() AND published = 1 ORDER BY postDate DESC ' .
                        $pages->get_limit());

                    while($row = $stmt->fetch()){

                        echo '<div>';
                        echo '<h1><a href="'.$row['postSlug'].'.html">'.$row['postTitle'].'</a></h1>';
                        if(isset($_SESSION['uid']) && $_SESSION['uid'] > 0) { echo '[<a href="/admin/edit-post.php?id='.$row["postID"].'">EDIT</a>]';}
                        echo '<p>Posted on '.date('jS M Y H:i:s', strtotime($row['postDate'])).' by <b>'.$row['name']. '</b> in ';

                        $stmt2 = $db->prepare('SELECT catTitle, catSlug	FROM blog_cats, blog_post_cats WHERE blog_cats.catID = blog_post_cats.catID AND blog_post_cats.postID = :postID');
                        $stmt2->execute(array(':postID' => $row['postID']));

                        $catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                        $links = array();
                        foreach ($catRow as $cat){
                            $links[] = "<a href='c-".$cat['catSlug']."'>".$cat['catTitle']."</a>";
                        }
                        echo implode(", ", $links);

                        echo '&nbsp;<img src="img/view.jpg" width="20" height="20" /> ' . $row["views"] . '</p>';
                        echo '<p>'.$parsedown->text($row['postDesc']).'</p>';
                        echo '<p><a href="'.$row['postSlug'].'">Read More</a></p>';
                        echo '</div>';

                    }

                    echo $pages->page_links();

                } catch(PDOException $e) {
                    echo $e->getMessage();
                }
                ?>

            </div>
        </div>