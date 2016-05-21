<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 15.05.16
 * Time: 11:14
 */
namespace JqGridBackend\Grid\View\Helper\Grid;

use Zend\Form\FieldsetInterface as GridObject;

trait GridObjectAwareTrait {

    protected $gridObject;

    /**
     * @return GridObject
     */
    public function getGridObject()
    {
        return $this->gridObject;
    }

    /**
     * @param GridObject $object
     * @return self
     */
    public function setGridObject(GridObject $object)
    {
        $this->gridObject = $object;
    }
}
