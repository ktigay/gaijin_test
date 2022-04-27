<?php

namespace App\Models;

use App\App;
use App\Data\AccessPointInterface;

abstract class AppStorage
{
    public const HAS_MANY = 'many';
    public const HAS_ONE = 'one';
    public const BELONGS_TO = 'belongsto';


    protected string $_tableName;
    protected string $_modelName;
    protected AccessPointInterface $_point;

    protected array $_relations;
    protected array $_binds = [];

    protected function __construct($modelName)
    {
        $this->_modelName = $modelName;
        $this->_point = App::getInstance()->getAccessPoint();
        $this->_relations = $this->setRelations();
    }

    public function tableName(): string
    {
        return $this->_tableName;
    }

    abstract public static function storage($modelName): static;

    public function relations(): array
    {
        return $this->_relations;
    }

    public function setRelations(): array
    {
        return [];
    }

    /**
     * @param int $id
     * @return AppModel|null
     */
    public function getById(int $id):? AppModel
    {
        $data = $this->_getDataById($id);
        if($data) {
            return $this->_createModel($data);
        }
        return null;
    }

    public function _getDataById(int $id):? array
    {
        $result = $this->_point->execute("select * from {$this->_tableName} where id = ? limit 1", [$id]);
        if($result && count($result)) {
            return $result[0];
        }
        return null;
    }

    /**
     * @param string $conditions
     * @param array $params
     * @return AppModel[]
     */
    public function findBy(string $conditions, array $params = [], $order = null, $select = '*'): array
    {
        $models = [];

        $result = $this->_findDataBy($conditions, $params, $order, $select);
        if($result && count($result)) {
            foreach ($result as $row) {
                $models[] = $this->_createModel($row);
            }
        }
        return $models;
    }

    public function _findDataBy(string $conditions, array $params = [], $order = null, $select = '*'):? array
    {
        return $this->_point->execute("select $select from {$this->_tableName} as t ". ($conditions ? " where $conditions" : "") . ($order ? " order by $order" : ''), $params);
    }

    public function save($data): mixed
    {
        $id = null;
        if(isset($data['id'])) {
            $id = $data['id'];
        }
        unset($data['id'], $data['update_at'], $data['create_at']);

        $fields = array_keys($data);
        $params = array_values($data);

        if($id) {
            $sql = "update {$this->_tableName}  set ". (join(' = ?, ', $fields) .' = ?') ." where id = ?";
            $params[] = $id;
        } else {
            $values = '(?'. str_repeat(', ?', count($data) - 1) .')';
            $sql = "insert into {$this->_tableName} (". join(',', $fields) .") values {$values}";
        }
        return $this->_point->execute($sql, $params);
    }

    public function bind($relName, array $bindParams = []): static
    {
        $this->_binds[] = [$relName,
            'conditions' => $bindParams['conditions'] ?? '',
            'params' => $bindParams['params'] ?? [],
            'select' => $bindParams['select'] ?? '*',
            'order' => $bindParams['order'] ?? '',
            'bind' => $bindParams['bind'] ?? null,
        ];
        return $this;
    }

    public function createEmpty(): AppModel
    {
        return new $this->_modelName([], $this);
    }

    public function _bindRelations(AppModel $model): void
    {
        if(!empty($this->_binds)) {
            $relations = $this->relations();
            foreach($this->_binds as $bindParams) {

                $relName = array_shift($bindParams);
                if(!isset($relations[$relName])) {
                    continue;
                }

                $this->_bindRelation($model, $relName, $relations[$relName], $bindParams);
            }
        }
    }

    protected function _createModel(array $rowData): AppModel
    {

        $model = new $this->_modelName($rowData, $this);
        $this->_bindRelations($model);

        return $model;
    }

    public function _bindRelation(AppModel $model, string $relName, array $relation, array $bindParams = []): void
    {
            $order = '';
            [$relationType, $className] = $relation;

            $conditions = $relation['conditions'];

            $params = [];
            $relBy = $relation['by'];
            if(!is_array($relBy)) {
                $relBy = [$relBy];
            }
            foreach ($relBy as $by) {
                $params[] = $model->{$by};
            }

            if(isset($relation['order'])) {
                $order = $relation['order'];
            }

            if(!empty($bindParams['conditions'])) {
                $conditions = $bindParams['conditions']. ' and '. $conditions;
            }
            if(!empty($bindParams['params'])) {
                $params = array_merge($bindParams['params'], $params);
            }
            if(!empty($bindParams['order'])) {
                $order = $bindParams['order'];
            }

            /**
             *@var AppStorage $storage
             */
            $storage = call_user_func(array("\\App\\Models\\$className", 'storage'));

            if(!empty($bindParams['bind'])) {
                if(!is_array($bindParams['bind'])) {
                    $bindParams['bind'] = [$bindParams['bind']];
                }

                foreach($bindParams['bind'] as $bindName) {
                    $storage->bind($bindName);
                }
            }

            $result = $storage->findBy($conditions, $params, $order, $bindParams['select'] ?? '*');

            if($relationType !== self::HAS_MANY) {
                $result = array_shift($result);
            }
            $model->{$relName} = $result;
    }
}