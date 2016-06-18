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

    protected function getSpecialAttributes(Element $column)
    {
        $res = [
            'searchoptions' => [
                'sopt' => new Sopt(['eq','ne','lt','le','gt','ge','bw','bn','in','ni','ew','en','cn','nc'],
                    \ArrayObject::STD_PROP_LIST),
            ],
        ];
        return $res;
    }
}