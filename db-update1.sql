DROP TABLE IF EXISTS `blog_posts_seo`;

CREATE TABLE `blog_posts_seo` (
  `postID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `postTitle` varchar(255) DEFAULT NULL,
  `postSlug` varchar(255) DEFAULT NULL,
  `postDesc` text,
  `postCont` text,
  `postDate` datetime DEFAULT NULL,
  PRIMARY KEY (`postID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE blog_posts ADD postSlug varchar(255) NOT NULL AFTER `postTitle`;
ALTER TABLE blog_posts ADD published tinyint(1) NOT NULL AFTER `postDate`;

The first things to do is alter the table structure for blog_posts add another column under postTitle called postSlug datatype is varchar and length is 255