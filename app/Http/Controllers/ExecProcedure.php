<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AbstractDatabaseOperation;

class ExecProcedure extends AbstractDatabaseOperation
{
  public readonly string $procedureName;
  public readonly array $params;

  public function __construct(string $procedure, array $params)
  {
    $this->procedureName = $procedure;
    $this->params = $params;

  }

  protected function getProcedureName(): string
  {
    return $this->procedureName;
  }

  protected function getParams(): array
  {
    return $this->params;
  }
}
