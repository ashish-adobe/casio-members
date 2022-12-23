<?php
/**
 * Copyright © CO-WELL ASIA CO.,LTD.
 * See COPYING.txt for license details.
 */

namespace Casio\CasioMembers\Model;

use Casio\CasioMembers\Api\GetCasioMembersRepositoryInterface;
use Casio\CasioMembers\Model\CasioMembers\Api;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Zend_Http_Client_Exception;
use Magento\Customer\Model\CustomerFactory;

class GetCasioMembersRepository implements GetCasioMembersRepositoryInterface
{
    /**
     * @var Api
     */
    protected Api $api;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var CustomerFactory
     */
    protected CustomerFactory $customerFactory;

    /**
     * GetCasioMembersRepository constructor.
     * @param Api $api
     * @param CustomerFactory $customerFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        Api $api,
        CustomerFactory $customerFactory,
        LoggerInterface $logger
    ) {
        $this->api = $api;
        $this->customerFactory = $customerFactory;
        $this->logger = $logger;
    }

    /**
     * @param int $customerId
     * @param string $selectLang
     * @param int $limit
     * @param int $offset
     * @param int $order
     * @return mixed|string|null
     */
    public function get($customerId, $selectLang, $limit, $offset, $order)
    {
        $customer = $this->customerFactory->create()->load($customerId);

        $params = [
            'access_token' => $customer->getCasioAccessToken(),
            'select_lang' => $selectLang,
            'limit' => $limit,
            'offset' => $offset,
            'order' => $order
        ];

        try {
            return $this->api->getCasioMembersData($params);
        } catch (LocalizedException | Zend_Http_Client_Exception $e) {
            $this->logger->critical('Casio Member Api fail. ' . $e->getTraceAsString());
        }

        return false;
    }
}
