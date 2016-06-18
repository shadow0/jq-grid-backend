<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 18.11.15
 * Time: 1:45
 */

namespace JqGridBackend\Grid\View\Helper\ColModel;

use Zend\Form\Element;

class TextAdapter extends ColModelAdapter {

    public function __construct()
    {
        $this->type = 'text';
    }

    protected function getAttributes(Element $column)
    {
        $res = parent::getAttributes($column);
        if ($gridOptions = $column->getOption('grid')) {
            $res = array_replace_recursive($res, $gridOptions);
        }
        return $res;
    }

}