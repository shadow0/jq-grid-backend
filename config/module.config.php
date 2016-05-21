<?php
namespace JqGridBackend;

use JqGridBackend\Grid\View\Helper\Grid;
use Zend\Form\Element as FormElement;
use Zend\Form\FieldsetInterface;
use JqGridBackend\Grid\View\Helper\ColModel;
//use JqGridBackend\Grid\View\Helper\Grid\SubgridPagerId;

return array(
    'view_helpers' => [
        'invokables' => [
            'jqGrid' => Grid::class,
        ]
    ],
    'controllers' => array(
		'invokables' => array(
			'JqGridBackend\Controller\Index' => 'JqGridBackend\Controller\IndexController',
		),
	),
	'router' => array(
        'routes' => array(
            'JqGridBackend' => array(
				'type' => 'Segment',
				'options' => array(
					'route' => '/jqgridbackend',
					'defaults' => array(
						'__NAMESPACE__' => 'JqGridBackend\Controller',
						'controller' => 'Index',
						'action' => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/[:controller[/[:action]]]',
							'constraints' => array(
								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(
							),
						),
					),
				),
			),
		),
    ),
    'view_manager' => array(
		'template_map' => array(
			//FIXME почему-то переопределяет глобально для всех модулей?
			//'layout/layout' => __DIR__ . '/../view/layout/layout.phtml'
		),
		'template_path_stack' => array(
			'jqgridbackend' => __DIR__ . '/../view',
		),
		'strategies' => array(
            'ViewJsonStrategy',
        ),
	),
	'doctrine' => array(
		'driver' => array(
			__NAMESPACE__ . '_driver' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(
					__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
				)
			),
			'orm_default' => array(
				'drivers' => array(
					__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
				)
			)
		)
	),
    'JqGridBackend' => [
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
             * what helper use for subgrid
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
    ]
	 
);
