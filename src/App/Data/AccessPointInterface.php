<?php

namespace App\Data;

interface AccessPointInterface
{
    public function __construct(array $params);

    public function execute(string $sql, array $params): mixed;
}