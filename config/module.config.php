<?php
namespace JqGridBackend;

use JqGridBackend\Grid\View\Helper\Grid;
use Zend\Form\Element as FormElement;
use Zend\Form\FieldsetInterface;
use JqGridBackend\Grid\View\Helper\ColModel;
//use JqGridBackend\Grid\View\Helper\Grid\SubgridPagerId;

use JqGridBackend\Grid\View\Helper\ColModelAdapterPluginManagerInterface;
use JqGridBackend\Grid\View\Helper\ColModelAdapterPluginManager;

return array(
    'view_helpers' => [
        'aliases' => [
            'jqGrid' => Grid::class
        ],
        'factories' => [
            Grid::class => function ($serviceManager) {
                $configKey = 'JqGridBackend';
                $parentServiceLocator = $serviceManager->getServiceLocator();
                $config = $parentServiceLocator->get('config');

                if (array_key_exists($configKey, $config) == false) {
                    throw new \InvalidArgumentException('missing config section JqGridBackend');
                }
                $colModelPM = $parentServiceLocator->get(ColModelAdapterPluginManagerInterface::class);
                return new Grid($colModelPM, $config[$configKey]);
            }
        ],
        'shared' => [
            Grid::class => false,
        ]
    ],
    'view_manager' => array(
		'template_map' => array(
		),
		'template_path_stack' => array(
			'jqgridbackend' => __DIR__ . '/../view',
		),
		'strategies' => array(
            'ViewJsonStrategy',
        ),
	),
    'JqGridBackend' => [
        'adapterMap' => [
            /**
             * Adapter converts element to javascript code for grid.
             * Element is compared with key on "instanceof"
             */
            FormElement\Text::class => ColModel\TextAdapter::class,
            FormElement\Select::class => ColModel\SelectAdapter::class,
        ],
        'subgridMap' => [
            /** If we use subgrid describe helpers to convert object to subgrid */
            FieldsetInterface::class => Grid::class,
        ],
        'simple' => [
            'template' => 'grid/index',
            'options' => [
                'url' => null,
                'datatype' => 'json',
                'multiSort' => true,
            ],
            'methods' => [
            ]
        ],
        'default' => [
            'template' => 'grid/index',
            'options' => [
                'url' => null,
                'datatype' => 'json',
                'multiSort' => true,
            ],
            'methods' => []
        ],
        'pagerFilter' => [
            'template' => 'grid/index',
            'options' => [
                'url' => null,
                'datatype' => 'json',
                'multiSort' => true,
                'pager' => new Grid\GridPagerId('GridPager-'),
                'rowNum' => 10,
                'viewrecords' => true,
            ],
            'methods' => [
                /**
                 * arr[0] - method name,
                 * arr[1...n] - array of parameters (each will be transformed to json)
                 */
                'method_1' => [
                    'filterToolbar',
                    [
                        "searchOnEnter" => true,
                        "stringResult" => true,
                        "groupOp" => 'AND',
                        "searchOperators" => true,
                    ]
                ]
            ]
        ],
        'subgrid' => [
            'template' => 'grid/subgrid',
            'options' => [
                'datatype' => 'json',
                'multiSort' => true,
            ],
            'methods' => []
        ],

    ],
	'service_manager' => [
        'factories' => [
            ColModelAdapterPluginManagerInterface::class => function ($serviceManager) {
                return new ColModelAdapterPluginManager($serviceManager);
            }
        ],
    ],
    'jqgrid_adapter_manager' => [
        'invokables' => [
            ColModel\TextAdapter::class => ColModel\TextAdapter::class,
            ColModel\SelectAdapter::class => ColModel\SelectAdapter::class,
        ],
    ]
);
