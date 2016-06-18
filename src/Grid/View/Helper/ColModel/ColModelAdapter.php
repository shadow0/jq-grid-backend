<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 21.04.16
 * Time: 22:40
 */
namespace JqGridBackend\Grid\View\Helper\ColModel;

use Zend\Form\Element;

abstract class ColModelAdapter
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @param Element $column
     * @return ColModel
     */
    public function __invoke(Element $column)
    {
        $res = $this->getAttributes($column);
        return new ColModel($column->getName(), $res);
    }

    /**
     * @param Element $column
     * @return array
     */
    protected function getAttributes(Element $column)
    {
        /** @var \Zend\Form\Element $column */
        $name = $column->getName();
        $label = $column->getLabel();
        if ($label == '') {
            $label = $name;
        }
        $type = $this->getType();

        $ret = [
            'name' => $name,
            'index' => $name,
            'label' => $label,
            'stype' => $type,
            'edittype' => $type,
        ];

        if (is_array($resSpecial = $this->getSpecialAttributes($column))) {
            $ret = array_replace_recursive($ret, $resSpecial);
        }
        if ($gridOptions = $column->getOption('grid')) {
            $ret = array_replace_recursive($ret, $gridOptions);
        }
        return $ret;
    }

    /**
     * @param Element $column
     * @return array
     */
    protected function getSpecialAttributes(Element $column)
    {
        return [];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
