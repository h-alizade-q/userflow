<?php

namespace App\Operations;

use App\Services\Operation;

class CheckPaymentType extends Operation
{
    public function getNextState(array $arguments) : array
    {
        return ['next' => '', 'error' => ''];
    }

}
