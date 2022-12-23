<?php
/**
 * Copyright © CO-WELL ASIA CO.,LTD.
 * See COPYING.txt for license details.
 */

namespace Casio\CasioMembers\Block\Customer;

use Casio\CasioIdAuth\Model\CasioId\Client;
use Casio\CasioMembers\Helper\Data;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Block\Account\Dashboard;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Store\Model\StoreManagerInterface;
use Zend_Json;

/**
 * Class CasioMembers
 * Create block casio members page
 */
class CasioMembers extends Dashboard
{
    /**
     * Enable new product registrantion path
     */
    const XML_PATH_ENABLE_NEW_PRODUCT_REGISTRATION = 'my_account/casio_members/enable_new_product_registration';

    /**
     * API URL path
     */
    const XML_PATH_API_URL = 'my_account/casio_members/api_url';

    /**
     * Paging number path
     */
    const XML_PATH_PAGING_NUMBER = 'my_account/casio_members/paging_number';

    const API_SERVICE_URL = 'rest/%s/V1/casio-members/mine';

    /**
     * @var array|LayoutProcessorInterface[]
     */
    protected array $layoutProcessors;

    /**
     * @var Data
     */
    protected Data $helper;

    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * CasioMembers constructor.
     * @param Template\Context $context
     * @param Session $customerSession
     * @param SubscriberFactory $subscriberFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $customerAccountManagement
     * @param Data $helper
     * @param Client $client
     * @param StoreManagerInterface $storeManager
     * @param array $layoutProcessors
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        SubscriberFactory $subscriberFactory,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $customerAccountManagement,
        Data $helper,
        Client $client,
        StoreManagerInterface $storeManager,
        array $layoutProcessors = [],
        array $data = []
    ) {
        parent::__construct(
            $context,
            $customerSession,
            $subscriberFactory,
            $customerRepository,
            $customerAccountManagement,
            $data
        );
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout']) ? $data['jsLayout'] : [];
        $this->layoutProcessors = $layoutProcessors;
        $this->helper = $helper;
        $this->client = $client;
        $this->storeManager = $storeManager;
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getJsLayout()
    {
        foreach ($this->layoutProcessors as $processor) {
            $this->jsLayout = $processor->process($this->jsLayout);
        }
        $pagingNumber = $this->helper->getConfig(self::XML_PATH_PAGING_NUMBER);
        $customerId = $this->customerSession->getId();

        $config = [
            'isEnableNewProductRegistration' => (boolean)$this->helper->getConfig(self::XML_PATH_ENABLE_NEW_PRODUCT_REGISTRATION),
            'serviceUrl' => sprintf(self::API_SERVICE_URL, $this->storeManager->getStore()->getCode()),
            'customerId' => $customerId,
            'dataIntegrationUrl' => $this->helper->getDataIntegrationUrl(),
            'productRegistrationUrl' => $this->helper->getProductRegistrationUrl(),
            'defaultShow' => $pagingNumber,
            'numberProductNeedShow' => $pagingNumber,
            'selectLang' => $this->client->getLanguage()
        ];

        $this->jsLayout['components']['casiomembers'] = array_merge($this->jsLayout['components']['casiomembers'], $config);

        return Zend_Json::encode($this->jsLayout);
    }
}
