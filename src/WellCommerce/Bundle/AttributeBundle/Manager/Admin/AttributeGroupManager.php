<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\AttributeBundle\Manager\Admin;

use WellCommerce\Bundle\CoreBundle\Manager\Admin\AbstractAdminManager;

/**
 * Class AttributeGroupManager
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AttributeGroupManager extends AbstractAdminManager
{
    /**
     * @var \WellCommerce\Bundle\AttributeBundle\Repository\AttributeGroupRepositoryInterface
     */
    protected $repository;

    public function getAttributeGroupSets() : array
    {
        return $this->repository->getAttributeGroupSet();
    }
}