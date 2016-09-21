<?php

namespace Omnipay\Alipay\Requests;

use Omnipay\Alipay\Message\QueryOrderStatusResponse;
use Omnipay\Common\Message\ResponseInterface;

/**
 * Class QueryOrderStatusRequest
 * @package Omnipay\Alipay\Requests
 */
class QueryOrderStatusRequest extends Request
{

    protected $service = 'single_trade_query';


    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate(
            'partner',
            '_input_charset'
        );

        $this->validateOne(
            'trade_no',
            'out_trade_no'
        );

        $data = array (
            'service'        => $this->service,
            'partner'        => $this->getPartner(),
            'trade_no'       => $this->getTradeNo(),
            'out_trade_no'   => $this->getOutTradeNo(),
            '_input_charset' => $this->getInputCharset()
        );

        return $data;
    }


    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $url = sprintf('%s?%s', $this->getEndpoint(), http_build_query($this->getData()));

        $result = $this->httpClient->get($url)->send()->getBody();

        $xml  = simplexml_load_string($result);
        $json = json_encode($xml);
        $data = json_decode($json, true);

        return $this->response = new QueryOrderStatusResponse($this, $data);
    }


    /**
     * @return mixed
     */
    public function getTradeNo()
    {
        return $this->getParameter('trade_no');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setTradeNo($value)
    {
        return $this->setParameter('trade_no', $value);
    }


    /**
     * @return mixed
     */
    public function getOutTradeNo()
    {
        return $this->getParameter('out_trade_no');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setOutTradeNo($value)
    {
        return $this->setParameter('out_trade_no', $value);
    }
}