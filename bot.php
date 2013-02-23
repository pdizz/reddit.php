<?php 

require_once("reddit.php");

$reddit = new Reddit();

// get 10 most popular subreddits
$top_subs = $reddit->get_popular_subreddits(10);
echo "The top subreddits right now are:<br/><ul>";
foreach($top_subs as $sub) {
    echo "<li><a href=\"http://www.reddit.com$sub->url\">$sub->display_name - $sub->url</a></li>";
}
echo "</ul><br/>";

// get 10 highest ranked posts in /r/php
$php_posts = $reddit->get_posts_from_subreddit("/r/php", 10);
echo "The top posts in /r/php right now are:<br/><ul>";
foreach($php_posts as $post) {
    echo "<li><a href=\"$post->url\">$post->title</a></li>";
}
echo "</ul>";




?>
