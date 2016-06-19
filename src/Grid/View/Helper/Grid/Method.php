<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 19.06.16
 * Time: 14:52
 */

namespace JqGridBackend\Grid\View\Helper\Grid;

use Zend\Json\Expr;
use Zend\Form\FieldsetInterface as GridObject;
use Zend\Json\Json;

/**
 * It stores method item for grid
 * Class Method
 * @package JqGridBackend\Grid\View\Helper\Grid
 */
class Method extends Expr implements GridObjectAwareInterface
{
    /** @var GridObject */
    protected $gridObject;
    /** @var  string */
    protected $name;
    /** @var array  */
    protected $params = [];

    public function __construct($name, array $params=[])
    {
        $this->setName($name);
        $this->setParams($params);
    }

    /**
     * Cast to string
     *
     * @return string holded javascript expression.
     */
    public function __toString()
    {
        $ret[] = '"' . $this->getName() . '"';
        foreach ($this->getParams() as $v) {
            $ret[] = Json::encode($v, false, [ 'enableJsonExprFinder'=>true ]);
        }
        return implode(',', $ret);
    }


    //====================================
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return self
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

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
        foreach ($this->getParams() as $v) {
            if ($v instanceof GridObjectAwareInterface) {
                $v->setGridObject($object);
            }
        }
        return $this;
    }
}