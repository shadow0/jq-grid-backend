<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 15.05.16
 * Time: 11:14
 */
namespace JqGridBackend\Grid\View\Helper\Grid;

use Zend\Form\FieldsetInterface as GridObject;

interface GridObjectAwareInterface {

    /**
     * @return GridObject
     */
    public function getGridObject();

    /**
     * @param GridObject $object
     * @return self
     */
    public function setGridObject(GridObject $object);
}