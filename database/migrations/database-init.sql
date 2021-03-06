CREATE DATABASE social_blog_db CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE USER 'social_blog_user'@'server' IDENTIFIED VIA mysql_native_password USING 'BVJKxI3boxe3DS5oBVJKxI3boxe3DS5oBVJKxI3bo';
GRANT ALL PRIVILEGES ON *.* TO 'social_blog_user'@'server' REQUIRE NONE
WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT ALL PRIVILEGES ON `social\_blog\_user\_%`.* TO 'social_blog_user'@'server';
