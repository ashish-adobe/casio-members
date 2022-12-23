<?php
/**
 * Copyright © CO-WELL ASIA CO.,LTD.
 * See COPYING.txt for license details.
 */
namespace Casio\CasioMembers\Controller\Entry;

use Casio\CasioIdAuth\Helper\Data;
use Casio\CasioIdAuth\Model\CasioId\Client;
use Casio\CasioIdAuth\Plugin\Magento\Customer\Model\UrlPlugin;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\Url;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Url\EncoderInterface;

class Index extends Action
{
    /**
     * @var \Casio\CasioIdAuth\Model\Session
     */
    protected \Casio\CasioIdAuth\Model\Session $_casioSession;

    /**
     * @var Client
     */
    protected Client $_clientCasioId;

    /**
     * @var Session
     */
    protected Session $customerSession;

    /**
     * @var Data
     */
    protected Data $helperData;

    /**
     * @var EncoderInterface
     */
    protected EncoderInterface $urlEncoder;

    /**
     * @var UrlPlugin
     */
    protected UrlPlugin $urlPlugin;

    /**
     * Index constructor.
     * @param Context $context
     * @param \Casio\CasioIdAuth\Model\Session $casioSession
     * @param Client $clientCasioId
     * @param Session $customerSession
     * @param Data $helperData
     * @param EncoderInterface $urlEncoder
     * @param UrlPlugin $urlPlugin
     */
    public function __construct(
        Context $context,
        \Casio\CasioIdAuth\Model\Session $casioSession,
        Client $clientCasioId,
        Session $customerSession,
        Data $helperData,
        EncoderInterface $urlEncoder,
        UrlPlugin $urlPlugin
    ) {
        $this->_casioSession = $casioSession;
        $this->_clientCasioId = $clientCasioId;
        $this->customerSession = $customerSession;
        $this->helperData = $helperData;
        $this->urlEncoder = $urlEncoder;
        $this->urlPlugin = $urlPlugin;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $redirectUri = $this->getRequest()->getParam('redirect_uri');
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if ($this->helperData->isCasioAuthMethod()) {
            if ($redirectUri) {
                $this->_casioSession->setUrl($redirectUri);
            } else {
                $url = $this->_casioSession->getUrl();
                $redirectUri = !empty($url) ? $url : $this->_url->getBaseUrl();
            }
            if (!$this->customerSession->isLoggedIn()) {
                $this->urlPlugin->setUrl('register');
                $redirectUri = $this->_clientCasioId->createAuthUrl('create');
            }
        } else {
            if ($redirectUri) {
                $redirectUri = $this->urlEncoder->encode($redirectUri);
                $redirectUri = $this->_url->getUrl(
                    'customer/account/create',
                    [Url::REFERER_QUERY_PARAM_NAME => $redirectUri]
                );
            } else {
                $redirectUri = $this->_url->getUrl('customer/account/create');
            }
        }

        $redirect->setUrl($redirectUri);
        return $redirect;
    }
}
