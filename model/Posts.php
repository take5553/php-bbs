<?php

class Posts implements IteratorAggregate
{
    private $posts;

    public function __construct()
    {
        $this->posts = array();
    }

    public function Add(Post $post)
    {
        $this->posts[] = $post;
    }

    public function HavePosts()
    {
        return count($this->posts) !== 0;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->posts);
    }
}
