<?php

namespace Plaramart\EpayPayments\Requests;

use Plaramart\EpayPayments\Interfaces\EpayRequestContract;

class EpayEasypayCodeRequest extends EpayRequest implements EpayRequestContract
{

    private string $price;
    private string $invoice;
    private string $expire;
    private string $description;

    public function __construct (string $price, string $invoice, string $expire, string $description = 'Payment')
    {
        $this->action = '/ezp/reg_bill.cgi';
        $this->method = 'GET';

        $this->price = $price;
        $this->invoice = $invoice;
        $this->expire = $expire;
        $this->description = $description;
    }

    public function getRequestDataFormatted (): string
    {
        $data = <<<DATA
        INVOICE={$this->invoice}
        AMOUNT={$this->price}
        EXP_TIME={$this->expire}
        DESCR={$this->description}
        DATA;

        return $data;
    }
}