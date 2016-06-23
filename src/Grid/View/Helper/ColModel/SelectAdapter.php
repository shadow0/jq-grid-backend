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

    private $emptyPair = ['__empty__' => ''];

    public function __construct()
    {
        $this->type = 'select';
    }

    protected function getSpecialAttributes(Element $column)
    {
        /** @var Element\Select $column */
        $valueOptions =  $column->getValueOptions();
        $searchOptions = array_merge($this->getEmptyPair(), $valueOptions);
        $res = [
            'formatter' => 'select',
            'searchoptions' => [
                'value' => (object) $searchOptions,
                'sopt' => ['eq','ne'],
            ],
            'editoptions' => [
                'value' => (object)$valueOptions
            ]
        ];
        return $res;
    }

    /**
     * @return array
     */
    public function getEmptyPair()
    {
        return $this->emptyPair;
    }

    /**
     * @param array $emptyPair
     * @return self
     */
    public function setEmptyPair(array $emptyPair)
    {
        $this->emptyPair = $emptyPair;
        return $this;
    }


}