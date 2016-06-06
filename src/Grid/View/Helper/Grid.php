<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 07.04.16
 * Time: 0:35
 */

namespace JqGridBackend\Grid\View\Helper;

use JqGridBackend\Form\SubgridInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;
use Zend\Form\FieldsetInterface as GridObject;
use Zend\ServiceManager\AbstractPluginManager;
//use JqGridBackend\Grid\View\Helper\ColModel\ColModelAdapterFactoryInterface;
//use JqGridBackend\Grid\View\Helper\ColModel\ColModelAdapterFactory;
use JqGridBackend\Grid\View\Helper\Grid\GridObjectAwareInterface;
use JqGridBackend\Grid\View\Helper\ColModelAdapterPluginManagerInterface;
use JqGridBackend\Exception;
use Zend\Json\Expr as JsonExpr;

use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\AggregateResolver;
use Zend\View\Resolver\TemplatePathStack;
use Zend\Form\Element as FormElement;

class Grid extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var array
     */
    protected $config;

    protected $adapterMapConfig;

    /**
     * @var string
     */
    protected $name;

    /**
     * default configuration name
     * @var string
     */
    protected $configName = 'default';

    /**
     * @var ColModelAdapterPluginManagerInterface
     */
    protected $colModelAdapterPluginManager;

    public function __construct($colModelAdapterPluginManager, $config)
    {
        $this->config = $config;
        $this->setColModelAdapterPluginManager($colModelAdapterPluginManager);
        $this->adapterMapConfig = $this->getConfig('adapterMap');
    }

    /**
     * @param GridObject $form
     * @param array $options
     * @return $this|string
     */
    public function __invoke(GridObject $form = null, array $options = [])
    {
        if (!$form) {
            return $this; //FIXME
        }

        return $this->render($form, $options);
    }

    /**
     * @param GridObject $obj
     * @param array $options
     * @return string
     * @throws \Exception
     */
    public function render(GridObject $obj, array $options = [])
    {
        foreach ($options as $k => $v) {
            switch ($k) {
                case 'configName':
                    $this->setConfigName($v);
                    break;
            }
        }

        $this->setName($obj->getName());

        $renderer = $this->getRenderer();
        $viewModel = new ViewModel([
            'grid' => $this,
            'object' => $obj,
            'options' => $options
        ]);
        $viewModel->setTemplate($this->getCurrentConfigKey('template'));
        return $renderer->render($viewModel);

    }

    public function getBody(GridObject $obj)
    {
        $ret = array_merge(
            $this->getGridOptions($obj),
            [
                'colModel' => $this->getColModel($obj)
            ],
            $this->getSubgridOptions($obj)
        );
        return $ret;
    }

    public function getGridMethods(GridObject $obj)
    {
        $gridOptions = $this->getGridOptions($obj);
        $configKey = $this->getGridConfigKey($gridOptions);
        $gridMethods = $this->getDefaultMethods($configKey);

        if (array_key_exists('_gridMethods_', $gridOptions)) {
            $gridMethods = array_merge_recursive($gridMethods, $gridOptions['_gridMethods_']);
        }
        return $gridMethods;
    }

    /**
     * return merge the default and user options for grid
     * @param GridObject $obj
     * @return array
     */
    public function getGridOptions(GridObject $obj)
    {
        $gridOptions = $this->getObjectGridOptions($obj);
        $configKey = $this->getGridConfigKey($gridOptions);
        $gridOptions = array_merge($this->getDefaultOptions($obj, $configKey), $gridOptions);
        return $gridOptions;
    }

    /**
     * get grid options from object. They are preffered over config
     * @param GridObject $obj
     * @return array
     */
    private function getObjectGridOptions(GridObject $obj)
    {
        $gridOptions = [];
        $options = $obj->getOptions();
        if (array_key_exists('gridOptions', $options)) {
            $gridOptions = $options['gridOptions'];
        }
        return $gridOptions;
    }

    /**
     * Get configuration section key for current grid
     * @param array $gridOptions
     * @return string
     */
    private function getGridConfigKey(array $gridOptions)
    {
        $configKey = (array_key_exists('configKey', $gridOptions)) ? $gridOptions['configKey'] : 'default';
        return $configKey;
    }

    private function getColModel(GridObject $obj)
    {
        $colModel = array();
        /** @var FormElement $column */
        foreach ($obj as $column) {
            $adapter = $this->getColModelAdapter($column);
            $colModel[] = $adapter($column);
        }
        return $colModel;
    }

    /**
     * get column adapter from column object
     * @param FormElement $column
     * @return ColModel\ColModelAdapter
     */
    protected function getColModelAdapter(FormElement $column)
    {
        /** @var string $adapterName */
        $adapterName = $this->getAdapterName($column);
        /** @var ColModelAdapterPluginManagerInterface $adapterPM */
        $adapterPM = $this->getColModelAdapterPluginManager();
        /** @var ColModel\ColModelAdapter $colModelAdapter */
        $colModelAdapter = $adapterPM->get($adapterName);
        return $colModelAdapter;
    }

    private function getDefaultOptions(GridObject $obj, $optionsKey = 'default')
    {

        $config = $this->getCurrentConfigKey('options');
        if (is_array($config) == false) {
            throw new Exception\InvalidArgumentException('missing "options" in '.$optionsKey.' configuration for JqGridBackend');
        }
        $ret = [];

        foreach ($config as $k => $v) {
            if ($v instanceof GridObjectAwareInterface) {
                $v->setGridObject($obj);
            }
            $ret[$k] = $v;
        }

        return $ret;
    }

    private function getDefaultMethods($methodsKey = 'default')
    {
        //TODO валидаторы на методы
        $config = $this->getCurrentConfigKey('methods');
        if (is_array($config) == false) {
            throw new Exception\InvalidArgumentException('missing "methods" in '.$methodsKey.' configuration for JqGridBackend');
        }
        return $config;
    }

    protected function getSubgridOptions($obj)
    {
        $ret = [];

        /** @var GridObject $obj */
        if ($obj instanceof SubgridInterface == false) {
            return $ret;
        }
        /** @var SubgridInterface $obj */
        if (($subgridObj = $obj->getSubgrid()) == null) {
            return $ret;
        }

        /** @var \Callable $subgridHelper */
        $subgridHelper = $this->getSubgridHelper($subgridObj);

        //FIXME каша с передачей опций. Они должны разыскиваться объектом самостоятельно
        $result = $subgridHelper(
            $subgridObj,
            [
                'configName' => 'subgrid',
//                'subgrid' => true,
//                'template' => 'grid/subgrid'
            ]
        );

        $ret['subGrid'] = true;
        $ret['subGridRowExpanded'] = new JsonExpr($result);

        return $ret;
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
     * @return PhpRenderer
     */
    private function getRenderer()
    {
        $renderer = clone $this->getView();
        $renderer->setVars([]);
        return $renderer;
    }

    /**
     * @return array|null
     */
    protected function getConfig($key)
    {
        if (array_key_exists($key, $this->config) == false) {
            return null;
        }

        return $this->config[$key];
    }

    /**
     * @param $key
     * @return null|array
     */
    protected function getCurrentConfigKey($key)
    {
        $curConfig = $this->getConfig($this->getConfigName());
        if (array_key_exists($key, $curConfig) == false) {
            return null;
        }

        return $curConfig[$key];
    }

//    /**
//     * @return \Zend\ServiceManager\ServiceLocatorInterface
//     */
//    protected function getParentServiceLocator()
//    {
//        $sm = $this->getServiceLocator();
//        if ($sm instanceof AbstractPluginManager) {
//            $sm = $sm->getServiceLocator();
//        }
//        return $sm;
//    }

    /**
     * get helper for subgrid rendering
     * @param $subgridObj
     * @return AbstractHelper
     */
    protected function getSubgridHelper($subgridObj)
    {
        if (($config = $this->getConfig('subgridMap')) == false) {
            throw new Exception\InvalidArgumentException('missing "subgridMap" section in JqGridBackend configuration');
        }
        $subgridHelperClass = null;
        foreach ($config as $objClass => $helperClass) {
            if ($subgridObj instanceof $objClass) {
                $subgridHelperClass = $helperClass;
            }
        }
        if (! $subgridHelperClass ) {
            throw new Exception\InvalidArgumentException('need subgrid helper in "subgridMap" section in JqGridBackend configuration');
        }

        //$sm = $this->getParentServiceLocator();
        $sm = $this->getServiceLocator();
        /** @var AbstractHelper $subgridHelper */
        $subgridHelper = $sm->get($subgridHelperClass);
        return $subgridHelper;
    }

    /**
     * @return mixed
     */
    public function getColModelAdapterPluginManager()
    {
        return $this->colModelAdapterPluginManager;
    }

    /**
     * @param mixed $colModelAdapterPluginManager
     * @return self
     */
    public function setColModelAdapterPluginManager($colModelAdapterPluginManager)
    {
        $this->colModelAdapterPluginManager = $colModelAdapterPluginManager;
        return $this;
    }

    /**
     * @param FormElement $element
     * @return string
     * @thrown Exception\OutOfBoundsException
     */
    public function getAdapterName(FormElement $element)
    {
        $adapterName = null;
        foreach ($this->adapterMapConfig as $k => $v) {
            if (is_a($element, $k) == true) {
                $adapterName = $v;
            }
        }
        if (!$adapterName) {
            throw new Exception\OutOfBoundsException("missing ColModelAdapter for class = ". get_class($element));
        }
        return $adapterName;
    }

    /**
     * @return string
     */
    public function getConfigName()
    {
        return $this->configName;
    }

    /**
     * @param string $configName
     * @return self
     */
    public function setConfigName($configName)
    {
        $this->configName = $configName;
        return $this;
    }


}