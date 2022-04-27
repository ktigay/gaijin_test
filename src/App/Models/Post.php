<?php
namespace App\Models;

class Post extends AppModel
{

    public function buildCommentsTree():? array
    {
        return self::buildCommentTree($this->comments);
    }

    /**
     * @param Comment[] $comments
     * @return array|null
     */
    public static function buildCommentTree(array $comments):? array
    {
        if(!$comments) {
            return [];
        }

        $comments = array_map(function($item) { return $item->toArray(); }, $comments);

        if(empty($comments)) {
            return [];
        }

        function _buildTree(&$items, $parentId = null): array {
            $treeItems = [];
            foreach ($items as $idx => $item) {
                if((empty($parentId) && empty($item['parent_id'])) || (!empty($item['parent_id']) && !empty($parentId) && $item['parent_id'] == $parentId)) {
                    $nodes = _buildTree($items, $item['id']);
                    !empty($nodes) && $items[$idx]['nodes'] = $nodes;

                    $treeItems []= $items[$idx];
                }
            }

            return $treeItems;
        }

        return _buildTree($comments, $comments[0]['parent_id']);
    }
}