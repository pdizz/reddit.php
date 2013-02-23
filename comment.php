<?php 
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
