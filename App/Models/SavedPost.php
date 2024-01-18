<?php

namespace App\Models;

use App\Core\Model;

class SavedPost extends Model
{
    protected ?int $id = null;
    protected ?int $post_id;
    protected ?string $save_author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getPostId(): ?int
    {
        return $this->post_id;
    }

    public function setPostId(?int $post_id): void
    {
        $this->post_id = $post_id;
    }

    public function getSaveAuthor(): ?string
    {
        return $this->save_author;
    }

    public function setSaveAuthor(?string $save_author): void
    {
        $this->save_author = $save_author;
    }

}