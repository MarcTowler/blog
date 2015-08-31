 alter table blog_posts_seo add views tinyint(4) default 0 after postDate;

 alter table blog_members add name varchar(60) after username;