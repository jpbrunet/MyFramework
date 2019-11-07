<?php

namespace App\Blog\Table;

use App\Blog\Entity\Post;
use App\Framework\Database\PaginatedQuery;
use Pagerfanta\Pagerfanta;

class PostTable
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * PostTable constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Paginate post
     * @param int $perPage
     * @param int $currentPage
     * @return Pagerfanta
     */
    public function findPaginated(int $perPage, int $currentPage): Pagerfanta
    {
        $query = new PaginatedQuery(
            $this->pdo,
            'SELECT * FROM posts ORDER BY created_at DESC',
            'SELECT COUNT(id) FROM posts',
            Post::class
        );
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    /**
     * Get a post from this id
     * @param int $id
     * @return Post|null
     */
    public function find(int $id): ?Post
    {
        $query = $this->pdo
            ->prepare('SELECT * FROM posts WHERE id = ?');
        $query->setFetchMode(\PDO::FETCH_CLASS, Post::class);
        $query->execute([$id]);
        return $query->fetch() ?: null;
    }
}
