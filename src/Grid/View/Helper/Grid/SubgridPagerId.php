<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 15.05.16
 * Time: 11:14
 */
namespace JqGridBackend\Grid\View\Helper\Grid;

use Zend\Json\Expr;

class SubgridPagerId extends GridPagerId
{

     /**
     * Cast to string
     *
     * @return string holded javascript expression.
     */
    public function __toString()
    {
        $ret = parent::__toString();
        //NB add predefined subgrid script variable
        return sprintf("%s + subgrid_table_id", $ret);
    }

}