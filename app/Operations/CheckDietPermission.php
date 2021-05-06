<?php

namespace App\Operations;

use App\Services\Operation;

class CheckDietPermission extends Operation
{
    public function getNextState(array $arguments): array
    {
        if( ! (
            isset($arguments['user_id']) and
            isset($arguments['diet_type_id']) and
            isset($arguments['bmi'])
        )){
            return ['next' => '', 'error' => 'Error: not Passed arguments!'];
        }
        $userId = $arguments['user_id'];
        $dietTypeId = $arguments['diet_type_id'];
        $Bmi = $arguments['bmi'];

        if($dietTypeId == 0 and $Bmi < 20) {
            return ['next' => '/alert', 'error' => ''];
        }
        if($dietTypeId == 1 and $Bmi > 30) {
            return ['next' => 'alert2', '' => ''];
        }
        return ['next' => '', 'error' => ''];
    }
}
