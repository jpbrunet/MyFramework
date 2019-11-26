<?php

namespace App\Blog\Entity;

use DateTime;
use Exception;

class Post
{
    public $id;
    public $name;
    public $slug;
    public $content;
    public $createdAt;
    public $updatedAt;
    public $categoryName;

    /**
     * @param $dateTime
     * @throws Exception
     */
    public function setCreatedAt($dateTime): void
    {
        if (is_string($dateTime)) {
            $this->createdAt = new DateTime($dateTime);
        }
    }

    /**
     * @param DateTime $dateTime
     * @throws Exception
     */
    public function setUpdatedAt(DateTime $dateTime): void
    {
        if (is_string($dateTime)) {
            $this->updatedAt = new DateTime($dateTime);
        }
    }
}
