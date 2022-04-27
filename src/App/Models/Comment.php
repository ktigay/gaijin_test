<?php
namespace App\Models;

class Comment extends AppModel
{
    public function isEditable(): bool
    {
        return !$this->deleted && strtotime($this->create_at) >= (time() - 60 * 60);
    }

    public function isRemoveable(): bool
    {
        return !$this->deleted && strtotime($this->create_at) >= (time() - 60 * 60);
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        $data['editable'] = $this->isEditable();
        $data['removeable'] = $this->isRemoveable();

        return $data;
    }
}