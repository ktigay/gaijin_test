<?php

namespace App\Models;

class Users extends AppStorage
{
    protected string $_tableName = 'users';

    public static function storage($modelName = 'App\Models\User'): static
    {
        return new static($modelName);
    }
}