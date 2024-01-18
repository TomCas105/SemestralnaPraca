<?php

namespace App\Models;

use App\Core\Model;
use DateTime;
use Exception;

class Review extends Model
{
    protected ?int $id = null;
    protected ?int $post_id;
    protected ?string $review_author;
    protected ?int $rating;
    protected ?string $date;

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): void
    {
        $this->date = $date;
    }

    protected ?string $review_text;

    public function getReviewText(): ?string
    {
        return $this->review_text;
    }

    public function getReviewTextShort(): ?string
    {
        if ($this->review_text < 47) {
            return $this->review_text;
        }
        return $this->review_text.substr(0,  0, 47) . "...";
    }

    public function setReviewText(?string $review_text): void
    {
        $this->review_text = $review_text;
    }

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

    public function getFormattedAge(): ?string
    {
        try {
            $currentTime = new DateTime();
            $postAge = $currentTime->diff(new DateTime($this->getDate()));
            if ($postAge->days < 1) {
                return $postAge->format("%hh");
            }
            if ($postAge->days < 30) {
                return (int)($postAge->days) . "d";
            }
            if ($postAge->days < 365) {
                return (int)($postAge->days / 30) . "m";
            }
            return (int)($postAge->days / 365) . "r";
        } catch (Exception) {
            return "";
        }
    }
}