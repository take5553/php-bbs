<?php
require_once('../lib/func.php');

class Posts implements IteratorAggregate
{
    private $posts;

    public function __construct($postsFromDB)
    {
        // $postsFromDBの中身は
        // 1. false（記事数0）
        // 2. 一次元配列（記事数1）
        // 3. 二次元配列（記事数2以上）
        if (! is_array($postsFromDB)) {
            $this->posts = array();
        } elseif (array_depth($postsFromDB) === 1) {
            $this->posts = array(new Post($postsFromDB));
        } else {
            $this->posts = array();
            foreach ($postsFromDB as $post) {
                $this->Add(new Post($post));
            }
        }
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
