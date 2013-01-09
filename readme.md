Sample usage:

<?php 

require_once "reddit.php";

//set optional limit (default 25) and instantiate Reddit object
$limit = 10;
$my_reddit = new Reddit();

//display top subreddits
$top_subs = $my_reddit->get_popular_subreddits($limit);
foreach($top_subs as $sub) {
    echo "<a href='http://www.reddit.com$sub->url'>$sub->display_name</a><br/>";
}

echo "<br/>";

// get posts from subreddit object
$my_sub = $top_subs[0];
$my_posts = $my_reddit->get_posts_from_subreddit($my_sub);
foreach($my_posts as $post) {
    echo "ID: $post->id -- <a href='http://www.reddit.com$post->permalink'>
            $post->title</a> by $post->author<br/>";
}

echo "<br/>";

// get top comments from post
$my_post = $my_posts[0];
$my_post->get_top_comments(); // must call this method to populate top_comments array
foreach($my_post->top_comments as $comment) {
    echo "$comment->author said: $comment->body</br>";
}

echo "<br/>";

// get comments from post
$my_post->get_comments(); // must call this method to populate comments array
foreach($my_post->comments as $comment) {
    echo "$comment->author said: $comment->body</br>";
}


?>


