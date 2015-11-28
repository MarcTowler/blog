<?php
require_once('includes/config.php');
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


    <h1><?php echo SITENAME; ?></h1>
    <div id="nav">
        <ul>
            <?php require_once('nav.php'); ?>
    </div>

    <div id='main'>

        <?php
        if(isset($_GET['month']) || isset($_GET['year'])) {
            try {
                //collect month and year data
                $month = $_GET['month'];
                $year = rtrim($_GET['year'], '.html');

                //set from and to dates
                $from = date('Y-m-01 00:00:00', strtotime("$year-$month"));
                $to = date('Y-m-31 23:59:59', strtotime("$year-$month"));
                $pages = new Paginator('5', 'p');
                $stmt = $db->prepare('SELECT postID FROM blog_posts_seo WHERE postDate >= :from AND postDate <= :to AND published = 1');
                $stmt->execute(array(
                    ':from' => $from,
                    ':to' => $to
                ));
                //pass number of records to
                $pages->set_total($stmt->rowCount());
                $stmt = $db->prepare('SELECT p.postID, m.username, p.postTitle,
                        p.postDesc, p.postDate, p.postSlug FROM blog_posts_seo p,
                        blog_members m WHERE p.poster = m.memberID AND postDate
                        >= :from AND postDate <= :to  AND published = 1 ORDER BY postID DESC ' .
                    $pages->get_limit());
                $stmt->execute(array(
                    ':from' => $from,
                    ':to' => $to
                ));
                while ($row = $stmt->fetch()) {
                    echo '<h1><a href="viewpost.php?id=' . $row['postID'] . '">' . $row['postTitle'] . '</a></h1>';
                    echo '<p>Posted on ' . date('jS M Y H:i:s', strtotime($row['postDate'])) . ' by <b>' . $row['username'] . '</b> in ';
                    $stmt2 = $db->prepare('SELECT catTitle, catSlug	FROM blog_cats, blog_post_cats WHERE blog_cats.catID = blog_post_cats.catID AND blog_post_cats.postID = :postID');
                    $stmt2->execute(array(':postID' => $row['postID']));
                    $catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                    $links = array();
                    foreach ($catRow as $cat) {
                        $links[] = "<a href='c-" . $cat['catSlug'] . ".html'>" . $cat['catTitle'] . "</a>";
                    }
                    echo implode(", ", $links);
                    echo '</p>';
                    echo '<p>' . $row['postDesc'] . '</p>';
                    echo '<p><a href="' . $row["postSlug"] . '.html">Read More</a></p>';
                }
                echo $pages->page_links("a-$month-$year&");
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        } else {
            echo('<p>Please choose a Month to view</p><ul>');

            $stmt = $db->query("SELECT count(postDate) as Count, Month(postDate) as Month, Year(postDate) as Year FROM blog_posts_seo GROUP BY Month(postDate), Year(postDate) ORDER BY postDate DESC");
            while($row = $stmt->fetch()){
                $monthName = date("F", mktime(0, 0, 0, $row['Month'], 10));
                $slug = 'a-'.$row['Month'].'-'.$row['Year'];
                echo '<li><a href="' . $slug . '.html">' . $monthName . ' '  . $row['Year'] . ' (' . $row["Count"] . ' posts)</a></li>';
            }
            echo('</ul>');
        }
        ?>

    </div>
    <div id='clear'></div>

</div>
<?php require_once('footer.html'); ?>