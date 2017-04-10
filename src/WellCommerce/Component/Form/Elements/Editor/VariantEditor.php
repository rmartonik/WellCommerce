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

namespace WellCommerce\Component\Form\Elements\Editor;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WellCommerce\Component\Form\Elements\AbstractField;
use WellCommerce\Component\Form\Elements\Attribute;
use WellCommerce\Component\Form\Elements\AttributeCollection;
use WellCommerce\Component\Form\Elements\ElementInterface;

/**
 * Class VariantEditor
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class VariantEditor extends AbstractField implements ElementInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        
        $resolver->setRequired([
            'set',
            'attribute_group_field',
            'category_field',
            'photo_field',
            'vat_values',
            'allow_generate',
            'suffixes',
            'get_groups_route',
            'get_photos_route',
            'get_attributes_route',
        ]);
        
        $resolver->setDefaults([
            'set'                         => null,
            'allow_generate'              => true,
            'suffixes'                    => ['+', '-', '%', '='],
            'availability'                => [],
            'get_groups_route'            => 'admin.attribute_group.ajax.index',
            'get_photos_route'            => 'admin.media.grid',
            'get_attributes_route'        => 'admin.attribute.ajax.index',
            'get_attributes_values_route' => 'admin.attribute_value.ajax.index',
            'add_attribute_route'         => 'admin.attribute.ajax.add',
            'add_attribute_value_route'   => 'admin.attribute_value.ajax.add',
            'generate_cartesian_route'    => 'admin.attribute.ajax.generate',
        ]);
        
        $resolver->setAllowedTypes('allow_generate', 'bool');
        $resolver->setAllowedTypes('attribute_group_field', ElementInterface::class);
        $resolver->setAllowedTypes('vat_values', 'array');
        $resolver->setAllowedTypes('availability', 'array');
        $resolver->setAllowedTypes('category_field', ElementInterface::class);
        
        $fieldNormalizer = function (Options $options, $value) {
            if (!$value instanceof ElementInterface) {
                throw new \InvalidArgumentException('Passed field should implement "ElementInterface" and have accessible "getName" method.');
            }
            
            return $value->getName();
        };
        
        $resolver->setNormalizer('attribute_group_field', $fieldNormalizer);
        $resolver->setNormalizer('category_field', $fieldNormalizer);
        $resolver->setNormalizer('photo_field', $fieldNormalizer);
    }
    
    public function prepareAttributesCollection(AttributeCollection $collection)
    {
        parent::prepareAttributesCollection($collection);
        $collection->add(new Attribute('sGetGroupsRoute', $this->getOption('get_groups_route')));
        $collection->add(new Attribute('sGetPhotosRoute', $this->getOption('get_photos_route')));
        $collection->add(new Attribute('sGetAttributesRoute', $this->getOption('get_attributes_route')));
        $collection->add(new Attribute('sGetAttributesValuesRoute', $this->getOption('get_attributes_values_route')));
        $collection->add(new Attribute('sAddAttributeRoute', $this->getOption('add_attribute_route')));
        $collection->add(new Attribute('sAddAttributeValueRoute', $this->getOption('add_attribute_value_route')));
        $collection->add(new Attribute('sGenerateCartesianRoute', $this->getOption('generate_cartesian_route')));
        $collection->add(new Attribute('sCategoryField', $this->getOption('category_field')));
        $collection->add(new Attribute('sPhotoField', $this->getOption('photo_field')));
        $collection->add(new Attribute('bAllowGenerate', $this->getOption('allow_generate'), Attribute::TYPE_BOOLEAN));
        $collection->add(new Attribute('aoVatValues', $this->getOption('vat_values'), Attribute::TYPE_ARRAY));
        $collection->add(new Attribute('aoAvailability', $this->getOption('availability'), Attribute::TYPE_ARRAY));
        $collection->add(new Attribute('aoSuffixes', $this->getOption('suffixes'), Attribute::TYPE_ARRAY));
        $collection->add(new Attribute('sAttributeGroupField', $this->getOption('attribute_group_field')));
        $collection->add(new Attribute('sSet', $this->getOption('set')));
    }
}
