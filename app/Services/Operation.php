<?php

namespace App\Services;

abstract class Operation
{
    abstract public function getNextState(array $arguments) : array;
}
