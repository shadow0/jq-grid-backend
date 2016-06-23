<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 19.06.16
 * Time: 17:57
 */
namespace JqGridBackend\Form;

use Zend\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class RuleFieldset extends Form\Fieldset implements InputFilterProviderInterface
{
    public function init()
    {
        $this->add([
            'name' => 'field',
            'type' => 'text'
        ]);
        $this->add([
            'name' => 'op',
            'type' => 'select',
            'options' => [
                'value_options' => [
                    'eq' => 'eq',
                    'ne' => 'ne',
                    'lt' => 'lt',
                    'le' => 'lt',
                    'gt' => 'gt',
                    'ge' => 'ge',
                    'bw' => 'bw',
                    'bn' => 'bn',
                    'in' => 'in',
                    'ni' => 'ni',
                    'ew' => 'ew',
                    'en' => 'en',
                    'cn' => 'cn',
                    'nc' => 'nc'
                ]
            ]
        ]);
        $this->add([
            'name' => 'data',
            'type' => 'text'
        ]);
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'field' => [
                'required' => true,
                'filters' => [
                    [ 'name' => 'StringTrim' ]
                ],
            ],
            'op' => [
                'required' => true,
            ],
            'data' => [
                'required' => true,
                'filters' => [
                    [ 'name' => 'StringTrim' ]
                ]
            ]
        ];
    }
}