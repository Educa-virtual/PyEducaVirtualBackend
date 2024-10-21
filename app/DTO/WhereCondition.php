<?php

namespace App\DTO;

class WhereCondition
{
    public string $COLUMN_NAME;
    public mixed $VALUE;

    public function __construct(string $columnName, mixed $value)
    {
        $this->COLUMN_NAME = $columnName;
        $this->VALUE = $value;
    }
}
