ALTER TABLE `blog_posts_seo`
ADD COLUMN `author` TINYINT(1) NOT NULL DEFAULT 0 AFTER `published`;

ALTER TABLE `blog_posts_seo`
CHANGE COLUMN `views` `views` INT(10) NOT NULL DEFAULT '0' COMMENT '' ;
