<?php

namespace Plaramart\EpayPayments\Interfaces;

interface EpayRequestContract
{
    public function getRequestDataFormatted (): string;

    public function getMethod (): string;

    public function getAction (): string;
}