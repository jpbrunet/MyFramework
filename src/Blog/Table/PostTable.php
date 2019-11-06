<?php

namespace App\Blog\Table;

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
     * @return array
     */
    public function findPaginated(): array
    {
        return $this->pdo
            ->query("SELECT * FROM posts ORDER BY updated_at DESC LIMIT 10")
            ->fetchAll();
    }

    /**
     * Get a post from this id
     * @param int $id
     * @return \stdClass
     */
    public function find(int $id): \stdClass
    {
        $query = $this->pdo
            ->prepare('SELECT * FROM posts WHERE id = ?');
        $query->execute([$id]);
        return $query->fetch();
    }
}
