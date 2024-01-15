<?php

namespace App\Models;

use App\Core\DB\Connection;
use App\Core\Model;
use Cassandra\Date;
use DateTime;

class Post extends Model
{

    public static function getTopFivePosts(): array
    {
        $posts = Post::getAll();

        $comparator = function($a, $b) {
            if ($a->getPostRating() < $b->getPostRating()) {
                return 1;
            } else if ($a->getPostRating() > $b->getPostRating()) {
                return -1;
            }
            return 0;
        };

        usort($posts, $comparator);
        return array_slice($posts, 0, 5);
    }

    public static function getRecommendedPosts(): array
    {
        $posts = Post::getAll(whereClause: "recommended = TRUE");

        $comparator = function($a, $b) {
            if ($a->getPostRating() < $b->getPostRating()) {
                return 1;
            } else if ($a->getPostRating() > $b->getPostRating()) {
                return -1;
            }
            return 0;
        };

        usort($posts, $comparator);
        return array_slice($posts, 0, 5);
    }

    protected ?int $id = null;
    protected ?string $author;
    protected ?string $title;
    protected ?string $date;
    protected ?string $picture;
    protected ?string $info;
    protected ?string $ingredients;

    protected ?string $recipe;

    protected ?bool $recommended;

    public function getRecommended(): ?bool
    {
        return $this->recommended;
    }

    public function setRecommended(?bool $recommended): void
    {
        $this->recommended = $recommended;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): void
    {
        $this->date = $date;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): void
    {
        $this->picture = $picture;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(string $info): void
    {
        $this->info = $info;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(?string $ingredients): void
    {
        $this->ingredients = $ingredients;
    }

    public function getRecipe(): ?string
    {
        return $this->recipe;
    }

    public function setRecipe(string $recipe): void
    {
        $this->recipe = $recipe;
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
        }
        catch (\Exception) {
            return  "";
        }
    }

    public function getPostRating(): ?float
    {
        $rating = 0.0;
        $reviews = Review::getAll(whereClause: "post_id = " . $this->getId());

        if (empty($reviews))
        {
            return 0;
        }

        foreach ($reviews as $review)
        {
            $rating += $review->getRating();
        }

        return $rating / sizeof($reviews);
    }
}