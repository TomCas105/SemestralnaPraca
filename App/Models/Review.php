<?php

namespace App\Models;

use App\Core\Model;

class Review extends Model
{
    protected ?int $id = null;
    protected ?int $post_id;
    protected ?string $review_author;
    protected ?int $rating;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPostId(): int
    {
        return $this->post_id;
    }

    public function setPostId(int $post_id): void
    {
        $this->post_id = $post_id;
    }

    public function getReviewAuthor(): string
    {
        return $this->review_author;
    }

    public function setReviewAuthor(string $review_author): void
    {
        $this->review_author = $review_author;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }
}