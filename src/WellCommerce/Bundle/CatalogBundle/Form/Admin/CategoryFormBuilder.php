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

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class CategoryForm
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CategoryFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.category';
    }
    
    public function buildForm(FormInterface $form)
    {
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => 'common.fieldset.general',
        ]));
        
        $languageData = $requiredData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => 'common.fieldset.translations',
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('category.repository')),
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
            'label'           => 'category.label.slug',
            'name_field'      => $name,
            'generate_route'  => 'route.generate',
            'translatable_id' => $this->getRequestHelper()->getAttributesBagParam('id'),
            'rules'           => [
                $this->getRule('required'),
            ],
        ]));
        
        $requiredData->addChild($this->getElement('checkbox', [
            'name'    => 'enabled',
            'label'   => 'category.label.enabled',
            'comment' => 'category.comment.enabled',
        ]));
        
        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'hierarchy',
            'label' => 'common.label.hierarchy',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'symbol',
            'label' => 'common.label.symbol',
        ]));
        
        $requiredData->addChild($this->getElement('tree', [
            'name'        => 'parent',
            'label'       => 'category.label.parent',
            'choosable'   => true,
            'selectable'  => false,
            'sortable'    => false,
            'clickable'   => false,
            'items'       => $this->get('category.dataset.admin')->getResult('flat_tree', ['limit' => 10000]),
            'restrict'    => $this->getRequestHelper()->getAttributesBagParam('id'),
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('category.repository')),
        ]));
        
        $descriptionData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'description_data',
            'label' => 'category.form.fieldset.description',
        ]));
        
        $languageData = $descriptionData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => 'common.fieldset.translations',
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('category.repository')),
        ]));
        
        $languageData->addChild($this->getElement('rich_text_editor', [
            'name'  => 'shortDescription',
            'label' => 'common.label.short_description',
        ]));
        
        $languageData->addChild($this->getElement('rich_text_editor', [
            'name'  => 'description',
            'label' => 'common.label.description',
        ]));
    
        $mediaData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'media_data',
            'label' => 'common.fieldset.photos'
        ]));
    
        $mediaData->addChild($this->getElement('image', [
            'name'         => 'photo',
            'label'        => 'form.media_data.image_id',
            'load_route'   => $this->getRouterHelper()->generateUrl('admin.media.grid'),
            'upload_url'   => $this->getRouterHelper()->generateUrl('admin.media.add'),
            'repeat_min'   => 0,
            'repeat_max'   => 1,
            'transformer'  => $this->getRepositoryTransformer('media_entity', $this->get('media.repository')),
            'session_id'   => $this->getRequestHelper()->getSessionId(),
            'session_name' => $this->getRequestHelper()->getSessionName(),
        ]));
        
        $this->addMetadataFieldset($form, $this->get('category.repository'));
        
        $this->addShopsFieldset($form);
        
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
