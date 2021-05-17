<?php

namespace App\Operations;

use App\Operations\Operation;

class CheckPaymentType extends Operation
{
    public function getNextState(array $arguments) : array
    {
        return ['next' => '', 'error' => ''];
    }

}
