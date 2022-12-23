<?php
/**
 * Copyright © CO-WELL ASIA CO.,LTD.
 * See COPYING.txt for license details.
 */

namespace Casio\CasioMembers\Controller;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;

abstract class MyPageAbstractClass extends Action
{
    /**
     * Customer session
     *
     * @var Session
     */
    protected Session $customerSession;

    /**
     * MyPageAbstractClass constructor.
     * @param Context $context
     * @param Session $customerSession
     */
    public function __construct(
        Context  $context,
        Session $customerSession
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
    }

    /**
     * Check customer authentication for some actions
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function dispatch(RequestInterface $request)
    {
        if (!$this->customerSession->authenticate()) {
            $this->_actionFlag->set('', 'no-dispatch', true);
        }

        return parent::dispatch($request);
    }
}
