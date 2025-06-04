<?php

namespace App\Http\Controllers;

  use Illuminate\Http\Request;
use App\Http\Controllers\AbstractDatabaseOperation;

class ExecProcedure extends AbstractDatabaseOperation
{
  public readonly string $procedureName;
  public readonly array $paramsRequest;
  public readonly array $paramsProcedure;
  public readonly Request $request;

  public function __construct(string $procedure, array $paramsRequest, array $paramsProcedure)
  {
    $this->procedureName = $procedure;
    $this->paramsRequest = $paramsRequest;
    $this->paramsProcedure = $paramsProcedure;
    $this->request = request();
  }

  protected function getRequest(): Request
  {
    return $this->request;
  }

  protected function getProcedureName(): string
  {
    return $this->procedureName;
  }

  protected function getParamsRequest(): array
  {
    return $this->paramsRequest;
  }

  protected function getParamsProcedure(): array {
    return $this->paramsProcedure;
  }


}
