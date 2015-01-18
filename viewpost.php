<?php require('includes/config.php');
$stmt = $db->prepare('SELECT postID, postTitle, postCont, postDate FROM blog_posts_seo WHERE postSlug = :postSlug');
$stmt->execute(array(':postSlug' => $_GET['id']));
$row = $stmt->fetch();
//if post does not exists redirect user.
if($row['postID'] == ''){
    header('Location: ./');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>
            Code Something :: Marc Towler's Blog
        </title>
        <meta name="description" content="Website Development and Design blog written by Marc Towler, includes game
                    reviews and book reviews" />
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="css/normalize.css" />
        <link rel="stylesheet" href="css/main.css">
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>

        <div id="wrapper">
            <h1>
                Code Something
            </h1>
            <hr />

            <p>
                <a href="./">
                    Blog Index
                </a>
            </p>
    <?php
    echo '<div>';
    echo '<h1>'.$row['postTitle'].'</h1>';
    echo '<p>Posted on '.date('jS M Y', strtotime($row['postDate'])).'</p>';
    echo '<p>'.$row['postCont'].'</p>';
    echo '</div>';
    ?>

        </div>
        <div id='sidebar'>
            <?php require_once('sidebar.php'); ?>
        </div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
    (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
        function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
        e=o.createElement(i);r=o.getElementsByTagName(i)[0];
        e.src='//www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
    ga('create','UA-XXXXX-X');ga('send','pageview');
</script>
    </body>
</html>