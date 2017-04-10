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

namespace WellCommerce\Bundle\AppBundle\Entity;

use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;
use WellCommerce\Bundle\CoreBundle\Entity\MailerConfiguration;
use WellCommerce\Extra\AppBundle\Entity\ShopExtraTrait;

/**
 * Class Shop
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Shop implements EntityInterface
{
    use Identifiable;
    use Timestampable;
    use Blameable;
    use Translatable;
    use ShopExtraTrait;
    
    protected $name            = '';
    protected $url             = '';
    protected $defaultCountry  = '';
    protected $defaultCurrency = '';
    
    /**
     * @var MinimumOrderAmount
     */
    protected $minimumOrderAmount;
    
    /**
     * @var MailerConfiguration
     */
    protected $mailerConfiguration;
    
    /**
     * @var Company
     */
    protected $company;
    
    /**
     * @var ClientGroup
     */
    protected $clientGroup;
    
    /**
     * @var Theme
     */
    protected $theme;
    
    public function __construct()
    {
        $this->mailerConfiguration = new MailerConfiguration();
        $this->minimumOrderAmount  = new MinimumOrderAmount();
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function setName(string $name)
    {
        $this->name = $name;
    }
    
    public function getCompany()
    {
        return $this->company;
    }
    
    public function setCompany(Company $company = null)
    {
        $this->company = $company;
    }
    
    public function getUrl(): string
    {
        return $this->url;
    }
    
    public function setUrl(string $url)
    {
        $this->url = $url;
    }
    
    public function getDefaultCountry(): string
    {
        return $this->defaultCountry;
    }
    
    public function setDefaultCountry(string $defaultCountry)
    {
        $this->defaultCountry = $defaultCountry;
    }
    
    public function getDefaultCurrency(): string
    {
        return $this->defaultCurrency;
    }
    
    public function setDefaultCurrency(string $defaultCurrency)
    {
        $this->defaultCurrency = $defaultCurrency;
    }
    
    public function setMailerConfiguration(MailerConfiguration $configuration)
    {
        $this->mailerConfiguration = $configuration;
    }
    
    public function getMailerConfiguration(): MailerConfiguration
    {
        return $this->mailerConfiguration;
    }
    
    public function getClientGroup()
    {
        return $this->clientGroup;
    }
    
    public function setClientGroup(ClientGroup $clientGroup = null)
    {
        $this->clientGroup = $clientGroup;
    }
    
    public function getTheme()
    {
        return $this->theme;
    }
    
    public function setTheme(Theme $theme = null)
    {
        $this->theme = $theme;
    }

    public function getMinimumOrderAmount(): MinimumOrderAmount
    {
        return $this->minimumOrderAmount;
    }
    
    public function setMinimumOrderAmount(MinimumOrderAmount $minimumOrderAmount)
    {
        $this->minimumOrderAmount = $minimumOrderAmount;
    }

    public function translate($locale = null, $fallbackToDefault = true): ShopTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}
