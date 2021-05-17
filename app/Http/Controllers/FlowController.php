<?php

namespace App\Http\Controllers;

use App\Flow\Flow;

class FlowController extends Controller
{
    public function test()
    {
        $arguments = [
            'user_id' => 15400,
            'diet_type_id' => 1,
            'bmi' => 21,
            'sick_ids' => [],
            'prohibited_sick_ids' => [15,18,19]
        ];
        $response = Flow::getNextState($arguments, false);
        if( !empty($response['error'])) { return $response['error']; }
        else { return $response['next']; }
    }

}
