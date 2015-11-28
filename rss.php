<?php

header("Content-Type: application/rss+xml; charset=UTF-8");

require_once('includes/config.php');

$rssfeed = "<?xml version='1.0' encoding='UTF-8'?>\n";
$rssfeed .= "<rss version='2.0' xmlns:atom='http://www.w3.org/2005/Atom'>\n";
$rssfeed .= "<channel>\n";
$rssfeed .= "<title>" . SITENAME . "</title>\n";
$rssfeed .= "<atom:link href='https://www.marctowler.co.uk/rss.php' rel='self' type='application/rss+xml' />\n";
$rssfeed .= "<link>https://www.marctowler.co.uk/</link>\n";
$rssfeed .= "<description>Website Development and Design blog written by Marc Towler, includes game reviews and book reviews</description>\n";
$rssfeed .= "<language>en-gb</language>\n";
$rssfeed .= "<copyright>Copyright (C) 2012-2015 marctowler.co.uk</copyright>\n";

$stmt = $db->query("SELECT * FROM blog_posts_seo WHERE published=1 ORDER BY postID DESC");


while($row = $stmt->fetch())
{
    $rssfeed .= "<item>\n";
    $rssfeed .= "<title>" . $row["postTitle"] . "</title>\n";
    $rssfeed .= "<description><![CDATA[" .  html_entity_decode(strip_tags($row['postDesc'])) . "]]></description>\n";
    $rssfeed .= "<link>https://www.marctowler.co.uk/" . $row['postSlug'] . ".html</link>\n";
    $rssfeed .= "<guid>https://www.marctowler.co.uk/" . $row['postSlug'] . ".html</guid>\n";
    $rssfeed .= "<pubDate>" .date('D, d M Y H:i:s', strtotime($row['postDate'])) . " GMT</pubDate>\n";
    $rssfeed .= "</item>\n";
}

$rssfeed .= "</channel>\n";
$rssfeed .= "</rss>\n";

echo $rssfeed;
?>