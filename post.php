<?php 
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

    public function __construct($_data)
    {
        $this->id = $_data->id;
        $this->title = $_data->title;
        $this->author = $_data->author;
        $this->url = $_data->url;
        $this->permalink = $_data->permalink;
    }

    public function get_top_comments()
    {
        $comments_object = json_decode(file_get_contents("http://www.reddit.com/comments/$this->id.json"));
        sleep(2); // after every page request
        
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
?>
