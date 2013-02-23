<?php 
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
?>
