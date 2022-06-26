# About

Epay.bg card payments and Easypay code payments PHP implementation. Soon will provide laravel package.

NOTE: Having merchant account **does not** mean you can access the demo system. You need another account associated with
demo system. Eventually I will refactor and add multiple clients inside the wrapper.

NOTE:

1. Send request to register your profile as merchant
2. Client number = (КИН) inside your profile
3. Client secret = A secret phrase generated from Epay.bg (maybe it will come by email).

**If you dont receive email with your phrase, call them and force them to send you. ROFL**

TODO: I mailed them about "how to provide my webhook". Their docs explains about some mysterious webhook, which you
cannot provide. I'll explain when they reach back.

# Installation

To be uploaded soon

```bash
composer require plaramart/epay-payments
```

# Usage

## Initialization

Initialize without framework

```php 
    $client = new \Plaramart\EpayPayments\ClientWrapper(
        'client-number',
        'client-secret',
        'contact@merchant.com'
    );
```

Initialize using laravel framework, or you can install my laravel implementation (TODO: To be created)

```php
// Inside AppServiceProvider.php
$this->app->singleton(\Plaramart\EpayPayments\ClientWrapper::class, function () {    
    return new \Plaramart\EpayPayments\ClientWrapper(
        'client-number',
        'client-secret',
        'contact@merchant.com'
    );
});
```

## Generate Easypay Code

```php
$client->generateEasypayCode(
    amount: 113, 
    invoice: '000001', 
    expires: '28.06.2022'
);
```

# Contribution

Send PR for bug fixes or improvements. For contact zgdevv@gmail.com

# License

MIT