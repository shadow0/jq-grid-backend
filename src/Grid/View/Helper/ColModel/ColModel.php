<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 23.04.16
 * Time: 20:20
 */

namespace JqGridBackend\Grid\View\Helper\ColModel;

use Zend\Hydrator\ClassMethods as Hydrator;

class ColModel implements \JsonSerializable
{
    public function __construct($name, array $attributes = [])
    {
        $this->setName($name);
        $this->init($attributes);
    }

    public function init(array $attributes)
    {
        foreach ($attributes as $k => &$v) {
            if (property_exists($this, $k)) {
                $setMethod = 'set'.ucfirst($k);
                $this->$setMethod($v);
            }
        }
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
        $hydrator = new Hydrator(false);
        /** @var array $ret */
        $ret = $hydrator->extract($this);
        foreach ($ret as $k => $v) {
            if ($v === null) {
                unset($ret[$k]);
            }
        }
        return $ret;
    }


    /**
     * @var string
     */
    private $align;
    /**
     * @var string
     * javascript function
     */
    private $cellattr;
    /**
     * @var string
     */
    private $classes;
    /**
     * @var string
     */
    private $datefmt;
    /**
     * @var string
     */
    private $defval;
    /**
     * @var boolean
     */
    private $editable;
    /**
     * @var array
     */
    private $editoptions;
    /**
     * @var array
     */
    private $editrules;
    /**
     * @var string
     */
    private $edittype;
    /**
     * @var string
     */
    private $firstsortorder;
    /**
     * @var boolean
     */
    private $fixed;
    /**
     * @var array
     */
    private $formoptions;
    /**
     * @var array
     */
    private $formatoptions;
    /**
     * @var mixed
     */
    private $formatter;
    /**
     * @var boolean
     */
    private $frozen;
    /**
     * @var boolean
     */
    private $hidedlg;
    /**
     * @var boolean
     */
    private $hidden;
    /**
     * @var string
     */
    private $index;
    /**
     * @var string
     */
    private $jsonmap;
    /**
     * @var boolean
     */
    private $key;
    /**
     * @var string
     */
    private $label;
    /**
     * @var string
     */
    private $name;
    /**
     * @var boolean
     */
    private $resizable;
    /**
     * @var boolean
     */
    private $search;
    /**
     * @var array
     */
    private $searchoptions;
    /**
     * @var boolean
     */
    private $sortable;
    /**
     * @var string
     * javascript function
     */
    private $sortfunc;
    /**
     * @var mixed
     */
    private $sorttype;
    /**
     * @var string
     */
    private $stype;
    /**
     * @var string
     */
    private $surl;
    /**
     * @var object
     */
    private $template;
    /**
     * @var boolean
     */
    private $title;
    /**
     * @var number
     */
    private $width;
    /** @var  string */
    private $xmlmap;
    /**
     * @var string
     * javascript function
     */
    private $unformat;
    /**
     * @var boolean
     */
    private $viewable;

    //===========================================

    /**
     * @return string
     */
    public function getAlign()
    {
        return $this->align;
    }

    /**
     * @param string $align
     * @return self
     */
    public function setAlign($align)
    {
        $this->align = $align;
        return $this;
    }

    /**
     * @return string
     */
    public function getCellattr()
    {
        return $this->cellattr;
    }

    /**
     * @param string $cellattr
     * @return self
     */
    public function setCellattr($cellattr)
    {
        $this->cellattr = $cellattr;
        return $this;
    }

