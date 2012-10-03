<?php

class Reddit
{    
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

class Subreddit
{
    public $id;
    public $display_name;
    public $url;
    
    // populated by get_posts()
    public $posts;
    
    public function __construct($_data)
    {
        $this->display_name = $_data->display_name;
        $this->url = $_data->url;
        $this->id = $_data->id;
    }
    
    public function get_posts($limit = 25)
    {        
        $posts_object = json_decode(file_get_contents("http://www.reddit.com$this->url.json?limit=$limit"));
        sleep(2); //after every page request
        
        //var_dump($posts_object->data->children);
        
        $this->posts = array();
        foreach ($posts_object->data->children as $post)
        {
            $p = new Post($post->data);
            $this->posts[] = $p;
        }
        return $this->posts;
    }
}

class Post
{
    public $id;
    public $title;
    public $author;
    public $url;
    public $permalink;
    
    // populated by get_comments()
    public $comments;
    public $top_comments;

    public function __construct($_data = NULL)
    {
        // allows us to instantiate generic post object to access methods
        if (!is_null($_data))
        {
            $this->id = $_data->id;
            $this->title = $_data->title;
            $this->author = $_data->author;
            $this->url = $_data->url;
            $this->permalink = $_data->permalink;
        }

    }

    public function get_top_comments()
    {
        $comments_object = json_decode(file_get_contents("http://www.reddit.com/comments/$this->id.json"));
        sleep(2); // after every page request

        //var_dump($comments_object[0]->data->children);
        //var_dump($comments_object[1]->data->children);
        
        $this->top_comments = array();
        foreach ($comments_object[1]->data->children as $comment)
        {
            // TODO: handle 'more' comments
            if ($comment->kind != 'more')
            {
                //var_dump($comment);
                $c = new Comment($comment->data);
                $this->top_comments[] = $c;
            }

        }
        return $this->top_comments;
    }
    
    public function get_comments($comments = NULL)
    {
              
        if (is_null($comments))
        {
            $comments_object = json_decode(file_get_contents("http://www.reddit.com/comments/$this->id.json"));
            sleep(2); // after every page request
            //var_dump($comments_object);
            $comments = $comments_object[1]->data->children;
        }
        
        foreach ($comments as $comment)
        {
            // TODO: handle 'more' comments
            
            if ($comment->kind != 'more')
            {
                $c = new Comment($comment->data);
                $this->comments[] = $c;
                //var_dump($c);
                if ($comment->data->replies != '')
                {
                    $this->get_comments($comment->data->replies->data->children);
                }
            }

        }
        return $this->comments;
        
    }
}

class Comment
{
    public $id;
    public $body;
    public $body_html;
    public $author;
    public $replies;
    
    public function __construct($_data)
    {
        $this->id = $_data->id;
        $this->body = $_data->body;
        $this->body_html = $_data->body_html;
        $this->author = $_data->author;
        $this->replies = $_data->replies;
    }
}



?>
