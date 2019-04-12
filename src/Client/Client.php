<?php

namespace Paddle\Client;

use Paddle\Client\Adapter\AdapterInterface;
use Paddle\Invoice;
use Paddle\RequestInterface;
use Paddle\ResponseInterface;


/**
 * Client used to send requests and receive responses for BitPay's Web API
 *
 * @package Bitpay
 */
class Client implements ClientInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var string
     */
    protected $vendorId;

    /**
     * @var string
     */
    protected $vendorAuthCode;


    public function setVendorId($id)
    {
        $this->vendorId = $id;
    }

    public function setVendorAuthCode($authCode)
    {
        $this->vendorAuthCode = $authCode;
    }

    /**
     * @param AdapterInterface $adapter
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @inheritdoc
     */
    public function createPaymentUrl(Invoice $invoice)
    {
        $request = $this->createNewRequest();
        $request->setMethod(Request::METHOD_POST);
        $request->setPath('invoices');

        $currency     = $invoice->getCurrency();
        $item         = $invoice->getItem();
        $buyer        = $invoice->getBuyer();
        $buyerAddress = $buyer->getAddress();

        $this->checkPriceAndCurrency($item->getPrice(), $currency->getCode());

        $body = array(
            'price'             => $item->getPrice(),
            'currency'          => $currency->getCode(),
            'posData'           => $invoice->getPosData(),
            'notificationURL'   => $invoice->getNotificationUrl(),
            'transactionSpeed'  => $invoice->getTransactionSpeed(),
            'fullNotifications' => $invoice->isFullNotifications(),
            'extendedNotifications' => $invoice->isExtendedNotifications(),
            'notificationEmail' => $invoice->getNotificationEmail(),
            'redirectURL'       => $invoice->getRedirectUrl(),
            'orderID'           => $invoice->getOrderId(),
            'itemDesc'          => $item->getDescription(),
            'itemCode'          => $item->getCode(),
            'physical'          => $item->isPhysical(),
            'buyerName'         => trim(sprintf('%s %s', $buyer->getFirstName(), $buyer->getLastName())),
            'buyerAddress1'     => isset($buyerAddress[0]) ? $buyerAddress[0] : '',
            'buyerAddress2'     => isset($buyerAddress[1]) ? $buyerAddress[1] : '',
            'buyerCity'         => $buyer->getCity(),
            'buyerState'        => $buyer->getState(),
            'buyerZip'          => $buyer->getZip(),
            'buyerCountry'      => $buyer->getCountry(),
            'buyerEmail'        => $buyer->getEmail(),
            'buyerPhone'        => $buyer->getPhone(),
            'buyerNotify'       => $buyer->getNotify(),
            'guid'              => Util::guid(),
            'nonce'             => Util::nonce(),
            'token'             => $this->token->getToken(),
        );

        $request->setBody(json_encode($body));
        $this->addIdentityHeader($request);
        $this->addSignatureHeader($request);
        $this->request  = $request;
        $this->response = $this->sendRequest($request);

        $body = json_decode($this->response->getBody(), true);
        $error_message = false;
        $error_message = (!empty($body['error'])) ? $body['error'] : $error_message;
        $error_message = (!empty($body['errors'])) ? $body['errors'] : $error_message;
        $error_message = (is_array($error_message)) ? implode("\n", $error_message) : $error_message;
        if (false !== $error_message) {
            throw new \Bitpay\Client\BitpayException($error_message);
        }
        $data = $body['data'];

        $invoice = $this->fillInvoiceData($invoice, $data);

        return $invoice;
    }

    /**
     * Returns the Response object that BitPay returned from the request that
     * was sent
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Returns the request object that was sent to BitPay
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function sendRequest(RequestInterface $request)
    {
        if (null === $this->adapter) {
            // Uses the default adapter
            $this->adapter = new Adapter\CurlAdapter();
        }

        return $this->adapter->sendRequest($request);
    }

}
