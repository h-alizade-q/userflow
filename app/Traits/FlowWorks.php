<?php

nameSpace App\Traits;
use App\Operations\CheckCardPaymentStatus;
use App\Operations\CheckDietPermission;
use App\Operations\CheckPaymentType;
use App\Operations\CheckSicknessStatus;
use App\Services\Flow;

trait FlowWorks
{
    protected $reg;
    protected $payment;

    public function __construct()
    {
        $checkDietPermission = new CheckDietPermission();
        $checkSicknessStatus = new CheckSicknessStatus();
        $checkPaymentType= new CheckPaymentType();
        $checkCardPaymentStatus = new CheckCardPaymentStatus();

        $this->reg = new Flow('reg', [
            '/diet/type',
            '/size',
            '/report'=>[],
            '/sick/select',
            '/package'=>[
                $checkDietPermission,
                $checkSicknessStatus,
                ],
        ]);

        $this->payment = new Flow('payment', [
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
        ]);

        $this->reg->addAccessory($this->payment);
    }
}
