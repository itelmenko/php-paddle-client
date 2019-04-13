<?php

namespace Paddle;

class Client {

    /**
     * @var string
     */
    protected $vendorId;

    /**
     * @var string
     */
    protected $vendorAuthCode;

    protected $timeout = 30;
    protected $baseUrl = 'https://vendors.paddle.com/api/';
    protected $apiVersion = '2.0';

    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_POST = 'POST';


    public function setVendorId($id)
    {
        $this->vendorId = $id;
    }

    public function setVendorAuthCode($authCode)
    {
        $this->vendorAuthCode = $authCode;
    }

    /**
     * Make a http call to Paddle API and return response
     * @param string $path
     * @param string $method
     * @param array $parameters
     * @return array
     * @throws \Exception
     */
    protected function httpCall($path, $method, $parameters = []) {
        if (!$this->vendorId) {
            throw new Exception\ArgumentException("VendorID not defined");
        }
        if (!$this->vendorAuthCode) {
            throw new Exception\ArgumentException("VendorAuthCode not defined");
        }

        $parameters['vendor_id'] = $this->vendorId;
        $parameters['vendor_auth_code'] = $this->vendorAuthCode;

        $method = strtoupper($method);
        $client = new \GuzzleHttp\Client([
            'base_uri' => $this->baseUrl.$this->apiVersion.'/',
            'timeout' => $this->timeout
        ]);

        if($method==self::HTTP_METHOD_GET) {
            $response = $client->request(self::HTTP_METHOD_GET, $path, [
                'query' => $parameters
            ]);
        } elseif ($method==self::HTTP_METHOD_POST) {
            $response = $client->request(self::HTTP_METHOD_POST, $path, [
                'form_params' => $parameters
            ]);
        } else {
            throw new Exception\ArgumentException("Incorrect HTTP method");
        }

        $code = $response->getStatusCode();
        $reason = $response->getReasonPhrase();
        if($code != 200) {
            throw new Exception\ConnectionException($reason, $code);
        }
        $body = $response->getBody();
        $apiResult = json_decode($body);

        if (!is_object($apiResult)) {
            throw new Exception\ConnectionException("Wrong structure of response");
        }
        if(!$apiResult->success) {
            throw new Exception\PaddleException($apiResult->error->message, $apiResult->error->code);
        }
        return $apiResult->response;
    }

        /**
     * @inheritdoc
     */
    public function createPaymentUrl(Invoice $invoice)
    {
        $data = $invoice->getData();
        $result = $this->httpCall('product/generate_pay_link', self::HTTP_METHOD_POST, $data);
        if(!isset($result->url)) {
            throw new Exception\PaddleException("URL not found");
        }
        return $result->url;
    }
}
