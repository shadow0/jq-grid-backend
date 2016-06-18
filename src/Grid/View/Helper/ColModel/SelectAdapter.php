<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 18.11.15
 * Time: 1:45
 */

namespace JqGridBackend\Grid\View\Helper\ColModel;

use Zend\Form\Element;

class SelectAdapter extends ColModelAdapter {

    public function __construct()
    {
        $this->type = 'select';
    }

    protected function getSpecialAttributes(Element $column)
    {
        /** @var Element\Select $column */
        $valueOptions = (object) $column->getValueOptions();
        $res = [
            'formatter' => 'select',
            'searchoptions' => [
                'value' => $valueOptions,
                'sopt' => ['eq','ne'],
            ],
            'editoptions' => [
                'value' => $valueOptions
            ]
        ];
        return $res;
    }

}