<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 19.06.16
 * Time: 12:35
 */

namespace JqGridBackend\Form;

use Zend\Form;
use Zend\ServiceManager\AbstractPluginManager;

class Condition extends Form\Form
{
    /** @var AbstractPluginManager  */
    protected $pluginManager;

    public function init()
    {
        $this->add([
            'name' => 'filters',
            'type' => GroupFieldset::class
        ]);
        $fieldset = $this->get('filters');
        $this->setBaseFieldset($fieldset);
    }

    /**
     * @return AbstractPluginManager
     */
    public function getPluginManager()
    {
        return $this->pluginManager;
    }

    /**
     * @param AbstractPluginManager $pluginManager
     * @return self
     */
    public function setPluginManager(AbstractPluginManager $pluginManager)
    {
        $this->pluginManager = $pluginManager;
        return $this;
    }


}