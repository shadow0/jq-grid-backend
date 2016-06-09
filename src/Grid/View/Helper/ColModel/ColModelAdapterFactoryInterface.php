<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 28.04.16
 * Time: 1:11
 */
namespace JqGridBackend\Grid\View\Helper\ColModel;

use Zend\Form\Element as FormElement;

interface ColModelAdapterFactoryInterface
{
    /**
     * @param FormElement $element
     * @return ColModelAdapter
     */
    public function getAdapter(FormElement $element);

    /**
     * @param FormElement $element
     * @return string
     */
    public function getAdapterName(FormElement $element);

}