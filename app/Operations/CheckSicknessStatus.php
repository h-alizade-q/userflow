<?php

namespace App\Operations;

use App\Operations\Operation;

class CheckSicknessStatus extends Operation
{
    public function getNextState(array $arguments): array
    {
        if( ! (
            isset($arguments['user_id']) and
            isset($arguments['sick_ids']) and
            isset($arguments['prohibited_sick_ids'])
        )){
            return ['next' => '', 'error' => 'Error: not Passed arguments!'];
        }

        $userId = $arguments['user_id'];
        $sickIds = $arguments['sick_ids'];
        $prohibitedSickIds = $arguments['prohibited_sick_ids'];

        foreach($sickIds as $id) {
            if (in_array($id, $prohibitedSickIds)) {
                return ['next' => 'alert', 'error' => ''];
            }
        }
        return ['next' => '', 'error' => ''];
    }
}
