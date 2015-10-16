<?php require('includes/config.php');
$stmt = $db->prepare('SELECT catID,catTitle FROM blog_cats WHERE catSlug = :catSlug');
$stmt->execute(array(':catSlug' => $_GET['id']));
$row = $stmt->fetch();
//if post does not exists redirect user.
if($row['catID'] == ''){
    header('Location: ./');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME . ' - ' . $row['catTitle'];?></title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
    <link rel="alternate" href="rss.php" title="My RSS feed" type="application/rss+xml" />
</head>
<body>

<div id="wrapper">

    <h1>Blog</h1>
    <p>Posts in <?php echo $row['catTitle'];?></p>
    <hr />
    <p><a href="./">Blog Index</a></p>

    <div id='main'>

        <?php
        try {
            $pages = new Paginator('5','p');
            $stmt = $db->prepare('SELECT blog_posts_seo.postID FROM blog_posts_seo, blog_post_cats WHERE blog_posts_seo.postID = blog_post_cats.postID AND blog_post_cats.catID = :catID');
            $stmt->execute(array(':catID' => $row['catID']));
            //pass number of records to
            $pages->set_total($stmt->rowCount());
            $stmt = $db->prepare('
					SELECT 
						blog_posts_seo.postID, blog_posts_seo.postTitle, blog_posts_seo.postDesc, blog_posts_seo.postDate, blog_members.username
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
					'.$pages->get_limit());
            $stmt->execute(array(':catID' => $row['catID']));
            while($row = $stmt->fetch()){

                echo '<div>';
                echo '<h1><a href="viewpost.php?id='.$row['postID'].'">'.$row['postTitle'].'</a></h1>';
                echo '<p>Posted on '.date('jS M Y H:i:s', strtotime($row['postDate'])). ' by <b>' . $row['username'] . '</b> in ';
                $stmt2 = $db->prepare('SELECT catTitle, catSlug	FROM blog_cats, blog_post_cats WHERE blog_cats.catID = blog_post_cats.catID AND blog_post_cats.postID = :postID');
                $stmt2->execute(array(':postID' => $row['postID']));
                $catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                $links = array();
                foreach ($catRow as $cat)
                {
                    $links[] = "<a href='c-".$cat['catSlug']."'>".$cat['catTitle']."</a>";
                }
                echo implode(", ", $links);
                echo '</p>';
                echo '<p>'.$row['postDesc'].'</p>';
                echo '<p><a href="viewpost.php?id='.$row['postID'].'">Read More</a></p>';
                echo '</div>';
            }
            echo $pages->page_links('c-'.$_GET['id'].'&');
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
        ?>

    </div>

    <div id='sidebar'>
        <?php require('sidebar.php'); ?>
    </div>

    <div id='clear'></div>

</div>

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
<noscript><p><img src="http://marctowler.co.uk/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
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