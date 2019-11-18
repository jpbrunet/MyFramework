<?php

namespace Tests\Blog\Table;


use App\Blog\Entity\Post;
use App\Blog\Table\PostTable;
use App\Framework\Database\NoRecordException;
use Tests\DatabaseTestCase;

class PostTableTest extends DatabaseTestCase
{

    /**
     * @var PostTable
     */
    private $postTable;

    protected function setUp(): void
    {
        parent::setUp();
        $pdo = $this->getPDO();
        $this->migrateDatabase($pdo);
        $this->postTable = new PostTable($pdo);
    }

    public function testFind()
    {
        $this->seedDatabase($this->postTable->getPdo());
        $post = $this->postTable->find(1);
        $this->assertInstanceOf(Post::class, $post);
    }

    public function testFindNotFoundRecord()
    {
        $this->expectException(NoRecordException::class);
        $post = $this->postTable->find(1);
        $this->assertNull($post);
    }

    public function testUpdate()
    {
        $this->seedDatabase($this->postTable->getPdo());
        $this->postTable->update(1, ['name' => 'Salut les copains', 'slug' => 'salut-les-copains']);
        $post = $this->postTable->find(1);
        $this->assertEquals('Salut les copains', $post->name);
        $this->assertEquals('salut-les-copains', $post->slug);
    }

    public function testInsert()
    {
        $this->postTable->insert(['name' => 'Salut les copains', 'slug' => 'salut-les-copains']);
        $post = $this->postTable->find(1);
        $this->assertEquals('Salut les copains', $post->name);
        $this->assertEquals('salut-les-copains', $post->slug);
    }

    public function testDelete()
    {
        $this->postTable->insert(['name' => 'Salut les copains', 'slug' => 'salut-les-copains']);
        $this->postTable->insert(['name' => 'Salut les gens', 'slug' => 'salut-les-gens']);
        $count = $this->postTable->getPdo()->query('SELECT COUNT(id) FROM posts')->fetchColumn();
        $this->assertEquals(2, (int)$count);
        $this->postTable->delete($this->postTable->getPdo()->lastInsertId());
        $count = $this->postTable->getPdo()->query('SELECT COUNT(id) FROM posts')->fetchColumn();
        $this->assertEquals(1, (int)$count);

    }
}
