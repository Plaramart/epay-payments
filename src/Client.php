<?php

namespace Plaramart\EpayPayments;


use Plaramart\EpayPayments\Interfaces\EpayRequestContract;

class Client
{
    public bool    $demoMode;
    private string $merchant;
    private string $secret;
    private string $email;

    public function __construct (string $merchant, string $secret, string $email, $demoMode = FALSE)
    {
        $this->email = $email;
        $this->secret = $secret;
        $this->merchant = $merchant;
        $this->demoMode = $demoMode;
    }

    public function execute (EpayRequestContract $request)
    {
        $data = $this->prefixAndEncrypt($request->getRequestDataFormatted());

        return $this->{$request->getMethod()}($request->getAction(), $data);
    }

    public function prefixAndEncrypt (string $data): array
    {
        $data = <<<DATA
        MIN={$this->merchant}
        EMAIL={$this->email}
        $data
        DATA;
        $ENCODED = base64_encode($data);
        $CHECKSUM = $this->hmac('sha1', $ENCODED, $this->secret); # XXX SHA-1 algorithm REQUIRED

        return [
            'PAGE'     => 'paylogin',
            'ENCODED'  => $ENCODED,
            'CHECKSUM' => $CHECKSUM,
        ];
    }

    public function hmac ($algo, $data, $passwd)
    {
        /* md5 and sha1 only */
        $algo = strtolower($algo);
        $p = ['md5' => 'H32', 'sha1' => 'H40'];
        if (strlen($passwd) > 64) $passwd = pack($p[$algo], $algo($passwd));
        if (strlen($passwd) < 64) $passwd = str_pad($passwd, 64, chr(0));

        $ipad = substr($passwd, 0, 64) ^ str_repeat(chr(0x36), 64);
        $opad = substr($passwd, 0, 64) ^ str_repeat(chr(0x5C), 64);

        return ($algo($opad . pack($p[$algo], $algo($ipad . $data))));
    }

    public function get ($target, $data)
    {
        $epay_ch = curl_init();
        curl_setopt_array($epay_ch, [
            CURLOPT_URL            => $this->getBaseURL() . $target . '?' . http_build_query($data),
            CURLOPT_RETURNTRANSFER => TRUE,
        ]);

        $response = curl_exec($epay_ch);
        $err = curl_error($epay_ch);
        curl_close($epay_ch);


        return $response;
    }

    public function getBaseURL ()
    {
        if ($this->demoMode) {
            return 'https://demo.epay.bg';
        }

        return 'https://epay.bg';
    }

    public function post ($target, $data)
    {
        $epay_ch = curl_init();
        curl_setopt_array($epay_ch, [
            CURLOPT_URL            => $this->getBaseURL() . $target,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 2,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_HTTPHEADER     => [
                "Content-Type: application/json",
                "cache-control: no-cache",
            ],
        ]);

        $response = curl_exec($epay_ch);
        $err = curl_error($epay_ch);
        curl_close($epay_ch);

        return $response;
    }
}