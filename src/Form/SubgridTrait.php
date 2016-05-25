<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 02.05.16
 * Time: 23:17
 */

namespace JqGridBackend\Form;

use Zend\Form\FieldsetInterface as GridObject;

trait SubgridTrait {

    /**
     * @var mixed
     */
    protected $subgrid = null;

    /**
     * @return mixed|null
     */
    public function getSubgrid()
    {
       return $this->subgrid;
    }

    /**
     * @param mixed $gridObject
     * @return self
     */
    public function setSubgrid($gridObject)
    {
        $this->subgrid = $gridObject;
        return $this;
    }

    /**
     * @return self
     */
    public function unsetSubgrid()
    {
        $this->subgrid = null;
        return $this;
    }
}