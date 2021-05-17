<?php

namespace App\Operations;

use App\Operations\Operation;

class CheckCardPaymentStatus extends Operation
{
    public function getNextState(array $arguments) : array
    {
        return ['next' => '', 'error' => ''];
    }
}
