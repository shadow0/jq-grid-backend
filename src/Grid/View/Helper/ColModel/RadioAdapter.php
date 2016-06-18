<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 18.11.15
 * Time: 1:45
 */

namespace JqGridBackend\Grid\View\Helper\ColModel;

use Zend\Form\Element;

class RadioAdapter extends ColModelAdapter {

    public function __construct()
    {
        $this->type = 'select';
    }

    protected function getAttributes(Element $column)
    {
        /** @var Element\Select $column */
        $valueOptions = (object) $column->getValueOptions();
        $res = parent::getAttributes($column);
        $res = array_merge($res, [
            'formatter' => 'select',
            'searchoptions' => [
                'value' => $valueOptions,
                'sopt' => ['eq','ne'],
            ],
            'editoptions' => [
                'value' => $valueOptions
            ]
        ]);
        if ($gridOptions = $column->getOption('grid')) {
            $res = array_replace_recursive($res, $gridOptions);
        }
        return $res;
    }

}