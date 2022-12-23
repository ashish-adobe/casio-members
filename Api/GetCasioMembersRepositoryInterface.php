<?php
/**
 * Copyright © CO-WELL ASIA CO.,LTD.
 * See COPYING.txt for license details.
 */
namespace Casio\CasioMembers\Api;

interface GetCasioMembersRepositoryInterface
{
    /**
     * @param int $customerId
     * @param string $selectLang
     * @param int $limit
     * @param int $offset
     * @param int $order
     * @return mixed
     */
    public function get($customerId, $selectLang, $limit, $offset, $order);
}
