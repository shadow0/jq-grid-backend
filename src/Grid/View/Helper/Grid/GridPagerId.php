<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 15.05.16
 * Time: 11:14
 */
namespace JqGridBackend\Grid\View\Helper\Grid;

use Zend\Json\Expr;

class GridPagerId extends Expr implements GridObjectAwareInterface
{
    use GridObjectAwareTrait;

     /**
     * Cast to string
     *
     * @return string holded javascript expression.
     */
    public function __toString()
    {
        $ret = parent::__toString();

        if (($object = $this->getGridObject()) != false) {
            $ret .= $object->getName();
        }
        return sprintf("\"#%s\"", $ret);
    }
}