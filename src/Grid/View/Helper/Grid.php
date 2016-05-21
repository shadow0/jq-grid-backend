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
use JqGridBackend\Grid\View\Helper\ColModel\ColModelAdapterFactoryInterface;
use JqGridBackend\Grid\View\Helper\ColModel\ColModelAdapterFactory;
use JqGridBackend\Grid\View\Helper\Grid\GridObjectAwareInterface;
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

//    /**
//     * @var GridObject
//     */
//    protected $gridObj;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $template = 'grid/index';

    /**
     * @var ColModelAdapterFactory
     */
    private $colModelAdapterFactory;

    public function __construct()
    {
        $a = 1;
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
                case 'template':
                    $this->setTemplate($v);
                    break;
            }
        }

        //$this->setGridObj($obj);
        $this->setName($obj->getName());

        $renderer = $this->getRenderer();
        $viewModel = new ViewModel([
            'grid' => $this,
            'object' => $obj,
            'options' => $options
        ]);
        $viewModel->setTemplate($this->getTemplate());
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
        $options = $this->getGridOptions($obj);
        $methodsKey = (array_key_exists('methodsKey', $options)) ? $options['methodsKey'] : 'default';
        $gridMethods = $this->getDefaultMethods($methodsKey);

        if (array_key_exists('gridMethods', $options)) {
            $gridMethods = array_merge_recursive($gridMethods, $options['gridMethods']);
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
        $gridOptions = [];
        $options = $obj->getOptions();
        if (array_key_exists('gridOptions', $options)) {
            $gridOptions = $options['gridOptions'];
        }
        $optionsKey = (array_key_exists('optionsKey', $gridOptions)) ? $gridOptions['optionsKey'] : 'default';

        $gridOptions = array_merge($this->getDefaultOptions($obj, $optionsKey), $gridOptions);

        return $gridOptions;
    }

    private function getColModel(GridObject $obj)
    {
        $colModel = array();
        /** @var FormElement $column */
        foreach ($obj as $column) {
            $colModel[] = $this->getColModelItem($column);
        }
        return $colModel;
    }

    protected function getColModelItem(FormElement $column) {
        /** @var ColModelAdapterFactory $colModelAdapterFactory */
        $colModelAdapterFactory = $this->getColModelAdapterFactory();

        /** @var ColModel\ColModelAdapter $colModelAdapter */
        $colModelAdapter = $colModelAdapterFactory->getAdapter($column);
        return $colModelAdapter($column);
    }

    private function getDefaultOptions(GridObject $obj, $optionsKey = 'default')
    {
        $config = $this->getConfig('options');
        if (!$config) {
            throw new Exception\InvalidArgumentException('missing "options" section in JqGridBackend configuration');
        }
        if (array_key_exists($optionsKey, $config) == false) {
            throw new Exception\InvalidArgumentException('missing '.$optionsKey.' options configuration for JqGridBackend');
        }
        $ret = [];

        foreach ($config[$optionsKey] as $k => $v) {
            if ($v instanceof GridObjectAwareInterface) {
                $v->setGridObject($obj);
            }
            $ret[$k] = $v;
//            switch ($k) {
//                case 'pager':
//                    //TODO getName() делает хелпер нереентерабельным, изменить алгоритм имени pager
//                    $ret[$k] = $v . $this->getName();
//                    break;
//                default:
//                    $ret[$k] = $v;
//            }
        }

        return $ret;
    }

    private function getDefaultMethods($methodsKey = 'default')
    {
        //TODO валидаторы на методы
        $config = $this->getConfig('methods');
        if (!$config) {
            throw new Exception\InvalidArgumentException('missing "methods" section in JqGridBackend configuration');
        }
        if (array_key_exists($methodsKey, $config) == false) {
            throw new Exception\InvalidArgumentException('missing '.$methodsKey.' methods configuration for JqGridBackend');
        }

        return $config[$methodsKey];
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
                'subgrid' => true,
                'template' => 'grid/subgrid'
            ]
        );

        $ret['subGrid'] = true;
        $ret['subGridRowExpanded'] = new JsonExpr($result);

        return $ret;
    }

//    /**
//     * @return GridObject
//     */
//    public function getGridObj()
//    {
//        return $this->gridObj;
//    }
//
//    /**
//     * @param GridObject $gridObj
//     * @return self
//     */
//    public function setGridObj($gridObj)
//    {
//        $this->gridObj = $gridObj;
//        return $this;
//    }

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
        $renderer = new PhpRenderer();
        $resolver = new AggregateResolver();
        $renderer->setResolver($resolver);
        $path = __DIR__ . '/../../../../../view';

        $stack = new TemplatePathStack(array(
            'script_paths' => array(
                $path,
                //$someOtherPath
            )
        ));
        $resolver->attach($stack);
        return $renderer;
    }

    /**
     * получает нужный шаблон
     */
    protected function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param $template
     * @return self
     */
    protected function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return ColModelAdapterFactory
     */
    protected function getColModelAdapterFactory()
    {
        if ( ! $this->colModelAdapterFactory) {
            $sm = $this->getParentServiceLocator();
            /** @var ColModelAdapterFactory $colModelAdapterFactory */
            $this->colModelAdapterFactory = $sm->get(ColModelAdapterFactoryInterface::class);
        }
        return $this->colModelAdapterFactory;
    }

    /**
     * @return array
     */
    protected function getConfig($key)
    {
        if (!$this->config) {
            $sm = $this->getParentServiceLocator();
            $config = $sm->get('config');
            if (array_key_exists('JqGridBackend', $config) == false) {
                throw new Exception\InvalidArgumentException('missing config section JqGridBackend');
            }
            $this->config = $config['JqGridBackend'];
        }

        if (array_key_exists($key, $this->config) == false) {
            return null;
        }

        return $this->config[$key];
    }

    /**
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected function getParentServiceLocator()
    {
        $sm = $this->getServiceLocator();
        if ($sm instanceof AbstractPluginManager) {
            $sm = $sm->getServiceLocator();
        }
        return $sm;
    }

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

}