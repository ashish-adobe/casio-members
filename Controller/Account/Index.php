<?php
/**
 * Copyright © CO-WELL ASIA CO.,LTD.
 * See COPYING.txt for license details.
 */
namespace Casio\CasioMembers\Controller\Account;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Customer\Controller\Account\Index
{
    /**
     * @var ForwardFactory
     */
    private ForwardFactory $resultForwardFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param ForwardFactory $resultForwardFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory
    ) {
        parent::__construct($context, $resultPageFactory);
        $this->resultForwardFactory = $resultForwardFactory;
    }

    /**
     * Default customer account page
     *
     * @return \Magento\Framework\Controller\Result\Forward|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('mypage');
    }
}
