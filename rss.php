<?php

header("Content-Type: application/rss+xml; charset=ISO-8859-1");

require_once('includes/config.php');

$rssfeed = '<?xml version="1.0" encoding="ISO-8859-1"?>';
$rssfeed .= '<rss version="2.0">';
$rssfeed .= '<channel>';
$rssfeed .= '<title>Code Something</title>';
$rssfeed .= '<link>http://www.marctowler.co.uk/</link>';
$rssfeed .= '<description>Website Development and Design blog written by Marc Towler, includes game
                    reviews and book reviews</description>';
$rssfeed .= '<language>en-gb</language>';
$rssfeed .= '<copyright>Copyright (C) 2012-2015 marctowler.co.uk</copyright>';

$stmt = $db->query("SELECT * FROM blog_posts_seo WHERE published=1 ORDER BY postID DESC");


while($row = $stmt->fetch())
{
    $rssfeed .= '<item>';
    $rssfeed .= '<title>' . $row["postTitle"] . '</title>';
    $rssfeed .= '<description>' .  html_entity_decode(strip_tags($row["postDesc"])) . '</description>';
    $rssfeed .= '<link>http://marctowler.co.uk/viewpost.php?id=' . $row["postID"] . '</link>';
    $rssfeed .= '<pubDate>' .date("jS M Y H:i:s", strtotime($row["postDate"])) . '</pubDate>';
    $rssfeed .= '</item>';
}

$rssfeed .= '</channel>';
$rssfeed .= '</rss>';

echo $rssfeed;
?>