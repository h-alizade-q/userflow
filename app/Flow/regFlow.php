<?php

namespace App\Flow;

use App\Operations\CheckDietPermission;
use App\Operations\CheckSicknessStatus;

class regFlow extends generalFlowClass
{

    public function __construct()
    {
        $this->setName('reg');

        $checkDietPermission = new CheckDietPermission();
        $checkSicknessStatus = new CheckSicknessStatus();

        $flow = [
            '/diet/type',
            '/size',
            '/report'=>[],
            '/sick/select',
            '/package'=>[
                $checkDietPermission,
                $checkSicknessStatus,
            ],
            '/asdas'=>[
                sdf
            ]

        ];

        $this->setFlow($flow);

        $payment = new paymentFlow();
        $this->addAccessory($payment,'/reg/report');
    }
}
