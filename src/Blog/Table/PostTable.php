<?php

namespace App\Blog\Table;

use App\Blog\Entity\Post;
use App\Framework\Database\PaginatedQuery;
use App\Framework\Database\Table;
use Pagerfanta\Pagerfanta;

class PostTable extends Table
{
    /**
     * @var string
     */
    protected $entity = Post::class;

    protected $table = 'posts';

    protected function paginationQuery()
    {
        return "
            SELECT p.id, p.name, c.name category_name 
            FROM {$this->table} as p
            LEFT JOIN categories as c ON p.category_id = c.id
            ORDER BY created_at DESC 
        ";
    }
}
