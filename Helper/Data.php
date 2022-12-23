<?php
/**
 * Copyright © CO-WELL ASIA CO.,LTD.
 * See COPYING.txt for license details.
 */

namespace Casio\CasioMembers\Helper;

use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * Casio Member helper class
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const API_URL = 'my_account/casio_members/api_url';
    const BASE_URL = 'my_account/casio_members/base_url';
    const INTEGRATION_URL = 'my_account/casio_members/integration_url';
    const INTEGRATION_SUPPORT_URL = 'my_account/casio_members/integration_support_url';
    const PRODUCT_REGISTRATION_URL = 'my_account/casio_members/product_registration_url';
    const PROXY = 'my_account/casio_members/proxy';

    /**
     * Get config follow path
     * @param $path
     * @return mixed
     */
    public function getConfig($path)
    {
        return $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE,
        );
    }

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return rtrim($this->getConfig(self::API_URL), '/') . '/';
    }

    /**
     * @return string
     */
    public function getCasioMemberBaseUrl(): string
    {
        return rtrim($this->getConfig(self::BASE_URL), '/') . '/';
    }

    /**
     * @return string
     */
    public function getDataIntegrationUrl(): string
    {
        $path = ltrim($this->getConfig(self::INTEGRATION_URL), '/');
        return $this->getCasioMemberBaseUrl() . $path;
    }

    /**
     * @return string
     */
    public function getDataIntegrationSupportUrl(): string
    {
        $path = ltrim($this->getConfig(self::INTEGRATION_SUPPORT_URL), '/');
        return $this->getCasioMemberBaseUrl() . $path;
    }

    /**
     * @return string
     */
    public function getProductRegistrationUrl(): string
    {
        $path = ltrim($this->getConfig(self::PRODUCT_REGISTRATION_URL), '/');
        return $this->getCasioMemberBaseUrl() . $path;
    }

    /**
     * Get Proxy config
     *
     * @return string
     */
    public function getProxy(): string
    {
        return (string)$this->getConfig(
            self::PROXY
        );
    }
}
