<?php 

require_once "reddit.php";

$reddit = new Reddit();

//$popular_subreddits = $reddit->get_popular_subreddits();
//var_dump($popular_subreddits);

//$popular_subreddits = $reddit->get_popular_subreddits(5);
//var_dump($popular_subreddits);

//$subreddit = $popular_subreddits[0];
//var_dump($subreddit);

//$posts = $subreddit->get_posts();
//var_dump($posts);
//var_dump($subreddit->posts);

//$posts = $subreddit->get_posts(5);
//var_dump($posts);
//var_dump($subreddit->posts);

$post = $reddit->get_post_by_id('10ucvf');
$comments = $post->get_comments();
//var_dump($post);
//var_dump($comments);
var_dump($post->comments);

?>
