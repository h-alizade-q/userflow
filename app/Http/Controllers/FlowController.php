<?php

namespace App\Http\Controllers;

use App\Traits\FlowWorks;
use Illuminate\Http\Request;

class FlowController extends Controller
{
    use flowWorks;
    public function test(request $request)
    {
        $arguments = [
            'user_id' => 15400,
            'diet_type_id' => 1,
            'bmi' => 21,
            'sick_ids' => [],
            'prohibited_sick_ids' => [15,18,19]
        ];
        $response = $this->reg->getNextState($request['flow'], $arguments);
        if( !empty($response['error'])) { return $response['error']; }
        else { return $response['next']; }
    }

}
