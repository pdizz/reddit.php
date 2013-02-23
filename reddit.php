<?php

function __autoload($classname) {
    $filename = "./". $classname .".php";
    require_once($filename);
}

class Reddit
{
    
    public static $user_agent = "Generic PHPraw client";
    
    public function __construct($user_agent = NULL)
    {
        if ($user_agent !== NULL)
        {
            self::$user_agent = $user_agent;
        }
    }
    
    public function get_popular_subreddits($limit = 25)
    {
        $popular_subreddits = json_decode(file_get_contents("http://www.reddit.com/reddits/popular.json?limit=$limit"));
        sleep(2); // after every page request
        //var_dump($popular_subreddits->data->children);
        
        $subreddits = array();
        foreach($popular_subreddits->data->children as $subreddit)
        {
            $s = new Subreddit($subreddit->data);
            $subreddits[] = $s;
        }
        return $subreddits;
        
    }
    
    public function get_post_by_id($post_id)
    {
        $post_object = json_decode(file_get_contents("http://www.reddit.com/$post_id/.json"));
        sleep(2); // after every page request
        //var_dump($post_object[0]->data->children[0]->data);
        
        $post = new Post($post_object[0]->data->children[0]->data);
        return $post;
    }
    
    public function get_posts_from_subreddit($subreddit, $limit = 25)
    {
        // allows us to pass a url string (ex: "/r/pics") or subreddit object
        if (gettype($subreddit) == "string")
        {
            $subreddit_url = $subreddit;
        }
        else
        {
            $subreddit_url = $subreddit->url;
        }
        
        $posts_object = json_decode(file_get_contents("http://www.reddit.com$subreddit_url.json?limit=$limit"));
        sleep(2); //after every page request
        
        //var_dump($posts_object);
        
        $posts = array();
        foreach ($posts_object->data->children as $post)
        {
            $p = new Post($post->data);
            $posts[] = $p;
        }
        return $posts;
    }
    
    public function get_comments_from_post($post)
    {
        // allows us to enter id string (ex: "zpv0i") or Post object
        if (gettype($post) == "string")
        {
            $p = new Post();
            $p->id = $post;
            $post = $p;
        }
        
        return $post->get_comments(); 
    }
}

?>
