# Adding published column to blog_posts_seo
ALTER TABLE `blog_posts_seo` (
      ADD published BOOLEAN NOT NULL DEFAULT FALSE
);

# Add default value of TIMESTAMP to post_date row
ALTER TABLE `blog_posts_seo` (
      CHANGE `postDate `postDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

#create comments table

CREATE TABLE IF NOT EXISTS `blog_comments` (
  `cid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `pid` tinyint(4) NOT NULL,
  `name` varchar(65) NOT NULL,
  `email` varchar(90) NOT NULL,
  `comment` text NOT NULL,
  `post_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published` BOOLEAN NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`cid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;