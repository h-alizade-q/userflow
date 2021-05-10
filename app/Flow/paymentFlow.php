<?php

namespace App\Flow;

use App\Operations\CheckCardPaymentStatus;
use App\Operations\CheckPaymentType;

class paymentFlow extends generalFlowClass
{

    public function __construct()
    {
        $this->setName('payment');

        $checkPaymentType= new CheckPaymentType();
        $checkCardPaymentStatus = new CheckCardPaymentStatus();

        $flow = [
            '/bill'=>[
                $checkPaymentType],
            '/card'=>[],
            '/card/wait'=>[
                $checkCardPaymentStatus],
            '/card/confirm'=>[
                '%goForNextStep'],
            '/online/success',
            '/online/fail',
            '/card/reject'=>[
                '%endPaymentProcess'],
        ];

        $this->setFlow($flow);
    }
}
