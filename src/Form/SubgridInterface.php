<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 02.05.16
 * Time: 23:17
 */

namespace JqGridBackend\Form;

use Zend\Form\FieldsetInterface as GridObject;

interface SubgridInterface {
    /**
     * @return mixed|null
     */
    public function getSubgrid();

    /**
     * @param mixed $gridObject
     * @return self
     */
    public function setSubgrid($gridObject);

    /**
     * @return self
     */
    public function unsetSubgrid();
}