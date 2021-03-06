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

namespace WellCommerce\Bundle\StandardEditionBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use WellCommerce\Bundle\CatalogBundle\Entity\Unit;
use WellCommerce\Bundle\StandardEditionBundle\DataFixtures\AbstractDataFixture;

/**
 * Class LoadUnitData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadUnitData extends AbstractDataFixture
{
    public static $samples = ['pcs.', 'set'];
    
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        foreach (self::$samples as $name) {
            $unit = new Unit();
            foreach ($this->getLocales() as $locale) {
                $unit->translate($locale->getCode())->setName($name);
            }
            $unit->mergeNewTranslations();
            $manager->persist($unit);
            $this->setReference('unit_' . $name, $unit);
        }
        
        $manager->flush();
    }
}
