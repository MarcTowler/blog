<?php
require('includes/config.php');

function section_status()
{
    return true;
}



//lets see if a comment has been submitted?
if(isset($_POST['submit']))
{
    $ins = $db->prepare('INSERT INTO blog_comments (pid, name, email, post_date, comment) VALUES(:pid, :name, :email, :post_date, :comment)');
    $ins->execute(array(
        ':pid'       => $_POST['comment_post_ID'],
        ':name'      => $_POST['comment_author'],
        ':email'     => $_POST['email'],
        ':post_date' => date('Y-m-d H:i:s'),
        ':comment'   => $_POST['comment']));

    $added = $db->lastInsertId();

    if($added)
    {
        header( 'Location: ' . $_SERVER['REQUEST_URI'] );
    }

}


if(isset($_GET['p']) && ($_GET['p'] == true) && $user->is_logged_in())
{
    $stmt = $db->prepare('SELECT p.postID, m.name, p.postTitle, p.postDesc, p.postCont, p.postDate, views FROM blog_posts_seo p, blog_members m WHERE m.memberID = p.poster AND postID = :postID AND published = 0');
} else {
    $stmt = $db->prepare('SELECT p.postID, m.name, p.postTitle, p.postDesc, p.postCont, p.postDate, views FROM blog_posts_seo p, blog_members m WHERE m.memberID = p.poster AND postID = :postID AND published = 1');
}
$stmt->execute(array(':postID' => $_GET['id']));
$row = $stmt->fetch();

if(!isset($_SESSION['uid']) || $_SESSION['uid'] <= 0) {
    $views = $row['views'] + 1;
    $vstmt = $db->prepare('UPDATE blog_posts_seo set views = :viewnum WHERE postID = :postID');
    $vstmt->execute(array(':viewnum' => $views, ':postID' => $_GET['id']));
}

//if post does not exists redirect user.
if($row['postID'] == ''){
    header('Location: ./');
    exit;
}


$cstmt = $db->prepare('SELECT cid, name, email, comment, post_date FROM blog_comments WHERE pid = :postid AND published = 1');
$cstmt->execute(array(':postid' => $row['postID']));

//track
whereFrom("viewpost.php?id=" . $_GET['id'], $row['postTitle']);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>
            <?php echo $row['postTitle'] . " : " . SITENAME;?>
        </title>
        <meta name="author" content="<?php echo $row['name']; ?>" />
        <meta name="description" content="<?php echo strip_tags($row['postDesc']); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="css/normalize.css" />
        <link rel="stylesheet" href="css/main.css">
        <link rel="alternate" href="rss.php" title="My RSS feed" type="application/rss+xml" />
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>

        <div id="wrapper">
            <h1>
                <?php echo SITENAME; ?>
            </h1>
            <div id="nav">
                <?php require_once('nav.php'); ?>
            </div>
            <div id='main'>

                <?php
                echo '<div>';
                if(isset($_GET['p']) && ($_GET['p'] == true) && $user->is_logged_in())
                {
                    echo '<h1 style="color: red">This is a Post Preview, This is Not Live</h1>';
                }
                echo '<h1>'.$row['postTitle'].'</h1>';
                echo '<p>Posted on '.date('jS M Y H:i:s', strtotime($row['postDate'])). ' by <b>' . $row['name'] . '</b> in ';

                $stmt2 = $db->prepare('SELECT catTitle, catSlug	FROM blog_cats, blog_post_cats WHERE blog_cats.catID = blog_post_cats.catID AND blog_post_cats.postID = :postID');
                $stmt2->execute(array(':postID' => $row['postID']));

                $catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                $links = array();
                foreach ($catRow as $cat)
                {
                    $links[] = "<a href='c-".$cat['catSlug']."'>".$cat['catTitle']."</a>";
                }
                echo implode(", ", $links);

                echo '&nbsp;<img src="img/view.jpg" height="20" width="20" /> ' . $row["views"] . '</p>';
                echo '<p>'.$row['postCont'].'</p>';
                echo '</div>';
                ?>
                <ul style="list-style: none;">
                    <li>
                        <a href="https://twitter.com/share" class="twitter-share-button" data-via="MarcTowler">Tweet</a>
                        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                    </li>
                </ul>
                <hr />
                <?php
                if(section_status('blog_comment'))
                {
                if($cstmt->rowCount() > 1)
                {
                    $has_comments = true;
                }
                ?>
                <section id="comments" class="body">

                    <header>
                        <h2>Comments</h2>
                    </header>

                    <ol id="posts-list" class="hfeed<?php echo($has_comments?' has-comments':''); ?>">
                       <?php if(!$has_comments) { echo('<li class="no-comments">Be the first to add a comment.</li>');}?>
                        <?php
                        while($comment = $cstmt->fetch())
                        {
                            ?>
                            <li><article id="comment_<?php echo($comment['cid']); ?>" class="hentry">
                                    <footer class="post-info">
                                        <abbr class="published" title="<?php echo($comment['post_date']); ?>">
                                            <?php echo($comment['post_date']);
                                            if(isset($_SESSION['uid']) && $_SESSION['uid'] > 0) {
                                                echo '[<a href="/admin/comments.php?del='.$comment["cid"].'">Delete</a>]';}?>
                                        </abbr>

                                        <address class="vcard author">
                                            By <a class="url fn" href="#"><?php echo($comment['name']); ?></a>
                                        </address>
                                    </footer>

                                    <div class="entry-content">
                                        <p><?php echo($comment['comment']); ?></p>
                                    </div>
                                </article></li>
                        <?php
                        }
                        ?>
                    </ol>

                    <div id="respond">

                        <h3>Leave a Comment</h3>

                        <form action="" method="post" id="commentform">

                            <label for="comment_author" class="required">Your name</label>
                            <input type="text" name="comment_author" id="comment_author" value="" tabindex="1" required="required">
                            <br />
                            <label for="email" class="required">Your email</label>
                            <input type="email" name="email" id="email" value="" tabindex="2" required="required">
                            <br />
                            <label for="comment" class="required">Your message</label>
                            <textarea name="comment" id="comment" rows="10" tabindex="4"  required="required"></textarea>
                            <br />
                            <input type="hidden" name="comment_post_ID" value="<?php echo($row['postID']); ?>" id="comment_post_ID" />
                            <input name="submit" type="submit" value="Submit comment" />

                        </form>

                    </div>
                    <?php
                    }
                    ?>
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
            <noscript><p><img src="https://marctowler.co.uk/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
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