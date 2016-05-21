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

    protected function getAttributes(Element $column)
    {
        /** @var Element\Select $column */
        $valueOptions = $column->getValueOptions();
        $res = parent::getAttributes($column);
        $res = array_merge($res, [
            'formatter' => 'select',
            'searchoptions' => $valueOptions,
            'editoptions' => $valueOptions
//            'searchoptions' => [
//                'value' => (object) $valueOptions
//            ]
        ]);
        if ($gridOptions = $column->getOption('grid')) {
            $res = array_merge($res, $gridOptions);
        }
        return $res;
    }

}