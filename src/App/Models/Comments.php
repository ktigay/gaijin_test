<?php

namespace App\Models;

class Comments extends AppStorage
{
    protected string $_tableName = 'comments';

    public static function storage($modelName = 'App\Models\Comment'): static
    {
        return new static($modelName);
    }

    public function setRelations(): array
    {
        return [
            'user' => [self::BELONGS_TO, 'Users', 'conditions' => 'id = ?', 'by' => 'user_id']
        ];
    }

    /**
     * @param int $id
     * @return AppModel|null
     */
    public function getById(int $id):? AppModel
    {
        $result = $this->_point->execute("select * from {$this->_tableName} where id = ? and deleted = 0 limit 1", [$id]);
        if($result && count($result)) {
            return $this->_createModel($result[0]);
        }
        return null;
    }

    public function findRecursive($post_id, $leaf_id): array
    {
        $models = [];
        $sql = "with recursive cte AS (
                    select *
                    from comments
                    where post_id = ? and parent_id = ?
                    union all
                    select c.*
                    from comments as c
                            inner join cte on cte.id = c.parent_id
                )
                SELECT cte.*, u.name
                from cte inner join users u on cte.user_id = u.id";

        $result = $this->_point->execute($sql, [$post_id, $leaf_id]);
        if($result && count($result)) {
            foreach ($result as $row) {
                $models[] = $this->_createModel($row);
            }
        }
        return $models;
    }
}