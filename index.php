<?php
require_once('includes/config.php');
WhereFrom('https://marctowler.co.uk/index.php', 'Main Index');
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
        <meta name="description" content="Website Development and Design blog written by Marc Towler, includes game
                    reviews and book reviews" />
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
                        echo '<p>'.$row['postDesc'].'</p>';
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
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <!-- Piwik -->
        <script type="text/javascript">
            var _paq = _paq || [];
            _paq.push(["setDocumentTitle", document.domain + "/" + document.title]);
            _paq.push(["setCookieDomain", "*.marctowler.co.uk"]);
            _paq.push(['trackPageView']);
            _paq.push(['enableLinkTracking']);
            (function() {
                var u=(("https:" == document.location.protocol) ? "https" : "http") + "://marctowler.co.uk/piwik/";
                _paq.push(['setTrackerUrl', u+'piwik.php']);
                _paq.push(['setSiteId', 1]);
                var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
                g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
            })();
        </script>
        <noscript><p><img src="https://www.marctowler.co.uk/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
        <!-- End Piwik Code -->

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-37729517-1', 'auto');
            ga('send', 'pageview');

        </script>
    </body>
</html>