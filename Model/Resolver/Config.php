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

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\AgeVerification\Helper\Data;

/**
 * Class Config
 * @package Mageplaza\AgeVerificationGraphQl\Model\Resolver
 */
class Config implements ResolverInterface
{

    /**
     * @var Data
     */
    private $_helperData;

    /**
     * Config constructor.
     *
     * @param Data $helperData
     */
    public function __construct(
        Data $helperData
    ) {
        $this->_helperData = $helperData;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $config = $this->_helperData->getConfigValue(
            Data::CONFIG_MODULE_PATH,
            $context->getExtensionAttributes()->getStore()->getId()
        );

        if (!empty($config['purchase_verify']['image'])) {
            $config['purchase_verify']['image'] = $this->_helperData->getImageNoticeUrl();
        }

        if (!empty($config['design']['image'])) {
            $config['design']['image'] = $this->_helperData->getLogoPopupUrl();
        }

        return $config;
    }
}
