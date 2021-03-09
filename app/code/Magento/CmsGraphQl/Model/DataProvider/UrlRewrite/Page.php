<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\CmsGraphQl\Model\DataProvider\UrlRewrite;

use Magento\CmsGraphQl\Model\Resolver\DataProvider\Page as PageDataProvider;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\UrlRewriteGraphQl\Model\DataProvider\EntityDataProviderInterface;

class Page implements EntityDataProviderInterface
{
    /**
     * @var PageDataProvider
     */
    private $pageDataProvider;

    /**
     * Route constructor.
     * @param PageDataProvider $pageDataProvider
     */
    public function __construct(
        PageDataProvider $pageDataProvider
    ) {
        $this->pageDataProvider = $pageDataProvider;
    }

    /**
     * Get Page data
     *
     * @param string $entity_type
     * @param int $id
     * @param ResolveInfo|null $info
     * @return array
     * @throws NoSuchEntityException
     */
    public function getData(string $entity_type, int $id, ResolveInfo $info = null): array
    {
        $result = $this->pageDataProvider->getDataByPageId((int)$id);
        $result['type_id'] = $entity_type;
        return $result;
    }
}