    /**
     * @return string
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @param string $classes
     * @return self
     */
    public function setClasses($classes)
    {
        $this->classes = $classes;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatefmt()
    {
        return $this->datefmt;
    }

    /**
     * @param string $datefmt
     * @return self
     */
    public function setDatefmt($datefmt)
    {
        $this->datefmt = $datefmt;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefval()
    {
        return $this->defval;
    }

    /**
     * @param string $defval
     * @return self
     */
    public function setDefval($defval)
    {
        $this->defval = $defval;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isEditable()
    {
        return $this->editable;
    }

    /**
     * @param boolean $editable
     * @return self
     */
    public function setEditable($editable)
    {
        $this->editable = $editable;
        return $this;
    }

    /**
     * @return array
     */
    public function getEditoptions()
    {
        return $this->editoptions;
    }

    /**
     * @param array|\Traversable $options
     * @return self
     */
    public function setEditoptions($options)
    {
        //TODO переделать на стратегию гидрирования
        if ($options instanceof \Traversable) {
            $this->editoptions = $options;
        } elseif (is_array($options)) {
            $this->editoptions = (object) $options;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getEditrules()
    {
        return $this->editrules;
    }

    /**
     * @param array $editrules
     * @return self
     */
    public function setEditrules($editrules)
    {
        $this->editrules = $editrules;
        return $this;
    }

    /**
     * @return string
     */
    public function getEdittype()
    {
        return $this->edittype;
    }

    /**
     * @param string $edittype
     * @return self
     */
    public function setEdittype($edittype)
    {
        $this->edittype = $edittype;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstsortorder()
    {
        return $this->firstsortorder;
    }

    /**
     * @param string $firstsortorder
     * @return self
     */
    public function setFirstsortorder($firstsortorder)
    {
        $this->firstsortorder = $firstsortorder;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isFixed()
    {
        return $this->fixed;
    }

    /**
     * @param boolean $fixed
     * @return self
     */
    public function setFixed($fixed)
    {
        $this->fixed = $fixed;
        return $this;
    }

    /**
     * @return array
     */
    public function getFormoptions()
    {
        return $this->formoptions;
    }

    /**
     * @param array $formoptions
     * @return self
     */
    public function setFormoptions($formoptions)
    {
        $this->formoptions = $formoptions;
        return $this;
    }

    /**
     * @return array
     */
    public function getFormatoptions()
    {
        return $this->formatoptions;
    }

    /**
     * @param array $formatoptions
     * @return self
     */
    public function setFormatoptions($formatoptions)
    {
        $this->formatoptions = $formatoptions;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * @param mixed $formatter
     * @return self
     */
    public function setFormatter($formatter)
    {
        $this->formatter = $formatter;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getFrozen()
    {
        return $this->frozen;
    }

    /**
     * @param boolean $frozen
     * @return self
     */
    public function setFrozen($frozen)
    {
        $this->frozen = $frozen;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getHidedlg()
    {
        return $this->hidedlg;
    }

    /**
     * @param boolean $hidedlg
     * @return self
     */
    public function setHidedlg($hidedlg)
    {
        $this->hidedlg = $hidedlg;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * @param boolean $hidden
     * @return self
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
        return $this;
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param string $index
     * @return self
     */
    public function setIndex($index)
    {
        $this->index = $index;
        return $this;
    }

    /**
     * @return string
     */
    public function getJsonmap()
    {
        return $this->jsonmap;
    }

    /**
     * @param string $jsonmap
     * @return self
     */
    public function setJsonmap($jsonmap)
    {
        $this->jsonmap = $jsonmap;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param boolean $key
     * @return self
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return self
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

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
     * @return boolean
     */
    public function isResizable()
    {
        return $this->resizable;
    }

    /**
     * @param boolean $resizable
     * @return self
     */
    public function setResizable($resizable)
    {
        $this->resizable = $resizable;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param boolean $search
     * @return self
     */
    public function setSearch($search)
    {
        $this->search = $search;
        return $this;
    }

    /**
     * @return array
     */
    public function getSearchoptions()
    {
        return $this->searchoptions;
    }

    /**
     * @param array $options
     * @return self
     */
    public function setSearchoptions($options)
    {
        if ($options instanceof \Traversable) {
            $this->searchoptions = $options;
        } elseif (is_array($options)) {
            $this->searchoptions = (object) $options;
        }

        return $this;
    }

    /**
     * @return boolean
     */
    public function getSortable()
    {
        return $this->sortable;
    }

    /**
     * @param boolean $sortable
     * @return self
     */
    public function setSortable($sortable)
    {
        $this->sortable = $sortable;
        return $this;
    }

    /**
     * @return string
     */
    public function getSortfunc()
    {
        return $this->sortfunc;
    }

    /**
     * @param string $sortfunc
     * @return self
     */
    public function setSortfunc($sortfunc)
    {
        $this->sortfunc = $sortfunc;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSorttype()
    {
        return $this->sorttype;
    }

    /**
     * @param mixed $sorttype
     * @return self
     */
    public function setSorttype($sorttype)
    {
        $this->sorttype = $sorttype;
        return $this;
    }

    /**
     * @return string
     */
    public function getStype()
    {
        return $this->stype;
    }

    /**
     * @param string $stype
     * @return self
     */
    public function setStype($stype)
    {
        $this->stype = $stype;
        return $this;
    }

    /**
     * @return string
     */
    public function getSurl()
    {
        return $this->surl;
    }

    /**
     * @param string $surl
     * @return self
     */
    public function setSurl($surl)
    {
        $this->surl = $surl;
        return $this;
    }

    /**
     * @return object
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param object $template
     * @return self
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isTitle()
    {
        return $this->title;
    }

    /**
     * @param boolean $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return number
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param number $width
     * @return self
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return string
     */
    public function getXmlmap()
    {
        return $this->xmlmap;
    }

    /**
     * @param string $xmlmap
     * @return self
     */
    public function setXmlmap($xmlmap)
    {
        $this->xmlmap = $xmlmap;
        return $this;
    }

    /**
     * @return string
     */
    public function getUnformat()
    {
        return $this->unformat;
    }

    /**
     * @param string $unformat
     * @return self
     */
    public function setUnformat($unformat)
    {
        $this->unformat = $unformat;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isViewable()
    {
        return $this->viewable;
    }

    /**
     * @param boolean $viewable
     * @return self
     */
    public function setViewable($viewable)
    {
        $this->viewable = $viewable;
        return $this;
    }



}