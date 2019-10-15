<?php

namespace App\ReadModel;

use App\ReadModel\Views\PostView;

class PostReadRepository
{
    private $posts;

    public function __construct()
    {
        $this->posts = [
            new PostView(1, new \DateTimeImmutable(), 'The First Post', 'Content of first post'),
            new PostView(2, new \DateTimeImmutable(), 'The Second Post', 'Content of second post'),
            new PostView(3, new \DateTimeImmutable(), 'The Third Post', 'Content of third post'),
            new PostView(4, new \DateTimeImmutable(), 'The Fourth Post', 'Content of fourth post'),
        ];
    }

    /**
     * @return PostView[]
     */
    public function getAll(): array
    {
        return array_reverse($this->posts);
    }

    public function find($id): ?PostView
    {
        foreach ($this->posts as $post) {
            if ($post->id === (int) $id) {
                return $post;
            }
        }

        return null;
    }
}
