<?php

namespace App\Models;

abstract class AppModel
{
    protected array $_data;
    protected array $_nestedData = [];
    protected AppStorage $_storage;

    public function __construct(array $data, AppStorage $storage)
    {
        $this->_data = $data;
        $this->_storage = $storage;
    }

    public function setData(array $data): void
    {
        $this->_data = array_merge($this->_data, $data);
    }

    public function __get($name)
    {
        $relations = $this->storage()->relations();
        if(isset($relations[$name])) {
            if(!isset($this->_nestedData[$name])) {
                $this->_storage->_bindRelation($this, $name, $relations[$name]);
            }
        }
        return $this->_nestedData[$name] ?? $this->_data[$name] ?? null;
    }

    public function __set($name, $value)
    {
        $relations = $this->storage()->relations();
        if(isset($relations[$name])) {
            $this->_nestedData[$name] = $value;
        } else {
            $this->_data[$name] = $value;
        }
    }

    public function toArray(): array
    {
        $data = $this->_data;
        $relations = array_keys($this->storage()->relations());
        foreach ($relations as $relName) {
            $data[$relName] = $this->$relName->toArray();
        }

        return $data;
    }

    public function storage(): AppStorage
    {
        return $this->_storage;
    }

    public function save(): bool
    {
        $isNew = empty($this->_data['id']);
        $result = $this->_storage->save($this->_data);

        if($isNew && $result) {
            $this->setData($this->_storage->_getDataById($result));
            $this->_storage->_bindRelations($this);
        }

        return !!$result;
    }
}