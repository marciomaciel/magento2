<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\CatalogGraphQl\DataProvider\UrlRewrite;

use Magento\Catalog\Model\CategoryRepository;
use Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\CategoryTree as CategoryTreeDataProvider;
use Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\ExtractDataFromCategoryTree;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\UrlRewriteGraphQl\Model\DataProvider\EntityDataProviderInterface;

class CatalogTreeDataProvider implements EntityDataProviderInterface
{
    const CATEGORY = 'CATEGORY';
    /**
     * @var ExtractDataFromCategoryTree
     */
    private $extractDataFromCategoryTree;

    /**
     * @var CategoryTreeDataProvider
     */
    private $categoryTree;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @param CategoryTreeDataProvider $categoryTree
     * @param ExtractDataFromCategoryTree $extractDataFromCategoryTree
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        CategoryTreeDataProvider $categoryTree,
        ExtractDataFromCategoryTree $extractDataFromCategoryTree,
        CategoryRepository $categoryRepository
    ) {
        $this->categoryTree = $categoryTree;
        $this->extractDataFromCategoryTree = $extractDataFromCategoryTree;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get catalog tree data
     *
     * @param string $entity_type
     * @param int $id
     * @param ResolveInfo|null $info
     * @return array
     * @throws GraphQlNoSuchEntityException
     */
    public function getData(
        string $entity_type,
        int $id,
        ResolveInfo $info = null
    ): array {
        $categoryId = (int)$id;
        $categoriesTree = $this->categoryTree->getTree($info, $categoryId);
        if (empty($categoriesTree) || ($categoriesTree->count() == 0)) {
            throw new GraphQlNoSuchEntityException(__('Category doesn\'t exist'));
        }
        $result = current($this->extractDataFromCategoryTree->execute($categoriesTree));
        $result['type_id'] = $entity_type;
        return $result;
    }
}
