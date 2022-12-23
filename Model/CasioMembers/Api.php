<?php
/**
 * Copyright © CO-WELL ASIA CO.,LTD.
 * See COPYING.txt for license details.
 */

namespace Casio\CasioMembers\Model\CasioMembers;

use Casio\CasioMembers\Helper\Data;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\ZendClientFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Zend_Http_Client;
use Zend_Http_Client_Exception;

/**
 * Class Api
 * Handle to get casio member product from api
 */
class Api
{
    /**
     * API URL path
     */
    const XML_PATH_API_URL = 'my_account/casio_members/api_url';

    /**
     * @var ZendClientFactory
     */
    protected ZendClientFactory $httpClient;

    /**
     * @var Json
     */
    protected Json $json;

    /**
     * @var Data
     */
    protected Data $data;

    /**
     * Api constructor.
     * @param ZendClientFactory $httpClient
     * @param Json $json
     * @param Data $data
     */
    public function __construct(
        ZendClientFactory $httpClient,
        Json $json,
        Data $data
    ) {
        $this->httpClient = $httpClient;
        $this->json = $json;
        $this->data = $data;
    }

    /**
     * @param $url
     * @param $method
     * @param array $params
     * @param array $headers
     * @return \Zend_Http_Response
     * @throws LocalizedException
     * @throws Zend_Http_Client_Exception
     */
    public function api($url, $method, $params = [], $headers = [])
    {
        /** @var \Magento\Framework\HTTP\ZendClient $apiCaller */
        $apiCaller = $this->httpClient->create();
        $apiCaller->setUri($url);
        $headers = array_merge([
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ], $headers);
        $apiCaller->setHeaders($headers);

        // Set proxy to connect
        if ($this->data->getProxy()) {
            $config = [
                'proxy' => $this->data->getProxy()
            ];
            $apiCaller->setConfig($config);
        }

        switch ($method) {
            case 'GET':
            case 'DELETE':
                $apiCaller->setParameterGet($params);
                break;
            case 'POST':
                $apiCaller->setParameterPost($params);
                break;
            default:
                throw new LocalizedException(
                    __('Required HTTP method is not supported.')
                );
        }
        $apiCaller->setMethod($method);

        return $apiCaller->request();
    }

    /**
     * @param $params
     * @return string|null
     * @throws LocalizedException
     * @throws Zend_Http_Client_Exception
     */
    public function getCasioMembersData($params)
    {
        $apiUrl = $this->data->getConfig(self::XML_PATH_API_URL);
        if (!$apiUrl) {
            return null;
        }

        $casioMembers = $this->api(
            $apiUrl,
            Zend_Http_Client::POST,
            $params
        );

        return $casioMembers::extractBody($casioMembers->getBody()) ?: $casioMembers->getBody();
    }
}
