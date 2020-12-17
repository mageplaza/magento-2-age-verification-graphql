<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_AgeVerificationGraphQl
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

declare(strict_types=1);

namespace Mageplaza\AgeVerificationGraphQl\Model\Resolver;

use Exception;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductRepository;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\AgeVerification\Api\Data\PageConfigInterface;
use Mageplaza\AgeVerification\Helper\Data;

/**
 * Class CheckNotify
 * @package Mageplaza\AgeVerificationGraphQl\Model\Resolver
 */
class CheckNotify implements ResolverInterface
{

    /**
     * @var Data
     */
    private $_helperData;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var GetCustomer
     */
    private $getCustomer;

    /**
     * CheckNotify constructor.
     *
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     * @param GetCustomer $getCustomer
     * @param Data $helperData
     */
    public function __construct(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        GetCustomer $getCustomer,
        Data $helperData
    ) {
        $this->_helperData        = $helperData;
        $this->productRepository  = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->getCustomer        = $getCustomer;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $object = $this->validateArgsPage($args, $context);
        if ($context->getExtensionAttributes()->getIsCustomer() !== false) {
            $customer = $this->getCustomer->execute($context);
            $groupId  = $customer->getGroupId();
        } else {
            $groupId = 0;
        }
        $storeId = $context->getExtensionAttributes()->getStore()->getId();

        if (!in_array(
            $groupId,
            explode(',', $this->_helperData->getConfigGeneral('customer_groups')),
            false
        )) {
            return false;
        }

        switch ($args['action']) {
            case 'product':
            case 'category':
                return $object->getExtensionAttributes()->getMpAgeVerification();
            case 'layout':
                return in_array(
                    $args['layoutId'],
                    $this->_helperData->getApplyFor($storeId),
                    true
                );
            case 'cms':
                return in_array(
                    $args['cmsKey'],
                    $this->_helperData->getApplyForCms($storeId),
                    true
                );
            case 'include':
                $includePaths = $this->_helperData->getIncludePages();

                return $this->checkPaths($includePaths, $args['includeUrl']);
            case 'exclude':
                $excludePaths = $this->_helperData->getExcludePages();

                return $this->checkPaths($excludePaths, $args['excludeUtl']);
        }

        return false;
    }

    /**
     * @param array $args
     * @param $context
     *
     * @return false|CategoryInterface|ProductInterface|Product
     * @throws GraphQlInputException
     */
    public function validateArgsPage(array $args, $context)
    {
        switch ($args['action']) {
            case 'product':
                if (isset($args['productSku'])) {
                    try {
                        return $this->productRepository->get($args['productSku']);
                    } catch (Exception $exception) {
                        throw new GraphQlInputException($exception->getMessage());
                    }
                }
                break;
            case 'layout':
                if (isset($args['layoutId']) && empty($args['layoutId'])) {
                    throw new GraphQlInputException(__('Layout id is not empty'));
                }
                break;
            case 'cms':
                if (isset($args['cmsKey']) && empty($args['cmsKey'])) {
                    throw new GraphQlInputException(__('Cms url key is not empty'));
                }
                break;
            case 'include':
                if (isset($args['includeUrl']) && empty($args['includeUrl'])) {
                    throw new GraphQlInputException(__('Include url key is not empty'));
                }
                break;
            case 'exclude':
                if (isset($args['excludeUtl']) && empty($args['excludeUtl'])) {
                    throw new GraphQlInputException(__('Exclude url key is not empty'));
                }
                break;
            case 'category':
                if (isset($args['categoryId'])) {
                    try {
                        return $this->categoryRepository->get(
                            $args['categoryId'],
                            $context->getExtensionAttributes()->getStore()->getId()
                        );
                    } catch (Exception $exception) {
                        throw new GraphQlInputException($exception->getMessage());
                    }
                }
                break;
            default:
                throw new GraphQlInputException(__('Please select 1 of the following actions: product, category, layout'));
        }

        return false;
    }

    /**
     * @param $pattern
     * @param $currentPath
     *
     * @return bool
     */
    public function checkRegularExpression($pattern, $currentPath)
    {
        $start = substr($pattern, 0, 1);
        $end   = substr($pattern, -1);
        $pos   = strpos($pattern, '\/');

        if ($pos === false) {
            $pattern = '/' . str_replace('/', '\/', substr($pattern, 1, -1)) . '/';
        }

        return $start === '/' && $end === '/' && preg_match($pattern, $currentPath);
    }

    /**
     * @param $paths
     * @param $currentPath
     *
     * @return bool
     */
    public function checkPaths($paths, $currentPath)
    {
        if ($paths) {
            $arrayPaths = explode("\n", $paths);
            $pathsUrl   = array_map('trim', $arrayPaths);

            foreach ($pathsUrl as $path) {
                if ($path &&
                    (strpos($currentPath, $path) !== false || $this->checkRegularExpression($path, $currentPath))
                ) {
                    return true;
                }
            }
        }

        return false;
    }
}
