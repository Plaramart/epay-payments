<?php

namespace Plaramart\EpayPayments\Requests;

abstract class EpayRequest
{

    protected string $action;
    protected string $method;

    public function getAction (): string
    {
        return $this->action;
    }

    public function getMethod (): string
    {
        return $this->method;
    }
}