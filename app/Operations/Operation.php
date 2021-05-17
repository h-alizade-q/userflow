<?php

namespace App\Operations;

abstract class Operation
{
    abstract public function getNextState(array $arguments) : array;
}
