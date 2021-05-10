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

    }
}
