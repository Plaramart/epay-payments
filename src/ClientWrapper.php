<?php

namespace Plaramart\EpayPayments;

use Plaramart\EpayPayments\Requests\EpayEasypayCodeRequest;

class ClientWrapper
{

    public Client $client;

    public function __construct (string $merchant, string $secret, string $email, bool $demoMode = FALSE)
    {
        $this->client = new Client($merchant, $secret, $email, $demoMode);
    }

    public function generateEasypayCode (string $price, string $invoice, string $expire, string $description = 'Payment')
    {
        $request = new EpayEasypayCodeRequest(
            $price,
            $invoice,
            $expire,
            $description
        );

        return $this->client->execute($request);
    }
}