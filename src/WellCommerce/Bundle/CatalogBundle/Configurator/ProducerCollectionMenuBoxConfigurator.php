<?php
/**
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\CatalogBundle\Configurator;

use WellCommerce\Bundle\CatalogBundle\Controller\Box\ProducerCollectionMenuBoxController;
use \WellCommerce\Bundle\CoreBundle\Layout\Configurator\AbstractLayoutBoxConfigurator;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\FormBuilderInterface;

/**
 * Class ProducerCollectionMenuBoxConfigurator
 *
 * @author  Rafał Martonik <rafal@wellcommerce.org>
 */
final class ProducerCollectionMenuBoxConfigurator extends AbstractLayoutBoxConfigurator
{
    public function __construct(ProducerCollectionMenuBoxController $controller)
    {
        $this->controller = $controller;
    }
    
    public function getType(): string
    {
        return 'ProducerCollectionMenu';
    }
    
    /**
     * {@inheritdoc}
     */
    public function addFormFields(FormBuilderInterface $builder, FormInterface $form, $defaults)
    {
        $fieldset = $this->getFieldset($builder, $form);
        
        $fieldset->addChild($builder->getElement('tip', [
            'tip' => 'producer.box.info',
        ]));
    }
}
