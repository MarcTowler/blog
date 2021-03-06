<?php

header("Content-Type: application/rss+xml; charset=UTF-8");

require_once('includes/config.php');
require_once('classes/Parsedown.php');

$p = new Parsedown();

$rssfeed = "<?xml version='1.0' encoding='UTF-8'?>\n";
$rssfeed .= "<rss version='2.0' xmlns:atom='http://www.w3.org/2005/Atom'>\n\n";
$rssfeed .= "<channel>\n";
$rssfeed .= "\t<title>" . SITENAME . "</title>\n";
$rssfeed .= "\t<atom:link href='" . URL . "rss.php' rel='self' type='application/rss+xml' />\n";
$rssfeed .= "\t<link>" . URL . "</link>\n";
$rssfeed .= "\t<description>" . DESCRIPTION . "</description>\n";
$rssfeed .= "\t<language>en-gb</language>\n";
$rssfeed .= "\t<copyright>Copyright (C) 2012-2015 " . URL . "</copyright>\n";

if(isset($_GET['id']))
{
    $stm = $db->prepare('SELECT catID FROM blog_cats WHERE catSlug = :catSlug');
    $stm->execute(array(':catSlug' => $_GET['id']));
    $row = $stm->fetch();

    $stmt = $db->query("SELECT * FROM blog_posts_seo, blog_post_cats WHERE blog_posts_seo.postID = blog_post_cats.postID AND blog_post_cats.catID = " . $row['catID'] . " AND published=1 AND postDate <= NOW() ORDER BY postDate DESC");
} else {
    $stmt = $db->query("SELECT * FROM blog_posts_seo WHERE published=1 AND postDate <= NOW() ORDER BY postDate DESC");
}

while($row = $stmt->fetch())
{
    $rssfeed .= "\t<item>\n";
    $rssfeed .= "\t\t<title>" . $row["postTitle"] . "</title>\n";
    $rssfeed .= "\t\t<link>" . URL . $row['postSlug'] . ".html</link>\n";
    $rssfeed .= "\t\t<pubDate>" .date('D, d M Y H:i:s', strtotime($row['postDate'])) . " GMT</pubDate>\n";
    $rssfeed .= "\t\t<description><![CDATA[" .  $p->text(html_entity_decode($row['postCont'])) . "]]></description>\n";
    $rssfeed .= "\t\t<guid>" . URL . $row['postSlug'] . ".html</guid>\n";
    $rssfeed .= "\t</item>\n";
}

$rssfeed .= "</channel>\n";
$rssfeed .= "</rss>\n";

echo $rssfeed;
?>