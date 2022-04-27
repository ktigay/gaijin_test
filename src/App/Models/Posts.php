<?php
namespace App\Models;

class Posts extends AppStorage
{
    protected string $_tableName = 'posts';

    public function setRelations(): array
    {
        return [
            'comments' => [self::HAS_MANY, 'Comments', 'conditions' => 'post_id = ?', 'by' => 'id', 'order' => 'level asc'],
            'user' => [self::HAS_ONE, 'Users', 'conditions' => 'id = ?', 'by' => 'user_id']
        ];
    }

    public static function storage($modelName = 'App\Models\Post'): static
    {
        return new static($modelName);
    }
}