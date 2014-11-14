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

namespace WellCommerce\Bundle\RoutingBundle\Repository;

use WellCommerce\Bundle\CoreBundle\Repository\RepositoryInterface;
use WellCommerce\Bundle\DataGridBundle\DataGrid\Repository\DataGridAwareRepositoryInterface;

/**
 * Interface RouteRepositoryInterface
 *
 * @package WellCommerce\Bundle\RoutingBundle\Repository
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 * @method \WellCommerce\Bundle\RoutingBundle\Entity\Route findOneByPath() findOneByPath($path) Find route by path
 */
interface RouteRepositoryInterface extends RepositoryInterface, DataGridAwareRepositoryInterface
{
}