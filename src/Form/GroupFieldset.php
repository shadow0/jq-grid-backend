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
use Zend\InputFilter\InputFilterProviderInterface;

class GroupFieldset extends Form\Fieldset implements InputFilterProviderInterface
{
    /** @var AbstractPluginManager  */
    protected $pluginManager;

    public function init()
    {
        $this->add([
            'name' => 'groupOp',
            'type' => 'select',
            'options' => [
                'value_options' => [
                    'OR' => 'OR',
                    'AND' => 'AND'
                ]
            ]
        ]);
        $this->add([
            'name' => 'rules',
            'type' => Form\Element\Collection::class,
            'options' => [
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => array(
                 'type' => RuleFieldset::class,
                ),
            ]
        ]);
//        $this->add([
//            'name' => 'groups',
//            'type' => LazyCollection::class,
//            'options' => [
//                'should_create_template' => true,
//                'allow_add' => true,
//                'target_element' => array(
//                    'type' => GroupFieldset::class,
//                ),
//            ]
//        ]);
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'groupOp' => [
                'required' => true,
            ],
        ];
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