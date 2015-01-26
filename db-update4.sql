#add a link to member TABLE and default to main user
ALTER TABLE `blog_posts_seo`
    Add poster tinyint(4) NOT NULL DEFAULT 1 AFTER `postID`;