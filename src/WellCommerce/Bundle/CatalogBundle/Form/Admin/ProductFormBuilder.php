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

namespace WellCommerce\Bundle\CatalogBundle\Form\Admin;

use Doctrine\Common\Collections\Collection;
use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\DataTransformer\DateTransformer;
use WellCommerce\Component\Form\Elements\ElementInterface;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class ProductFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.product';
    }
    
    public function buildForm(FormInterface $form)
    {
        $currencies = $this->get('currency.dataset.admin')->getResult('select', ['order_by' => 'code'], [
            'label_column' => 'code',
            'value_column' => 'code',
        ]);
        
        $vatValues = $this->get('tax.dataset.admin')->getResult('select', ['order_by' => 'value']);
        
        $mainData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'main_data',
            'label' => 'common.fieldset.general',
        ]));
        
        $languageData = $mainData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => 'locale.label.language',
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('product.repository')),
        ]));
        
        $name = $languageData->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => 'common.label.name',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $languageData->addChild($this->getElement('slug_field', [
            'name'            => 'slug',
            'label'           => 'product.label.slug',
            'name_field'      => $name,
            'generate_route'  => 'route.generate',
            'translatable_id' => $this->getRequestHelper()->getAttributesBagParam('id'),
            'rules'           => [
                $this->getRule('required'),
            ],
        ]));
        
        $mainData->addChild($this->getElement('checkbox', [
            'name'    => 'enabled',
            'label'   => 'common.label.enabled',
            'comment' => 'product.comment.enabled',
        ]));
        
        $mainData->addChild($this->getElement('text_field', [
            'name'  => 'hierarchy',
            'label' => 'common.label.hierarchy',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $descriptionData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'description_data',
            'label' => 'common.fieldset.description',
        ]));
        
        $languageData = $descriptionData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => 'locale.label.translations',
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('product.repository')),
        ]));
        
        $languageData->addChild($this->getElement('rich_text_editor', [
            'name'  => 'shortDescription',
            'label' => 'common.label.short_description',
        ]));
        
        $languageData->addChild($this->getElement('rich_text_editor', [
            'name'  => 'description',
            'label' => 'common.label.description',
        ]));
        
        $mainData->addChild($this->getElement('text_field', [
            'name'  => 'sku',
            'label' => 'common.label.sku',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $mainData->addChild($this->getElement('text_field', [
            'name'  => 'barcode',
            'label' => 'product.label.barcode',
        ]));
        
        $mainData->addChild($this->getElement('select', [
            'name'        => 'producer',
            'label'       => 'common.label.producer',
            'options'     => $this->get('producer.dataset.admin')->getResult('select'),
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('producer.repository')),
        ]));
        
        $mainData->addChild($this->getElement('select', [
            'name'        => 'producer_collection',
            'label'       => $this->trans('common.label.producer_collection'),
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('producer_collection.repository')),
        ]));
        
        $this->addMetadataFieldset($form, $this->get('product.repository'));
        
        $categoryPane = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'category_pane',
            'label' => 'common.fieldset.categories',
        ]));
        
        $categoriesField = $categoryPane->addChild($this->getElement('tree', [
            'name'        => 'categories',
            'label'       => 'common.label.categories',
            'choosable'   => false,
            'selectable'  => true,
            'sortable'    => false,
            'clickable'   => false,
            'items'       => $this->get('category.dataset.admin')->getResult('flat_tree', ['limit' => 10000]),
            'transformer' => $this->getRepositoryTransformer('collection', $this->get('category.repository')),
        ]));
        
        $pricePane = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'price_pane',
            'label' => 'common.fieldset.prices',
        ]));
        
        $buyPriceSettings = $pricePane->addChild($this->getElement('nested_fieldset', [
            'name'  => 'buy_price_settings',
            'label' => 'product.label.buy_price.settings',
            'class' => 'priceGroup',
        ]));
        
        $buyPriceSettings->addChild($this->getElement('select', [
            'name'    => 'buyPrice.currency',
            'label'   => 'common.label.currency',
            'options' => $currencies,
        ]));
        
        $buyPriceTax = $buyPriceSettings->addChild($this->getElement('select', [
            'name'            => 'buyPriceTax',
            'label'           => 'common.label.tax',
            'options'         => $vatValues,
            'addable'         => true,
            'onAdd'           => 'onTaxAdd',
            'add_item_prompt' => 'product.label.add_tax_prompt',
            'transformer'     => $this->getRepositoryTransformer('entity', $this->get('tax.repository')),
        ]));
        
        $buyPriceSettings->addChild($this->getElement('price_editor', [
            'name'      => 'buyPrice.grossAmount',
            'label'     => 'product.label.buy_price.gross_amount',
            'filters'   => [
                $this->getFilter('comma_to_dot_changer'),
            ],
            'vat_field' => $buyPriceTax,
        ]));
        
        $sellPriceSettings = $pricePane->addChild($this->getElement('nested_fieldset', [
            'name'  => 'sell_price_settings',
            'label' => 'product.label.sell_price.settings',
            'class' => 'priceGroup',
        ]));
        
        $sellPriceSettings->addChild($this->getElement('select', [
            'name'    => 'sellPrice.currency',
            'label'   => 'common.label.currency',
            'options' => $currencies,
        ]));
        
        $sellPriceTax = $sellPriceSettings->addChild($this->getElement('select', [
            'name'            => 'sellPriceTax',
            'label'           => 'common.label.tax',
            'options'         => $vatValues,
            'addable'         => true,
            'onAdd'           => 'onTaxAdd',
            'add_item_prompt' => 'product.label.add_tax_prompt',
            'transformer'     => $this->getRepositoryTransformer('entity', $this->get('tax.repository')),
        ]));
        
        $sellPriceSettings->addChild($this->getElement('price_editor', [
            'name'      => 'sellPrice.grossAmount',
            'label'     => 'product.label.sell_price.gross_amount',
            'filters'   => [
                $this->getFilter('comma_to_dot_changer'),
            ],
            'vat_field' => $sellPriceTax,
        ]));
        
        $sellPriceSettings->addChild($this->getElement('price_editor', [
            'name'      => 'sellPrice.discountedGrossAmount',
            'label'     => 'common.label.discounted_price',
            'filters'   => [
                $this->getFilter('comma_to_dot_changer'),
            ],
            'vat_field' => $sellPriceTax,
        ]));
        
        $sellPriceSettings->addChild($this->getElement('date', [
            'name'        => 'sellPrice.validFrom',
            'label'       => 'common.label.valid_from',
            'transformer' => new DateTransformer('m/d/Y'),
        ]));
        
        $sellPriceSettings->addChild($this->getElement('date', [
            'name'        => 'sellPrice.validTo',
            'label'       => 'common.label.valid_to',
            'transformer' => new DateTransformer('m/d/Y'),
        ]));
        
        $stockData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'stock_data',
            'label' => 'product.form.fieldset.stock',
        ]));
        
        $stockData->addChild($this->getElement('text_field', [
            'name'  => 'stock',
            'label' => 'common.label.stock',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $stockData->addChild($this->getElement('checkbox', [
            'name'    => 'trackStock',
            'label'   => 'product.label.track_stock',
            'comment' => 'product.comment.track_stock',
        ]));
        
        $stockData->addChild($this->getElement('select', [
            'name'        => 'unit',
            'label'       => 'product.label.unit',
            'options'     => $this->get('unit.dataset.admin')->getResult('select'),
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('unit.repository')),
        ]));
        
        $stockData->addChild($this->getElement('text_field', [
            'name'    => 'weight',
            'label'   => 'common.label.dimension.weight',
            'filters' => [
                $this->getFilter('comma_to_dot_changer'),
            ],
            'rules'   => [
                $this->getRule('required'),
            ],
            'default' => 1,
        ]));
        
        $stockData->addChild($this->getElement('text_field', [
            'name'    => 'dimension.width',
            'label'   => 'common.label.dimension.width',
            'filters' => [
                $this->getFilter('comma_to_dot_changer'),
            ],
        ]));
        
        $stockData->addChild($this->getElement('text_field', [
            'name'    => 'dimension.height',
            'label'   => 'common.label.dimension.height',
            'filters' => [
                $this->getFilter('comma_to_dot_changer'),
            ],
        ]));
        
        $stockData->addChild($this->getElement('text_field', [
            'name'    => 'dimension.depth',
            'label'   => 'common.label.dimension.depth',
            'filters' => [
                $this->getFilter('comma_to_dot_changer'),
            ],
        ]));
        
        $stockData->addChild($this->getElement('text_field', [
            'name'    => 'packageSize',
            'label'   => 'product.label.package_size',
            'filters' => [
                $this->getFilter('comma_to_dot_changer'),
            ],
            'rules'   => [
                $this->getRule('required'),
            ],
        ]));
        
        $availabilityField = $stockData->addChild($this->getElement('select', [
            'name'        => 'availability',
            'label'       => 'product.label.availability',
            'options'     => $this->get('availability.dataset.admin')->getResult('select'),
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('availability.repository')),
        ]));
        
        $mediaData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'media_data',
            'label' => 'product.form.fieldset.photos',
        ]));
        
        $photoField = $mediaData->addChild($this->getElement('image', [
            'name'         => 'productPhotos',
            'label'        => 'product.label.photos',
            'load_route'   => $this->getRouterHelper()->generateUrl('admin.media.grid'),
            'upload_url'   => $this->getRouterHelper()->generateUrl('admin.media.add'),
            'repeat_min'   => 0,
            'repeat_max'   => ElementInterface::INFINITE,
            'transformer'  => $this->getRepositoryTransformer('product_photo_collection', $this->get('media.repository')),
            'session_id'   => $this->getRequestHelper()->getSessionId(),
            'session_name' => $this->getRequestHelper()->getSessionName(),
        ]));
        
        $distinctionData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'distinctions',
            'label' => 'product.form.fieldset.statuses',
        ]));
        
        $distinctionData->addChild($this->getElement('distinction_editor', [
            'name'        => 'distinctions',
            'label'       => 'product.label.distinctions',
            'transformer' => $this->getRepositoryTransformer('distinction_collection', $this->get('product_status.repository')),
            'statuses'    => $this->get('product_status.dataset.admin')->getResult('select'),
        ]));
        
        if ($this->getAttributeGroups()->count()) {
            $attributesData = $form->addChild($this->getElement('nested_fieldset', [
                'name'  => 'attributes_data',
                'label' => 'product.form.fieldset.variants',
            ]));
            
            $attributeGroupField = $attributesData->addChild($this->getElement('hidden', [
                'name'        => 'attributeGroup',
                'label'       => 'product.label.attribute_group',
                'transformer' => $this->getRepositoryTransformer('entity', $this->get('attribute_group.repository')),
            ]));
            
            $attributesData->addChild($this->getElement('variant_editor', [
                'name'                  => 'variants',
                'label'                 => 'product.label.variants',
                'suffixes'              => ['+', '-', '%'],
                'vat_values'            => $vatValues,
                'attribute_group_field' => $attributeGroupField,
                'category_field'        => $categoriesField,
                'photo_field'           => $photoField,
                'availability'          => $availabilityField->getOption('options'),
                'transformer'           => $this->getRepositoryTransformer('variant_collection', $this->get('variant.repository')),
            ]));
        }
        
        $this->addShopsFieldset($form);
        
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
    
    protected function getAttributeGroups(): Collection
    {
        return $this->get('attribute_group.repository')->getCollection();
    }
}
