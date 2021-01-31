<?php
require_once('./lib/func.php');

class Post
{
    private $id;
    private $name;
    private $email;
    private $body;
    private $password;
    private $posted_at;
    private $updated_at;

    public function __construct($postArray)
    {
        $this->id = $postArray['id'];
        $this->name = $postArray['name'];
        $this->email = $postArray['email'];
        $this->body = $postArray['body'];
        $this->password = $postArray['password'];
        $this->posted_at = $postArray['posted_at'];
        $this->updated_at = $postArray['updated_at'];
    }

    public function TheId()
    {
        return h($this->id);
    }

    public function TheName()
    {
        return h($this->name);
    }

    public function TheEmail()
    {
        return h($this->email);
    }

    public function TheBody()
    {
        return h($this->body);
    }

    public function ThePostedDate()
    {
        return h($this->posted_at);
    }

    public function TheUpdatedDate()
    {
        return $this->IsUpdated() ? h($this->updated_at) : '';
    }

    public function IsUpdated()
    {
        return $this->posted_at === $this->updated_at;
    }
}
