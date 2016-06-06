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
    'oldJqGridBackend' => [
        'subgrid' => [
            'template' => 'grid/subgrid',
        ],
        'adapterMap' => [
            /**
             * map from element class-name to adapter class-name.
             * There will be compare if form element class is_a() map-key, and will take
             * the last from the successfull comparison
             */
            FormElement\Text::class => ColModel\TextAdapter::class,
            FormElement\Select::class => ColModel\SelectAdapter::class,
        ],
        'subgridMap' => [
            /**
             * what  helper use for subgrid
             */
            FieldsetInterface::class => Grid::class
        ],
        'options' => [
            'default' => [
                'url' => null,
                'datatype' => 'json',
                'multiSort' => true,
                'rowNum' => 10,
                'rowList' => [10,20,30],
                'pager' => new Grid\GridPagerId('GridPager-'),
                'viewrecords' => true,
                'caption' =>  'Тестовый грид'
            ],
            'test1' => [
                'datatype' => 'json',
                'multiSort' => true,
                'rowNum' => 20,
                'rowList' => [10,20,30],
                'pager' => new Grid\GridPagerId('GridPager-'),
                'viewrecords' => true,
                'caption' =>  'Тестовый грид'
            ],
            'subgrid' => [
                'datatype' => 'json',
                'multiSort' => true,
                'rowNum' => 20,
                'rowList' => [10,20,30],
                'pager' => new Grid\SubgridPagerId('GridPager-'),
                'viewrecords' => true,
                'caption' =>  'Тестовый грид'
            ],
        ],
        'methods' => [
            'default' => [
                /**
                 * key - method name,
                 * value - array of parameters (will be transformed to json)
                 */
                'filterToolbar' =>  [
                    'filterToolbar',
                    [
                        "searchOnEnter" => true,
                        "enableClear" => false
                    ]
                ]
            ],
            'test' => [
                'filterToolbar' =>  [
                    'filterToolbar',
                    [
                        "searchOnEnter" => true,
                        "enableClear" => true
                    ]
                ]
            ]
        ]
    ],
    'JqGridBackend' => [
        'adapterMap' => [
            /**
             * map from element class-name to adapter class-name.
             * There will be compare if form element class is_a() map-key, and will take
             * the last from the successfull comparison
             */
            FormElement\Text::class => ColModel\TextAdapter::class,
            FormElement\Select::class => ColModel\SelectAdapter::class,
        ],
        'subgridMap' => [
            /**
             * what  helper use for subgrid
             */
            FieldsetInterface::class => Grid::class
        ],
        'default' => [
            'template' => 'grid/index',
            'options' => [
                'url' => null,
                'datatype' => 'json',
                'multiSort' => true,
                'rowNum' => 10,
                'rowList' => [10,20,30],
                'pager' => new Grid\GridPagerId('GridPager-'),
                'viewrecords' => true,
                'caption' =>  'Тестовый грид',


            ],
            'methods' => [
                /**
                 * key - method name,
                 * value - array of parameters (will be transformed to json)
                 */
                'filterToolbar' =>  [
                    'filterToolbar',
                    [
                        "searchOnEnter" => true,
                        "enableClear" => false
                    ]
                ]
            ]
        ],
        'subgrid' => [
            'template' => 'grid/subgrid',
            'options' => [
                'datatype' => 'json',
                'multiSort' => true,
                'rowNum' => 20,
                'rowList' => [10,20,30],
                'pager' => new Grid\SubgridPagerId('GridPager-'),
                'viewrecords' => true,
                'caption' =>  'Тестовый грид'
            ],
            'methods' => [
                'filterToolbar' =>  [
                    'filterToolbar',
                    [
                        "searchOnEnter" => true,
                        "enableClear" => false
                    ]
                ],
            ]
        ],
    ],
	'service_manager' => [
        'invokables' => [
            ColModelAdapterPluginManagerInterface::class => ColModelAdapterPluginManager::class
        ]
    ],
    'jqgrid_adapter_manager' => [
        'invokables' => [
            ColModel\TextAdapter::class => ColModel\TextAdapter::class,
            ColModel\SelectAdapter::class => ColModel\SelectAdapter::class,
        ],
    ]
);
