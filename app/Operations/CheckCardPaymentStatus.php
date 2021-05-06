<?php

namespace App\Operations;

use App\Services\Operation;

class CheckCardPaymentStatus extends Operation
{
    public function getNextState(array $arguments) : array
    {
        return ['next' => '', 'error' => ''];
    }
}
