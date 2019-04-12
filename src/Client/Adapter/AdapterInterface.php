<?php

namespace Paddle\Client\Adapter;

use Paddle\Client\RequestInterface;
use Paddle\Client\ResponseInterface;

/**
 * A client can be configured to use a specific adapter to make requests, by
 * default the CurlAdapter is what is used.
 */
interface AdapterInterface
{
    /**
     * Send request to BitPay
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function sendRequest(RequestInterface $request);
}
