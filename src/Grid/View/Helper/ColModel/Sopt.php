<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 18.06.16
 * Time: 19:43
 */

namespace JqGridBackend\Grid\View\Helper\ColModel;

/**
 * Conteins the list of search operations for element http://www.trirand.com/jqgridwiki/doku.php?id=wiki:search_config
 * Class Sopt
 * @package JqGridBackend\Grid\View\Helper\ColModel
 */
class Sopt implements \JsonSerializable
{
    private $options = [];

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return $this->options;
    }
}
