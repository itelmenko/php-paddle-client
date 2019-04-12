<?php

namespace Paddle\Client;

use Paddle\Invoice;

/**
 * Sends request(s) to Paddle server
 *
 */
interface ClientInterface
{

    const NAME    = 'Paddle PHP-Client';
    const VERSION = '2.0';

    /**
     * @param Invoice $invoiceId
     * @return string
     * @throws \Exception
     */
    public function createPaymentUrl(Invoice $invoice);

}
