## Paddle.com API Client PHP library

This library provides convenient way of querying Paddle API from php code.

## Requirements

PHP 7.2 or later.

## Installation via Composer

```sh
composer require itelmenko/php-paddle-client
```

## Usage

```php

$client = new \Paddle\Client();
$client->setVendorId(111);
$client->setVendorAuthCode('dlkegvke3klge3mg3...');
$price = new \Paddle\Price($order->amount, new \Paddle\Currency('USD'));
$invoice = new \Paddle\Invoice();

$invoice->addPrice(5.95)
    ->setPassthrough('order-id-2464')
    ->setReturnUrl('https://yourdomain.com/return')
    ->setQuantity(1, FALSE)
    ->setExpires(time())
    ->setCustomerEmail('user@mail.com');

$invoice->setProductId(111111);

$url = $client->createPaymentUrl($invoice);

```
